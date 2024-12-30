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
            $table->string('nama_supplier');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel 'produks'
            $table->date('tanggal')->nullable(); // Kolom untuk menyimpan tanggal suplai
            $table->enum('status', ['Buka', 'Tutup'])->default('Tutup')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->foreign('id_produk')->references('id')->on('produks')->onDelete('cascade');
            $table->integer('jumlah_suplai')->nullable(); // Kolom untuk jumlah suplai
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