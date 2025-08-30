<?php

namespace App\Expense;

use Illuminate\Database\Eloquent\Model;
use App\Expense\Expense;

class Item2 extends Model
{
    protected $table = 'expenses_items2';

    protected $fillable = [
        'expense_id', 'amount_applied',
        'document',
        'account_payable_id',
        'account_payable_name',
        'account_payable_number',
        'account_payable_currency_id',
        'description',
        'date',
        'reference',
        'debit',
        'debit_vat',
        'account_payable_debit_vat_name',
        'account_payable_debit_vat_id',

    ];

    public function expense()
    {
        return $this->belongsTo(Invoice::class, 'expense_id', 'id');
    }

}
