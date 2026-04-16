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
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="mb-0">Description</label>
                        <button type="button" class="btn btn-sm btn-info" id="btn-generate-ai">
                            <i class="bi bi-magic"></i> Perbaiki dengan AI
                        </button>
                    </div>
                    <textarea name="description" id="task-description" class="form-control" rows="4" required>{{ old('description', $task->description) }}</textarea>
                    <small class="text-muted d-block mt-1" id="ai-status"></small>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Task Date</label>
                    <input type="text" name="date" class="form-control flatpickr" value="{{ old('date', $task->date) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Deadline</label>
                    <input type="text" name="deadline" class="form-control flatpickr" value="{{ old('deadline', $task->deadline) }}">
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
                <div class="col-md-12 mb-3">
                    <label>Assign To (Engineer)</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">-- Unassigned --</option>
                        @foreach($engineers as $en)
                            <option value="{{ $en->id }}" {{ old('assigned_to', $task->assigned_to) == $en->id ? 'selected' : '' }}>{{ $en->name }}</option>
                        @endforeach
                    </select>
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

@push('scripts')
<script>
    document.getElementById('btn-generate-ai').addEventListener('click', async function() {
        const descriptionInput = document.getElementById('task-description');
        const statusText = document.getElementById('ai-status');
        const originalText = descriptionInput.value;

        if (!originalText.trim()) {
            alert('Mohon isi deskripsi terlebih dahulu sebelum diperbaiki oleh AI.');
            return;
        }

        // Tampilkan loading state
        this.disabled = true;
        const originalBtnHtml = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
        
        statusText.innerText = 'AI sedang merangkai kalimat profesional...';
        statusText.className = 'text-info d-block mt-1';

        try {
            const response = await fetch('{{ route('tasks.ai-description') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ description: originalText })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Set text update
                descriptionInput.value = result.data;
                statusText.innerText = '✨ Deskripsi berhasil diperbaiki!';
                statusText.className = 'text-success d-block mt-1 fw-bold';
            } else {
                alert(result.message || 'Terjadi kesalahan saat memproses data ke AI.');
                statusText.innerText = 'Gagal memperbaiki deskripsi.';
                statusText.className = 'text-danger d-block mt-1';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi atau server.');
            statusText.innerText = 'Kesalahan sistem saat memanggil AI.';
            statusText.className = 'text-danger d-block mt-1';
        } finally {
            // Restore button
            this.disabled = false;
            this.innerHTML = originalBtnHtml;
        }
    });

    // Initialize Flatpickr
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
</script>
@endpush
