<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    protected $primaryKey = 'komentarID';
    protected $fillable = ['isiKomentar', 'fotoID', 'userID'];

    public function photo() {
        return $this->belongsTo(Photo::class, 'fotoID');
    }

    public function user() {
        return $this->belongsTo(User::class, 'userID');
    }
}
