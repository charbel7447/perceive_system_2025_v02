<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{

    use Search;

    protected $table = 'notifications';

    

    protected $search = [
       'number', 'document_type','description','document_number','created_at'
    ];

    protected $columns = [
        'user_id','manager_id','number', 'document_type','description','link','document_number','qty','date','created_by','created_by','created_at'
    ];

    protected $fillable = [
        'user_id','manager_id','number', 'document_type','description','link','document_number','qty','date','created_by','created_by','created_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $appends = [
        'text'
    ];
    
    function getTextAttribute()
    {
        return $this->attributes['number'].'-'.$this->attributes['description'];
    }
}
