<?php

namespace App\Services\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AndinApiServices
{
    public function findAllMemorandum(Request $request)
    {
        $url_andin = config('app.andin_url') . '/api/external/v1/surat-internal/memorandum/index';

        // encode keyword
        $keyword = urldecode($request->keyword);

        $response_andin = Http::get($url_andin, [
            'search' => $keyword,
        ]);

        if ($response_andin->failed()) {
            throw new \Exception($response_andin->body(), $response_andin->status());
        }

        $response = [
            'status' => $response_andin->status(),
            'body' => $response_andin->body(),
            'data' => $response_andin->json()['data'] ?? [],
            'url' => $url_andin . '?search=' . $keyword,
        ];

        return $response;
    }
}
