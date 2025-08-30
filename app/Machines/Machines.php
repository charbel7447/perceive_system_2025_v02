<?php

namespace App\Machines;

// use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Support\HasManyRelation;
use App\Support\Search;

use App\Machines\Settings as MachineSettings;

class Machines extends Authenticatable
{
    use HasManyRelation;
    // use Notifiable;
    use Search;

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

       

    public function settings()
    {
        return $this->hasMany(MachineSettings::class,'machines_id','id');
    }


    protected $appends = ['text'];
    public function getTextAttribute()
    {
        if(is_null($this->attributes['code'])) {
            return $this->attributes['name'];
        }

        return $this->attributes['name'] .' - '. $this->attributes['code'];;
    }

    
}
