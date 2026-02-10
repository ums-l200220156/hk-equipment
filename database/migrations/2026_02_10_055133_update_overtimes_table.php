<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('overtimes', function (Blueprint $table) {
        // Kolom untuk merekam waktu kerja lembur yang sebenarnya
        if (!Schema::hasColumn('overtimes', 'started_at')) {
            $table->timestamp('started_at')->nullable();
        }
        if (!Schema::hasColumn('overtimes', 'ended_at')) {
            $table->timestamp('ended_at')->nullable();
        }
        // Kolom untuk harga lembur yang ditentukan Admin
        if (!Schema::hasColumn('overtimes', 'price_per_hour')) {
            $table->decimal('price_per_hour', 15, 2)->default(0);
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
