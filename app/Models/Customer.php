<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['dni', 'cuil', 'id_message', 'tag_id', 'order_id', 'name', 'lastname', 'phone', 'email'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
