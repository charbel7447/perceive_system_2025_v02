<?php

namespace App\FinishedProduct;

// use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Support\Search;

class Type extends Authenticatable
{
    use Search;

    protected $table = 'product_type';

    protected $search = [
        'code', 'name', 'machine', 'warehouse'
    ];

    protected $columns = [
        'id', 'code', 'name', 'machine', 'warehouse','status'
    ];

    protected $fillable = [
        'code', 'name', 'machine', 'warehouse','status'
    ];

    protected $appends = [
        'text'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['code'] .' - '. $this->attributes['name'];
    }
    
}
