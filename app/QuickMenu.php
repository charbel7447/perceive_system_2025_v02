<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class QuickMenu extends Model
{
    protected $table = 'quick_menu';

    use Search;
}
