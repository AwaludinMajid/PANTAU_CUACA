@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-cloud-sun"></i> Detail Cuaca: {{ $data['location']['name'] ?? 'Kota Tidak Diketahui' }}
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-map-marker-alt"></i> {{ $data['province'] ?? ($data['location']['region'] ?? 'Provinsi Tidak Diketahui') }}, {{ $data['location']['country'] ?? 'Negara Tidak Diketahui' }}
                        </p>
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Current Weather Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-thermometer-half"></i> Kondisi Saat Ini
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="weather-icon me-3">
                                        <i class="fas fa-sun fa-3x text-warning"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ isset($data['current']['temp_c']) ? number_format($data['current']['temp_c'], 1) . '°C' : 'N/A' }}</h3>
                                        <p class="text-muted mb-0">{{ $data['current']['condition']['text'] ?? 'Data tidak tersedia' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="weather-detail-item">
                                            <i class="fas fa-tint text-info"></i>
                                            <span>Kelembaban</span>
                                            <strong>{{ $data['current']['humidity'] ?? 'N/A' }}%</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="weather-detail-item">
                                            <i class="fas fa-wind text-success"></i>
                                            <span>Angin</span>
                                            <strong>{{ isset($data['current']['wind_kph']) ? number_format($data['current']['wind_kph'], 0) . ' km/h' : 'N/A' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="weather-detail-item">
                                            <i class="fas fa-compass text-primary"></i>
                                            <span>Arah Angin</span>
                                            <strong>{{ $data['current']['wind_dir'] ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="weather-detail-item">
                                            <i class="fas fa-eye text-secondary"></i>
                                            <span>Visibilitas</span>
                                            <strong>{{ isset($data['current']['vis_km']) ? number_format($data['current']['vis_km'], 1) . ' km' : 'N/A' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="weather-detail-item">
                                            <i class="fas fa-tachometer-alt text-danger"></i>
                                            <span>Tekanan</span>
                                            <strong>{{ isset($data['current']['pressure_mb']) ? number_format($data['current']['pressure_mb'], 0) . ' mb' : 'N/A' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="weather-detail-item">
                                            <i class="fas fa-sun text-warning"></i>
                                            <span>UV Index</span>
                                            <strong>{{ $data['current']['uv'] ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-leaf"></i> Kualitas Udara
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($data['current']['air_quality']))
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="air-quality-item">
                                        <span class="label">PM2.5</span>
                                        <span class="value">{{ number_format($data['current']['air_quality']['pm2_5'] ?? 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="air-quality-item">
                                        <span class="label">PM10</span>
                                        <span class="value">{{ number_format($data['current']['air_quality']['pm10'] ?? 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="air-quality-item">
                                        <span class="label">CO</span>
                                        <span class="value">{{ number_format($data['current']['air_quality']['co'] ?? 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="air-quality-item">
                                        <span class="label">NO₂</span>
                                        <span class="value">{{ number_format($data['current']['air_quality']['no2'] ?? 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="air-quality-item">
                                        <span class="label">O₃</span>
                                        <span class="value">{{ number_format($data['current']['air_quality']['o3'] ?? 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="air-quality-item">
                                        <span class="label">SO₂</span>
                                        <span class="value">{{ number_format($data['current']['air_quality']['so2'] ?? 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Data kualitas udara tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Weather Explanations Section -->
        @if(isset($data['explanations']))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Penjelasan Lengkap Cuaca
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($data['explanations'] as $key => $explanation)
                            <div class="col-md-6 mb-3">
                                <div class="explanation-card">
                                    <div class="explanation-header">
                                        <i class="fas fa-{{ $explanationIcons[$key] ?? 'info-circle' }} me-2"></i>
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                    </div>
                                    <div class="explanation-content">
                                        {{ $explanation }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Forecast Section -->
        @if(isset($data['forecast']['forecastday']) && count($data['forecast']['forecastday']) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt"></i> Prakiraan Cuaca 3 Hari Kedepan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($data['forecast']['forecastday'] as $day)
                            <div class="col-md-4 mb-3">
                                <div class="forecast-card">
                                    <div class="forecast-date">
                                        <strong>{{ date('l, d M', strtotime($day['date'])) }}</strong>
                                    </div>
                                    <div class="forecast-temps">
                                        <span class="max-temp">{{ isset($day['day']['maxtemp_c']) ? number_format($day['day']['maxtemp_c'], 0) : 'N/A' }}°</span>
                                        <span class="min-temp">{{ isset($day['day']['mintemp_c']) ? number_format($day['day']['mintemp_c'], 0) : 'N/A' }}°</span>
                                    </div>
                                    <div class="forecast-condition">
                                        <i class="fas fa-cloud"></i>
                                        <span>{{ $day['day']['condition']['text'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="forecast-details">
                                        <small>
                                            <i class="fas fa-tint"></i> {{ isset($day['day']['totalprecip_mm']) ? number_format($day['day']['totalprecip_mm'], 1) : 'N/A' }}mm |
                                            <i class="fas fa-wind"></i> {{ isset($day['day']['maxwind_kph']) ? number_format($day['day']['maxwind_kph'], 0) : 'N/A' }}km/h |
                                            <i class="fas fa-tint"></i> {{ $day['day']['avghumidity'] ?? 'N/A' }}%
                                        </small>
                                    </div>
                                    @if(isset($day['astro']))
                                    <div class="forecast-astro mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-sun"></i> {{ $day['astro']['sunrise'] ?? 'N/A' }} |
                                            <i class="fas fa-moon"></i> {{ $day['astro']['sunset'] ?? 'N/A' }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Additional Information -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Informasi Tambahan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Lokasi: {{ $data['location']['name'] }}</h6>
                                <p class="mb-1"><strong>Provinsi:</strong> {{ $data['province'] ?? $data['location']['region'] }}</p>
                                <p class="mb-1"><strong>Negara:</strong> {{ $data['location']['country'] }}</p>
                                @if(isset($data['location']['lat']) && isset($data['location']['lon']))
                                <p class="mb-1"><strong>Koordinat:</strong> {{ number_format($data['location']['lat'], 4) }}, {{ number_format($data['location']['lon'], 4) }}</p>
                                @endif
                                @if(isset($data['location']['localtime']))
                                <p class="mb-0"><strong>Waktu Lokal:</strong> {{ date('d M Y, H:i', strtotime($data['location']['localtime'])) }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>Tips Kesehatan & Aktivitas</h6>
                                <ul class="mb-0">
                                    <li>Pantau perkembangan cuaca secara berkala</li>
                                    <li>Siapkan payung jika ada potensi hujan</li>
                                    <li>Gunakan sunscreen saat UV index tinggi</li>
                                    <li>Perhatikan kualitas udara untuk kesehatan pernapasan</li>
                                    <li>Tetap terhidrasi terutama saat cuaca panas</li>
                                    <li>Gunakan pakaian sesuai kondisi cuaca</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $explanationIcons = [
        'temperature' => 'thermometer-half',
        'humidity' => 'tint',
        'wind' => 'wind',
        'condition' => 'cloud-sun',
        'air_quality' => 'leaf',
        'uv' => 'sun'
    ];
@endphp

<style>
.weather-detail-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.weather-detail-item i {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.weather-detail-item span {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 2px;
}

.weather-detail-item strong {
    font-size: 1.1rem;
    color: #495057;
}

.air-quality-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 8px;
}

.air-quality-item .label {
    font-weight: 500;
    color: #495057;
}

.air-quality-item .value {
    font-weight: bold;
    color: #dc3545;
}

.explanation-card {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f4f8 100%);
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    height: 100%;
}

.explanation-header {
    font-weight: bold;
    color: #495057;
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.explanation-content {
    color: #6c757d;
    line-height: 1.5;
    font-size: 0.95rem;
}

.forecast-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    height: 100%;
}

.forecast-date {
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.forecast-temps {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 15px;
}

.forecast-temps .max-temp {
    font-size: 1.8rem;
    font-weight: bold;
}

.forecast-temps .min-temp {
    font-size: 1.4rem;
    opacity: 0.8;
}

.forecast-condition {
    margin-bottom: 10px;
}

.forecast-condition i {
    display: block;
    font-size: 2rem;
    margin-bottom: 5px;
}

.forecast-details {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-bottom: 10px;
}

.forecast-astro {
    border-top: 1px solid rgba(255,255,255,0.3);
    padding-top: 8px;
}
</style>