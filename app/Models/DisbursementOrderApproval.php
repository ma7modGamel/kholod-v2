<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisbursementOrderApproval extends Model


{ 
    protected $table = "disbursement_order_approvals";
    protected $primaryKey = 'id';
public $incrementing = true;
protected $keyType = 'int';
    protected $fillable = [
        'user_id',
        'disbursement_order_id',
        'approved_at',
        'order', 
    ];

   
    protected $casts = [
        'approved_at' => 'datetime',
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