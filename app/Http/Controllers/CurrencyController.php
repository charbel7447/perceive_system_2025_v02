<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
use App\DeliveryCondition;
use App\PaymentCondition;

class CurrencyController extends Controller
{
    public function search()
    {
        $results = Currency::orderBy('name')
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%')
                    ->orWhere('code', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get();

        return api([
            'results' => $results
        ]);
    }

    public function searchPaymentCondition()
    {
        $results = PaymentCondition::orderBy('payment_conditions')
            ->when(request('q'), function($query) {
                $query->where('payment_conditions', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get();

        return api([
            'results' => $results
        ]);
    }

    public function searchDeliveryTerm()
    {
        $results = DeliveryCondition::orderBy('name')
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get();

        return api([
            'results' => $results
        ]);
    }

}
