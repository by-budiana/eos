@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome to Engineering On-Site Task Management System')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendors/apexcharts/apexcharts.css') }}">
<style>
    .location-info { font-size: 0.9rem; color: #6c757d; }
</style>
@endpush
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
    // Geolocation script
    document.addEventListener("DOMContentLoaded", function() {
        const locationStatus = document.getElementById('location-status');
        const inLat = document.getElementById('check-in-lat');
        const inLng = document.getElementById('check-in-lng');
        const outLat = document.getElementById('check-out-lat');
        const outLng = document.getElementById('check-out-lng');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                if (inLat) inLat.value = lat;
                if (inLng) inLng.value = lng;
                if (outLat) outLat.value = lat;
                if (outLng) outLng.value = lng;
                
                if (locationStatus) {
                    locationStatus.classList.remove('alert-secondary');
                    locationStatus.classList.add('alert-success');
                    locationStatus.innerHTML = `<i class="bi bi-geo-alt-fill"></i> Lokasi berhasil didapatkan (Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)})`;
                }
            }, function(error) {
                if (locationStatus) {
                    locationStatus.classList.remove('alert-secondary');
                    locationStatus.classList.add('alert-warning');
                    locationStatus.innerHTML = "Gagal mengambil lokasi. Pastikan GPS aktif dan izinkan browser mengakses lokasi.";
                }
                console.warn(error);
            });
        } else {
            if (locationStatus) locationStatus.innerHTML = "Browser Anda tidak mendukung Geolocation.";
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
