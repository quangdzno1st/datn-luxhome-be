<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Room\UpdateRoomStatusRequest;
use App\Models\Room;

class RoomStatusController extends Controller
{
    //
    public function update(UpdateRoomStatusRequest $request, $roomId)
    {
        try{
            $validated = $request;
            $room = Room::findOrFail($roomId);
            $room->status = $validated['status'];
            $room->save();
    
            return response()->json([
                'message' => 'Trạng thái phòng đã được cập nhật thành công',
                'room' => $room
            ]);
        }catch(\Exception $e){
            return response()->json([
                'errors' => $e,
            ]);
        }
    }

}
