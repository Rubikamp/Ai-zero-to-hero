<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use OpenAI;
use OpenAI\Client;

class MetisClient
{
    public static function getClient(): Client
    {
        return OpenAI::factory()
            ->withBaseUri('https://api.metisai.ir/openai/v1')
            ->withHttpHeader('Authorization', "Bearer " . env('OPENAI_API_KEY'))
            ->make();
    }

    public static function embed(array $data, string $model = 'text-embedding-3-small')
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer " . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.metisai.ir/openai/v1/embeddings', ([
            'model' => $model,
            'input' => $data
        ]));

        return $response->json();
    }
}
