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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // Kolom untuk menyimpan path gambar
            $table->string('alamat'); // Kolom untuk nama customer
            $table->text('alamat'); // Kolom untuk alamat customer
            $table->string('phone'); // Kolom untuk nomor telepon customer
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
