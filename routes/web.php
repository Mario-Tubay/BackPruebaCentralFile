<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/user/login', [UserController::class, 'loginUser'])->name("user.loginUser");
Route::post('/user/create', [UserController::class, 'create'])->name("user.create");
Route::get('/user/{id}', [UserController::class, 'getUsers'])->name("user.getUsers");
Route::get('/user/getContacts/{id}', [UserController::class, 'getContacts'])->name("user.getContacts");
Route::get('/user/getNotContact/{id}', [UserController::class, 'getNotContact'])->name("user.getNotContact");
Route::post('/user/addUserContact', [UserController::class, 'addUserContact'])->name("user.addUserContact");
Route::post('/user/uploadImage', [UserController::class, 'uploadImage'])->name("user.uploadImage");


