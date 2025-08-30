<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportWidget extends Model
{
    protected $table = 'report_widgets';

    protected $fillable = [
        'name', 'description', // add any config fields you want
    ];
}
