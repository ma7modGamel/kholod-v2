<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasurementUnit extends Model
{
    
    use HasFactory;

    protected $fillable = ['name', 'symbol', 'description'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}