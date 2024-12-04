<?php

namespace App\Livewire\Conversation;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Source;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use OpenAI;
use OpenAI\Client;

class Messages extends Component
{
    public $conversation;

    public $message_input = '';

    protected ?Client $client = null;

    public function mount(Conversation $conversation)
    {
        if ($conversation->user_id !== Auth::id()) return abort(403);

        $this->conversation = $conversation;
    }

    public function sendMessage()
    {
        $this->validate([
            'message_input' => ['required', 'string', 'min:2', 'max:500']
        ]);

        $result = $this->getClient()->chat()->create([
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
            Only answer the questions, provided in the context below. you MUST not answer to any question, if the data for it is not provided.

            <context>
        ";

        $sources = Source::where('content', 'LIKE', '%' . $this->message_input . '%')->limit(3)->get();

        foreach ($sources as $source) {
            $prompt = $prompt . "\n" . $source->content."\n";
        }

        return $prompt . "</context>";
    }

    protected function getClient(): Client
    {
        return OpenAI::factory()
            ->withBaseUri('https://api.metisai.ir/openai/v1')
            ->withHttpHeader('Authorization', "Bearer " . env('OPENAI_API_KEY'))
            ->make();
    }

    public function render()
    {
        return view('livewire.conversation.messages');
    }
}
