<?php

use App\Models\Task;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::get('/', [TaskController::class, 'dashboard'])->name('dashboard');
Route::post('change/{task}', [TaskController::class, 'change'])->name('change');
Route::get('mark-completed/{task}', [TaskController::class, 'markAsCompleted'])->name('markAsCompleted');
Route::resource('tasks', TaskController::class);
