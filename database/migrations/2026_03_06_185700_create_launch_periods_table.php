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
        Schema::create('launch_periods', function (Blueprint $table) {
            $table->id();
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->enum('status', ['upcoming', 'active', 'completed'])->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('launch_periods');
    }
};
