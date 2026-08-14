<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_report_upvotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Prevent a user from upvoting the same report multiple times
            $table->unique(['facility_report_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_report_upvotes');
    }
};
