<?php

namespace App\Api\Repositories\User;

use App\Models\User;
use App\Repositories\EloquentRepository;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{

    public function getModel(): string
    {
        return User::class;
    }
    public function count(){
        return $this->model->count();
    }
    public function searchAllLimit($keySearch = '', $meta = [], $limit = 10){

        $this->instance = $this->model;

        $this->getQueryBuilderFindByKey($keySearch);

        $this->applyFilters($meta);

        return $this->instance->limit($limit)->get();
    }

    protected function getQueryBuilderFindByKey($key){
        $this->instance = $this->instance->where(function($query) use ($key){
            return $query->where('phone', 'LIKE', '%'.$key.'%')
                ->orWhere('email', 'LIKE', '%'.$key.'%')
                ->orWhere('fullname', 'LIKE', '%'.$key.'%');
        });
    }
    public function chartUser(array $dateBetween){
        $period = CarbonPeriod::create(...$dateBetween);
        $array = [];

        $this->instance = $this->model->select(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as sell_date'), DB::raw('count(*) as user'))
            ->whereBetween('created_at', [$dateBetween[0]->subDay(), $dateBetween[1]->addDay()])
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'))
            ->orderBy(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'))
            ->pluck('user', 'sell_date')->toArray();

        foreach($period as $item){
            $array[] = [
                'sell_date' => $item->format('d-m-Y'),
                'user' => (int) ( $this->instance[$item->format('d-m-Y')] ?? 0 )
            ];
        }
        return collect($array);
    }
    public function createUser($discount,$DataUser){
        foreach($DataUser as $key => $item){
            $discountCodeProduct = new DiscountCodesUser();
            $discountCodeProduct->discount_code_id = $discount->id;
            $discountCodeProduct->user_id = $item;
            $discountCodeProduct->save();
        }
    }
}
