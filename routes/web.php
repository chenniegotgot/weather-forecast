<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WeatherForecastController@index')->name('index');
Route::post('/weather-forecast', 'WeatherForecastController@getForecast')->name('weather-forecast');

Route::get('cities', 'CityController@index')->name('cities.index');
