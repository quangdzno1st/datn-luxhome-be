<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ward;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Province\ProvinceRepository;

class WardController extends Controller
{
    public $provinceRepository;
    public function __construct(
        ProvinceRepository      $provinceRepository,
    )
    {
        $this->provinceRepository = $provinceRepository;

//        $this->middleware(['permission:admin_list'])->only('index');
//        $this->middleware(['permission:admin_add'])->only('create');
//        $this->middleware(['permission:admin_edit'])->only('edit');
//        $this->middleware(['permission:admin_delete'])->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = $this->provinceRepository->pluck('name', 'id');
        $wards = Ward::OrderBy('id','desc')->paginate(30);
        return view('admin.content.Address.Ward.index',compact('wards','provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $districts = new Ward;
        $districts->district_id = $request->district_id;
        $districts->name = $request->name;
        $districts->type = $request->type;
        $districts->latlng = '';
        $districts->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
