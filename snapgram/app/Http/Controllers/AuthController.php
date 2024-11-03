<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    
    // Menampilkan halaman login
    public function showLoginForm() {
        return view('auth.login');
    }
public function postLogin(Request $request) {
$request->validate([
    'username'=> 'required|string',
    'password'=> 'required|string',
]);    
$credentials = $request->only('username', 'password');
if (Auth::attempt($credentials)){
    //Login Berhasil
    return redirect()->route('home');
}
//Login gagal
return back ();
}
//Menampilkan Halaman registrasi
public function showRegistrationForm(){
    return view('auth.register');
}

public function register(Request $request){
    $request->validate([
        'username' => 'required|string|unique:users,username|max:255',
        'password' => 'required|string|confirmed|min:8',
    ]);
    //Membuat Pengguna Akun Baru
    User::create([
        'username' => $request->username,
        'password' => Hash::make($request->password),
    ]);
    //
    return redirect()->route('login');

}

public function logout(Request $request){
    Auth::logout();
    return redirect()->route('login');
}
}