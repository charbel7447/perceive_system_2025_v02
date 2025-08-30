<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\Items\Items;
use App\ReceiveOrder\ReceiveOrder;
use App\PurchaseOrder\PurchaseOrder;
use App\Quotation\Quotation;
use App\MediaUpload;

class Brand extends Model
{
    protected $connection = "mysql";
    // protected $table = 'uom';
    protected $table = 'product_brands';
    use Search;

    protected $search = [
        'title','status','image','created_by','created_at','order'
    ];

    protected $columns = [
        'title','status','image','created_by','created_at','order'
    ];

    protected $fillable = [
        'title','status','image','created_by','created_at','order'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['title'];
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
    
    
    public function images()
    {
        return $this->belongsTo(MediaUpload::class,'image','id');
    }

    protected $appends = ['text'];
}
