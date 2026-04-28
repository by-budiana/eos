@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome to Engineering On-Site Task Management System')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendors/apexcharts/apexcharts.css') }}">
<style>
    /* Hide default page title */
    .page-title { display: none !important; }

    /* Custom Premium Header */
    .premium-header {
        margin-bottom: 2rem;
    }
    .greeting-text {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #25396f;
        margin-bottom: 0.2rem;
    }
    .greeting-subtext {
        font-size: 1.1rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Cards */
    .smart-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid rgba(255,255,255,0.8);
        padding: 24px;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .smart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(45, 55, 72, 0.05);
        padding: 24px;
        height: 100%;
        transition: all 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(45, 55, 72, 0.1);
    }

    /* Weather Specific */
    .weather-temp {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1;
        background: -webkit-linear-gradient(45deg, #25396f, #435ebe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .aqi-bar {
        height: 8px;
        border-radius: 4px;
        background: linear-gradient(90deg, #00e400 0%, #ffff00 25%, #ff7e00 50%, #ff0000 75%, #8f3f97 100%);
        position: relative;
        overflow: hidden;
    }
    .aqi-indicator {
        position: absolute;
        top: -2px;
        bottom: -2px;
        width: 4px;
        background: #fff;
        border: 1px solid #000;
        border-radius: 2px;
        box-shadow: 0 0 5px rgba(0,0,0,0.5);
        transition: left 1s ease;
    }

    /* Clock Specific */
    .clock-digital {
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: #25396f;
    }
    .clock-date {
        font-size: 1rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }
    
    /* Analog Clock */
    .analog-clock {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.1), 0 5px 15px rgba(0,0,0,0.05);
        position: relative;
        background: #f8f9fa;
        margin: 0 auto;
    }
    .analog-clock::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        background: #435ebe;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
    }
    .hand {
        position: absolute;
        bottom: 50%;
        left: 50%;
        transform-origin: bottom;
        border-radius: 4px;
        z-index: 5;
    }
    .hand.hour {
        width: 4px;
        height: 30%;
        background: #25396f;
        margin-left: -2px;
    }
    .hand.minute {
        width: 3px;
        height: 40%;
        background: #435ebe;
        margin-left: -1.5px;
    }
    .hand.second {
        width: 2px;
        height: 45%;
        background: #dc3545;
        margin-left: -1px;
    }
</style>
@endpush

<!-- PREMIUM HEADER -->
<div class="premium-header">
    <h1 class="greeting-text" id="dynamic-greeting">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }} 👋</h1>
    <p class="greeting-subtext">Semoga pekerjaan hari ini lancar.</p>
</div>

