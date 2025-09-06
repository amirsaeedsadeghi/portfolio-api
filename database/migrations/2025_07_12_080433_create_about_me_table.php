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
        Schema::create('about_me', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('summary');
            $table->string('location');
            $table->integer('years_of_experience');
            $table->json('language');
            $table->string('currently_learning');
            $table->boolean('availability')->default(true);
            $table->string('cv_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_me');
    }
};
