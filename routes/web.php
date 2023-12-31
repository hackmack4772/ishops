<?php

use App\Http\Controllers\admin\AdminHomeController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix("/admin")->group(function(){
Route::group(["middleware"=>"admin.guest"],function(){
    Route::get("login",[AdminLoginController::class,"index"])->name("admin.login");
    Route::post("authenticate",[AdminLoginController::class,"authenticate"])->name("admin.authenticate");
});
Route::group(["middleware"=>"admin.auth"],function(){
    Route::get("dashboard",[AdminHomeController::class,"index"])->name("admin.dashboard");
    Route::get("logout",[AdminLoginController::class,"logout"])->name("admin.logout");
    Route::resource("category", CategoryController::class);
    Route::resource("subcategory", SubCategoryController::class);
    Route::post("category-slug",[CategoryController::class,"getSlug"])->name("admin.category-slug");
    Route::post("upload-temp-image",[TempImageController::class,"create"])->name("temp-images.create");
});
});
