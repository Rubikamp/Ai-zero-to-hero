<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Source') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <x-input-label for="website" :value="__('Website URL')" />
                        <x-text-input wire:model="url" id="website" name="url" type="text"
                            class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('url')" class="mt-2" />
                    </div>

                    <div wire:loading> 
                        Loading content ...
                    </div>

                    @if (!empty($content))
                        <pre class="max-h-64 overflow-auto bg-gray-200 p-4 rounded-md mt-5 whitespace-pre-line">{{ $content }}</pre>
                    @endif
                    <div class="flex justify-end mt-5">
                        <x-primary-button wire:click='submit'>
                            Submit
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
