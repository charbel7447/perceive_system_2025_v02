<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Currency;
use App\Employee;

class Payroll extends Model
{
    use Search;

    protected $search = [
        'id', ' number', 'payment_date', 'amount_paid', 'amount_paid_lbp','description','exchangerate'
    ];

    protected $columns = [
        'id', 'number', 'payment_date', 'amount_paid', 'amount_paid_lbp','description',
        'created_at','employee_id','exchangerate'
    ];

    protected $fillable = [
        'employee_id', 'description', 'currency_id', 'payment_date',
        'amount_paid','amount_paid_lbp','exchangerate'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
