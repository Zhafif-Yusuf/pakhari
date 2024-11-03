<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('komentarID');
            $table->text('isiKomentar');
            $table->foreignId('fotoID')->constrained('photos', 'fotoID')
            ->onDelete('cascade');
            $table->foreignId('userID')->constrained('users', 'userID')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('comments');
    }
};
