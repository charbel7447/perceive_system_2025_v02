<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    protected $table = 'currencies';

    use Search;

    protected $search = [
        'code','name','decimal_place','created_at'
    ];

    protected $columns = [
        'code','name','decimal_place','created_at'
    ];

    protected $fillable = [
        'code','name','decimal_place','created_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['name'];
    }
    protected $appends = ['text'];
}
