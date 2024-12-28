<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/users",[UserController::class,"index"]);
Route::get("/users/{id}",[UserController::class,"show"]);
Route::put("/users/update/active", [UserController::class,"updateActive"]);
Route::get("/users/{limit}/{page}",[UserController::class,"getPagination"]);
Route::post("/users/add", [UserController::class,"create"]);