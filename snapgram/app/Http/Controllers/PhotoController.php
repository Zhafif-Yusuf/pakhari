<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Photo;
use App\Models\LikePhoto;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller{

   // Menampilkan daftar foto dari album yang dipilih
    public function index(Album $album) {
    // Memuat relasi photos dari album
    $album->load('photos');

    // Mengembalikan tampilan daftar foto dari album
    return view('photos.index', compact('album'));
    }



    public function create() {
        // Mengambil daftar album milik pengguna yang sedang login
        $albums = Album::where('userID', auth()->id())->get();
        // Mengembalikan tampilan form untuk menambah foto
        return view('photos.create', compact('albums'));
    }
    


    public function store(Request $request) {
        // Validasi input dari form
        $request->validate([
            // Foto harus ada dan harus berformat gambar dengan ukuran maksimal 2048KB
            'photo' => 'required|image|max:2048',
            // Judul foto harus ada dan maksimal 255 karakter
            'judulFoto' => 'required|string|max:255',
            // Deskripsi foto bersifat opsional, maksimal 255 karakter
            'description' => 'nullable|string|max:255',
            // Album ID harus ada dan valid
            'albumID' => 'required|exists:albums,albumID',
        ]);
    
        // Menyimpan foto ke storage dan mendapatkan path-nya
        $photo = $request->file('photo');
        $path = $photo->store('photos', 'public');
    
        // Membuat entri foto baru di database
        Photo::create([
            'userID' => auth()->id(),
            'lokasiFile' => $path,
            'judulFoto' => $request->judulFoto,
            'deskripsiFoto' => $request->description,
            'tanggalUnggah' => now(),
            'albumID' => $request->albumID,
        ]);
    
        // Mengalihkan pengguna ke halaman utama setelah berhasil
        return redirect()->route('home');
    }


    
    public function show(Photo $photo) {
        // Menampilkan detail foto berdasarkan model yang dipassing
    }



    // Mengupdate informasi foto
    public function update(Request $request, Photo $photo) {
    // Memastikan hanya pemilik foto yang dapat mengupdate
    if ($photo->userID !== Auth::id()) {
        // Menghentikan eksekusi jika pengguna tidak berwenang
        abort(403, 'Unauthorized action.');
    }
    // Validasi input dari form
    $request->validate([
        // Judul foto harus ada dan maksimal 255 karakter
        'judulFoto' => 'required|string|max:255',
        // Deskripsi foto bersifat opsional, maksimal 255 karakter
        'description' => 'nullable|string|max:255',
    ]);
    // Jika ada foto baru, validasi dan simpan foto baru
    if ($request->hasFile('photo')) {
        // Validasi foto baru
        $request->validate(['photo' => 'image|max:2048']);
        
        // Menghapus foto lama dari storage
        Storage::delete($photo->lokasiFile);

        // Menyimpan foto baru
        $path = $request->file('photo')->store('photos', 'public');
        // Mengupdate path foto
        $photo->lokasiFile = $path;
    }
    // Mengupdate informasi judul dan deskripsi foto
    $photo->judulFoto = $request->judulFoto;
    $photo->deskripsiFoto = $request->description;

    // Menyimpan perubahan di database
    $photo->save();

    // Mengalihkan pengguna kembali ke album foto setelah berhasil diupdate
    return redirect()->route('albums.photos', $photo->albumID);
    }



    // Menghapus foto
    public function destroy(Photo $photo) {
    // Memastikan hanya pemilik foto yang dapat menghapus
    if ($photo->userID !== Auth::id()) {
        // Menghentikan eksekusi jika pengguna tidak berwenang
        abort(403, 'Unauthorized action.');
    }
    // Menghapus foto dari storage
    Storage::delete($photo->lokasiFile);

    // Menghapus entri foto dari database
    $photo->delete();

    // Mengalihkan pengguna kembali ke halaman album setelah foto dihapus
    return redirect()->route('albums.photos', $photo->albumID);
    }



    // Menampilkan form untuk mengedit foto
    public function edit(Photo $photo) {
    // Memastikan hanya pemilik foto yang dapat mengedit
    if ($photo->userID !== Auth::id()) {
        // Menghentikan eksekusi jika pengguna tidak berwenang
        abort(403, 'Unauthorized action.');
    }

    // Mengambil daftar album milik pengguna
    $albums = Album::where('userID', Auth::id())->get();

    // Mengembalikan tampilan form edit foto
    return view('photos.edit', compact('photo', 'albums'));
    }



    // Menyukai atau membatalkan like pada foto
    public function like(Photo $photo) {
    // Memeriksa apakah foto sudah disukai oleh pengguna
    if ($photo->isLikedByAuthUser()) {
        // Jika sudah disukai, hapus like dari database
        $photo->likes()->where('userID', Auth::id())->delete();
    } else {
        // Jika belum disukai, buat entri like baru di database
        $photo->likes()->create([
            'userID' => Auth::id(),
            'fotoID' => $photo->fotoID,
            'tanggalLike' => now(),
        ]);
    }

    // Mengalihkan pengguna kembali ke halaman utama
    return redirect()->route('home');
}



    
    // Menampilkan komentar pada foto
    public function showComments(Photo $photo) {
    // Memuat foto beserta relasi komentar dan user
    $photo->load('comments.user');
    
    // Mengembalikan tampilan komentar foto
    return view('photos.comment', compact('photo'));
}



    
    // Menyimpan komentar baru
    public function storeComment(Request $request, Photo $photo) {
    // Validasi input komentar
    $request->validate([
        // Komentar harus ada dan maksimal 200 karakter
        'isiKomentar' => 'required|string|max:200',
    ]);

    // Membuat entri komentar baru di database
    Comment::create([
        'isiKomentar' => $request->isiKomentar,
        // Mengaitkan komentar dengan foto yang bersangkutan
        'fotoID' => $photo->fotoID,
        // Mengaitkan komentar dengan pengguna yang sedang login
        'userID' => Auth::id(),
    ]);

    // Mengalihkan pengguna kembali ke halaman komentar foto setelah berhasil
    return redirect()->route('photos.comments', $photo->fotoID);
}

}
