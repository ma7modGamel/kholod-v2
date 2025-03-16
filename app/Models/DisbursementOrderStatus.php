<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisbursementOrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'disbursement_order_id',
        'user_id',
        'status',
        'notes'
    ];
    public function disbursementOrder()
    {
        return $this->belongsTo(DisbursementOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}