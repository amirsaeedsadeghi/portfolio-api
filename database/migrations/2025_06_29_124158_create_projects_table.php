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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('summary');
            $table->text('description');
            $table->string('primary_image');
            $table->string('client')->nullable();
            $table->string('demo_link')->nullable();
            $table->string('github')->nullable();
            $table->string('category');
            $table->string('role');
            $table->integer('order')->default(1);
            $table->date('start_date')->nullable();
            $table->string('slug');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
