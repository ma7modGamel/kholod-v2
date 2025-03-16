<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestStatus extends Model
{
    use HasFactory;
    protected $table = 'leave_request_statuses';
    protected $fillable = ['leave_request_id', 'approver_id', 'status', 'notes'];

    public function leaveRequestOrder()
    {
        return $this->belongsTo(LeaveRequest::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

 
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
    public function leaveRequest()
{
    return $this->belongsTo(LeaveRequest::class, 'leave_request_id');
}

}