<?php

use Illuminate\Support\Facades\Route;
use Modules\Expenses\app\Controllers\ExpenseController;

Route::prefix('api/expenses')->middleware('api')->group(function () {
    // RESTful routes
    Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('{id}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::put('{id}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::patch('{id}', [ExpenseController::class, 'update'])->name('expenses.patch');
    Route::delete('{id}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
});