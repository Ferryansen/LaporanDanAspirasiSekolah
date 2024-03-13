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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->nullable()->references('id')->on('reports')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('aspirationId')->nullable()->references('id')->on('aspirations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('staff')->references('id')->on('users');
            $table->string('staffMessage', 255);
            $table->foreignId('student')->references('id')->on('users');
            $table->string('studentMessage', 255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
