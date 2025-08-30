<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\Account;

class TransferAccount extends Model
{
    protected $table = 'transfer_accounts';

    use Search;

    protected $search = [
        'from_account_id','to_account_id','amount','exchangerate','number','note'
    ];

    protected $columns = [
        'from_account_id','to_account_id','amount','exchangerate','number','note','transfer_date'
    ];

    protected $fillable = [
        'from_account_id','to_account_id','amount','exchangerate','number','note','transfer_date'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['amount'];
    }

    public function from_account()
    {
        return $this->belongsTo(Account::class,'from_account_id','id');
    }
    public function to_account()
    {
        return $this->belongsTo(Account::class,'to_account_id','id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    
    protected $appends = ['text'];
}
