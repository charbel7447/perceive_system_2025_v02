<?php

namespace App\FinishedProduct;

use Illuminate\Database\Eloquent\Model;
use App\FinishedProduct\FinishedProduct;
use App\RawMaterialType;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Machines\Machines as FPMachines;

class Machines extends Model {

    use Search;
    use HasManyRelation;

    protected $table = 'finished_product_machines';

    protected $fillable = [
        'speed' ,'machine_id','comment','machines_id','product_id','machine_process_id','machine'
    ];

    // public function settings()
    // {
    //     return $this->hasMany(MachinesSettings::class, 'finished_product_machine_id', 'id');
    // }

    public function settings()
    {
        return $this->hasMany(MachinesSettings::class);
    }

    public function machines()
    {
        return $this->belongsTo(Machines::class,'machine_id','id');
    }

    public function machinex()
    {
        return $this->belongsTo(FPMachines::class,'machine_id','id');
    }



//    finished_product_machine_id
}
