<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDashboard extends Model
{
    protected $fillable = ['user_id','name', 'widgets'];

    protected $casts = [
        'widgets' => 'array' // store widgets as JSON
    ];

     public function widgets()
    {
        return $this->hasMany(\App\ReportWidget::class, 'dashboard_id', 'id')
                    ->orderBy('position'); // optional, order widgets
    }
}
