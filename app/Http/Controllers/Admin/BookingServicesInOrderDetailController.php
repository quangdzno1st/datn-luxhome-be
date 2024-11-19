<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingServicesInOrderDetailController extends Controller
{
    public function addBookingServicesInOrderDetail($orderId,Request $request){
//        dd($request,$orderId);
        for ($i=0;$i<count($request->services);$i++){
            $result=BookingService::query()->insert([
                'id'=>Str::uuid()->toString(),
                'order_id' => $orderId,
                'room_id'=>$request->roomId,
                'service_id'=>$request->services[$i],
                'quantity'=>$request->quantity,
                'status'=>$request->status,
                'price'=>100000
            ]);
        }
        return redirect()->back()->with('success','Thêm service thành công!');
    }
}
