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
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->nullable()->references('id')->on('reports')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('aspiration_id')->nullable()->references('id')->on('aspirations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('name', 255);
            $table->string('context', 255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};
