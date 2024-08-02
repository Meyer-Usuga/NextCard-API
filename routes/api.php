<?php

use App\Http\Controllers\GroupsController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Ruta general (POST, GET, PUT, DELETE)
Route::resource('/role',RoleController::class);
Route::resource('/group',GroupsController::class); 
