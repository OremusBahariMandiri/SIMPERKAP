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
        Schema::create('a04_dm_jenis_kpl', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode')->unique();
            $table->string('jenis_kpl');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id_kode')->on('a01_dm_users')->onDelete('set null');
            $table->foreign('updated_by')->references('id_kode')->on('a01_dm_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a04_dm_jenis_kpl');
    }
};