<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    use Search;
}
