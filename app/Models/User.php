<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\LeaveRequestApprover;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'second_email',
        'approved',
        'account_approved',
        'address',
        'signature',
        'code',
        'employee_type',
        'id_number',
        'employeemanager_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function titles()
    {

        return $this->belongsTo(Title::class,'title_id');
    }
    public function title()
{
    return $this->hasOne(Title::class, 'id', 'title_id');
}

    public function trackings() :HasMany
    {
        return $this->hasMany(CorrespondenceTracking::class,'to_user_id');

    }
    public function role()
    {
        return $this->roles();
    }
    public function manager()
{
    return $this->belongsTo(User::class, 'employeemanager_id');
}

public function employees()
{
    return $this->hasMany(User::class, 'employeemanager_id');
} 
public function approvers()
{
    return $this->hasMany(LeaveRequestApprover::class, 'user_id');
}

}