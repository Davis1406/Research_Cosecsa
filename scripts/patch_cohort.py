#!/usr/bin/env python3
"""
Patch the repaired COHORT STUDY DESIGN PPTX by removing references to
slides that couldn't be recovered, so python-pptx can open the file
without crashing on missing relationship targets.
"""
import zipfile, io, re, sys

path   = '/var/www/research/public/materials/presentations/COHORT STUDY DESIGN_repaired.pptx'
output = '/var/www/research/public/materials/presentations/COHORT STUDY DESIGN_repaired2.pptx'

with zipfile.ZipFile(path, 'r') as z:
    names = z.namelist()
    present_slides = set(re.findall(r'ppt/slides/(slide\d+\.xml)', ' '.join(names)))
    entries = {name: z.read(name) for name in names}

print('Slides in ZIP:', sorted(present_slides, key=lambda x: int(re.search(r'\d+', x).group())))

# ── Patch ppt/_rels/presentation.xml.rels ──────────────────────────────────
rels_xml = entries['ppt/_rels/presentation.xml.rels'].decode('utf-8')

surviving_rids = set()
removed_rels   = []

def filter_rel(m):
    tag = m.group(0)
    target_m = re.search(r'Target="([^"]+)"', tag)
    rid_m    = re.search(r'Id="([^"]+)"',     tag)
    if target_m and target_m.group(1).startswith('slides/'):
        slide_file = target_m.group(1).split('/')[-1]
        if slide_file not in present_slides:
            removed_rels.append(tag)
            return ''                     # drop
    if rid_m:
        surviving_rids.add(rid_m.group(1))
    return tag

rels_xml = re.sub(r'<Relationship [^>]+/>', filter_rel, rels_xml)
entries['ppt/_rels/presentation.xml.rels'] = rels_xml.encode('utf-8')
print(f'Removed {len(removed_rels)} rels entries for missing slides')

# ── Patch ppt/presentation.xml sldIdLst ────────────────────────────────────
pres_xml = entries['ppt/presentation.xml'].decode('utf-8')
removed_ids = []

def filter_sldid(m):
    tag   = m.group(0)
    rid_m = re.search(r'r:id="([^"]+)"', tag)
    if rid_m and rid_m.group(1) not in surviving_rids:
        removed_ids.append(rid_m.group(1))
        return ''
    return tag

pres_xml = re.sub(r'<p:sldId\s[^>]*/>', filter_sldid, pres_xml)
entries['ppt/presentation.xml'] = pres_xml.encode('utf-8')
print(f'Removed {len(removed_ids)} sldId entries for missing slides')

# ── Rebuild ZIP ─────────────────────────────────────────────────────────────
buf = io.BytesIO()
with zipfile.ZipFile(buf, 'w', zipfile.ZIP_DEFLATED) as out:
    for name, data in entries.items():
        out.writestr(name, data)

with open(output, 'wb') as f:
    f.write(buf.getvalue())

print('Written:', output)
