<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class HomeController extends Controller {

    // Fungsi untuk menampilkan halaman home
    public function index() {
        // Mengambil semua foto untuk ditampilkan di halaman home
        $photos = Photo::all();
        return view('home', compact('photos'));
    }
}
