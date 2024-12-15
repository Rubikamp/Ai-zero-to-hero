<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                #{{ $conversation->id }} Messages
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col space-y-4">
                    <!-- Example of a received message -->
                    <div class="flex justify-start">
                        <div class="bg-gray-100 rounded-r-lg rounded-bl-lg max-w-[80%] px-4 py-2 shadow">
                            <div class="text-sm font-medium mb-1">
                                AI
                            </div>
                            <div>
                                How can I help you?
                            </div>
                        </div>
                    </div>

                    @foreach ($conversation->messages()->get() as $message)
                        <div class="flex {{ $message->sender === 'user' ? 'justify-end' : 'justify-start' }}">
                            <div class="{{ $message->sender === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-100' }} rounded-l-lg rounded-br-lg max-w-[80%] px-4 py-2 shadow">
                                <div class="text-sm font-medium mb-1">
                                    @if ($message->sender === 'user')
                                        {{ auth()->user()->name }}
                                    @endif
                                </div>

                                <div class="ai-content">{!! $message->message !!}</div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Message Input -->
                <div class="mt-4 border-t pt-4">
                    <form wire:submit="sendMessage" class="flex gap-2">
                        <input wire:model="message_input" type="text"
                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            placeholder="Type your message...">

                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                            <span wire:loading.remove>Send</span>
                            <span wire:loading>....</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
