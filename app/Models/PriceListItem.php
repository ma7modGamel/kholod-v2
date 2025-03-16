<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceListItem extends Model
{
    protected $fillable = [
        'price_list_id',
        'material_id',
       ' item_cat_id',
        'description',
      'measurement_unit_id',
        'quantity',
        'price'
    ];
    public function itemCat()
    {
        return $this->belongsTo(ItemCat::class);
    }
    public function priceList()
    {
        return $this->belongsTo(PriceList::class);
    }
    public function measurementUnit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id');
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }


}