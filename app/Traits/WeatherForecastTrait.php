<?php

namespace App\Traits;

use Cache;
use GuzzleHttp\Exception\RequestException;

trait WeatherForecastTrait
{
    public function getWeatherForecastByCityName($cityName)
    {
		$config = config("openweather");
    	$params = $this->getUrlParams();

    	//get current weather
		$currentWeatherUrl = "{$config['api_endpoint_current']}q={$cityName}{$params}";
        $currentWeather = $this->sendRequest($currentWeatherUrl);

        if ($currentWeather && $currentWeather['cod'] == 200) {
        	// get weather forecast
        	$forecastWeatherUrl = "{$config['api_endpoint_forecast']}q={$cityName}{$params}";
        	$forecast = $this->sendRequest($forecastWeatherUrl);

        	//get average temperature
        	if ($forecast && $forecast['cod'] == 200) {
        		$temp1 = $forecast['list'][0]['main']['temp'];
        		$temp2 = $currentWeather['main']['temp'];
        		$avgTemp = $this->getAverageTemp($temp1, $temp2);

        		$currentWeather['avgTemp'] = $avgTemp;
        		$currentWeather['forecast_temperature'] = $temp1;
        	}
        }

        return $currentWeather;
    }

    private function getUrlParams()
    {
    	$config = config("openweather");
		$params = "&cnt=1&lang={$config['api_lang']}&units={$config['api_units']}&APPID={$config['api_key']}";

        return $params;
    }

    public function getAverageTemp($temp1, $temp2)
    {
        return round(($temp1 + $temp2)/2);
    }

    private function sendRequest($url)
    {
    	try {
			$guzzle = new \GuzzleHttp\Client();
        	$response = $guzzle->request('GET', $url);

        	return json_decode($response->getBody(), true);
    	} catch(RequestException $e) {
    		return json_decode($e->getResponse()->getBody()->getContents(), true);
    	}
    }
}