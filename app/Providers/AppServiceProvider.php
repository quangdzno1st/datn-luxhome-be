<?php

namespace App\Providers;

use App\Services\AttributeValueService;
use App\Services\CatalogueRoomService;
use App\Services\CommonKeyCodeService;
use App\Services\impl\AttributeValueServiceImpl;
use App\Services\impl\CatalogueRoomServiceImpl;
use App\Services\impl\CommonKeyCodeServiceImpl;
use App\Services\impl\RoomServiceImpl;
use App\Services\RoomService;
use Illuminate\Pagination\Paginator;
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
        $this->app->bind(RoomService::class, RoomServiceImpl::class);
        $this->app->bind(CommonKeyCodeService::class, CommonKeyCodeServiceImpl::class);
        $this->app->bind(AttributeValueService::class, AttributeValueServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
    }
}
