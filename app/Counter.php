<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $table = 'counters';

    use Search;

    protected $search = [
        'key','prefix','value','created_at'
    ];

    protected $columns = [
        'key','prefix','value','created_at'
    ];

    protected $fillable = [
        'key','prefix','value','created_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['value'];
    }
    protected $appends = ['text'];
}
