<?php

namespace App\EmployeeReport;

use Illuminate\Database\Eloquent\Model;
use App\Currency;
use App\ReturnDeposit;
use App\Deposit;
use App\ExchangeRate\ExchangeRate;
use App\Payroll;
use App\User;
use App\Employee;
use App\Account;

class Item extends Model
{
    protected $table = 'employee_report_items';

    protected $fillable = [
        'user_id',
        'employee_id',
        'created_by',
        'from_date',
        'to_date',
    ];

    public function deposit()
    {
        return $this->hasMany(Deposit::class, 'deposit_id', 'id');
    }

    public function return_deposit()
    {
        return $this->hasMany(ReturnDeposit::class,'return_deposit_id','id');
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class,'payroll_id','id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function to_account()
    {
        return $this->belongsTo(Account::class,'to_account_id','id');
    }

    public function from_account()
    {
        return $this->belongsTo(Account::class,'from_account_id','id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id','id');
    }

}
