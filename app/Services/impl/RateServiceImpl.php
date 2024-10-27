<?php
namespace App\Services\impl;

use App\Repositories\Rate\RateRepository;
use App\Services\RateService;
use App\Services\VoucherService;
class RateServiceImpl implements RateService {
    protected $rate;
    public function __construct(RateRepository $rate)
    {
        $this->rate=$rate;
    }

    public function listRate($numRecord){
        return $this->rate->listRate($numRecord);
    }
    public function createRate($data){

    }
    public function showRate($id){

    }
    public function deleteRate($id){
        try {
            $rate = $this->rate->remove($id);
            return $rate;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function restoreRate($id){
        try {
            $rate = $this->rate->restore($id);
            return $rate;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function forceDeleteRate($id){
        try {
            $rate = $this->rate->destroy($id);
            return $rate;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function averageRate($hotel_id){
        try {
            $rate = $this->rate->averageRate($hotel_id);
            return $rate;
        } catch (\Exception $e) {
            throw $e;
        }

    }
}