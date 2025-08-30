<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;



class ClientDropDown2 extends Model
{
    use Search;
    use HasManyRelation;

    // protected $connection = "sqlsrv2";
    protected $table = 'client_dropdown2';

   
    
    protected $search = [
        'id','name'
    ];


    protected $SearchProducts = [
        'name'
    ];

    protected $columns = [
        'id', 'name'
    ];

    protected $fillable = [
        'id','name'
    ];

    protected $appends = [
        'text'
    ];

  
    public function getTextAttribute()
    {
        return $this->attributes['id'] .' - '. $this->attributes['name'].' - <span style="color:red;">[('. $this->attributes['id'].')]</span>';
    }
}
