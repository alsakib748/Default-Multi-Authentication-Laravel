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

Route::middleware(['auth','role:1'])->group(function(){

    Route::get('/dashboard',[AdminController::class,'Dashboard'])->name('dashboard');

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
