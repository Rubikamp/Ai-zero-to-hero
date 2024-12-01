<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;


class Secret extends Component
{

    #[Layout('layouts.admin-layout')] 
    public function render()
    {
        return view('livewire.secret');
    }
}
