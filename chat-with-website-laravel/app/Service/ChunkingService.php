<?php

namespace App\Service;

class ChunkingService
{
    public function __construct(public string $content) {}

    public function byWord(int $words_limit = 500): array
    {
        $chunks = [];

        $words = explode(" ", $this->content);

        $index = 0;
        $current = 0;

        foreach ($words as $word) {
            if ($current >= $words_limit) {
                $current = 0;
                $index++;
            }

            if (!isset($chunks[$index])) $chunks[$index] = '';

            $chunks[$index] = mb_trim($chunks[$index] . " " . $word);
            $current++;
        }

        return [
            'total_words' => count($words),
            'total_chunks' => count($chunks),
            'chunks' => $chunks
        ];
    }
}
