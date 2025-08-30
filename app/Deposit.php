<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\Account;
use App\Employee;
use App\Currency;


class Deposit extends Model
{
    protected $table = 'deposits';

    use Search;

    protected $search = [
        'employee_id','to_account_id','amount','exchangerate','number','note'
    ];


    protected $columns = [
        'employee_id','to_account_id','amount','exchangerate','number','note','exchangerate','currency_id','deposit_date'
    ];

    protected $fillable = [
        'employee_id','to_account_id','amount','exchangerate','number','note','exchangerate','currency_id','deposit_date'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['amount'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }

    public function to_account()
    {
        return $this->belongsTo(Account::class,'to_account_id','id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }


    
    protected $appends = ['text'];
}
