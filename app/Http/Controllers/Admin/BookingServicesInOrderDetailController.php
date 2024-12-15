<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingService;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingServicesInOrderDetailController extends Controller
{
    public function addBookingServicesInOrderDetail($orderId,Request $request){
//        $validate=$request->validate([
//            'services'=>'required',
//        ],[
//            'services.required'=>'Phải chọn dịch vụ'
//        ]);dd($validate);
//        dd($request->services);
        if ($request->services==null){
            return redirect()->back()->with('error','Phải chọn dịch vụ!');
        }else{
        for ($i=0;$i<count($request->services);$i++){
            $price=Service::query()->where('id',$request->services[$i])->first()->price;
            BookingService::query()->insert([
                'id'=>Str::uuid()->toString(),
                'order_id' => $orderId,
                'room_id'=>$request->roomId,
                'service_id'=>$request->services[$i],
                'status'=>$request->status,
                'price'=>$price
            ]);
        }
        return redirect()->back()->with('success','Thêm service thành công!');
        }
    }
}
