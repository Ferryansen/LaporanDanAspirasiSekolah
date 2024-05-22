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
            $table->string('reportNo', 20);
            $table->foreignId('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('description', 255);
            $table->boolean('isUrgent');
            $table->boolean('isChatOpened');
            $table->dateTime('processDate')->nullable();
            $table->dateTime('processEstimationDate')->nullable();
            $table->foreignId('processedBy')->nullable()->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('approvalBy', 255)->nullable();
            $table->string('lastUpdatedBy', 255)->nullable();
            $table->string('status', 255);
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
        Schema::dropIfExists('reports');
    }
};