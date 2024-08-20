<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'phone_number',
        'message',
        'message_type',
        'status',
        'response_id'
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
