<?php

namespace App\Machines;

use Illuminate\Database\Eloquent\Model;
use App\SiegwerkExcel\Tools;

class Settings extends Model
{
    // ];
    protected $table = 'machines_settings';

    protected $fillable = [
            'machines_id',
            'settings_id',
            'settings_name',
            'settings_value',
            'settings_comment'
          
    ];

    protected $columns = [
        'id','machines_id',
        'settings_id',
        'settings_name',
        'settings_value',
        'settings_comment'

    ];


    public function machine()
    {
        return $this->belongsTo(Machines::class, 'machines_id', 'id');
    }

}