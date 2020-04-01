<?php

namespace App\Providers;

use App\Models\OfficeModel;
use App\Models\PrefixModel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->share(['prefixs'=> PrefixModel::all(['code','name']),'hospitals'=> OfficeModel::whereNull('deleted_at')->get(['id','name'])]);
    }
}
