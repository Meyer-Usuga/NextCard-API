<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ExitController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//----------------- Rutas roles ----------------------
Route::resource('/role', RoleController::class);

//----------------- Rutas grupos ----------------------
Route::resource('/group', GroupsController::class);
Route::get('group/students/{groupId}', [GroupsController::class, 'getStudents']);

//----------------- Rutas carnets ----------------------
Route::resource('/card', CardController::class);
Route::get('card/byuser/{userId}', [CardController::class, 'getCardByUser']);
Route::post('card/uploadImage/{cardId}', [CardController::class, 'uploadImage']);

//----------------- Rutas users ----------------------
Route::resource('/user', UserController::class);
Route::post('user/login', [UserController::class, 'login']);
Route::post('user/updateStatus/{userId}', [UserController::class, 'updateStatus']);

//----------------- Rutas ingresos ----------------------
Route::resource('/entries', EntryController::class);

//----------------- Rutas salidas ----------------------
Route::resource('/exits', ExitController::class);

