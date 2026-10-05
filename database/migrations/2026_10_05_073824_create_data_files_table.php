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
        Schema::create('data_files', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('deskripsi_file')->nullable();
            $table->enum('tipe_file', ['pdf', 'docx', 'xlsx', 'jpg', 'png'])->nullable();
            $table->text('path_file');

            $table->foreignId('uploader_id')->constrained('users')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('data_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('deskripsi_file')->nullable();
            $table->text('thumbnail_path');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_files');
        Schema::dropIfExists('data_thumbnails');
    }
};
