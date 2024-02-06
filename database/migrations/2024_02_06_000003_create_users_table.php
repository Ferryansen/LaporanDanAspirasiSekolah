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
            $table->string('user_no', 10);
            $table->foreignId('staff_type_id')->nullable()->references('id')->on('staff_types')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('password');
            $table->char('phone_number', 12);
            $table->string('gender', 6);
            $table->date('birth_date');
            $table->string('role', 15);
            $table->boolean('is_suspended');
            $table->string('suspend_reason', 255)->nullable();
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
