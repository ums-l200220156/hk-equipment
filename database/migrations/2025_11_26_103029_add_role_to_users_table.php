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
        Schema::table('users', function (Blueprint $table) {
            // 1. Tambahkan kolom 'role'
            $table->string('role')->default('customer')->after('email'); // 'admin' atau 'customer'

            // 2. Kolom 'name' sudah ada di migration standar Laravel, 
            //    tapi jika ingin memastikan atau mengubah posisinya:
            //    $table->string('name')->nullable()->after('role'); 
            //    Jika kolom 'name' sudah ada dan Anda tidak ingin memindahkannya, baris ini bisa dihilangkan.

            // 3. Tambahkan kolom 'phone' (hanya satu kali)
            $table->string('phone')->nullable()->after('role');

            // 4. Tambahkan kolom 'address' untuk alamat lengkap
            $table->text('address')->nullable()->after('phone'); // Menggunakan 'text' untuk alamat lengkap

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pastikan hanya kolom yang Anda tambahkan di 'up()' yang dihapus.
            // Kolom 'name' tidak perlu dihapus karena sudah ada di migration awal.
            $table->dropColumn(['role', 'phone', 'address']);
        });
    }
};