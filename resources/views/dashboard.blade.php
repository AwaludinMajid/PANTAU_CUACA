@extends('layouts.app')

@section('content')
    <div class="header-section">
        <h1>
            <i class="fas fa-cloud-sun"></i> Pantau Cuaca Indonesia
        </h1>
        <p>Memantau cuaca real-time di {{ isset($weatherData) ? count($weatherData) : 0 }} ibu kota provinsi</p>
    </div>

    <div class="d-flex gap-2 mb-4 flex-column flex-md-row">
        <input id="search" class="form-control" type="search" placeholder="Cari kota atau provinsi..." />
        <button id="refreshCache" class="btn btn-light text-dark">Segarkan Data</button>
    </div>

    <div class="update-info">
        <i class="fas fa-sync-alt"></i> Data diperbarui: <span id="updateTime">Sekarang</span>
    </div>

    @if(isset($errors) && count($errors) > 0)
        <div class="api-error">
            <strong><i class="fas fa-exclamation-circle"></i> Informasi API:</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                @foreach($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <small>Menggunakan data cache/default.</small>
        </div>
    @endif

    @if(isset($weatherData) && count($weatherData) > 0)
        <div class="row-cards">
            @foreach ($weatherData as $data)
                @php
                    $icon = $data['icon'] ?? 'cloud-sun';
                @endphp
                <div class="weather-card" data-city="{{ $data['location']['name'] ?? 'Unknown City' }}" onclick="showWeatherDetail(this.dataset.city)" style="cursor: pointer;"> 
                    <div class="city-name">{{ $data['location']['name'] ?? 'Unknown City' }}</div>
                    <div class="province-name">
                        <i class="fas fa-map-marker-alt"></i> {{ $data['province'] ?? $data['location']['region'] ?? 'Indonesia' }}
                    </div>
                    
                    <div class="weather-info">
                        <div class="weather-item">
                            <div class="condition-icon">
                                <i class="fas fa-{{ $icon }}"></i>
                            </div>
                            <div class="label">Kondisi</div>
                            <div class="value" style="font-size: 1rem; color: #333;">{{ $data['current']['condition']['text_id'] ?? $data['current']['condition']['text'] ?? 'Data kosong' }}</div>
                        </div>
                        
                        <div class="weather-item">
                            <div class="label">Suhu</div>
                            <div class="value">{{ isset($data['current']['temp_c']) ? number_format($data['current']['temp_c'], 1) . '°C' : 'N/A' }}</div>
                        </div>
                        
                        <div class="weather-item">
                            <div class="label">Kelembaban</div>
                            <div class="value">{{ $data['current']['humidity'] ?? 'N/A' }}%</div>
                        </div>
                        
                        <div class="weather-item">
                            <div class="label">Angin</div>
                            <div class="value">{{ isset($data['current']['wind_kph']) ? number_format($data['current']['wind_kph'], 0) . ' km/h' : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Tidak ada data cuaca tersedia.
        </div>
    @endif

    <!-- Weather Detail Modal -->
    <div class="modal fade" id="weatherDetailModal" tabindex="-1" aria-labelledby="weatherDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="weatherDetailModalLabel">
                        <i class="fas fa-cloud-sun"></i> Detail Cuaca Lengkap
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="weatherDetailContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail cuaca...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="viewFullDetailBtn" class="btn btn-primary" style="display: none;">
                        <i class="fas fa-external-link-alt"></i> Lihat Halaman Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    window.addEventListener('DOMContentLoaded', function() {
        const updateTimeEl = document.getElementById('updateTime');
        if (updateTimeEl) {
            updateTimeEl.textContent = new Date().toLocaleTimeString('id-ID');
        }

        const searchEl = document.getElementById('search');
        const refreshBtn = document.getElementById('refreshCache');

        function filterCards() {
            const query = searchEl.value.trim().toLowerCase();
            document.querySelectorAll('.weather-card').forEach(card => {
                const city = (card.querySelector('.city-name')?.textContent || '').toLowerCase();
                const province = (card.querySelector('.province-name')?.textContent || '').toLowerCase();
                card.style.display = (city.includes(query) || province.includes(query)) ? '' : 'none';
            });
        }

        if (searchEl) {
            searchEl.addEventListener('input', filterCards);
        }

        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                location.reload();
            });
        }

        // Auto refresh 30 menit
        setTimeout(function() {
            location.reload();
        }, 30 * 60 * 1000);
    });

    function showWeatherDetail(cityName) {
        const modal = new bootstrap.Modal(document.getElementById('weatherDetailModal'));
        const content = document.getElementById('weatherDetailContent');
        const viewFullBtn = document.getElementById('viewFullDetailBtn');

        // Show loading
        content.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Memuat detail cuaca untuk <strong>${cityName}</strong>...</p>
                <small class="text-muted">Mengambil data terkini dan penjelasan lengkap</small>
            </div>
        `;

        // Hide full detail button initially
        viewFullBtn.style.display = 'none';

        // Show modal
        modal.show();

        // Fetch weather detail
        fetch(`/weather/${encodeURIComponent(cityName)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                content.innerHTML = html;
                viewFullBtn.style.display = 'inline-block';
                viewFullBtn.href = `/weather/${encodeURIComponent(cityName)}`;
                viewFullBtn.target = '_blank';
            })
            .catch(error => {
                console.error('Error loading weather detail:', error);
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Gagal memuat detail cuaca</strong><br>
                        <small class="text-muted mt-2">Error: ${error.message}</small><br>
                        <small>Silakan coba lagi atau periksa koneksi internet Anda.</small>
                    </div>
                `;
            });
    }
</script>
