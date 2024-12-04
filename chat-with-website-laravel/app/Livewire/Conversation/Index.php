<?php

namespace App\Livewire\Conversation;

use Livewire\Component;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{

    public $conversations;

    public function mount()
    {
        $this->loadConversations();
    }

    public function createConversation()
    {
        $conversation = Conversation::create([
            'user_id' => Auth::id(),
            'title' => null
        ]);

        $this->loadConversations();
    }

    public function render()
    {

        return view('livewire.conversation.index');
    }

    protected function loadConversations()
    {
        $this->conversations = Conversation::where('user_id', Auth::id())->latest()->get();
    }
}
