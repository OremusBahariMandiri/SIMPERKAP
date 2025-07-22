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
        Schema::create('b01_dokumen_kpl', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode')->unique();
            $table->string('no_reg')->unique();
            $table->string('nama_kpl');
            $table->string('kategori_dok');
            $table->string('nama_dok');
            $table->string('penerbit_dok')->nullable();
            $table->string('validasi_dok')->nullable();
            $table->date('tgl_terbit_dok')->nullable();
            $table->date('tgl_berakhir_dok')->nullable();
            $table->string('masa_berlaku')->nullable();
            $table->date('tgl_peringatan')->nullable();
            $table->string('masa_peringatan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('file_dok')->nullable();
            $table->string('status_dok')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('nama_kpl')->references('id_kode')->on('a05_dm_kapal')->onDelete('cascade');
            $table->foreign('kategori_dok')->references('id_kode')->on('a06_dm_kategori_dok')->onDelete('cascade');
            $table->foreign('nama_dok')->references('id_kode')->on('a07_dm_nama_dok')->onDelete('cascade');
            $table->foreign('created_by')->references('id_kode')->on('a01_dm_users')->onDelete('set null');
            $table->foreign('updated_by')->references('id_kode')->on('a01_dm_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b01_dokumen_kpl');
    }
};