<?php

namespace App\Http\Controllers;

use session;
use App\Models\User;
use App\Mail\Websitemail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function userLogin(){
        return view('auth.login');
    }

    public function userSignup(){
        return view('auth.register');
    }

    public function registration(Request $request){

        $token = hash('sha256',time());

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = '0';
        $user->status = 'pending';
        $user->token = $token;
        $user->save();

        $verification_link = url('registration/verify/'.$token.'/'.$request->email);

        $message = 'Please click on this link: <br/> <a href="'.$verification_link.'" target="'."_blank".'">'."Verify Email".'</a>';

        Mail::to($request->email)->send(new Websitemail($message));

        $notification = array([
            'status' => true,
            'message' => 'Verify link sent on your email'
        ]);

        return redirect()->route('login')->with('notification',$notification);

    }

    public function registrationVerify($token,$email){

        $user = User::where('token',$token)->where('email',$email)->first();

        if(!$user){

            $notification = array([
                'status' => false,
                'message' => 'Invalid link'
            ]);

            return redirect()->route('login')->with('notification',$notification);

        }else{
            $user->status = 'active';
            $user->token ='';
            $user->save();

            $notification = array([
                'status' => true,
                'message' => $user->name.' you are register successfully'
            ]);

            return redirect()->route('dashboard')->with('notification',$notification);
        }

    }

    public function userLoginSubmit(Request $request){

        $credentials = [

            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active'

        ];

        if(Auth::attempt($credentials)){

            if(Auth::guard('web')->user()->role == 1){
                return redirect()->route('dashboard');
            }else if(Auth::guard('web')->user()->role == 0){
                return redirect()->route('home');
            }else{
                return redirect()->route('login');
            }

        }else{
            return redirect()->route('login');
        }

    }

    public function logout(){

        Auth::guard('web')->logout();

        return redirect()->route('login');

    }

    public function forgetPassword(){
        return view('auth.forget_password');
    }

    public function forgetPasswordSubmit(Request $request){

        $token = hash('sha256',time());

        $user = User::where('email',$request->email)->first();

        if(!$user){
            return redirect()->back();
        }

        $user->token = $token;
        $user->update();

        $reset_link = url('reset-password/'.$token.'/'.$request->email);

        $message = 'Please click on the following link: <br><a href="'.$reset_link.'">'."Click here".'</a>';

        Mail::to($request->email)->send(new Websitemail($message));

        return redirect()->back();

    }

    public function resetPassword($token, $email){

        $user = User::where('token',$token)->where('email',$email)->first();

        if(!$user){
            return redirect()->route('forget.password');
        }

        return view('auth.reset_password',compact('token','email'));

    }

    public function resetPasswordSubmit(Request $request){

        $user = User::where('email',$request->email)->where('token',$request->reset_token)->first();

        if(!$user){
            echo "Email and Token Invalid";
        }else{

            $password_match = Hash::check($request->old_password,$user->password);

            if($password_match == true){

                if($request->new_password != $request->new_retype_password){
                    echo "The two password combinatins doesn't match";
                }else{

                    $user->token = '';
                    $user->password = Hash::make($request->new_password);
                    $user->update();

                    return redirect()->route('login');
                }

            }else{
                echo "Previous password doesn't match";

                return redirect()->route('forget.password');
            }

        }


    }




}
