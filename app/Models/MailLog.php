<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    use HasFactory;

    protected $table = 'mail_logs';

    protected $fillable = [
        'subject',
        'body',
        'recipient',
        'sender',
        'status',
        'error_message',
    ];
}