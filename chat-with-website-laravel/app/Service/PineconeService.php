<?php

namespace App\Service;

use Probots\Pinecone\Client as Pinecone;

class PineconeService
{
    public static function getClient(): Pinecone
    {
        return new Pinecone(apiKey: env("PINECONE_API_KEY"), indexHost: env("PINECONE_HOST"));
    }
}
