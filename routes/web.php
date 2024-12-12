<?php

use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('PDFRekapDropping', [PDFController::class, 'generatePDFRekapDropping'])->name('PDFRekapDropping');
