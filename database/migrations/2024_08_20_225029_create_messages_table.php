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

            //Mensajes generales
            $table->text('message');
            $table->enum('message_type', ['text', 'image', 'video', 'document', 'audio', 'sticker', 'location', 'contacts', 'reaction', 'poll', 'button', 'template']);
            $table->enum('direction', ['incoming', 'outbound']);
            $table->enum('status', ['sent', 'delivered', 'read', 'failed', 'pending', 'received'])->default('sent');

            //Ubicacion
            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();

            //Documentos
            $table->string('document_name')->nullable();

            //Reacciones
            $table->string('reaction_emoji')->nullable();
            $table->string('reaction_message_id')->nullable();

            //Contacto
            $table->string('contact_name')->nullable();
            $table->text('contact_phone_numbers')->nullable();
            $table->text('contact_emails')->nullable();


            $table->unsignedBigInteger('response_id')->nullable(); 
            $table->string('whatsapp_message_id')->nullable();
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
