<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Home and Dashboard Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employee Routes
    Route::get('/tasks', [TaskController::class, 'index'])->name('employee.tasks');
    Route::patch('/tasks/{task}', [TaskController::class, 'updateStatus'])->name('employee.updateStatus');

    // Admin Routes
    Route::get('/admin/tasks', [TaskController::class, 'adminIndex'])->name('admin.tasks');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Additional routes for showing and accepting/rejecting tasks
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('/tasks/{task}/accept', [TaskController::class, 'accept'])->name('tasks.accept');
    Route::patch('/tasks/{task}/reject', [TaskController::class, 'reject'])->name('tasks.reject');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\DashboardController;

// Dashboard Controller Route (only one definition)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
