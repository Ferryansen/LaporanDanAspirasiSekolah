<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('description', 1005);
            $table->foreignId('consultant')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->json('attendees')->nullable();
            $table->integer('attendeeLimit');
            $table->string('location', 255)->nullable();
            $table->string('status', 255);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->boolean('is_online');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultation_events');
    }
};
