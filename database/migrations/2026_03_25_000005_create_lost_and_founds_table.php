<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lost_and_founds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Reporter
            $table->enum('type', ['lost', 'found']);
            $table->text('item_characteristics');
            $table->string('last_location');
            $table->string('photo')->nullable();
            $table->enum('status', [
                'Mencari', 
                'Diamankan Admin', 
                'Menunggu Verifikasi', 
                'Dikembalikan'
            ])->default('Mencari');
            $table->foreignId('claimed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_and_founds');
    }
};
