<?php

namespace App\Services\impl;

use App\Repositories\Reigion\RegionRepository;
use App\Services\RegionService;
use Illuminate\Support\Facades\DB;

class RegionServiceImpl implements RegionService
{
    private  $regionRepo;

    public function __construct(RegionRepository $regionRepo)
    {
        $this->regionRepo = $regionRepo;
    }

    public function createNewReigon($data){
        DB::beginTransaction();
        try{
            $region = $this->regionRepo->create($data);

            DB::commit();
            return $region;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function updateReigon($data, $id){
        DB::beginTransaction();
        try{
            $region = $this->regionRepo->regionDetail($id);

            if($region === null){
                throw new \Exception('Region not found');
            }

            $region->update($data);

            DB::commit();
            return $region;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteReigon($id){
        try{
            $region = $this->regionRepo->regionDetail($id);

            if($region === null){
                throw new \Exception('Region not found');
            }

            $region->delete();

            return $region;
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function restoreReigon($id){
        DB::beginTransaction();
        try{
            $region = $this->regionRepo->getRegions($id);

            if($region === null){
                throw new \Exception('Region not found');
            }

            $region->restore();

            DB::commit();
            return $region;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
    public function forceDeleteReigon($id){
        try{
            $region = $this->regionRepo->getRegions($id);

            if($region === null){
                throw new \Exception('Region not found');
            }

            $region->forceDelete();

            return $region;
        }catch(\Exception $e){
            throw $e;
        }
    }
}