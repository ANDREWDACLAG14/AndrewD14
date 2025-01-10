<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function loginform(){
        return view('loginform');
    }

    public function registerform(){
        return view('registerform');
    }

    public function dashboard(){
        return view('dashboard');
    }

    public function profile(){
        return view('profile');
    }

    public function logout()
    {
        auth::logout();
        return redirect('/')->with('logout_message', 'GOODBYE');
    }

    public function submit(Request $request)
    {
        $IncomingFields = $request->validate([
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        User::create($IncomingFields);
        return redirect('/')->with('success_msg', 'Registration successful! Please log in.');
    }

    public function login(Request $request)
    {
        $IncomingFields = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (auth::attempt([
            'username' => $IncomingFields['username'],
            'password' => $IncomingFields['password'],
            ]))
            {
                $request->session()->regenerate();
                return redirect('/dashboard');
            }
            else {
                return redirect('/')->with('incorrect_msg', 'Incorrect Credentials. Login Unsuccesful.');
            }
    }
}
