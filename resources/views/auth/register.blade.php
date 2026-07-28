@extends('layouts.auth')

@section('title', 'Register | KOPI ANCUA HARMONI')

@section('content')

<main class="flex-grow-1 d-flex flex-column align-items-center justify-content-start px-3 bg-white" style="padding-top: 88px; padding-bottom: 48px;">
    <!-- Top Branding Logo Section -->
    <div class="w-100 mb-4 d-flex flex-column align-items-center justify-content-center">
        <!-- Round Logo -->
        <div class="rounded-circle shadow mb-3 overflow-hidden d-flex align-items-center justify-content-center" style="width: 140px; height: 140px; border: 5px solid #fff; background-color: #fff;">
            <img src="{{ asset('images/logo-ancua.png') }}" class="w-100 h-100" style="object-fit: contain;" alt="KOPI ANCUA Logo">
        </div>
    </div>
    
    <div class="w-100" style="max-width: 480px;">
        <h2 class="text-oswald text-uppercase text-dark mb-1" style="font-size: 1.5rem; font-weight: 700;">Create Account</h2>
        
        <form method="POST" action="{{ route('register') }}" class="d-flex flex-column gap-3">
            @csrf

            <!-- Name Field -->
            <div class="form-group">
                <label class="text-oswald text-uppercase small fw-bold text-dark mb-1" for="name">Full Name</label>
                <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus class="form-control form-control-lg border-2 border-dark rounded-0 @error('name') is-invalid @enderror" placeholder="ENTER YOUR NAME" type="text"/>
                @error('name')
                    <div class="invalid-feedback fw-bold">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label class="text-oswald text-uppercase small fw-bold text-dark mb-1" for="email">Email Address</label>
                <input id="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-control form-control-lg border-2 border-dark rounded-0 @error('email') is-invalid @enderror" placeholder="YOUR@EMAIL.COM" type="email"/>
                @error('email')
                    <div class="invalid-feedback fw-bold">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="row g-3">
                <div class="col-md-6 form-group">
                    <label class="text-oswald text-uppercase small fw-bold text-dark mb-1" for="password">Password</label>
                    <input id="password" name="password" required autocomplete="new-password" class="form-control form-control-lg border-2 border-dark rounded-0 @error('password') is-invalid @enderror" placeholder="********" type="password"/>
                    @error('password')
                        <div class="invalid-feedback fw-bold">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label class="text-oswald text-uppercase small fw-bold text-dark mb-1" for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" name="password_confirmation" required autocomplete="new-password" class="form-control form-control-lg border-2 border-dark rounded-0" placeholder="********" type="password"/>
                </div>
            </div>

            <!-- Registration Agreement -->
            <div class="form-check d-flex align-items-start gap-2 pt-2">
                <input class="form-check-input border-2 border-dark rounded-0 mt-1" id="terms" type="checkbox" required/>
                <label class="form-check-label text-secondary text-uppercase small" for="terms" style="line-height: 1.2;">
                    I agree to the <span class="text-dark fw-bold text-decoration-underline" style="cursor: pointer;">Terms of Service</span> and <span class="text-dark fw-bold text-decoration-underline" style="cursor: pointer;">Privacy Policy</span>
                </label>
            </div>

            <!-- Register Button -->
            <button type="submit" class="btn w-100 text-white text-oswald text-uppercase fw-bold d-flex justify-content-between align-items-center mt-4 px-4" style="background-color: #291714; height: 56px; letter-spacing: 2px;">
                <span>REGISTER</span>
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </form>

        <div class="mt-5 text-center">
            <a class="text-oswald text-uppercase text-secondary text-decoration-none small border-bottom border-transparent pb-1" href="{{ route('login') }}">
                Already have an account? <span class="text-dark fw-bold">Log In</span>
            </a>
        </div>
    </div>
    
    
</main>

<!-- Bottom Action Bar -->
<div class="fixed-bottom w-100 d-flex justify-content-between align-items-center px-3" style="background-color: #291714; color: white; height: 32px; z-index: 1030;">
    <span class="text-oswald" style="font-size: 10px; letter-spacing: 1px; opacity: 0.6;">© {{ date('Y') }} KOPI ANCUA HARMONI</span>
    <span class="text-oswald" style="font-size: 10px; letter-spacing: 1px; opacity: 0.6;">SECURE REGISTRATION</span>
</div>
@endsection
