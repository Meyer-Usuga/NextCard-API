<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ExitController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//----------------- Rutas roles ----------------------
Route::resource('/role',RoleController::class);

//----------------- Rutas grupos ----------------------
Route::resource('/group',GroupsController::class); 

//----------------- Rutas carnets ----------------------
Route::resource('/card', CardController::class); 

//----------------- Rutas users ----------------------
Route::resource('/user', UserController::class);
Route::post('user/login', [UserController::class, 'login']); 

//----------------- Rutas ingresos ----------------------


//----------------- Rutas salidas ----------------------
Route::resource('/exits', ExitController::class);

