<?php

namespace App\Services;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderSearchRequest;
use Illuminate\Http\Request;

interface OrderService
{
    public function create(OrderRequest $request);

    public function paymentReturn(Request $request);

    public function getTotalOrderMapByCityId(array $cityIds);

    public function searchByPage(OrderSearchRequest $request);

    public function generateUrlRedirect($total_amount);

    public function paymentOrder($orderId);

    public function getDataBookingOrder(Request $request);

    public function getRoomOrderQtyMapByCatalogueRoomId( $roomsOrder);

    public function getDataBookingForConfirm(Request $request, $hotelId);
    public function getDataBookingForFinish($hotelId);

    public function cancelOrder($orderId);

    public function getOrderById($orderId);

    public function handleBookingForConfirmData($hotelId, $data);
}