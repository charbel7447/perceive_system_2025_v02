<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class ReportsViews extends Model
{
    protected $table = 'report_views';

    use Search;
}
