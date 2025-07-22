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
        Schema::create('a07_dm_nama_dok', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode')->unique();
            $table->string('id_kode_a06');
            $table->string('nama_dok');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('id_kode_a06')->references('id_kode')->on('a06_dm_kategori_dok')->onDelete('cascade');
            $table->foreign('created_by')->references('id_kode')->on('a01_dm_users')->onDelete('set null');
            $table->foreign('updated_by')->references('id_kode')->on('a01_dm_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a07_dm_nama_dok');
    }
};