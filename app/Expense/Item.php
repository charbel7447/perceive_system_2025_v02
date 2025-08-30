<?php

namespace App\Expense;

use Illuminate\Database\Eloquent\Model;
use App\Expense\Expense;

class Item extends Model
{
    protected $table = 'expenses_items';

    protected $fillable = [
        'expense_id', 'amount_applied',
        'document',
        'account_receivable_id',
        'account_receivable_name',
        'account_receivable_number',
        'account_receivable_currency_id',
        'description',
        'date',
        'reference',
        'debit',
        'debit_vat',
        'account_receivable_debit_vat_name',
        'account_receivable_debit_vat_id',
        'account_receivable_debit_vat_code'
    ];

      protected $columns = [
  'expense_id', 'amount_applied',
        'document',
        'account_receivable_id',
        'account_receivable_name',
        'account_receivable_number',
        'account_receivable_currency_id',
        'description',
        'date',
        'reference',
        'debit',
        'debit_vat',
        'account_receivable_debit_vat_name',
        'account_receivable_debit_vat_id',
        'account_receivable_debit_vat_code'
    ];

    public function expense()
    {
        return $this->belongsTo(Invoice::class, 'expense_id', 'id');
    }

}
