<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisbursementOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'project_manager',
        'disbursementordernumber',
        'project_employee',
        'purchase_code',
        'purchase_date',
        'total_value',
        'residual_value',
        'payment',
        'notes',
        'purchase_order_id',
        'purchasing_user_id',
        'order_item_id',
        'status'
    ];
  
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function purchaseUser() :BelongsTo{
        return $this->belongsTo(User::class,'purchasing_user_id')
            ->whereHas('titles', function ($query) {
            $query->where('slug', 'purchasing');
        });
    }
    public function orderItem():BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class,'order_item_id');
    }

    public static  function generateUniqueDisbursementOrderNumber()
    {
        do {
            $randomNumber = rand(10000, 99999);
            \Log::info('Generated number: ' . $randomNumber); 
        } while (DB::table('disbursement_orders')->where('disbursementordernumber', $randomNumber)->exists());
    
        \Log::info('Final unique number: ' . $randomNumber);
        return $randomNumber;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->disbursementordernumber = $model->generateUniqueDisbursementOrderNumber();
        });
    }
    public function supplier()
{
    return $this->belongsTo(Supplier::class);
}

public function approvals()
{
    return $this->hasMany(DisbursementOrderApproval::class);
}

public function nextApprover()
{
    $lastApproval = $this->approvals()->latest()->first();

    $projectUsers = ProjectUser::query()
        ->where('project_id', $this->purchaseOrder->project->id)
        ->where('management_type', 'disbursement_order')
        ->orderBy('order')
        ->get();

    if (!$lastApproval) {
        return $projectUsers->first();
    }

    $currentApprover = $projectUsers->firstWhere('user_id', $lastApproval->user_id);
    
    return $projectUsers->where('order', '>', $currentApprover->order)->first();
}



public function project()
{
    return $this->belongsTo(Project::class, 'project_id');

}
public function isFullyApproved()
{
    // الحصول على جميع الموظفين الذين يجب عليهم الموافقة
    $projectUsers = ProjectUser::query()
        ->where('project_id', $this->purchaseOrder->project->id)
        ->where('management_type', 'disbursement_order')
        ->orderBy('order')
        ->get();

  
    $approvals = $this->approvals()->orderBy('order')->get();

    if ($approvals->count() == $projectUsers->count()) {
        foreach ($approvals as $index => $approval) {
            if ($approval->approved_at == null || $approval->order != $projectUsers[$index]->order) {
                return false; 
            }
        }
        return true;
    }

    return false;
}

 
}