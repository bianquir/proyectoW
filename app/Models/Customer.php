<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['dni', 'cuil', 'name', 'lastname', 'phone_number', 'email','address'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'assigned_tags');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'customer_id'); 
    }
}
