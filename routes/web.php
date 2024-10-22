<?php

use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;


Route::controller(WebsiteController::class)->group(function(){

    Route::get('/','home')->name('home');

});

Route::middleware('auth')->group(function(){

    Route::get('/logout',[UserController::class,'logout'])->name('logout');

});


Route::controller(UserController::class)->group(function(){

    Route::get('/login','userLogin')->name('login');

    Route::get('/signup','userSignup')->name('signup');

    Route::get('/forget-password','forgetPassword')->name('forget.password');

    Route::post('/register','registration')->name('register');

    Route::get('registration/verify/{token}/{email}','registrationVerify')->name('registration.verify');

    Route::post('user/login','userLoginSubmit')->name('user.login');

    Route::post('/forget/password','forgetPasswordSubmit')->name('forget.password.submit');

    Route::get('/reset-password/{token}/{email}','resetPassword')->name('reset.password');

    Route::post('/reset-password/submit','resetPasswordSubmit')->name('reset.password.submit');

});

Route::middleware('admin:admin')->group(function(){

    Route::get('/dashboard',[AdminController::class,'Dashboard'])->name('dashboard');

    Route::get('/admin/logout',[AdminController::class,'adminLogout'])->name('admin.logout');

});

Route::controller(AdminController::class)->group(function(){

    Route::get('/admin/login',action: [AdminController::class,'adminLogin'])->name('admin.login');

    Route::post('/admin/login/submit',action: [AdminController::class,'adminLoginSubmit'])->name('admin.login.submit');

});
