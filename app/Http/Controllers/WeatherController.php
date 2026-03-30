<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    private $provinces = [
        'Banda Aceh' => 'Aceh',
        'Medan' => 'Sumatera Utara',
        'Pekanbaru' => 'Riau',
        'Jambi' => 'Jambi',
        'Palembang' => 'Sumatera Selatan',
        'Bengkulu' => 'Bengkulu',
        'Bandar Lampung' => 'Lampung',
        'Pangkal Pinang' => 'Kepulauan Bangka Belitung',
        'Tanjung Pinang' => 'Kepulauan Riau',
        'Jakarta' => 'DKI Jakarta',
        'Bogor' => 'Jawa Barat',
        'Semarang' => 'Jawa Tengah',
        'Yogyakarta' => 'DI Yogyakarta',
        'Surabaya' => 'Jawa Timur',
        'Serang' => 'Banten',
        'Denpasar' => 'Bali',
        'Mataram' => 'Nusa Tenggara Barat',
        'Kupang' => 'Nusa Tenggara Timur',
        'Pontianak' => 'Kalimantan Barat',
        'Palangkaraya' => 'Kalimantan Tengah',
        'Banjarmasin' => 'Kalimantan Selatan',
        'Samarinda' => 'Kalimantan Timur',
        'Tanjung Selor' => 'Kalimantan Utara',
        'Manado' => 'Sulawesi Utara',
        'Palu' => 'Sulawesi Tengah',
        'Makassar' => 'Sulawesi Selatan',
        'Kendari' => 'Sulawesi Tenggara',
        'Gorontalo' => 'Gorontalo',
        'Mamuju' => 'Sulawesi Barat',
        'Ambon' => 'Maluku',
        'Ternate' => 'Maluku Utara',
        'Manokwari' => 'Papua Barat',
        'Jayapura' => 'Papua',
        'Merauke' => 'Papua Selatan',
    ];

    public function index()
    {
        $weatherData = [];
        $errors = [];
        $apiKey = env('WEATHER_API_KEY');

        foreach ($this->provinces as $city => $province) {
            $cacheKey = 'weather_' . strtolower(str_replace(' ', '_', $city));
            $cityItem = Cache::get($cacheKey);

            if (!$cityItem) {
                try {
                    $response = Http::timeout(8)->get("https://api.weatherapi.com/v1/current.json", [
                        'key' => $apiKey,
                        'q' => $city,
                        'aqi' => 'no'
                    ]);

                    if ($response->successful()) {
                        $cityItem = $response->json();
                        $cityItem['province'] = $province;
                        Cache::put($cacheKey, $cityItem, now()->addMinutes(10));
                    } else {
                        $errors[] = "Weather API failed for {$city}: HTTP " . $response->status();
                        Log::warning("Weather API failed for {$city}: " . $response->status());
                        $cityItem = Cache::get($cacheKey, $this->getMockWeatherData($city, $province));
                    }
                } catch (\Exception $e) {
                    $errors[] = "Weather API exception for {$city}: " . $e->getMessage();
                    Log::error("Weather API exception for {$city}: " . $e->getMessage());
                    $cityItem = Cache::get($cacheKey, $this->getMockWeatherData($city, $province));
                }
            }

            if ($cityItem) {
                $cityItem['current']['is_day'] = $cityItem['current']['is_day'] ?? 1;
                $cityItem['current']['condition']['text_id'] = $this->translateCondition($cityItem['current']['condition']['text'] ?? '');
                $cityItem['icon'] = $this->getWeatherIcon($cityItem['current']);
                $weatherData[] = $cityItem;
            }
        }

        return view('dashboard', compact('weatherData', 'errors'));
    }

    public function detail($city)
    {
        try {
            $apiKey = env('WEATHER_API_KEY');
            $province = array_search($city, $this->provinces) ?: 'Indonesia';

            Log::info("Weather detail request for city: {$city}, province: {$province}");

            // Get current weather with air quality
            $response = Http::timeout(10)->get("https://api.weatherapi.com/v1/current.json", [
                'key' => $apiKey,
                'q' => $city,
                'aqi' => 'yes'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $data['province'] = $province;
                $data['current']['is_day'] = $data['current']['is_day'] ?? 1;
                $data['current']['condition']['text_id'] = $this->translateCondition($data['current']['condition']['text'] ?? '');
                $data['icon'] = $this->getWeatherIcon($data['current']);

                // Get forecast data
                $forecastResponse = Http::timeout(10)->get("https://api.weatherapi.com/v1/forecast.json", [
                    'key' => $apiKey,
                    'q' => $city,
                    'days' => 3,
                    'aqi' => 'yes',
                    'alerts' => 'yes'
                ]);

                if ($forecastResponse->successful()) {
                    $forecastData = $forecastResponse->json();
                    $data['forecast'] = $forecastData['forecast'];

                    if (isset($data['forecast']['forecastday']) && is_array($data['forecast']['forecastday'])) {
                        foreach ($data['forecast']['forecastday'] as &$day) {
                            $dayCondition = $day['day']['condition']['text'] ?? '';
                            $day['day']['condition']['text_id'] = $this->translateCondition($dayCondition);
                            $day['day']['icon'] = $this->getWeatherIcon(['condition' => ['text' => $dayCondition], 'is_day' => 1]);
                        }
                        unset($day);
                    }
                }

                // Add detailed explanations
                $data['explanations'] = $this->getWeatherExplanations($data);

                return view('weather-detail', compact('data'));
            } else {
                Log::warning("Weather API failed for {$city}: HTTP " . $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Weather detail exception for {$city}: " . $e->getMessage());
        }

        // Fallback to mock data with explanations
        Log::info("Using mock data for {$city}");
        $data = $this->getDetailedMockWeatherData($city, $province ?? 'Indonesia');
        $data['current']['is_day'] = $data['current']['is_day'] ?? 1;
        $data['current']['condition']['text_id'] = $this->translateCondition($data['current']['condition']['text'] ?? '');
        $data['icon'] = $this->getWeatherIcon($data['current']);
        $data['explanations'] = $this->getWeatherExplanations($data);

        return view('weather-detail', compact('data'));
    }

    private function translateCondition($condition)
    {
        $condition = trim(strtolower($condition));

        $map = [
            'sunny' => 'Cerah',
            'clear' => 'Cerah',
            'partly cloudy' => 'Berawan',
            'cloudy' => 'Berawan',
            'overcast' => 'Berawan',
            'rain' => 'Hujan',
            'light rain' => 'Hujan ringan',
            'moderate rain' => 'Hujan sedang',
            'heavy rain' => 'Hujan lebat',
            'thunderstorm' => 'Badai petir',
            'snow' => 'Salju',
            'fog' => 'Kabut',
            'mist' => 'Berkabut',
            'patchy rain possible' => 'Potensi hujan',
            'patchy snow possible' => 'Potensi salju',
            'partly sunny' => 'Cerah berawan'
        ];

        foreach ($map as $key => $value) {
            if (strpos($condition, $key) !== false) {
                return $value;
            }
        }

        if ($condition === '') {
            return 'Tidak tersedia';
        }

        return ucfirst($condition);
    }

    private function getWeatherIcon($current)
    {
        $condition = strtolower($current['condition']['text'] ?? '');
        $isDay = isset($current['is_day']) ? (int)$current['is_day'] : 1;

        $isRain = strpos($condition, 'hujan') !== false || strpos($condition, 'rain') !== false || strpos($condition, 'showers') !== false || strpos($condition, 'thunder') !== false;
        $isCloudy = strpos($condition, 'berawan') !== false || strpos($condition, 'cloudy') !== false || strpos($condition, 'mendung') !== false || strpos($condition, 'overcast') !== false;
        $isClear = strpos($condition, 'cerah') !== false || strpos($condition, 'sunny') !== false || strpos($condition, 'clear') !== false;
        $isThunder = strpos($condition, 'thunder') !== false || strpos($condition, 'petir') !== false;
        $isSnow = strpos($condition, 'salju') !== false || strpos($condition, 'snow') !== false;

        if ($isThunder) {
            return 'bolt';
        }

        if ($isSnow) {
            return 'snowflake';
        }

        if ($isRain) {
            return $isDay ? 'cloud-rain' : 'cloud-moon';
        }

        if ($isCloudy) {
            return $isDay ? 'cloud-sun' : 'cloud-moon';
        }

        if ($isClear) {
            return $isDay ? 'sun' : 'moon';
        }

        return $isDay ? 'cloud-sun' : 'cloud-moon';
    }

    private function getWeatherExplanations($data)
    {
        $explanations = [];
        $current = $data['current'] ?? [];
        $condition = strtolower($current['condition']['text'] ?? 'cerah');

        // Temperature explanation
        $temp = $current['temp_c'] ?? 25;
        if ($temp < 20) {
            $explanations['temperature'] = "Suhu dingin ({$temp}°C). Waktu yang tepat untuk beraktivitas di luar ruangan. Gunakan pakaian hangat jika diperlukan.";
        } elseif ($temp < 25) {
            $explanations['temperature'] = "Suhu sejuk ({$temp}°C). Kondisi yang nyaman untuk berbagai aktivitas sehari-hari.";
        } elseif ($temp < 30) {
            $explanations['temperature'] = "Suhu hangat ({$temp}°C). Tetap terhidrasi dan gunakan sunscreen jika beraktivitas di luar.";
        } else {
            $explanations['temperature'] = "Suhu panas ({$temp}°C). Hindari aktivitas berat di luar ruangan pada siang hari. Pastikan asupan cairan cukup.";
        }

        // Humidity explanation
        $humidity = $current['humidity'] ?? 60;
        if ($humidity < 30) {
            $explanations['humidity'] = "Kelembaban rendah ({$humidity}%). Udara kering, risiko dehidrasi meningkat. Gunakan pelembab kulit.";
        } elseif ($humidity < 60) {
            $explanations['humidity'] = "Kelembaban normal ({$humidity}%). Kondisi udara yang nyaman untuk kesehatan.";
        } else {
            $explanations['humidity'] = "Kelembaban tinggi ({$humidity}%). Mungkin terasa lembab dan pengap. Risiko pertumbuhan jamur.";
        }

        // Wind explanation
        $wind = $current['wind_kph'] ?? 5;
        if ($wind < 5) {
            $explanations['wind'] = "Angin tenang ({$wind} km/h). Kondisi yang stabil untuk berbagai aktivitas.";
        } elseif ($wind < 15) {
            $explanations['wind'] = "Angin sepoi-sepoi ({$wind} km/h). Membantu sirkulasi udara dan terasa menyegarkan.";
        } elseif ($wind < 30) {
            $explanations['wind'] = "Angin kencang ({$wind} km/h). Hati-hati dengan benda-benda yang mudah terbang. Kondisi baik untuk olahraga angin.";
        } else {
            $explanations['wind'] = "Angin sangat kencang ({$wind} km/h). Potensi bahaya, hindari beraktivitas di luar jika memungkinkan.";
        }

        // Condition explanation
        if (strpos($condition, 'cerah') !== false || strpos($condition, 'sunny') !== false) {
            $explanations['condition'] = "Cuaca cerah. Sempurna untuk aktivitas luar ruangan. Jangan lupa gunakan sunscreen dan kacamata hitam.";
        } elseif (strpos($condition, 'berawan') !== false || strpos($condition, 'cloudy') !== false) {
            $explanations['condition'] = "Cuaca berawan. Kondisi yang nyaman untuk berbagai aktivitas. Risiko hujan rendah.";
        } elseif (strpos($condition, 'hujan') !== false || strpos($condition, 'rain') !== false) {
            $explanations['condition'] = "Cuaca hujan. Bawa payung atau jas hujan. Hati-hati dengan genangan air dan jalanan licin.";
        } elseif (strpos($condition, 'mendung') !== false) {
            $explanations['condition'] = "Cuaca mendung. Kemungkinan hujan sedang. Siapkan diri dengan perlengkapan hujan.";
        } else {
            $explanations['condition'] = "Kondisi cuaca: {$current['condition']['text']}. Pantau perkembangan cuaca secara berkala.";
        }

        // Air Quality explanation
        if (isset($current['air_quality'])) {
            $pm25 = $current['air_quality']['pm2_5'] ?? 0;
            if ($pm25 < 12) {
                $explanations['air_quality'] = "Kualitas udara baik (PM2.5: {$pm25}). Aman untuk semua aktivitas luar ruangan.";
            } elseif ($pm25 < 35) {
                $explanations['air_quality'] = "Kualitas udara sedang (PM2.5: {$pm25}). Masih aman, tapi kurangi aktivitas berat di luar.";
            } elseif ($pm25 < 55) {
                $explanations['air_quality'] = "Kualitas udara tidak sehat untuk kelompok sensitif (PM2.5: {$pm25}). Orang dengan penyakit pernapasan sebaiknya membatasi aktivitas luar.";
            } elseif ($pm25 < 150) {
                $explanations['air_quality'] = "Kualitas udara tidak sehat (PM2.5: {$pm25}). Kurangi aktivitas luar ruangan, gunakan masker jika terpaksa keluar.";
            } elseif ($pm25 < 250) {
                $explanations['air_quality'] = "Kualitas udara sangat tidak sehat (PM2.5: {$pm25}). Hindari aktivitas luar, gunakan masker dan purifier udara.";
            } else {
                $explanations['air_quality'] = "Kualitas udara berbahaya (PM2.5: {$pm25}). Tetap di dalam rumah, gunakan masker dan purifier udara.";
            }
        }

        // UV Index explanation
        $uv = $current['uv'] ?? 0;
        if ($uv < 3) {
            $explanations['uv'] = "Indeks UV rendah ({$uv}). Risiko terbakar matahari minimal.";
        } elseif ($uv < 6) {
            $explanations['uv'] = "Indeks UV sedang ({$uv}). Gunakan sunscreen dan kacamata hitam saat beraktivitas di luar.";
        } elseif ($uv < 8) {
            $explanations['uv'] = "Indeks UV tinggi ({$uv}). Gunakan sunscreen SPF 30+, topi, dan kacamata hitam. Hindari sinar matahari langsung.";
        } elseif ($uv < 11) {
            $explanations['uv'] = "Indeks UV sangat tinggi ({$uv}). Minimalisir paparan sinar matahari. Gunakan perlindungan penuh.";
        } else {
            $explanations['uv'] = "Indeks UV ekstrem ({$uv}). Hindari beraktivitas di luar ruangan pada siang hari.";
        }

        return $explanations;
    }

    private function getMockWeatherData($city, $province)
    {
        $conditions = ['Cerah', 'Berawan', 'Hujan', 'Panas Terik', 'Mendung'];
        $temps = [22, 25, 28, 30, 26];
        
        $rand = array_rand($conditions);
        
        $isDay = rand(0, 1);
        $current = [
            'temp_c' => $temps[$rand],
            'humidity' => rand(50, 90),
            'condition' => ['text' => $conditions[$rand]],
            'wind_kph' => rand(5, 20),
            'is_day' => $isDay,
        ];
        $current['icon'] = $this->getWeatherIcon($current);

        return [
            'location' => [
                'name' => $city,
                'region' => $province,
                'country' => 'Indonesia'
            ],
            'current' => $current,
            'province' => $province,
            'icon' => $current['icon'],
        ];
    }

    private function getDetailedMockWeatherData($city, $province)
    {
        $baseData = $this->getMockWeatherData($city, $province);

        // Add detailed mock data
        $baseData['current']['is_day'] = rand(0, 1);
        $baseData['current']['condition']['text_id'] = $this->translateCondition($baseData['current']['condition']['text'] ?? '');
        $baseData['current']['icon'] = $this->getWeatherIcon($baseData['current']);
        $baseData['current']['wind_dir'] = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'][rand(0, 7)];
        $baseData['current']['pressure_mb'] = rand(1000, 1020);
        $baseData['current']['vis_km'] = rand(5, 15);
        $baseData['current']['uv'] = rand(1, 11);
        $baseData['current']['air_quality'] = [
            'co' => rand(100, 500),
            'no2' => rand(10, 50),
            'o3' => rand(20, 80),
            'so2' => rand(5, 30),
            'pm2_5' => rand(10, 100),
            'pm10' => rand(20, 150)
        ];

        // Mock forecast for 3 days
        $baseData['forecast'] = ['forecastday' => []];
        $conditions = ['Cerah', 'Berawan', 'Hujan', 'Panas Terik', 'Mendung'];
        for ($i = 0; $i < 3; $i++) {
            $date = date('Y-m-d', strtotime("+{$i} days"));
            $dayCondition = $conditions[rand(0, 4)];
            $forecastIcon = $this->getWeatherIcon(['condition' => ['text' => $dayCondition], 'is_day' => 1]);
            $baseData['forecast']['forecastday'][] = [
                'date' => $date,
                'day' => [
                    'maxtemp_c' => $baseData['current']['temp_c'] + rand(-2, 5),
                    'mintemp_c' => $baseData['current']['temp_c'] - rand(2, 8),
                    'avgtemp_c' => $baseData['current']['temp_c'] + rand(-1, 3),
                    'maxwind_kph' => rand(10, 30),
                    'totalprecip_mm' => rand(0, 20),
                    'avghumidity' => rand(60, 90),
                    'condition' => [
                        'text' => $dayCondition,
                        'text_id' => $this->translateCondition($dayCondition),
                    ],
                    'uv' => rand(1, 11),
                    'icon' => $forecastIcon,
                ],
                'astro' => [
                    'sunrise' => '06:' . rand(10, 59) . ' AM',
                    'sunset' => '06:' . rand(10, 59) . ' PM',
                    'moonrise' => '07:' . rand(10, 59) . ' PM',
                    'moonset' => '07:' . rand(10, 59) . ' AM'
                ]
            ];
        }

        return $baseData;
    }
}
