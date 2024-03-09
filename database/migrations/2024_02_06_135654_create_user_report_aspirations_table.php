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
        Schema::create('user_report_aspirations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('aspirationId')->references('id')->on('aspirations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('reportReason', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_report_aspirations');
    }
};
