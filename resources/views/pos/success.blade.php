@extends('layouts.pos')

@section('title', 'Order Successful - Kopi Ancua Harmoni')

@push('scripts')
<style>
    /* Custom scrollbar for editorial feel */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #FFFFFF; }
    ::-webkit-scrollbar-thumb { background: #000000; }
    
    /* Force no rounded corners globally for this brand */
    * { border-radius: 0px !important; }
</style>
@endpush

@section('content')
<!-- Top Navigation Bar -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('home') }}" class="btn btn-link text-primary p-0 text-decoration-none">
            <span class="material-symbols-outlined" style="font-size: 1.5rem;">arrow_back</span>
        </a>
    </div>
    <h1 class="text-oswald fw-bold text-primary text-uppercase mb-0" style="font-size: 1.25rem; letter-spacing: -0.5px;">ORDER COMPLETE</h1>
    <div style="width: 24px;"></div> <!-- Spacer for center alignment -->
</header>

<main class="flex-grow-1 pt-4 pb-4 mb-4 mt-4 mx-auto" style="max-width: 42rem;">
    <!-- Hero Success Section -->
    <section class="d-flex flex-column align-items-center py-3 px-3 bg-white border-bottom border-dark">
        <div class="mb-3 position-relative pt-2">
            <div class="bg-primary d-flex align-items-center justify-content-center text-white" style="width: 72px; height: 72px;">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 3rem;">check_circle</span>
            </div>
        </div>
        <h2 class="text-oswald fw-bold text-center text-uppercase mb-1 lh-1" style="font-size: 1.5rem;">
            Thank You! <br> Your Coffee is on the way.
        </h2>
        <p class="text-secondary text-uppercase fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Kopi Ancua Harmoni • Jakarta</p>
    </section>
    
    <!-- Order Information Cards -->
    <section class="row g-0 border-bottom border-dark bg-white">
        <div class="col-6 p-3 d-flex flex-column gap-1 border-end border-dark">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Order ID</span>
            <span class="text-oswald fw-bold" style="font-size: 1.1rem;">{{ $order->nomor_pesanan }}</span>
        </div>
        <div class="col-6 p-3 d-flex flex-column gap-1">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Estimated Pickup</span>
            <span class="text-oswald fw-bold text-primary" style="font-size: 1.1rem;">10-15 MINS</span>
        </div>
    </section>
    
    <!-- Order Summary Area -->
    <section class="p-3">
        <h3 class="text-secondary text-uppercase fw-bold mb-3 pb-2 border-bottom border-dark" style="font-size: 0.75rem; letter-spacing: 1px;">Your Order Summary</h3>
        <div class="d-flex flex-column gap-2">
            @foreach($order->detail as $item)
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex gap-3">
                    <div class="bg-light border border-dark" style="width: 48px; height: 48px;">
                        <img class="w-100 h-100 object-fit-cover" src="{{ $item->menu->gambar ?? '' }}">
                    </div>
                    <div>
                        <p class="text-oswald fw-bold text-uppercase mb-0" style="font-size: 1.1rem;">{{ $item->menu->nama ?? 'Product' }}</p>
                        <p class="text-secondary mb-0" style="font-size: 0.85rem;">{{ $item->jumlah }}x • {{ $item->tingkat_gula }} Sugar, {{ $item->tingkat_es }} Ice</p>
                    </div>
                </div>
                <span class="fw-bold" style="font-size: 1rem;">IDR {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        
        <!-- Tax and Total -->
        <div class="mt-3 pt-2 border-top border-dark border-2 d-flex flex-column gap-1">
            <div class="d-flex justify-content-between align-items-center text-secondary">
                <span class="text-uppercase fw-bold" style="font-size: 0.75rem;">Subtotal</span>
                <span class="fw-bold" style="font-size: 1rem;">IDR {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center text-secondary">
                <span class="text-uppercase fw-bold" style="font-size: 0.75rem;">Service & Tax (10%)</span>
                <span class="fw-bold" style="font-size: 1rem;">IDR {{ number_format($order->pajak, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-2">
                <span class="text-oswald fw-bold text-uppercase" style="font-size: 1.1rem;">Total Paid</span>
                <span class="text-oswald fw-bold text-primary" style="font-size: 1.25rem; letter-spacing: -0.5px;">IDR {{ number_format($order->total_keseluruhan, 0, ',', '.') }}</span>
            </div>
        </div>
    </section>
    
   
</main>

<!-- Fixed Bottom Action Bar -->
<div class="fixed-bottom fixed-responsive bg-white border-top border-start border-end border-dark p-3 d-flex flex-column gap-2" style="z-index: 1030;">
    <a href="{{ route('pos.order-status', $order->id) }}" class="btn btn-primary rounded-0 w-100 d-flex align-items-center justify-content-center gap-2 text-oswald fw-bold text-uppercase border-0" style="height: 48px; font-size: 0.95rem; letter-spacing: 1px;">
        <span class="material-symbols-outlined" style="font-size: 1.2rem;">local_cafe</span>
        TRACK ORDER STATUS
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('pos.receipt', $order->id) }}" class="btn text-white rounded-0 flex-grow-1 d-flex align-items-center justify-content-center gap-2 text-oswald fw-bold text-uppercase border-0" style="height: 40px; background-color: #291714; font-size: 0.85rem;">
            <span class="material-symbols-outlined" style="font-size: 1.1rem;">receipt</span>
            RECEIPT
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-0 flex-grow-1 d-flex align-items-center justify-content-center gap-2 text-oswald fw-bold text-uppercase border-2" style="height: 40px; font-size: 0.85rem;">
            <span class="material-symbols-outlined" style="font-size: 1.1rem;">restaurant_menu</span>
            ORDER MORE
        </a>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .custom-hover-black:hover {
        color: black !important;
    }
    .custom-hover-black:hover .custom-border-hover-black {
        border-color: black !important;
    }
</style>
<script>
    // Simple micro-interaction for the success icon
    document.addEventListener('DOMContentLoaded', () => {
        const icon = document.querySelector('.material-symbols-outlined.text-white');
        if(icon) {
            icon.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            icon.style.transform = 'scale(0.5)';
            setTimeout(() => {
                icon.style.transform = 'scale(1)';
            }, 200);
        }
    });
</script>
@endpush

