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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_no', 20);
            $table->foreignId('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('description', 255);
            $table->boolean('is_urgent');
            $table->boolean('is_chat_opened');
            $table->dateTime('process_date')->nullable();
            $table->dateTime('process_estimation_date')->nullable();
            $table->string('processed_by', 255)->nullable();
            $table->string('status', 20);
            $table->string('deleted_by', 255)->nullable();
            $table->string('delete_reason', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
