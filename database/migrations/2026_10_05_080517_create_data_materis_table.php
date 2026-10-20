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
            $table->integer('urutan_materi')->default(0);
            $table->foreignId('permainan_id')->constrained('data_permainans')->onUpdate('cascade');

            $table->string('nama_materi');
            $table->enum('jenis_materi', ['Teks', 'Gambar', 'Video'])->default('Teks');
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
