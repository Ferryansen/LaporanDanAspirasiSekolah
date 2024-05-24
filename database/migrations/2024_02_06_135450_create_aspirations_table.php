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
            $table->string('aspirationNo', 20);
            $table->foreignId('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('description', 255);
            $table->boolean('isPinned');
            $table->boolean('isChatOpened');
            $table->dateTime('processDate')->nullable();
            $table->foreignId('processedBy')->nullable()->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('approvedBy')->nullable()->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('status', 20);
            $table->integer('likeCount')->nullable();
            $table->integer('dislikeCount')->nullable();
            $table->integer('problematicAspirationCount')->nullable();
            $table->string('deletedBy', 255)->nullable();
            $table->string('deleteReason', 255)->nullable();
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
