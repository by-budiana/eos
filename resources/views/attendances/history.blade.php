@extends('layouts.app')

@section('title', 'Riwayat Absensi')
@section('subtitle', 'Daftar riwayat kehadiran karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Filter Data</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('attendances.history') }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="date">Tanggal</label>
                    <input type="date" class="form-control" name="date" id="date" value="{{ request('date') }}">
                </div>
                
                @if(auth()->user()->role == 'admin')
                <div class="col-md-3 mb-3">
                    <label for="user_id">Karyawan</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">Semua Karyawan</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3 mb-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                    <a href="{{ route('attendances.history') }}" class="btn btn-light">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Data Absensi</h4>
        <a href="{{ route('attendances.export', request()->all()) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>Tanggal</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Pulang</th>
                        <th>Status</th>
                        <th>Lokasi Masuk</th>
                        <th>Lokasi Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $key => $attendance)
                    <tr>
                        <td>{{ $attendances->firstItem() + $key }}</td>
                        <td>{{ $attendance->user->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                        <td>{{ $attendance->check_in_time }}</td>
                        <td>{{ $attendance->check_out_time ?? '-' }}</td>
                        <td>
                            @if($attendance->status == 'hadir')
                                <span class="badge bg-success">Hadir</span>
                            @else
                                <span class="badge bg-warning">Terlambat</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance->check_in_latitude && $attendance->check_in_longitude)
                                <a href="https://maps.google.com/?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-geo-alt"></i> Lihat
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($attendance->check_out_latitude && $attendance->check_out_longitude)
                                <a href="https://maps.google.com/?q={{ $attendance->check_out_latitude }},{{ $attendance->check_out_longitude }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-geo-alt"></i> Lihat
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Data absensi tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $attendances->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
