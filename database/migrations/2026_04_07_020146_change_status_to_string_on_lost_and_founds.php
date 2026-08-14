<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sintaks PostgreSQL untuk alter column type & default value
        DB::statement("ALTER TABLE lost_and_founds ALTER COLUMN status TYPE VARCHAR(255), ALTER COLUMN status SET DEFAULT 'Mencari'");
        
        // Tabel lost_found_claims
        DB::statement("ALTER TABLE lost_found_claims ALTER COLUMN status TYPE VARCHAR(255), ALTER COLUMN status SET DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};