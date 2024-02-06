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
        Schema::create('aspirations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('aspiration_no', 20);
            $table->foreignId('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('description', 255);
            $table->boolean('is_pinned');
            $table->boolean('is_chat_opened');
            $table->dateTime('process_date')->nullable();
            $table->string('processedBy', 255)->nullable();
            $table->string('status', 20);
            $table->integer('like_count')->nullable();
            $table->integer('dislike_count')->nullable();
            $table->integer('problematic_aspiration_count')->nullable();
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
        Schema::dropIfExists('aspirations');
    }
};
