<?php
namespace App\Models;

use App\Models\Title;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequestApprover extends Model
{
    use HasFactory;

    protected $table = "leave_request_approvers";
    // protected $primaryKey = 'id';
    // public $incrementing = true;
    // protected $keyType = 'int';

    protected $fillable = [
        'leave_request_id',
        'user_id',
        'title_id',
        'status',
        'approved_at',
        'notes'
    ];
  
    public function latestApprover()
    {
        return $this->hasOne(LeaveRequestApprover::class)->latest();
    }
    

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id'); 
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id');
    }
 

    protected $casts = [
        'approved_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($approver) {
            if (!$approver->user_id) {
                $approver->user_id = auth()->id(); 
            }
        });
    }
    
}