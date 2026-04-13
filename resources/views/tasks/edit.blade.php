@extends('layouts.app')

@section('title', 'Edit Task')
@section('subtitle', 'Update task information')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Task Detail</h4>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $task->location) }}" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $task->description) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', $task->date) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Deadline</label>
                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline', $task->deadline) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending" {{ old('status', $task->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="On Progress" {{ old('status', $task->status) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                        <option value="Done" {{ old('status', $task->status) == 'Done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Priority</label>
                    <select name="priority" class="form-control" required>
                        <option value="Low" {{ old('priority', $task->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $task->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $task->priority) == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Assign To (Engineer)</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">-- Unassigned --</option>
                        @foreach($engineers as $en)
                            <option value="{{ $en->id }}" {{ old('assigned_to', $task->assigned_to) == $en->id ? 'selected' : '' }}>{{ $en->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label>Note (Catatan)</label>
                    <textarea name="note" class="form-control" rows="2">{{ old('note', $task->note) }}</textarea>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
