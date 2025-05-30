<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(){
        return view('login');
    }

    public function handleLogin(Request $request){
        Auth::loginUsingId($request->id);
        return redirect('/');
    }
    
    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
