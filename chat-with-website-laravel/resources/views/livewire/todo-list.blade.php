<div class="h-screen flex flex-col justify-center items-center text-center p-44">
    @foreach ($todos as $todo)
        <div
            class="w-full {{ $todo['done'] === 0 ? "bg-amber-500" : "bg-emerald-500" }} text-white flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <span>
                    {{ $todo['title'] }}
                </span>

                <div>
                    @if ($todo['done'] == 0)
                        <button wire:click="doneTodo({{ $todo['id'] }})" class="bg-emerald-500 text-white px-4 py-2 rounded">Done</button>
                    @else
                        <span>Complete</span>
                    @endif
                    <button wire:click="deleteTodo({{ $todo['id'] }})"
                        class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                </div>
            </div>
        </div>
    @endforeach

    <div class="flex items-center gap-2 mt-5">
        <input wire:model="todo_input_text" type="text" class="w-full border-2 border-gray-300 rounded-md p-2"
            placeholder="Add a new task">
        <button wire:click="createTodo" class="bg-emerald-500 text-white px-4 py-2 rounded">Add</button>
    </div>
</div>
