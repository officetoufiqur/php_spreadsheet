<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/excel-upload', [ExcelController::class, 'index'])->name('excel.index');
// Route::post('/excel-upload/step-a', [ExcelController::class, 'stepA'])->name('excel.stepA');
// Route::post('/excel-upload/step-b', [ExcelController::class, 'stepB'])->name('excel.stepB');
// Route::post('/excel-upload/step-c', [ExcelController::class, 'stepC'])->name('excel.stepC');

// Route::match(['get', 'post'], '/excel', [ExcelController::class, 'handle'])
//     ->name('excel.handle');

Route::get('/excel', [ExcelController::class, 'handle'])->name('excel.index');
Route::post('/excel/handle', [ExcelController::class, 'handle'])->name('excel.handle');
Route::post('/excel/save', [ExcelController::class, 'save'])->name('excel.save');



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
