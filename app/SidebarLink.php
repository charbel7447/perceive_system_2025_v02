<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;


class SidebarLink extends Model
{
    protected $connection = "mysql";
    // protected $table = 'uom';
    protected $table = 'sidebar_links';
   
}
