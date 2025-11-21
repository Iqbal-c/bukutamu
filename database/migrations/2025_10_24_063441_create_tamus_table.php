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
        Schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('alamat', 255);
            $table->string('no_hp', 20);
            $table->text('pesan_kesan');
            //$table->string('paraf')->nullable(); // Upload gambar paraf
            $table->text('paraf')->nullable();
            $table->string('foto')->nullable();  // Upload foto tamu
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};
