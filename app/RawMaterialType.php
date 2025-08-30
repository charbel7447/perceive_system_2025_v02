<?php

namespace App;

// use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Support\Search;

class RawMaterialType extends Authenticatable
{
    // use Notifiable;
    use Search;
    
    protected $table = 'raw_material_type';

    protected $search = [
        'code', 'name'
    ];

    protected $columns = [
        'id','code', 'name'

    ];

    protected $fillable = [ 
        'id','code', 'name'
    ];
    
      

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
       
    ];
    protected $appends = ['text'];
    public function getTextAttribute()
    {
        if(is_null($this->attributes['code'])) {
            return $this->attributes['name'];
        }

        return $this->attributes['name'] .' - '. $this->attributes['code'];;
    }

    
}
