<?php

namespace App\JournalVoucher;

use Illuminate\Database\Eloquent\Model;

class JournalFlowMapping extends Model
{
    protected $fillable = ['process', 'mappings'];

    protected $casts = [
        'mappings' => 'array',
    ];
    
}
