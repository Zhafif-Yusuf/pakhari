<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model {
    protected $primaryKey = 'albumID'; 
    protected $fillable = [ 'namaAlbum','deskripsi',
                            'tanggalDibuat','userID'];
    /**
     * Relasi one-to-one antara Album dan User.
     * Setiap album dimiliki oleh satu user.
     * Relasi ini menggunakan foreign key 'userID'.
     */
    public function user() {
        return $this->belongsTo(User::class, 'userID');
    }
    /**
     * Relasi one-to-many antara Album dan Photo.
     * Setiap album bisa memiliki banyak foto.
     * Relasi ini menggunakan foreign key 'albumID'.
     */
    public function photos() {
        return $this->hasMany(Photo::class, 'albumID');
    }
}
