@extends('layouts.app')

@section('title', 'Profile')
@section('subtitle', 'Update your personal profile settings')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Profile Settings</h4>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('settings.profile.update') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>New Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" minlength="8">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" minlength="8">
                </div>
                <div class="col-md-12 mb-3">
                    <label>Role</label>
                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
