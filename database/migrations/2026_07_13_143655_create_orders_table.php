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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kasir')->constrained('users')->onDelete('cascade'); // Cashier who made the order
            $table->string('nomor_pesanan')->unique();
            $table->string('nama_pelanggan')->nullable();
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->decimal('pajak', 10, 2)->default(0);
            $table->decimal('total_keseluruhan', 10, 2)->default(0);
            $table->string('status')->default('pending'); // pending, preparing, completed, cancelled
            $table->string('metode_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
