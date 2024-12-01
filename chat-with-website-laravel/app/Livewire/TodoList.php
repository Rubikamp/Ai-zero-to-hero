<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;

class TodoList extends Component
{
    public $todos;

    public string $todo_input_text = "";

    public function createTodo()
    {
        Todo::create([
            "title" => $this->todo_input_text,
            "done" => false
        ]);

        $this->todo_input_text = "";
    }

    public function doneTodo($id)
    {
        Todo::find($id)->update([
            "done"=> true
        ]);
    }

    public function deleteTodo($id)
    {
        Todo::find($id)->delete();
    }

    public function render()
    {
        $this->todos = Todo::get();

        return view('livewire.todo-list');
    }
}
