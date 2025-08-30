<?php

namespace App\EmployeeReport;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;

use App\ReturnDeposit;
use App\Deposit;
use App\ExchangeRate\ExchangeRate;
use App\Payroll;
use App\User;
use App\Employee;

class EmployeeReport extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const SENT = 2;
    const CONFIRMED = 3;
    const BILLED = 4;
    const CANCELLED = 5;
    const CLOSED = 6;
    const CONFIRMEDPO = 9;

    protected $table = 'employee_report';

    protected $search = [
        'reference', 'date', 'terms', 'number'
    ];

    protected $columns = [
        'id',
        'user_id',
        'employee_id',
        'created_by',
        'from_date',
        'to_date',
    ];

    protected $fillable = [
        'user_id',
        'employee_id',
        'created_by',
        'from_date',
        'to_date',
    ];
    protected $casts = [
        'status_id'=> 'integer',
    ];

    protected $appends = ['is_editable'];

    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'deposit_id', 'id');
    }

    public function return_deposit()
    {
        return $this->belongsTo(ReturnDeposit::class,'return_deposit_id','id');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class,'payroll_id','id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id','id');
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->attributes['status_id'], [1, 2]);
    }

}
