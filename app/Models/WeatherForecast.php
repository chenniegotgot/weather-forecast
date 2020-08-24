<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class WeatherForecast extends Model
{
    use Notifiable;

    protected $fillable = [
        'city_id',
        'city_name',
        'temp',
        'description'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
