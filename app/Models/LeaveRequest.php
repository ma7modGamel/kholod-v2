<?php

namespace App\Models;

use App\Models\User;
use App\Models\LeaveRequestApprover;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;
    protected $table = 'leave_requests';
    protected $with = ['employee.manager'];
    
    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'status',
        'notes',
        'type',
     
        
    ];

    // public function employee()
    // {
    //     return $this->belongsTo(User::class, 'employee_id');
    // } 


    public function employee()
{
    return $this->belongsTo(User::class, 'employee_id');
}

    public function manager()
    {
        return $this->employee->manager();
    }
    
    public function titles()
    {
        return $this->hasMany(Title::class, 'leaverequest_id ');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }


    // public function approvers()
    // {
    //     return $this->hasMany(LeaveRequestApprover::class,'leave_request_id');
    // }
    
    public function approvers()
    {
        return $this->hasMany(LeaveRequestApprover::class, 'leave_request_id');
    }

    
    
    // public function finishLeaveRequest()
    // {
    //     $order_items = $this->items()->pluck('id')->toArray();
    //     $leaveRequest_items = $this->LeaveRequests()->pluck('order_item_id')->toArray();
    //     $diff =   array_diff($order_items, $leaveRequest_items);
    //     if ($diff === []) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($leaveRequest) {
        if (!$leaveRequest->employee_id) {
            $leaveRequest->employee_id = auth()->id();
        }
            if (!$leaveRequest->status) {
                $leaveRequest->status = 'pending'; 
            }
      
    });

  
    
    // static::created(function ($status) {
    //     if (!$status->leave_request_id) {
    //         \Log::error('LeaveRequestStatus created without leave_request_id', ['status' => $status]);
    //         return;
    //     }
    
    //     if ($status->leaveRequest) {
    //         $status->leaveRequest->update(['status' => $status->status]);
    //     } else {
    //         \Log::error('LeaveRequest relationship is null', ['status' => $status]);
    //     }
    // });
    // static::created(function ($leaveRequest) {

    //     $manager = $leaveRequest->employee->employeemanager;

    //     if ($manager) {
    //         $leaveRequest->approvers()->create([
    //             'user_id' => $manager->id,
    //             'status' => 0, 
    //         ]);
    //     }
    // });

    
 
}

public function getLatestStatusAttribute()
{
    return $this->approvers()->latest()->first()->status ?? null;
}


// public function latestStatus()
// {
//     return $this->hasOne(LeaveRequestStatus::class)->latest();
// }
// public function statuses()
// {
//     return $this->hasMany(LeaveRequestStatus::class, 'leave_request_id');
// }
public function leaveRequest()
{
    return $this->belongsTo(LeaveRequest::class, 'leave_request_id');
}


public function latestApprover()
{
    return $this->hasOne(LeaveRequestApprover::class)->latestOfMany();
}

}