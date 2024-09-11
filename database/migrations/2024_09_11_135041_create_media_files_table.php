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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');  // Relación con el mensaje
            $table->string('media_type');              // Tipo de archivo (image, video, audio, etc.)
            $table->string('media_url');               // URL o ID del archivo
            $table->string('media_extension');         // Tipo MIME del archivo (image/jpeg, video/mp4, etc.)
            $table->string('media_sha256')->nullable(); // Hash SHA-256 para validar el archivo
            $table->string('caption')->nullable();     // Descripción del archivo (si existe)
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
