@extends('layouts.pos')

@section('title', 'Notifications - KOPI ANCUA HARMONI')

@section('content')
<!-- TopAppBar -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('home') }}" class="btn btn-link text-dark p-0 text-decoration-none">
            <span class="material-symbols-outlined" style="font-size: 1.5rem;">arrow_back</span>
        </a>
        <h1 class="text-oswald fw-bold text-dark text-uppercase mb-0 ms-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">NOTIFICATIONS</h1>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('pos.cart') }}" class="btn btn-link text-primary p-0 position-relative text-decoration-none">
            <span class="material-symbols-outlined" style="font-size: 1.5rem;">shopping_cart</span>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    {{ array_sum(array_column(session('cart'), 'jumlah')) }}
                </span>
            @endif
        </a>
    </div>
</header>

<!-- Content Canvas -->
<main class="flex-grow-1 pt-5 pb-5 mb-5 mt-4 px-0">
    <!-- Section Header -->
    <div class="px-3 mb-3 d-flex justify-content-between align-items-end pt-3">
        <h2 class="text-oswald fw-bold text-uppercase border-start border-4 border-primary ps-2 mb-0" style="font-size: 1.25rem;">Recent Activity</h2>
        <button class="btn btn-link text-secondary text-decoration-underline p-0 custom-hover-primary text-uppercase fw-bold" style="font-size: 0.75rem;">MARK ALL AS READ</button>
    </div>

    <!-- Notification List -->
    <div class="d-flex flex-column">
        @forelse($orders as $order)
        <!-- Order Notification -->
        <a href="{{ route('pos.order-status', $order->id) }}" class="group border-bottom border-dark p-3 d-flex gap-3 align-items-start text-decoration-none custom-hover-bg transition-colors
            {{ $loop->first ? 'bg-light' : 'bg-white' }}">
            <div class="bg-primary p-2 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px;">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 1.2rem;">
                    {{ $order->status === 'completed' ? 'check_circle' : 'coffee' }}
                </span>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h3 class="text-oswald fw-bold text-primary text-uppercase mb-0 lh-1" style="font-size: 1.1rem;">
                        {{ $order->status === 'completed' ? 'ORDER COMPLETE' : 'ORDER RECEIVED' }}
                    </h3>
                    <span class="text-secondary text-uppercase fw-bold text-nowrap ms-2" style="font-size: 0.65rem;">{{ $order->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-dark mb-0" style="font-size: 0.9rem;">
                    Order <span class="fw-bold">{{ $order->nomor_pesanan }}</span> 
                    — IDR {{ number_format($order->total_keseluruhan, 0, ',', '.') }}
                    via {{ strtoupper($order->metode_pembayaran) }}.
                </p>
            </div>
        </a>
        @empty
        <!-- Promo Alert (Static) -->
        <div class="group bg-white border-bottom border-dark p-3 d-flex gap-3 align-items-start custom-hover-bg transition-colors">
            <div class="bg-dark p-2 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">campaign</span>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h3 class="text-oswald fw-bold text-dark text-uppercase mb-0 lh-1" style="font-size: 1.1rem;">WELCOME!</h3>
                    <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem;">JUST NOW</span>
                </div>
                <p class="text-dark mb-0" style="font-size: 0.9rem;">Belum ada pesanan. Mulai pesan kopi favoritmu sekarang!</p>
            </div>
        </div>
        @endforelse

        <!-- Promo Alert (Static) -->
        <div class="group bg-white border-bottom border-dark p-3 d-flex gap-3 align-items-start custom-hover-bg transition-colors">
            <div class="bg-dark p-2 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">campaign</span>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h3 class="text-oswald fw-bold text-dark text-uppercase mb-0 lh-1" style="font-size: 1.1rem;">PROMO ALERT</h3>
                    <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem;">1H AGO</span>
                </div>
                <p class="text-dark mb-0" style="font-size: 0.9rem;">Buy 1 Get 1 Free untuk semua Manual Brew hari ini saja!</p>
            </div>
        </div>

        <!-- Loyalty Update (Static) -->
        <div class="group bg-white border-bottom border-dark p-3 d-flex gap-3 align-items-start custom-hover-bg transition-colors">
            <div class="bg-dark p-2 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">military_tech</span>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h3 class="text-oswald fw-bold text-dark text-uppercase mb-0 lh-1" style="font-size: 1.1rem;">LOYALTY UPDATE</h3>
                    <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem;">3H AGO</span>
                </div>
                <p class="text-dark mb-0" style="font-size: 0.9rem;">Kamu sudah earned 50 points! Semakin dekat dengan reward berikutnya.</p>
            </div>
        </div>
    </div>

    <!-- Featured Banner -->
    <div class="mt-4 px-3 pb-4">
        <div class="position-relative bg-dark text-white p-4 overflow-hidden">
            <div class="position-absolute end-0 top-0 opacity-25" style="transform: translate(20px, -20px) rotate(12deg);">
                <span class="material-symbols-outlined" style="font-size: 120px;">coffee</span>
            </div>
            <div class="position-relative z-1">
                <h4 class="text-oswald fw-bold text-uppercase mb-2" style="font-size: 1.5rem;">MISSING YOUR DAILY BREW?</h4>
                <p class="mb-3" style="font-size: 0.9rem; max-width: 240px;">Re-order your favorites with just one tap and skip the queue.</p>
                <a href="{{ route('home') }}" class="btn text-white rounded-0 text-oswald fw-bold text-uppercase px-4 py-2 custom-btn-primary">ORDER NOW</a>
            </div>
        </div>
    </div>
</main>


@endsection

@push('scripts')
<style>
    .custom-hover-primary:hover {
        color: #a80006 !important;
    }
    .custom-hover-bg:hover {
        background-color: #f8f9fa !important;
    }
    .custom-btn-primary {
        background-color: #a80006;
    }
    .custom-btn-primary:hover {
        background-color: #8a0005;
        color: white;
    }
    .bg-primary-fixed {
        background-color: #fce4e4 !important;
    }
</style>
<script>
    document.querySelectorAll('.group').forEach(item => {
        item.addEventListener('click', () => {
            item.classList.add('bg-primary-fixed');
            setTimeout(() => {
                item.classList.remove('bg-primary-fixed');
                item.style.opacity = '0.7';
            }, 200);
        });
    });
</script>
@endpush

