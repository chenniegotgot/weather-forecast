<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\WeatherForecast;
use App\Repositories\Repository;
use App\Traits\WeatherForecastTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeatherForecastController extends Controller
{
    use WeatherForecastTrait;

    protected $forecast;
    protected $country;
    protected $state;

    public function __construct(WeatherForecast $forecast, Country $country, State $state)
    {
        // set the model
        $this->forecast = new Repository($forecast);
        $this->country = new Repository($country);
        $this->state = new Repository($state);
    }

    public function index() 
    {
        $countries = Cache::remember('countries', 24*60, function() {
            return $this->country->all();
        });
        $states = Cache::remember('states', 24*60, function() {
            return $this->state->all();
        });

        return view('weather-forecast.view', compact('countries', 'states'));
    }

    public function getForecast()
    {
        try {
            $cityId = request()->input('city_id');
            $cityName = request()->input('city_name');
            $forecast = $this->getWeatherForecastByCityName($cityName);

            if ($forecast['cod'] == 200) {
                $this->forecast->create([
                    'city_name' => $cityName,
                    'city_id'   => $cityId,
                    'temp'      => $forecast['avgTemp'],
                    'description' => $forecast['weather'][0]['description']
                ]);
            }

            return response()->json($forecast);
        } catch (\Exception $e) {
            return response()->json([
                'cod' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
