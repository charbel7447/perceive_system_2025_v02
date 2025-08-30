<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class DashboardViews extends Model
{
    protected $table = 'dashboard_views';

    use Search;
}
