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
        Schema::create('project_stack', function (Blueprint $table) {
            $table->foreignId('project_id');
            $table->foreign('project_id')->on('projects')->references('id')->onDelete('cascade');
            $table->foreignId('stack_id');
            $table->foreign('stack_id')->on('stacks')->references('id')->onDelete('cascade');
            $table->primary(['project_id','stack_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_stack');
    }
};
