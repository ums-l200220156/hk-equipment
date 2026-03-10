<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE rentals 
            MODIFY status VARCHAR(30) 
            NOT NULL DEFAULT 'waiting_payment'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE rentals 
            MODIFY status ENUM(
                'pending',
                'approved',
                'on_progress',
                'completed'
            ) DEFAULT 'pending'
        ");
    }
};