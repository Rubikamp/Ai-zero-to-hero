<?php

namespace App\Livewire\Conversation;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Source;
use App\Service\MetisClient;
use App\Service\PineconeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use OpenAI\Client;
use Probots\Pinecone\Client as PineconeClient;

class Messages extends Component
{
    public $conversation;

    public $message_input = '';

    protected Client $client;
    protected PineconeClient $pinecone;

    public function mount(Conversation $conversation)
    {
        if ($conversation->user_id !== Auth::id()) return abort(403);

        $this->conversation = $conversation;

        // $this->client = MetisClient::getClient();
        // $this->pinecone = PineconeService::getClient();
    }

    public function sendMessage()
    {
        $this->validate([
            'message_input' => ['required', 'string', 'min:2', 'max:500']
        ]);

        $result = MetisClient::getClient()->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => $this->getMessagesAsOpenAiArray(),
        ]);

        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender' => 'user',
            'message' => $this->message_input
        ]);

        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender' => 'assistant',
            'message' => $result->choices[0]->message->content
        ]);

        $this->message_input = '';
    }

    protected function getMessagesAsOpenAiArray()
    {
        $messages = [
            [
                'role' => 'system',
                'content' => $this->getSystemPrompt()
            ]
        ];

        foreach ($this->conversation->messages()->get() as $message) {
            $messages[] = [
                'role' => $message->sender,
                'content' => $message->message
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $this->message_input
        ];

        return $messages;
    }

    protected function getSystemPrompt()
    {
        $prompt = "You are a helpful assistant with access to a document containing code snippets from a websites. Your task is to answer questions about the functionality and structure of these code snippets.
            Only answer the questions, provided in the context below. you MUST not answer to any question, if the data for it is not provided. Return the answer in HTML format.

            Start Context:
        ";

        $embeddings = MetisClient::getClient()->embeddings()->create([
            "model" => "text-embedding-3-small",
            "input" => $this->message_input
        ]);

        $response = PineconeService::getClient()->data()->vectors()->query(
            vector: $embeddings->embeddings[0]->embedding,
            topK: 3,
            namespace: "source_user_" . Auth::id()
        );

        $matches = $response->json()['matches'];

        foreach ($matches as $match) {
            $prompt = $prompt . "\n" . $match['metadata']['content'] . "\n";
        }

        return $prompt . "End Context";
    }

    public function render()
    {
        return view('livewire.conversation.messages');
    }
}
