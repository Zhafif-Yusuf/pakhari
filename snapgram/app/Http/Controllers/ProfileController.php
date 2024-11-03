<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller {
    public function index() {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request) {
        // Mendapatkan user yang sedang login
        $user = Auth::user();
        
        // Melakukan validasi input yang dikirimkan dari form
        $request->validate([
            // username wajib diisi, harus berupa string, dan maksimal 255 karakter
            'username' => 'required|string|max:255',
            // nama lengkap wajib diisi, harus berupa string, dan maksimal 255 karakter
            'namaLengkap' => 'required|string|max:255',
            // email wajib diisi, harus berupa string, harus format email valid, dan maksimal 255 karakter
            'email' => 'required|string|email|max:255',
            // password boleh tidak diisi (nullable), minimal 8 karakter, dan harus dikonfirmasi dengan password_confirmation
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        // Memperbarui atribut user dengan input yang valid
        $user->username = $request->username;
        $user->namaLengkap = $request->namaLengkap;
        $user->email = $request->email;
    
        // Jika password diisi, maka update password dengan hash
        if ($request->filled('password')) {
            // Mengenkripsi password sebelum disimpan
            $user->password = Hash::make($request->password);
        }
    
        // Menyimpan perubahan profil user ke database
        $user->save();
    
        // Mengarahkan kembali pengguna ke halaman profil setelah update berhasil
        return redirect()->route('profile.index');
    }
    
}
