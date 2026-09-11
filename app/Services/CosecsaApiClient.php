<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

// Thin wrapper around Laravel's HTTP client for server-to-server calls to
// cosecsa-api (see COSECSA_API_URL/COSECSA_API_TOKEN in .env). Mirrors the
// ApiClient used by the sibling Cosecsa MIS app — GET-only here since this
// app only needs read access to reference/lookup data (programmes,
// hospitals) for now.
class CosecsaApiClient
{
    private string $baseUrl;
    private ?string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.cosecsa_api.url'), '/');
        $this->token   = config('services.cosecsa_api.token');
    }

    // Hits /api/internal/{path}, authenticated with the shared machine token
    // (see InternalTokenAuth on the cosecsa-api side).
    public function get(string $path, array $query = []): Response
    {
        return Http::withToken($this->token)
            ->timeout(10)
            ->get($this->baseUrl . '/api/internal/' . ltrim($path, '/'), $query);
    }
}
