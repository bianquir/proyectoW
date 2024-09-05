<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'message',
        'message_type',
        'direction',
        'status',
        'response_id',
        'media_url',
        'caption'
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
}
