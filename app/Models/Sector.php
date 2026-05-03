<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    protected $fillable = ['name', 'description'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}