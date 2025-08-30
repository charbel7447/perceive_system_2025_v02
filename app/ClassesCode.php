<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\ChartOfAccount;

class ClassesCode extends Model
{
    protected $table = 'chart_classes';

    use Search;

    protected $search = [
        'name_en','name_ar'
    ];

    protected $columns = [
        'name_en','name_ar'
    ];

    protected $fillable = [
        'name_en','name_ar'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

        // In ChartClass.php
    public function accounts()
    {
        return $this->hasMany(ChartOfAccount::class, 'class_code', 'code');
    }
    

    public function getTextAttribute()
    {
        // '('.$this->attributes['id'].')'  '.
        return '<b style="color:red;">('.$this->attributes['code'].')</b> '.$this->attributes['name_en'].' - ('.$this->attributes['name_ar'].')';
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    
    protected $appends = ['text'];
}
