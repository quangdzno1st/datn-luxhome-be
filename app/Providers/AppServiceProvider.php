<?php

namespace App\Providers;

use App\Services\AttributeValueService;
use App\Services\CatalogueRoomService;
use App\Services\CityService;
use App\Services\CommonKeyCodeService;
use App\Services\FileUploadService;
use App\Services\HotelService;
use App\Services\HotelServiceService;
use App\Services\impl\AttributeValueServiceImpl;
use App\Services\impl\CatalogueRoomServiceImpl;
use App\Services\impl\CityServiceImpl;
use App\Services\impl\CommonKeyCodeServiceImpl;
use App\Services\impl\FileUploadServiceImpl;
use App\Services\impl\HotelServiceImpl;
use App\Services\impl\HotelServiceServiceImpl;
use App\Services\impl\OrderServiceImpl;
use App\Services\impl\RoomServiceImpl;
use App\Services\OrderService;
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
        $this->app->bind(HotelService::class, HotelServiceImpl::class);
        $this->app->bind(FileUploadService::class, FileUploadServiceImpl::class);
        $this->app->bind(OrderService::class, OrderServiceImpl::class);
        $this->app->bind(CityService::class, CityServiceImpl::class);
        $this->app->bind(HotelServiceService::class, HotelServiceServiceImpl::class);
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
