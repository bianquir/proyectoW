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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->text('message');
            $table->enum('message_type', ['text', 'image', 'video', 'document', 'audio', 'sticker', 'location', 'contact', 'poll', 'button', 'template']);
            $table->enum('direction', ['incoming', 'outgoing']);
            $table->enum('status', ['sent', 'delivered', 'read', 'failed', 'pending', 'received'])->default('sent');
            $table->unsignedBigInteger('response_id')->nullable(); 
            $table->string('whatsapp_message_id')->nullable();
            $table->string('media_url')->nullable();
            $table->string('caption')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
