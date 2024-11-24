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
        Schema::create('suplais', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->date('tanggal'); // Kolom untuk menyimpan tanggal suplai
            $table->foreignId('nama_produk')->constrained('produks')->onDelete('cascade'); // Relasi ke tabel 'produks'
            $table->integer('jumlah_suplai'); // Kolom untuk jumlah suplai
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suplais');
    }
};
