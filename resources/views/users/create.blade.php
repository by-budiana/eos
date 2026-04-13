@extends('layouts.app')

@section('title', 'Add User')
@section('subtitle', 'Create a new user account')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">User Account Detail</h4>
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

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="engineer" {{ old('role') == 'engineer' ? 'selected' : '' }}>Engineer</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
