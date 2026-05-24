<?php

namespace App\Http\Controllers;

use App\TrainingMaterial;
use App\TraineeDocument;
use App\TraineeDocumentComment;

class MaterialViewerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(TrainingMaterial $material)
    {
        $material->load(['facilitator', 'schedules.speaker']);

        // Strip any local dev /research/ prefix from the URL
        $fileUrl = null;
        if ($material->external_url) {
            $fileUrl = preg_replace('#^/research/#', '/', $material->external_url);
        } elseif ($material->file) {
            $fileUrl = $material->file->url;
        }

        return view('material-viewer', compact('material', 'fileUrl'));
    }

    /**
     * Shared PPTX slide renderer — no admin gate, any authenticated user.
     */
    public function renderSlides(TrainingMaterial $material)
    {
        if ($material->type !== 'presentation') {
            return response()->json(['error' => 'Not a presentation'], 400);
        }

        $filePath = $this->resolveFilePath($material->external_url);

        if (!$filePath || !file_exists($filePath)) {
            return response()->json(['error' => 'File not found on server.'], 404);
        }

        return $this->runPythonRenderer($filePath);
    }

    /**
     * Render a trainee's uploaded presentation (PPTX) — for facilitator review.
     * Trainees may only render their own documents; facilitators/admins can render any.
     */
    public function renderTraineeSlides(TraineeDocument $document)
    {
        $user      = auth()->user();
        $userRoles = $user->roles->pluck('title')
            ->map(fn($t) => strtolower(str_replace(' ', '-', $t)))
            ->toArray();

        $isFacilitatorOrAdmin = collect(['admin', 'super-admin', 'facilitator', 'lead-facilitator'])
            ->contains(fn($r) => in_array($r, $userRoles));

        if (!$isFacilitatorOrAdmin) {
            // Trainees can only render their own documents
            $trainee = $user->trainee;
            if (!$trainee || $document->trainee_id !== $trainee->id) {
                abort(403, 'You do not have permission to view this document.');
            }
        }

        $fullPath = $document->full_path;

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return $this->runPythonRenderer($fullPath);
    }

    /**
     * Convert an external_url (e.g. /research/materials/...) to a filesystem path.
     */
    private function resolveFilePath(?string $url): ?string
    {
        if (!$url) return null;
        // Strip any leading /research/ subfolder prefix (local XAMPP dev) or
        // leading slash (production), leaving a relative path under public/
        $relative = ltrim(preg_replace('#^/research/#', '/', $url), '/');
        return public_path($relative);
    }

    /**
     * Run the Python PPTX-to-HTML renderer and return JSON.
     */
    private function runPythonRenderer(string $filePath)
    {
        $script = base_path('scripts/pptx_to_html.py');

        $python3 = trim(shell_exec('which python3 2>/dev/null') ?? '');
        if (!$python3 || !file_exists($python3)) {
            foreach ([
                '/usr/bin/python3',
                '/usr/local/bin/python3',
                '/Library/Developer/CommandLineTools/usr/bin/python3',
                '/opt/homebrew/bin/python3',
            ] as $candidate) {
                if (file_exists($candidate)) { $python3 = $candidate; break; }
            }
        }

        if (!$python3) {
            return response()->json(['error' => 'python3 not available on this server.'], 500);
        }

        $output = shell_exec(escapeshellarg($python3) . ' ' . escapeshellarg($script) . ' ' . escapeshellarg($filePath) . ' 2>&1');

        if (!$output) {
            return response()->json(['error' => 'Renderer returned no output.'], 500);
        }

        $data = json_decode($output, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Slide renderer error: ' . substr($output, 0, 200)], 500);
        }
        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 500);
        }

        return response()->json($data);
    }
}
