
<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WeatherController::class, 'index'])->name('home');
Route::get('/weather/{city}', [WeatherController::class, 'detail'])->name('weather.detail');

