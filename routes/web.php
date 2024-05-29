<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UsuarioController;

Route::get('/user/info', [UsuarioController::class, 'getUserInfo']);
Route::post('/user/insertUser', [UsuarioController::class, 'insertUser']); // TODO
Route::get('/experts', [UsuarioController::class, 'getExperts']);
Route::get('/user/{id}', [UsuarioController::class, 'getById']);
Route::delete('/user/{id}', [UsuarioController::class, 'delete']); // TODO
Route::put('/user/{id}', [UsuarioController::class, 'update']); // TODO
Route::post('/thumb', [UsuarioController::class, 'postThumb']);  // TODO
Route::post('/registration', [UsuarioController::class, 'postRegistration']); // TODO
Route::get('/registration/{id}', [UsuarioController::class, 'getRegistration']);
Route::get('/registration/user/{userId}', [UsuarioController::class, 'getRegistrationByUserId']);
Route::post('/register', [UsuarioController::class, 'postRegister']); // TODO
Route::post('/activate', [UsuarioController::class, 'activate']); // TODO
