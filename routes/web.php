<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/users",[UserController::class,"index"]);
Route::post("/users/add", [UserController::class,"create"]);