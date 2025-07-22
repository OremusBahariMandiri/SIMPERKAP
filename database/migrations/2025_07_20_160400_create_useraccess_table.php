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
        Schema::create('a02_dm_user_access', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode_a01');
            $table->string('menu_acs');
            $table->boolean('tambah_acs')->default(false);
            $table->boolean('ubah_acs')->default(false);
            $table->boolean('hapus_acs')->default(false);
            $table->boolean('download_acs')->default(false);
            $table->boolean('detail_acs')->default(false);
            $table->boolean('monitoring_acs')->default(false);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('id_kode_a01')->references('id_kode')->on('a01_dm_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a02_dm_user_access');
    }
};