<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-6 col-12 d-flex align-items-center justify-content-center bg-white">
                <div id="auth-left" class="w-100 p-lg-5 p-4" style="max-width: 500px;">
                    <div class="auth-logo mb-5 text-center">
                        <a href="/">
                             <div style="width: 220px; height: 70px; background-image: url('{{ asset('assets/images/logo.jpg') }}'); background-size: contain; background-position: center top; background-repeat: no-repeat; margin: 0 auto;"></div>
                        </a>
                    </div>
                    <h1 class="auth-title h2 mb-2 text-center" style="font-weight: 800; color: #435ebe;">Welcome Back</h1>
                    <p class="auth-subtitle mb-5 text-center text-muted" style="font-size: 1.1rem;">Please enter your credentials to access the system.</p>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        @if($errors->any())
                            <div class="alert alert-danger py-2 mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="form-group position-relative has-icon-left mb-4">
                            <label class="form-label text-muted small ms-1 mb-1">Email Address</label>
                            <input type="email" class="form-control form-control-xl border-0 bg-light" placeholder="email@ngoerahsun.com" name="email" value="{{ old('email') }}" required autofocus style="border-radius: 12px;">
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <label class="form-label text-muted small ms-1 mb-1">Password</label>
                            <input type="password" class="form-control form-control-xl border-0 bg-light" placeholder="Your password" name="password" required style="border-radius: 12px;">
                            <div class="form-control-icon">
                                <i class="bi bi-lock"></i>
                            </div>
                        </div>
                        
                        <div class="form-check form-check-lg d-flex align-items-end mb-4">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault" name="remember">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div>

                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-2 py-3" style="border-radius: 12px; font-weight: bold; background: linear-gradient(135deg, #435ebe 0%, #5a8dee 100%); border: none;">Login to Dashboard</button>
                    </form>
                    
                    {{-- <div class="text-center mt-5">
                        <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="font-bold text-primary">Sign up</a>.</p>
                    </div> --}}
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block p-0">
                <div id="auth-right" class="h-100" style="background-image: url('{{ asset('assets/images/bg/login-bg.png') }}'); background-size: cover; background-position: center; position: relative;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.4));"></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
