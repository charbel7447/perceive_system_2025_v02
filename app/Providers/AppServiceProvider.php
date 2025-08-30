<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\CustomValidators;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->app->validator->resolver(function($translator, $data, $rules, $messages)
        {   
            Schema::defaultStringLength(191);
            return new CustomValidators($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
