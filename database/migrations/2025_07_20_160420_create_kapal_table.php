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
        Schema::create('a05_dm_kapal', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode')->unique();
            $table->string('nama_prsh');
            $table->string('no_imo')->nullable();
            $table->string('nama_kpl');
            $table->string('jenis_kpl');
            $table->string('tonase_kpl')->nullable();
            $table->string('tanda_panggilan_kpl')->nullable();
            $table->integer('awak_kpl')->nullable();
            $table->integer('penumpang_kpl')->nullable();
            $table->string('bendera_kpl')->nullable();
            $table->year('thn_pbtn_kpl')->nullable();
            $table->string('asal_pbtn_kpl')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('nama_prsh')->references('id_kode')->on('a03_dm_perusahaan')->onDelete('cascade');
            $table->foreign('jenis_kpl')->references('id_kode')->on('a04_dm_jenis_kpl')->onDelete('cascade');
            $table->foreign('created_by')->references('id_kode')->on('a01_dm_users')->onDelete('set null');
            $table->foreign('updated_by')->references('id_kode')->on('a01_dm_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a05_dm_kapal');
    }
};