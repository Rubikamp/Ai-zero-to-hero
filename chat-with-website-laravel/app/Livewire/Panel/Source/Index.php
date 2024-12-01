<?php

namespace App\Livewire\Panel\Source;

use App\Models\Source;
use App\Service\ChunkingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Index extends Component
{
    public string $base_url = "https://r.jina.ai/";

    public string $url = '';
    public string $content = '';

    public function submit()
    {
        $this->validate([
            'url' => ['required', 'url']
        ]);

        $response = Http::get($this->base_url . $this->url);

        if ($response->failed()) {
            $this->content = "Failed to load markdown content";
            return;
        }

        $this->content = $response->body();

        if (str_contains($this->content, 'Markdown Content:')) {
            $this->content = mb_trim(explode('Markdown Content:', $this->content)[1]);

            $chunkingService = new ChunkingService($this->content);
            $chunks = $chunkingService->byWord(words_limit: 500);

            $this->content = json_encode($chunks, JSON_PRETTY_PRINT);

            if ($chunks['total_chunks'] > 0) {
                Source::where('url', $this->url)->delete();
            }

            foreach ($chunks['chunks'] as $value) {
                $content_hash = hash('sha256', $value);

                Source::create([
                    'user_id' => Auth::id(),
                    'url' => $this->url,
                    'hash' => $content_hash,
                    'content' => $value
                ]);
            }
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.panel.source.index');
    }
}
