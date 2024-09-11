<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;
    protected $fillable= ['message_id', 'media_type', 'media_url', 'media_extension', 'media_sha256', 'caption'];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}
