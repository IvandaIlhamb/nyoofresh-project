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
        Schema::create('hasil_penjualans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal'); // Kolom untuk menyimpan tanggal 
            $table->foreignId('id_suplai')->constrained('suplais')->onDelete('cascade'); // Relasi ke tabel 'produks'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_penjualans');
    }
};
