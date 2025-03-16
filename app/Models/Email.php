<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model
{
    use HasFactory;
    
    protected $table = 'emails'; 

    protected $fillable = [
        'from_email',
        'to_email',
        'subject',
        'body',
        'received_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];
}