<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Parking Management
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit="submit" class="flex gap-2 w-full">
                <input wire:model="input" type="text"
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    placeholder="Type your message...">

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    <span wire:loading.remove>Send</span>
                    <span wire:loading>....</span>
                </button>
            </form>

            <pre class="my-5 bg-gray-200 rounded-md p-3 whitespace-pre-line">{{ $response }}</pre>

            <div class="relative overflow-x-auto mt-5">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Car Number
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Car name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Car color
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Car price
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cars as $car)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $car['number'] }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $car['name'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $car['color'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $car['price'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
