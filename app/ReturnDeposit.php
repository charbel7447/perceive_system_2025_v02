<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\Account;
use App\Employee;
use App\Currency;
use App\Deposit;

class ReturnDeposit extends Model
{
    protected $table = 'return_deposits';

    use Search;

    protected $search = [
        'employee_id','from_account_id','amount','exchangerate','number','note'
    ];


    protected $columns = [
        'employee_id','from_account_id','amount','exchangerate','number','note','exchangerate','currency_id','return_date'
    ];

    protected $fillable = [
        'employee_id','from_account_id','amount','exchangerate','number','note','exchangerate','currency_id','return_date'
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

    public function from_account()
    {
        return $this->belongsTo(Account::class,'from_account_id','id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }


    
    protected $appends = ['text'];
}
