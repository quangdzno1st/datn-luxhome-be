<?php

namespace App\Providers;

use App\Services\CatalogueRoomService;
use App\Services\impl\CatalogueRoomServiceImpl;
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
        $this->app->bind(CatalogueRoomService::class, CatalogueRoomServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
