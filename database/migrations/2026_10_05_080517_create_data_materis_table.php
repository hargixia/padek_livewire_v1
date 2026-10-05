<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_materis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('permainan_id')->constrained('data_permainans')->onUpdate('cascade');
            $table->foreignId('thumbnail_id')->constrained('data_thumbnails')->onUpdate('cascade');

            $table->string('nama_materi');
            $table->string('deskripsi_materi')->nullable();
            $table->text('isi_materi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_materis');
    }
};
