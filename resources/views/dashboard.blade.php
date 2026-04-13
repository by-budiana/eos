@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome to Engineering On-Site Task Management System')

@section('content')
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
    </div>
</div>
@endsection
