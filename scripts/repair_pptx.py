#!/usr/bin/env python3
"""
PPTX corruption repair tool.
Attempts to salvage a corrupted PPTX by scanning local file headers,
extracting every entry that can be decompressed, and repackaging into
a new valid ZIP/PPTX.

Usage:
    python3 repair_pptx.py <input.pptx> <output.pptx>

Exit codes:
    0  success (repaired file written)
    1  unrecoverable (no XML entries could be extracted)
    2  usage error
"""
import sys
import os
import struct
import zlib
import io
import zipfile
import json

# ── ZIP constants ────────────────────────────────────────────────────────────
LOCAL_HEADER_SIG  = b'PK\x03\x04'
DATA_DESCRIPTOR_SIG = b'PK\x07\x08'
MIN_ENTRY_SIZE    = 30        # bytes for a local file header

def scan_local_headers(data: bytes):
    """
    Scan raw bytes for every PK\x03\x04 local file header.
    Yields (offset, filename, compression, compressed_size,
            uncompressed_size, crc32, data_offset, raw_data).
    Skips entries that cannot be decompressed.
    """
    pos = 0
    length = len(data)
    recovered = []

    while pos < length - MIN_ENTRY_SIZE:
        idx = data.find(LOCAL_HEADER_SIG, pos)
        if idx == -1:
            break
        pos = idx

        try:
            # Local file header layout (30 bytes + filename + extra)
            (sig, ver, flags, compression,
             mod_time, mod_date,
             crc32_stored, comp_size, uncomp_size,
             fname_len, extra_len) = struct.unpack_from('<4sHHHHHIIIHH', data, pos)

            if sig != LOCAL_HEADER_SIG:
                pos += 1
                continue

            header_end = pos + 30 + fname_len + extra_len
            if header_end > length:
                pos += 1
                continue

            filename_bytes = data[pos + 30: pos + 30 + fname_len]
            try:
                filename = filename_bytes.decode('utf-8')
            except UnicodeDecodeError:
                filename = filename_bytes.decode('latin-1')

            # Skip directories
            if filename.endswith('/'):
                pos = header_end
                continue

            data_start = header_end

            # If comp_size is 0 (data descriptor follows), try to guess end
            # by looking for the next local header or end of file
            if comp_size == 0:
                next_pk = data.find(LOCAL_HEADER_SIG, data_start)
                if next_pk == -1:
                    comp_size = length - data_start
                else:
                    # Back up past any data descriptor
                    chunk = data[data_start:next_pk]
                    dd_pos = chunk.rfind(DATA_DESCRIPTOR_SIG)
                    if dd_pos != -1:
                        comp_size = dd_pos
                    else:
                        comp_size = next_pk - data_start

            data_end = data_start + comp_size
            if data_end > length:
                comp_size = length - data_start
                data_end = length

            compressed = data[data_start:data_end]

            # Try to decompress
            if compression == 0:          # STORED
                raw = compressed
            elif compression == 8:        # DEFLATE
                try:
                    raw = zlib.decompress(compressed, -15)
                except zlib.error:
                    # Try with different window sizes
                    decompressed = None
                    for wbits in (-15, 15, 47):
                        try:
                            decompressed = zlib.decompress(compressed, wbits)
                            break
                        except zlib.error:
                            continue
                    if decompressed is None:
                        # Skip this entry — cannot recover
                        pos = data_start + 1
                        continue
                    raw = decompressed
            else:
                # Unsupported compression method — skip
                pos = data_start + 1
                continue

            recovered.append((filename, compression, crc32_stored, raw))
            pos = data_end

        except struct.error:
            pos += 1
            continue

    return recovered


def repair_pptx(input_path: str, output_path: str) -> dict:
    with open(input_path, 'rb') as f:
        data = f.read()

    # ── Phase 1: try normal zipfile read first ──────────────────────────────
    try:
        with zipfile.ZipFile(input_path, 'r') as zf:
            names = zf.namelist()
            buf = io.BytesIO()
            with zipfile.ZipFile(buf, 'w', zipfile.ZIP_DEFLATED) as out:
                errors = []
                for name in names:
                    try:
                        raw = zf.read(name)
                        out.writestr(name, raw)
                    except Exception as e:
                        errors.append(f"{name}: {e}")
            if not errors:
                with open(output_path, 'wb') as f:
                    f.write(buf.getvalue())
                return {"method": "clean_repack", "entries": len(names), "skipped": 0}
            # Some entries failed — fall through to raw scan
    except (zipfile.BadZipFile, Exception):
        pass

    # ── Phase 2: raw local-header scan ─────────────────────────────────────
    recovered = scan_local_headers(data)

    if not recovered:
        return {"error": "No entries could be recovered from the file"}

    # Deduplicate (keep last occurrence of each filename — usually most complete)
    seen = {}
    for filename, compression, crc32_stored, raw in recovered:
        seen[filename] = (compression, crc32_stored, raw)

    # Build new ZIP
    buf = io.BytesIO()
    skipped = []
    written = []
    with zipfile.ZipFile(buf, 'w', zipfile.ZIP_DEFLATED) as out:
        # Must have [Content_Types].xml to be a valid OOXML package
        for filename, (compression, crc32_stored, raw) in seen.items():
            try:
                out.writestr(filename, raw)
                written.append(filename)
            except Exception as e:
                skipped.append(f"{filename}: {e}")

    if not written:
        return {"error": "Could not write any entries to output archive"}

    # Check we have the minimum OOXML structure
    has_content_types = any('[Content_Types]' in w for w in written)
    has_presentation  = any('ppt/presentation.xml' in w for w in written)

    if not has_content_types or not has_presentation:
        return {
            "error": f"Recovered {len(written)} entries but missing critical PPTX structure "
                     f"(content_types={has_content_types}, presentation={has_presentation}). "
                     f"File may be too severely damaged.",
            "recovered": written,
        }

    with open(output_path, 'wb') as f:
        f.write(buf.getvalue())

    return {
        "method":   "raw_scan_repack",
        "written":  len(written),
        "skipped":  skipped,
        "output":   output_path,
    }


def main():
    if len(sys.argv) != 3:
        print(json.dumps({"error": "Usage: repair_pptx.py <input.pptx> <output.pptx>"}))
        sys.exit(2)

    input_path  = sys.argv[1]
    output_path = sys.argv[2]

    if not os.path.exists(input_path):
        print(json.dumps({"error": f"Input file not found: {input_path}"}))
        sys.exit(1)

    result = repair_pptx(input_path, output_path)
    print(json.dumps(result, indent=2))

    if "error" in result:
        sys.exit(1)
    sys.exit(0)


if __name__ == "__main__":
    main()
