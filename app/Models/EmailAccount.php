<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAccount extends Model
{
    protected $table = 'email_accounts';
    protected $fillable = [
        'user_id',
        'email',
        'password',
        'imap_host',
        'imap_port',
        'imap_encryption',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    
} 
}