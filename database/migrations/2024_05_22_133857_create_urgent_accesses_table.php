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
        Schema::create('urgent_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->references('id')->on('reports')->onUpdate('cascade')->onDelete('cascade');
            $table->string('accessCode', 7)->unique();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('accessCode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urgent_accesses');
    }
};
