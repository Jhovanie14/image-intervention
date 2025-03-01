<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/upload', function () {
    return view('image');
});

// Route::post('/upload-test', function () {
//     return response()->json($_FILES);
// });
Route::post('/upload', [ImageController::class, 'uploadImage'])->name('upload.image');
