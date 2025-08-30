<?php

namespace App\ContainerReceiveOrder;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;
use App\Shipper;
use App\ContainerOrder\ContainerOrder;

class ContainerReceiveOrder extends Model
{
    use Search;
    use HasManyRelation;

    const RECEIVED = 1;
    const PARTIALLY_RECEIVED = 8;

    protected $table = 'container_receive_orders';

    protected $search = [
        'number', 'date', 'note'
    ];

    protected $columns = [
        'id', 'number', 'date', 'note', 'status_id', 'created_at'
    ];

    protected $fillable = [
        'shipper_id', 'date', 'note'
    ];

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'container_receive_order_id', 'id');
    }

    public function containerOrder()
    {
        return $this->belongsTo(ContainerOrder::class, 'container_order_id', 'id');
    }
}
