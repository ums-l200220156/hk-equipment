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
    Schema::create('equipment', function (Blueprint $table) {
        $table->id();
        $table->string('name');                         // Nama alat
        $table->string('category');                     // Excavator / Excavator Breaker / Dump Truck
        $table->text('description')->nullable();        // Deskripsi
        $table->integer('year')->nullable();            // Tahun
        $table->string('brand')->nullable();            // Merk
        $table->decimal('price_per_hour', 12, 2);        // Harga sewa
        $table->enum('status', ['available', 'rented'])->default('available');
        $table->string('image')->nullable();            // Foto alat
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('equipment');
}

};
