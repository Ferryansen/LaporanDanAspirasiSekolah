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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('userNo', 12);
            $table->foreignId('staffType_id')->nullable()->references('id')->on('staff_types')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('password');
            $table->char('phoneNumber', 12);
            $table->string('gender', 6);
            $table->date('birthDate');
            $table->string('role', 15);
            $table->boolean('isSuspended');
            $table->string('suspendReason', 255)->nullable();
            $table->date('suspendDate')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
