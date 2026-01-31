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
    Schema::create('rentals', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');

        $table->date('rent_date');                // tanggal sewa
        $table->time('start_time');               // jam mulai
        $table->integer('duration_hours');        // lama sewa (jam)

        $table->string('location');               // lokasi proyek
        $table->text('notes')->nullable();        // catatan tambahan

        $table->enum('status', ['pending', 'approved', 'on_progress', 'completed'])
              ->default('pending');

        $table->decimal('total_price', 12, 2);    // otomatis dihitung

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('rentals');
}

};
