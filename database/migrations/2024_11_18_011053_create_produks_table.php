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
        Schema::create('produks', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->tinyInteger('is_active')->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel 'produks'
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel 'produks'
            $table->string('nama_produk')->default('Nama Produk Default'); // Nama produk
            $table->text('deskripsi')->nullable(); // Deskripsi produk (opsional)
            $table->decimal('harga_kulak', 10, 2); // Harga kulak dengan format desimal
            $table->decimal('harga_jual', 10, 2); // Harga jual dengan format desimal
            $table->string('foto_produk')->nullable(); // URL atau path ke foto produk (opsional)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
