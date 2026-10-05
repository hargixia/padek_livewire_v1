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
        Schema::create('data_permainans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_permainan');
            $table->string('deskripsi_permainan')->nullable();

            $table->foreignId('pembuat_id')->constrained('users')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_permainans');
    }
};
