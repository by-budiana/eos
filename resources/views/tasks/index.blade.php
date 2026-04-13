@extends('layouts.app')

@section('title', 'Tasks Management')
@section('subtitle', 'Manage all engineering on-site tasks')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Tasks List</h4>
        <div>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add Task</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('tasks.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Filter Date">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- All Status --</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="On Progress" {{ request('status') == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                    <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('tasks.export', request()->all()) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Deadline</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->location }}</td>
                            <td>{{ $task->date }}</td>
                            <td>{{ $task->deadline ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $task->priority == 'High' ? 'danger' : ($task->priority == 'Medium' ? 'warning' : 'info') }}">
                                    {{ $task->priority }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $task->status == 'Done' ? 'success' : ($task->status == 'On Progress' ? 'primary' : 'secondary') }}">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td>{{ $task->user->name ?? 'Unassigned' }}</td>
                            <td>
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No tasks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $tasks->links() }}
    </div>
</div>
@endsection
