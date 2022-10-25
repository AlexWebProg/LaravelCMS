<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\SubRequest;
use App\Models\SubscribeSettings;
use App\Models\Subscribe;

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
        //
        Schema::defaultStringLength(191);

        // Российский формат дат
        Carbon::setLocale('ru_RU');

        // Передаём во все view основную информацию о заявках
        View::share('arSubRequestBasicInfo', SubRequest::getSubRequestsBasicInfo());

        // Передаём во все view рабочие месяцы
        View::share('arPlannedDataLine', Subscribe::getPlannedDataLine());
    }
}
