<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MultiAuthentication
{

    // public function handle(Request $request, Closure $next): Response
    // {

    //     if(auth()->user()->role == 1){
    //         return $next($request);
    //     }

    //     return redirect()->route('home');

    // }
}