<!-- TOP SECTION: Weather & Clock -->
<div class="row mb-4">
    <!-- Weather Card -->
    <div class="col-12 col-xl-6 mb-4 mb-xl-0">
        <div class="smart-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="mb-0 text-muted fw-bold text-uppercase fs-6">Cuaca Saat Ini</h5>
                    <div class="d-flex align-items-center mt-1">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        <span id="weather-location" class="fw-semibold">Mengambil Lokasi...</span>
                    </div>
                </div>
                <div class="weather-icon text-primary" style="font-size: 2.5rem; line-height: 1;" id="weather-icon">
                    <i class="bi bi-cloud-sun"></i>
                </div>
            </div>
            
            <div class="row align-items-center mt-4">
                <div class="col-6 border-end">
                    <div class="weather-temp" id="weather-temp">--°C</div>
                    <div class="text-muted mt-1 fw-medium">Feels like <span id="weather-feels">--°C</span></div>
                </div>
                <div class="col-6 ps-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-thermometer-half text-danger me-2"></i>
                        <span class="text-muted">Min/Max:</span> 
                        <span class="ms-auto fw-bold"><span id="weather-min">--</span> / <span id="weather-max">--</span></span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-droplet-fill text-info me-2"></i>
                        <span class="text-muted">Humidity:</span> 
                        <span class="ms-auto fw-bold" id="weather-humidity">--%</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted fs-7 fw-semibold">Air Quality Index (AQI)</span>
                    <span class="fw-bold" id="aqi-value">--</span>
                </div>
                <div class="aqi-bar">
                    <div class="aqi-indicator" id="aqi-indicator" style="left: 0%;"></div>
                </div>
            </div>
            
            <div class="text-end mt-3">
                <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock-history"></i> Last update: <span id="weather-updated">--:--</span></small>
            </div>
        </div>
    </div>
    
    <!-- Live Clock Card -->
    <div class="col-12 col-xl-6">
        <div class="glass-card d-flex flex-column justify-content-between h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-muted fw-bold text-uppercase fs-6">Waktu Indonesia Tengah (WITA)</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">Asia/Makassar</span>
            </div>
            
            <div class="row flex-grow-1 align-items-center">
                <div class="col-12 col-md-5 text-center mb-4 mb-md-0">
                    <div class="analog-clock">
                        <div class="hand hour" id="hour-hand"></div>
                        <div class="hand minute" id="minute-hand"></div>
                        <div class="hand second" id="second-hand"></div>
                    </div>
                </div>
                <div class="col-12 col-md-7 text-center text-md-start">
                    <div class="clock-digital mb-2" id="digital-clock">00:00:00 PM</div>
                    <div class="clock-date" id="date-display">Senin, 01 Januari 2026</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Absensi Aksi & Info -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Aksi Absensi</h4>
            </div>
            <div class="card-body text-center pb-5">
                <div id="location-status" class="alert alert-secondary mb-4">
                    Mengambil lokasi Anda...
                </div>
                
                <div class="d-flex justify-content-center gap-3">
                    <form action="{{ route('attendances.check-in') }}" method="POST" id="form-check-in">
                        @csrf
                        <input type="hidden" name="latitude" id="check-in-lat">
                        <input type="hidden" name="longitude" id="check-in-lng">
                        <button type="submit" class="btn btn-success btn-lg" 
                            {{ $attendanceToday ? 'disabled' : '' }} id="btn-check-in">
                            <i class="bi bi-box-arrow-in-right"></i> Check In
                        </button>
                    </form>

                    <form action="{{ route('attendances.check-out') }}" method="POST" id="form-check-out">
                        @csrf
                        <input type="hidden" name="latitude" id="check-out-lat">
                        <input type="hidden" name="longitude" id="check-out-lng">
                        <button type="submit" class="btn btn-danger btn-lg" 
                            {{ (!$attendanceToday || $attendanceToday->check_out_time) ? 'disabled' : '' }} id="btn-check-out">
                            <i class="bi bi-box-arrow-left"></i> Check Out
                        </button>
                    </form>
                </div>
                
                <div class="mt-4">
                    @if($attendanceToday)
                        <p class="mb-1"><strong>Waktu Check In:</strong> {{ $attendanceToday->check_in_time }}</p>
                        <p class="mb-1"><strong>Waktu Check Out:</strong> {{ $attendanceToday->check_out_time ?? 'Belum check out' }}</p>
                    @else
                        <p class="text-muted">Anda belum melakukan check in hari ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistik Absensi Singkat -->
    <div class="col-12 col-lg-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon purple mb-2">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Hadir (Hari Ini)</h6>
                                <h6 class="font-extrabold mb-0">{{ $totalHadirHariIni }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon blue mb-2">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Belum Check Out</h6>
                                <h6 class="font-extrabold mb-0">{{ $belumCheckOut }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h5 class="mb-3 mt-4">Ringkasan Task</h5>
<div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                        <div class="stats-icon purple mb-2">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Tasks Today</h6>
                        <h6 class="font-extrabold mb-0">{{ $totalTasksToday }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                        <div class="stats-icon blue mb-2">
                            <i class="bi bi-list-check"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Completed</h6>
                        <h6 class="font-extrabold mb-0">{{ $completed }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                        <div class="stats-icon green mb-2">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Pending / Progress</h6>
                        <h6 class="font-extrabold mb-0">{{ $pending }} / {{ $onProgress }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                        <div class="stats-icon red mb-2">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Overdue</h6>
                        <h6 class="font-extrabold mb-0">{{ $overdue }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Progress Summary</h4>
            </div>
            <div class="card-body">
                <div class="progress progress-primary  mb-4">
                    <div class="progress-bar progress-label" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h5>Total Completion: {{ $progress }}%</h5>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4>Latest Activity</h4>
            </div>
            <div class="card-body">
                @if($latestActivities->count() > 0)
                    <ul class="list-group">
                        @foreach($latestActivities as $task)
                        <li class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $task->title }}</h6>
                                <small>{{ $task->updated_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1"><span class="badge bg-{{ $task->status == 'Done' ? 'success' : ($task->status == 'On Progress' ? 'warning' : 'secondary') }}">{{ $task->status }}</span></p>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p>No activity yet.</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Grafik Kehadiran (7 Hari Terakhir)</h4>
            </div>
            <div class="card-body">
                <div id="chart-attendance"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
<script>
    // === DYNAMIC GREETING & CLOCK (WITA / Asia/Makassar) ===
    function updateClockAndGreeting() {
        // We need WITA time (UTC+8)
        const now = new Date();
        const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        const witaTime = new Date(utc + (3600000 * 8));

        const hours = witaTime.getHours();
        const minutes = witaTime.getMinutes();
        const seconds = witaTime.getSeconds();

        // Greeting Logic
        let greeting = 'Selamat Pagi';
        let emoji = '☀️';
        if (hours >= 5 && hours < 11) {
            greeting = 'Selamat Pagi'; emoji = '☀️';
        } else if (hours >= 11 && hours < 15) {
            greeting = 'Selamat Siang'; emoji = '🌤️';
        } else if (hours >= 15 && hours < 18) {
            greeting = 'Selamat Sore'; emoji = '🌇';
        } else {
            greeting = 'Selamat Malam'; emoji = '🌙';
        }
        
        const userName = "{{ explode(' ', Auth::user()->name)[0] }}";
        const greetingEl = document.getElementById('dynamic-greeting');
        if(greetingEl) {
            greetingEl.innerText = `${greeting}, ${userName} ${emoji}`;
        }

        // Digital Clock
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const hours12 = hours % 12 || 12;
        const pad = (num) => num.toString().padStart(2, '0');
        
        const digitalClock = document.getElementById('digital-clock');
        if(digitalClock) {
            digitalClock.innerText = `${pad(hours12)}:${pad(minutes)}:${pad(seconds)} ${ampm}`;
        }

        // Date Display
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dateDisplay = document.getElementById('date-display');
        if(dateDisplay) {
            dateDisplay.innerText = `${days[witaTime.getDay()]}, ${witaTime.getDate()} ${months[witaTime.getMonth()]} ${witaTime.getFullYear()}`;
        }

        // Analog Clock
        const hourDeg = (hours % 12) * 30 + (minutes * 0.5);
        const minDeg = minutes * 6;
        const secDeg = seconds * 6;
        
        const hourHand = document.getElementById('hour-hand');
        if(hourHand) hourHand.style.transform = `rotate(${hourDeg}deg)`;
        
        const minHand = document.getElementById('minute-hand');
        if(minHand) minHand.style.transform = `rotate(${minDeg}deg)`;
        
        const secHand = document.getElementById('second-hand');
        if(secHand) secHand.style.transform = `rotate(${secDeg}deg)`;
    }

    setInterval(updateClockAndGreeting, 1000);
    updateClockAndGreeting();

    // === WEATHER SMART CARD ===
    async function fetchWeather(lat, lng, cityName) {
        try {
            document.getElementById('weather-location').innerText = cityName;
            
            // Using Open-Meteo for Weather
            const weatherRes = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,weather_code&daily=temperature_2m_max,temperature_2m_min&timezone=auto`);
            const weatherData = await weatherRes.json();
            
            // Using Open-Meteo for AQI (Air Quality)
            const aqiRes = await fetch(`https://air-quality-api.open-meteo.com/v1/air-quality?latitude=${lat}&longitude=${lng}&current=european_aqi&timezone=auto`);
            const aqiData = await aqiRes.json();

            // Populate UI
            const current = weatherData.current;
            const daily = weatherData.daily;
            
            document.getElementById('weather-temp').innerText = `${Math.round(current.temperature_2m)}°C`;
            document.getElementById('weather-feels').innerText = `${Math.round(current.apparent_temperature)}°C`;
            document.getElementById('weather-min').innerText = `${Math.round(daily.temperature_2m_min[0])}°C`;
            document.getElementById('weather-max').innerText = `${Math.round(daily.temperature_2m_max[0])}°C`;
            document.getElementById('weather-humidity').innerText = `${current.relative_humidity_2m}%`;
            
            // AQI
            const aqi = aqiData.current.european_aqi || 20; // fallback if undefined
            let aqiText = 'Good';
            let aqiLeft = (aqi / 100) * 100;
            if (aqiLeft > 100) aqiLeft = 100;
            
            if (aqi <= 20) aqiText = 'Sangat Baik';
            else if (aqi <= 40) aqiText = 'Baik';
            else if (aqi <= 60) aqiText = 'Sedang';
            else if (aqi <= 80) aqiText = 'Buruk';
            else aqiText = 'Sangat Buruk';
            
            document.getElementById('aqi-value').innerText = `${Math.round(aqi)} (${aqiText})`;
            document.getElementById('aqi-indicator').style.left = `${aqiLeft}%`;
            
            // Weather Icon (Simple mapping)
            const code = current.weather_code;
            const isDay = current.is_day;
            let iconClass = 'bi-cloud-sun';
            if (code == 0) iconClass = isDay ? 'bi-sun' : 'bi-moon-stars';
            else if (code >= 1 && code <= 3) iconClass = isDay ? 'bi-cloud-sun' : 'bi-cloud-moon';
            else if (code >= 51 && code <= 67) iconClass = 'bi-cloud-rain';
            else if (code >= 71 && code <= 77) iconClass = 'bi-snow';
            else if (code >= 80 && code <= 82) iconClass = 'bi-cloud-showers-heavy';
            else if (code >= 95) iconClass = 'bi-cloud-lightning-rain';
            
            document.getElementById('weather-icon').innerHTML = `<i class="bi ${iconClass}"></i>`;
            
            // Last update
            const now = new Date();
            const pad = (num) => num.toString().padStart(2, '0');
            document.getElementById('weather-updated').innerText = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
            
        } catch (error) {
            console.error("Weather fetch error: ", error);
            document.getElementById('weather-location').innerText = "Gagal memuat cuaca";
        }
    }

    // Geolocation script for both Attendance and Weather
    document.addEventListener("DOMContentLoaded", function() {
        const locationStatus = document.getElementById('location-status');
        const inLat = document.getElementById('check-in-lat');
        const inLng = document.getElementById('check-in-lng');
        const outLat = document.getElementById('check-out-lat');
        const outLng = document.getElementById('check-out-lng');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Set Attendance forms
                if (inLat) inLat.value = lat;
                if (inLng) inLng.value = lng;
                if (outLat) outLat.value = lat;
                if (outLng) outLng.value = lng;
                
                if (locationStatus) {
                    locationStatus.classList.remove('alert-secondary');
                    locationStatus.classList.add('alert-success');
                    locationStatus.innerHTML = `<i class="bi bi-geo-alt-fill"></i> Lokasi absensi didapatkan (Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)})`;
                }

                // Get Weather using user's location
                try {
                    const geoRes = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                    const geoData = await geoRes.json();
                    const city = geoData.address.city || geoData.address.town || geoData.address.village || geoData.address.county || "Lokasi Anda";
                    fetchWeather(lat, lng, city);
                } catch (e) {
                    fetchWeather(lat, lng, "Lokasi Anda");
                }
                
            }, function(error) {
                if (locationStatus) {
                    locationStatus.classList.remove('alert-secondary');
                    locationStatus.classList.add('alert-warning');
                    locationStatus.innerHTML = "Gagal mengambil lokasi. Pastikan GPS aktif.";
                }
                console.warn("Geolocation denied/failed. Fallback to Denpasar for weather.", error);
                fetchWeather(-8.6500, 115.2167, "Denpasar");
            });
        } else {
            if (locationStatus) locationStatus.innerHTML = "Browser Anda tidak mendukung Geolocation.";
            fetchWeather(-8.6500, 115.2167, "Denpasar");
        }
    });

    // Chart Script for Attendance
    var options = {
        series: [{
            name: 'Hadir',
            data: {!! isset($weeklyData) ? json_encode(array_reverse($weeklyData['data'])) : '[]' !!}
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: {!! isset($weeklyData) ? json_encode(array_reverse($weeklyData['labels'])) : '[]' !!},
        },
        colors: ['#435ebe']
    };

    var chart = new ApexCharts(document.querySelector("#chart-attendance"), options);
    chart.render();
</script>
@endpush
