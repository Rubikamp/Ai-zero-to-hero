<?php

namespace App\Livewire\Panel\Source;

use App\Models\Source;
use App\Service\ChunkingService;
use App\Service\MetisClient;
use App\Service\PineconeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Probots\Pinecone\Client as PineconeClient;
use OpenAI\Client as OpenAIClient;

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
                $source_ids = Source::where('url', $this->url)->pluck('id')
                    ->map(fn($id) => "source_" . $id)
                    ->toArray();

                if (count($source_ids) > 0) {
                    PineconeService::getClient()->data()->vectors()->delete(ids: $source_ids, namespace: "source_user_" . Auth::id());
                    Source::where('url', $this->url)->delete();
                }
            }

            $vectors = [];

            foreach ($chunks['chunks'] as $value) {
                $content_hash = hash('sha256', $value);

                $embeddings = MetisClient::getClient()->embeddings()->create([
                    "model" => "text-embedding-3-small",
                    "input" => $value
                ]);

                $source = Source::create([
                    'user_id' => Auth::id(),
                    'url' => $this->url,
                    'hash' => $content_hash,
                    'content' => $value
                ]);

                $vectors[] = [
                    'id' => "source_" . $source->id,
                    'values' => $embeddings->embeddings[0]->embedding,
                    'metadata' => [
                        'url' => $this->url,
                        'content' => $value
                    ]
                ];
            }

            PineconeService::getClient()->data()->vectors()->upsert(vectors: $vectors, namespace: "source_user_" . Auth::id());
        }

        $this->content = "Source added successfully\n\n" . $response->body();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.panel.source.index');
    }
}
