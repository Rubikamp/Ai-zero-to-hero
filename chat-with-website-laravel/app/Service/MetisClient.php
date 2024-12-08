<?php

namespace App\Service;

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
}
