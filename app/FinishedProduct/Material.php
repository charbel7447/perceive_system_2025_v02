<?php

namespace App\FinishedProduct;

use Illuminate\Database\Eloquent\Model;
use App\FinishedProduct\FinishedProduct;
use App\RawMaterialType;

class Material extends Model {

    protected $table = 'finished_product_materials';

    protected $fillable = [
        // 'material_id', 'percentage' ,'material','product_id'
        'material_id','percentage'
    ];

    public function material()
    {
        return $this->belongsTo(RawMaterialType::class, 'material_id', 'id');
    }
}
