<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
	protected $city;

    public function __construct(City $city)
    {
        // set the model
        $this->city = new Repository($city);
    }

    public function index()
    {
        $stateId = request()->input('state_id');
        $cities = Cache::remember('cities', 24*60, function() use ($stateId) {
            return $this->city->pluckedRelatedModelAttr($stateId, 'state', 'name');
        });

        return response()->json($cities);
    }
}
