<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class MediaUpload extends Model
{
    protected $connection = "mysql";
    // protected $table = 'uom';
    protected $table = 'media_uploads';
    use Search;

   
  
    protected $appends = [
        'text'
    ];
  
    public function getTextAttribute()
    {
        return $this->attributes['path'];
    }

}
