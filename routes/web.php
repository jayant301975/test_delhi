<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

 Route::get('/',[UserController::class,'index'])->name('users.index');

 
  Route::get('/export',[UserController::class,'exportcsv'])->name('export');