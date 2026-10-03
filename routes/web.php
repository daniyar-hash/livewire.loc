<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');


// Route::livewire('/post/create', 'pages::post.create');

Route::livewire('/', 'pages::user.list')->name('home');
Route::livewire('/user-create', 'pages::user.create')->name('user-create');