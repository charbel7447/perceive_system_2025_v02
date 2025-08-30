<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;

class ReleaseNote extends Model
{
    protected $table = 'release_note';

    use Search;
}
