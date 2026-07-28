@extends('layouts.pos')

@section('title', 'Kopi Ancua - Catalog')

@section('content')
<!-- TopAppBar -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        @auth
        {{-- Hamburger Dropdown --}}
        <div class="dropdown">
            <button class="btn btn-link text-dark p-1 d-flex align-items-center justify-content-center text-decoration-none" type="button" id="hamburgerMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="material-symbols-outlined" style="font-size: 1.5rem;">menu</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-start border border-dark shadow-sm" aria-labelledby="hamburgerMenu" style="min-width: 200px; border-radius: 0 !important;">
                {{-- Header: nama user --}}
                <li class="px-3 py-2 border-bottom border-dark">
                    <p class="text-oswald fw-bold text-uppercase mb-0 lh-1" style="font-size: 0.95rem;">{{ auth()->user()->nama ?? auth()->user()->name }}</p>
                    <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.7rem;">{{ ucfirst(auth()->user()->role) }}</span>
                </li>
                {{-- Riwayat Pesanan --}}
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('pos.orders') }}">
                        <span class="material-symbols-outlined text-primary" style="font-size: 1.1rem;">receipt_long</span>
                        <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.85rem;">Riwayat Pesanan</span>
                    </a>
                </li>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'kasir')
                {{-- Panel Admin/Kasir --}}
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('pos.kasir.orders') }}">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">dashboard</span>
                        <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.85rem;">{{ auth()->user()->role === 'admin' ? 'Admin Panel' : 'Panel Kasir' }}</span>
                    </a>
                </li>
                @endif
                <li><hr class="dropdown-divider border-dark m-0"></li>
                {{-- Logout --}}
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                            <span class="material-symbols-outlined" style="font-size: 1.1rem;">logout</span>
                            <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.85rem;">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn btn-link text-primary p-1 d-flex align-items-center justify-content-center text-decoration-none" title="Login">
            <span class="material-symbols-outlined">login</span>
        </a>
        @endauth
        <h1 class="text-oswald fw-bold text-dark text-uppercase mb-0 ms-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">
            KOPI ANCUA
        </h1>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('pos.cart') }}" class="btn btn-link text-primary p-1 d-flex align-items-center justify-content-center text-decoration-none position-relative">
            <span class="material-symbols-outlined">shopping_cart</span>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    {{ array_sum(array_column(session('cart'), 'jumlah')) }}
                </span>
            @endif
        </a>
    </div>
</header>


<!-- Category Navigation -->
<nav class="sticky-top bg-white border-bottom border-dark overflow-auto no-scrollbar d-flex px-3" style="height: 48px; top: 64px; z-index: 1020; white-space: nowrap;">
    <a href="{{ route('home') }}" class="d-flex align-items-center justify-content-center px-3 text-oswald fw-bold text-decoration-none text-uppercase {{ !request('category') ? 'text-primary border-bottom border-primary border-2' : 'text-secondary' }}" style="font-size: 0.85rem;">
        ALL
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('home', ['category' => $cat->id]) }}" class="d-flex align-items-center justify-content-center px-3 text-oswald fw-bold text-decoration-none text-uppercase {{ request('category') == $cat->id ? 'text-primary border-bottom border-primary border-2' : 'text-secondary' }}" style="font-size: 0.85rem;">
            {{ $cat->nama }}
        </a>
    @endforeach
</nav>

<!-- Main Content (Product Grid) -->
<main class="flex-grow-1 px-3 pt-3 pb-5 mb-5 mt-5">
    <div class="row g-3">
        @foreach($menus as $menu)
            <!-- Product Card -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-dark rounded-0">
                    <div class="position-relative overflow-hidden bg-light" style="padding-top: 100%;">
                        <img class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" src="{{ $menu->gambar }}" alt="{{ $menu->nama }}">
                    </div>
                    <div class="card-body p-2 d-flex flex-column">
                        <h3 class="text-oswald text-uppercase fw-bold mb-1 lh-sm" style="font-size: 1rem;">{{ $menu->nama }}</h3>
                        <p class="text-primary fw-bold mt-auto mb-2" style="font-size: 0.85rem;">IDR {{ number_format($menu->harga, 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('pos.detail', $menu->id) }}" class="btn text-white w-100 rounded-0 text-oswald fw-bold text-uppercase py-2" style="background-color: #291714; font-size: 0.85rem;">
                        VIEW
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</main>

<!-- BottomNavBar / Action Bar -->
@php
    $cart = session('cart', []);
    $totalItems = 0;
    $cartSubtotal = 0;
    foreach($cart as $item) {
        $totalItems += $item['jumlah'];
        $cartSubtotal += $item['harga'] * $item['jumlah'];
    }
@endphp
<div class="fixed-bottom fixed-responsive d-flex text-white p-0 border-start border-end border-dark" style="height: 64px; z-index: 1030; background-color: #a80006;">
    <div class="flex-grow-1 d-flex align-items-center px-3">
        <div class="d-flex flex-column">
            <span class="text-oswald text-uppercase" style="font-size: 0.75rem; opacity: 0.8; letter-spacing: 1px;">YOUR ORDER</span>
            <span class="text-oswald fw-bold" style="font-size: 1.1rem;">{{ $totalItems }} ITEMS | IDR {{ number_format($cartSubtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    <a href="{{ route('pos.cart') }}" class="d-flex align-items-center justify-content-center px-4 text-decoration-none text-primary bg-white text-oswald fw-bold text-uppercase h-100 border-start border-dark" style="font-size: 1rem;">
        CHECKOUT
        <span class="material-symbols-outlined ms-2">arrow_forward</span>
    </a>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('a.btn').forEach(btn => {
        btn.addEventListener('mousedown', function() {
            this.style.opacity = '0.8';
        });
        btn.addEventListener('mouseup', function() {
            this.style.opacity = '1';
        });
    });
</script>
@endpush

