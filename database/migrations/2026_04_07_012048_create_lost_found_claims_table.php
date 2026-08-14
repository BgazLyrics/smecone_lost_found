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
        Schema::create('lost_found_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lost_found_id')->constrained('lost_and_founds')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('proof_description');
            $table->string('proof_photo')->nullable();
            $table->string('status')->default('Menunggu'); // Menunggu, Disetujui, Ditolak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_found_claims');
    }
};
