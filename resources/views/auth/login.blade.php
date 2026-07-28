@extends('layouts.auth')

@section('title', 'Login - KOPI ANCUA HARMONI')

@section('content')
<!-- Top Branding Logo Section -->
<section class="w-100 d-flex flex-column align-items-center justify-content-center pt-5 pb-3 bg-white">
    <!-- Round Logo -->
    <div class="rounded-circle shadow mb-3 overflow-hidden d-flex align-items-center justify-content-center" style="width: 140px; height: 140px; border: 5px solid #fff; background-color: #fff;">
        <img src="{{ asset('images/logo-ancua.png') }}" class="w-100 h-100" style="object-fit: contain;" alt="KOPI ANCUA Logo">
    </div>
    
</section>

<!-- Login Form Container -->
<main class="flex-grow-1 d-flex flex-column px-4 pt-5 pb-5 bg-white">
    <div class="container-fluid p-0" style="max-width: 480px; margin: 0 auto;">
        <header class="mb-4">
            <h2 class="text-oswald text-uppercase fw-bold mb-0">Login</h2>
            <p class="text-secondary text-uppercase small mt-1" style="letter-spacing: 2px;">Access your roast profile</p>
        </header>

        <form method="POST" action="{{ route('login') }}" class="d-flex flex-column gap-3">
            @csrf

            <!-- Email Input -->
            <div class="form-group">
                <label class="text-oswald text-uppercase small fw-bold text-secondary mb-1" for="email">Email Address</label>
                <input class="form-control form-control-lg border-2 border-dark rounded-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="COFFEE@ANCUA.COM" type="email"/>
                @error('email')
                    <div class="invalid-feedback fw-bold">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <div class="d-flex justify-content-between align-items-end mb-1">
                    <label class="text-oswald text-uppercase small fw-bold text-secondary mb-0" for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-oswald small text-secondary text-decoration-none" href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>
                <input class="form-control form-control-lg border-2 border-dark rounded-0 @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" type="password"/>
                @error('password')
                    <div class="invalid-feedback fw-bold">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <!-- Remember Me -->
            <div class="form-check d-none pt-2">
                <input class="form-check-input border-2 border-dark rounded-0" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}/>
                <label class="form-check-label text-secondary text-uppercase small" for="remember">Remember Me</label>
            </div>

            <!-- Action Button -->
            <div class="pt-3">
                <button class="btn btn-primary w-100 btn-lg text-oswald text-uppercase fw-bold d-flex align-items-center justify-content-center gap-2" type="submit" style="height: 56px; letter-spacing: 2px;">
                    <span>LOGIN</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </form>

        <!-- Footer Links -->
        <footer class="mt-5 text-center">
            <p class="text-dark">
                Don't have an account? 
                <a class="fw-bold text-primary text-decoration-none border-bottom border-primary border-2 pb-1 ms-1" href="{{ route('register') }}">Sign Up</a>
            </p>
        </footer>
    </div>
</main>

<!-- Decorative Industrial Element -->
<div class="w-100 d-flex" style="height: 8px; background-color: #291714;">
    <div class="h-100 w-33 bg-primary" style="width: 33.33%;"></div>
    <div class="h-100 w-33 bg-secondary" style="width: 33.33%;"></div>
    <div class="h-100 w-33" style="width: 33.33%; background-color: #004fa6;"></div>
</div>
@endsection
