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
            $table->integer('priority');
            $table->boolean('isUrgent');
            $table->boolean('isFromConsultation');
            $table->string('consultationName', 255)->nullable();
            $table->date('consultationDate')->nullable();
            $table->boolean('isChatOpened');
            $table->dateTime('processDate')->nullable();
            $table->date('processEstimationDate')->nullable();
            $table->foreignId('processedBy')->nullable()->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('approvalBy', 255)->nullable();
            $table->string('lastUpdatedBy', 255)->nullable();
            $table->string('status', 255);
            $table->string('rejectReason', 255)->nullable();
            $table->string('closedReason', 255)->nullable();
            $table->timestamps();
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