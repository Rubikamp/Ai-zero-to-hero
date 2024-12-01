<?php

use App\Livewire\Panel\Source\Index;
use App\Livewire\Secret;
use App\Livewire\TodoList;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/todos', TodoList::class);
Route::get('/secret', Secret::class);


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/source', Index::class)
    ->middleware('auth')
    ->name('source.index');

// Conversation routes:



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
