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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
        
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('response_id')->references('id')->on('messages')->onDelete('set null');
        });

        Schema::table('assigned_tags', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');    
        });

        Schema::table('media_files', function (Blueprint $table) {
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade'); // Si se elimina un mensaje, se eliminan sus archivos multimedia
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']);
        });
        
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['response_id']);
        });
        
        Schema::table('assigned_tags', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['tag_id']);
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        Schema::table('media_files', function (Blueprint $table) {
            $table->dropForeign(['message_id']);
        });
        
        // Ahora se pueden eliminar las tablas principales y dependientes
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('assigned_tags');
        Schema::dropIfExists('media_files');
    }
};
