<?php

namespace App\Services;

use App\Http\Requests\CatalogueRequest;
use App\Http\Requests\CatalogueRoomSearchRequest;
use Illuminate\Http\Request;


interface CatalogueRoomService
{
    public function createOrUpdate($id, CatalogueRequest $request);

    public function delete($id);

    public function detail($id);

<<<<<<< HEAD
    public function searchByPage(CatalogueRoomSearchRequest $request);

    public function existsById($id);
=======
    public function search(Request $request);

>>>>>>> 11e2e04 (viết api số lượng phòng còn lại theo điều kiện lọc của từng loại phòng(mặc định là ngày hiện tại và ngày hôm sau) , Viết api lấy danh sách phòng còn trống theo điều kiện lọc(mặc định là ngày hiện tại và ngày hôm sau), Viết api tìm kiếm loại phòng còn phòng trống theo điều kiện lọc(Mặc định tìm theo ngày hiện tại và ngày tiếp theo))
}