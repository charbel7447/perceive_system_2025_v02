<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class SavedQuery extends Model
{
    protected $table = 'saved_queries';

    use Search;
}
