@extends('layouts.pos')

@section('title', 'My Orders - KOPI ANCUA HARMONI')

@section('content')
<!-- TopAppBar -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('home') }}" class="btn btn-link text-dark p-0 text-decoration-none">
            <span class="material-symbols-outlined" style="font-size: 1.5rem;">arrow_back</span>
        </a>
        <h1 class="text-oswald fw-bold text-dark text-uppercase mb-0 ms-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">MY ORDERS</h1>
    </div>
    <div class="d-flex align-items-center">
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

<main class="flex-grow-1 pt-5 pb-5 mb-5 mt-5">
    @forelse($orders as $order)
    <!-- Order Card -->
    <a href="{{ route('pos.order-status', $order->id) }}" class="d-block border-bottom border-dark p-3 text-decoration-none bg-white hover-bg-light">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                <h3 class="text-oswald fw-bold text-dark text-uppercase mb-0" style="font-size: 1.1rem;">{{ $order->nomor_pesanan }}</h3>
            </div>
            <span class="badge text-uppercase p-2 rounded-0
                {{ $order->status === 'completed' ? 'bg-success text-white' : 'bg-primary text-white' }}" style="font-size: 0.75rem;">
                {{ strtoupper($order->status) }}
            </span>
        </div>
        <p class="text-secondary mb-2" style="font-size: 0.85rem;">
            {{ $order->detail->map(fn($i) => $i->jumlah . 'x ' . ($i->menu->nama ?? 'Item'))->join(', ') }}
        </p>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">via {{ strtoupper($order->metode_pembayaran) }}</span>
            <span class="text-oswald fw-bold text-primary" style="font-size: 1rem;">IDR {{ number_format($order->total_keseluruhan, 0, ',', '.') }}</span>
        </div>
    </a>
    @empty
    <div class="d-flex flex-column align-items-center justify-content-center py-5 px-3 text-center" style="min-height: 50vh;">
        <span class="material-symbols-outlined text-secondary mb-3" style="font-size: 4rem;">receipt_long</span>
        <h3 class="text-oswald fw-bold text-uppercase mb-2 text-dark" style="font-size: 1.5rem;">No Orders Yet</h3>
        <p class="text-secondary mb-4" style="font-size: 0.95rem;">Mulai pesan kopi favoritmu dari katalog kami.</p>
        <a href="{{ route('home') }}" class="btn text-white rounded-0 text-oswald fw-bold text-uppercase px-4 py-2" style="background-color: #291714; font-size: 1rem;">
            BROWSE MENU
        </a>
    </div>
    @endforelse

    @if($orders->hasPages())
    <div class="px-3 py-4">
        {{ $orders->links() }}
    </div>
    @endif
</main>


@endsection

@push('scripts')
<style>
    .hover-bg-light:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

