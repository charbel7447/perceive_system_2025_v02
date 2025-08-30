<?php

namespace App\Damaged;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Damaged\Item;

class Damaged extends Model
{
    use Search;
    use HasManyRelation;

    protected $table = 'damaged';

    protected $search = [
        'number'
    ];

    protected $columns = [
        'id',  'number','description','qty','date'
    ];

    protected $fillable = [
        'number','description','qty','date'
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'damaged_id', 'id');
    }


}
