<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void {
        Schema::create('photos', function (Blueprint $table) {
            $table->id('fotoID');
            $table->string('judulFoto');
            $table->text('deskripsiFoto')->nullable();
            $table->date('tanggalUnggah');
            $table->string('lokasiFile');
            $table->foreignId('albumID')
                ->constrained('albums', 'albumID')->onDelete('cascade');
            $table->foreignId('userID')
                ->constrained('users', 'userID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('photos');
    }
};
