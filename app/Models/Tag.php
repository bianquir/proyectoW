<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    use HasFactory;
    protected $fillable=['name_tag', 'description', 'color'];
    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
