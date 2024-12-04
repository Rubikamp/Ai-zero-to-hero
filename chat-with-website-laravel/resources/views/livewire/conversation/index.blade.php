<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Conversations
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-5">
                <x-primary-button wire:click="createConversation">
                    New conversation
                </x-primary-button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col divide-y divide-zinc-200">

                    @if ($conversations->count() === 0)
                        <div class="bg-amber-500 p-6 rounded-lg">
                            <strong>Warning:</strong>
                            <p>
                                Please create a conversation first.
                            </p>
                        </div>
                    @endif

                    @foreach ($conversations as $conversation)
                        <div class="flex items-center justify-between py-4">
                            <div>
                                <span class="block text-lg font-bold">
                                    #{{ $conversation->id }}
                                    {{ $conversation->title ?? 'Untitled Conversation' }}
                                </span>
                                <small>
                                    {{ $conversation->updated_at->ago() }}
                                </small>
                            </div>
                            <a href="{{ route('conversation.messages', $conversation->id) }}">
                                <x-secondary-button>
                                    View conversation
                                </x-secondary-button>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
