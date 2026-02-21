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
    Schema::create('finances', function (Blueprint $table) {
        $table->id();
        // 'income' (masuk) atau 'expense' (keluar)
        $table->string('type')->default('expense'); 
        
        // Kategori: 'Maintenance', 'Gaji', 'Sewa Alat', dll
        $table->string('category'); 
        
        // Nominal uang
        $table->decimal('amount', 15, 2); 
        
        // Catatan tambahan (misal: Gaji operator si A)
        $table->string('description')->nullable(); 
        
        // Tanggal transaksi (agar bisa difilter bulanan/mingguan)
        $table->date('transaction_date'); 
        
        // Opsional: Untuk menghubungkan ke ID transaksi rental/overtime jika tipenya income
        $table->unsignedBigInteger('reference_id')->nullable(); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
