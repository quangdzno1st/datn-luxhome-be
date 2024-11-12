<?php
namespace App\Repositories\Rate;
use App\Models\Rate;
use App\Repositories\Base\BaseRepository;


class RateRepository extends BaseRepository implements RateInterface {
    public function model(): string
    {
        return Rate::class;
    }

    public function listRate($numRecord)
    {
        try{
            $page = $numRecord;
            $rates = Rate::query()
                        ->paginate(10, ['*'], 'page', $page);
    
            return $rates;
        }catch(\Exception $e){
            return "Error:".$e;
        }
    }

    public function remove($id)
    {
        $query = $this->model->find($id);
        $query->delete();
        return $query;
    }

    public function restore($id)
    {
        $query = $this->model->onlyTrashed()->where('id', $id)->first();
        $query->restore();
        return $query;
    }

    public function destroy($id)
    {
        $query = $this->model->withTrashed()->where('id', $id)->first();
        $query->forceDelete();
        return $query;
    }
    public function averageRate($hotel_id){
        $query=Rate::where('hotel_id', $hotel_id)->avg('rate');
        return $query;
    }

}