<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\ClassesCode;
use App\JournalVoucher\Item as JournalVoucherItem;

class ChartOfAccount extends Model
{
    protected $table = 'chart_accounts';

    use Search;

    protected $search = [
        'name_en','name_ar','code'
    ];

    protected $columns = [
        'currency_id','name_en','balance','name_ar','code','credit','debit','class_code','vat_account_id',
        'vat_account_code','vat_account_name'
    ];

    protected $fillable = [
        'currency_id','name_en','balance','name_ar','code','credit','debit','class_code','vat_account_id',
        'vat_account_code','vat_account_name'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
        return '<span style="color:red;"> ('.$this->attributes['code'] .'</span>) - '. $this->attributes['name_en'] .' - ('. $this->attributes['name_ar'] .')';
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function currency2()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }

       public function items()
    {
        return $this->hasMany(\App\JournalVoucher\Item::class, 'account_id', 'id');
    }

    public function classes()
    {
        return $this->belongsTo(ClassesCode::class,'class_code','code');
    }

    public function vat_account()
    {
        return $this->belongsTo(ChartOfAccount::class,'vat_account_id','id');
    }
    
        public function journalItems()
    {
        return $this->hasMany(JournalVoucherItem::class, 'account_id', 'id');
    }
    
    protected $appends = ['text'];
}
