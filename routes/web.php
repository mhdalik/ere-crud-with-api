<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', ['blogs' => [1, 2, 3, 4, 5, 6, 7]]);
})->middleware(['auth', 'verified'])->name('dashboard');


// Route::get('/blog_edit/{id}', function ($id) {
//     return view('blog_edit', ['blogs' => [1, 2, 3, 4, 5, 6, 7]]);
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('blogs', BlogController::class);
});

require __DIR__ . '/auth.php';
