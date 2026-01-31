<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE equipment 
            MODIFY status ENUM('available','rented','maintenance') 
            DEFAULT 'available'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE equipment 
            MODIFY status ENUM('available','rented') 
            DEFAULT 'available'
        ");
    }
};
