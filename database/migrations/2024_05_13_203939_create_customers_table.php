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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('dni')->unique(); 
            $table->bigInteger('cuil')->unique();
            $table->integer('id_message')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable(); 
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->string('phone'); 
            $table->string('email')->unique()->nullable();
            $table->timestamps();

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
