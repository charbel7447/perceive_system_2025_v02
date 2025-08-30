<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class PriceChanges extends Model
{
    protected $table = 'price_changes';

    use Search;
}
