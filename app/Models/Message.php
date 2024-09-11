<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'customer_id',
        'message',
        'message_type',
        'direction',
        'status',
        'latitude',
        'longitude',
        'document_name',
        'reaction_emoji',
        'reaction_message_id',
        'contact_name',
        'contact_phone_numbers',
        'contact_emails',
        'response_id',
        'whatsapp_message_id',
        'timestamp'
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function parentMessage()
    {
        return $this->belongsTo(Message::class, 'response_id');
    }

    // Relación con los mensajes que responden a este mensaje
    public function replies()
    {
        return $this->hasMany(Message::class, 'response_id');
    }

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class, 'message_id');
    }
}
