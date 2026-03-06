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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->string('tagline', 100);
            $table->text('description');
            $table->string('logo')->nullable();
            $table->string('website_url');
            $table->foreignId('category_id')->constrained();
            $table->enum('listing_type', ['launch', 'directory', 'both'])->default('both');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->date('featured_until')->nullable();
            $table->date('launch_date')->nullable();
            $table->unsignedInteger('vote_count')->default(0);
            $table->boolean('is_do_follow')->default(false);
            $table->string('twitter_handle')->nullable();
            $table->text('maker_comment')->nullable();
            $table->enum('pricing', ['free', 'freemium', 'paid'])->default('free');
            $table->timestamps();

            $table->index('status');
            $table->index('launch_date');
            $table->index('listing_type');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
