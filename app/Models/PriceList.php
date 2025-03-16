<?php

namespace App\Models;

use App\Models\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function itemCat()
{
    return $this->belongsTo(ItemCat::class, 'item_cat_id');
}
public function priceListItems()
{
    return $this->hasMany(PriceListItem::class);
}

public function material()
{
    return $this->belongsTo(Material::class);
}
}