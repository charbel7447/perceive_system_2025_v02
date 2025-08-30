<?php

namespace App;

// use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Support\Search;

class Menu extends Authenticatable
{
    // use Notifiable;
    use Search;
    
    protected $table = 'menu';

    protected $search = [
        'name', 'link'
    ];

    protected $columns = [
        'id','link', 'name'

    ];

    protected $fillable = [ 
        'id','link', 'name'
    ];
    
      

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
       
    ];
    
    protected $appends = ['text'];

    public function getTextAttribute()
    {

        return $this->attributes['name'];
    }
    
}
