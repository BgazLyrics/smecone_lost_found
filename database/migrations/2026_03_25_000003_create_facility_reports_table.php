<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->nullable();
            $table->string('evidence_photo');
            $table->text('description');
            $table->enum('status', [
                'Menunggu', 
                'Ditolak', 
                'Diproses', 
                'Selesai (Diperbaiki)', 
                'Selesai (Diganti Baru)'
            ])->default('Menunggu');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_reports');
    }
};
