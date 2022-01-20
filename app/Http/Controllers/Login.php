<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{

    public function __construct()
    {
        if (Auth::viaRemember()) {
            return redirect()->route('user.index');
        }
    }

    public function index()
    {
        Auth::logout();
        Session::forget('log');
        return view('auth/index');
    }

    public function process(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required|max:100',
            'password'  => 'required|max:100',
        ]);

        $user = $request->username;
        $pass = $request->password;
        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt(['username' => $user, 'password' => $pass, 'active' => 1], $remember)) {
            // Authentication passed...
            session()->put('log', Auth::user()->role->nama_role);
            // dd(session('log'));
            return redirect()->route('user.index');
        } else {
            return redirect()->route('auth')->with('alert', 'Username atau password salah!')->withInput();
        }
    }

    public function logout()
    {
        // Hapus semua data pada session
        // session()->destroy();

        // redirect ke halaman login	
        return redirect()->route('auth');
    }
}
