<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingService;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingServicesInOrderDetailController extends Controller
{
    public function addBookingServicesInOrderDetail($orderId,Request $request){
        for ($i=0;$i<count($request->services);$i++){
            $price=Service::query()->where('id',$request->services[$i])->first()->price;
//            dd($price);
            $result=BookingService::query()->insert([
                'id'=>Str::uuid()->toString(),
                'order_id' => $orderId,
                'room_id'=>$request->roomId,
                'service_id'=>$request->services[$i],
//                'quantity'=>$request->quantity,
                'status'=>$request->status,
                'price'=>$price
            ]);
        }
        return redirect()->back()->with('success','Thêm service thành công!');
    }
}
