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
            $table->string('dni')->nullable()->index();
            $table->string('cuil')->nullable();
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->string('wa_id', 20)->index();
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable();
            $table->boolean('whatsapp_opt_in')->default(true);
            $table->timestamps();
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
