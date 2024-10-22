<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function adminLogin(){

        return view('admin.auth.login');

    }

    public function adminLoginSubmit(Request $request){

        $credentials = [

            'email' => $request->email,
            'password' => $request->password,
            'status' => 1

        ];

        if(Auth::guard('admin')->attempt($credentials)){
            return redirect()->route('dashboard');
        }else{
            return redirect()->route('admin.login');
        }

    }

    public function Dashboard(){
        return view('admin.index');
    }

    public function adminLogout(){

        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');

    }

}
