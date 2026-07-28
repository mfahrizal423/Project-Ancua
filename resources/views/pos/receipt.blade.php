@extends('layouts.pos')

@section('title', 'Receipt | ' . $order->nomor_pesanan . ' - KOPI ANCUA')

@section('content')
<!-- Top Navigation -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        @php
            $backUrl = route('pos.order-status', $order->id);
            if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kasir'])) {
                $backUrl = route('pos.kasir.orders');
            }
        @endphp
        <a href="{{ $backUrl }}" class="btn btn-link text-primary p-0 text-decoration-none">
            <span class="material-symbols-outlined" style="font-size: 1.5rem;">arrow_back</span>
        </a>
        <h1 class="text-oswald fw-bold text-dark text-uppercase mb-0 ms-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">KOPI ANCUA</h1>
    </div>
    <span class="material-symbols-outlined text-primary" style="font-size: 1.5rem;">search</span>
</header>

<main class="flex-grow-1 pt-5 pb-5 mb-5 mt-5 px-3">
    <!-- Header Info -->
    <div class="d-flex flex-column align-items-center text-center mb-4 pt-4">
        <div class="bg-primary mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
            <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1; font-size: 2.5rem;">coffee</span>
        </div>
        <h2 class="text-oswald fw-bold text-uppercase mb-1" style="font-size: 1.5rem;">TRANSACTION SUCCESSFUL</h2>
        <p class="text-secondary text-uppercase fw-bold" style="font-size: 0.85rem;">ORDER ID: {{ $order->nomor_pesanan }}</p>
    </div>

    <div class="bg-white p-3 border border-dark">
        <!-- Meta Data -->
        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">DATE & TIME</span>
            <span class="fw-bold" style="font-size: 0.85rem;">{{ $order->created_at->format('d M Y | H:i') }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">CASHIER</span>
            <span class="fw-bold" style="font-size: 0.85rem;">{{ auth()->user()->nama }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">CUSTOMER</span>
            <span class="fw-bold" style="font-size: 0.85rem;">{{ $order->nama_pelanggan }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">LOCATION</span>
            <span class="fw-bold text-end" style="font-size: 0.85rem;">HARMONI CENTRAL</span>
        </div>

        <div class="border-top border-2 border-dark border-dashed w-100 my-3"></div>

        <!-- Itemized List -->
        <div class="d-flex flex-column gap-3">
            @foreach($order->detail as $item)
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex flex-column">
                    <span class="text-oswald fw-bold text-uppercase" style="font-size: 1.1rem;">{{ $item->jumlah }}x {{ $item->menu->nama ?? 'Item' }}</span>
                    <span class="text-secondary" style="font-size: 0.85rem;">{{ $item->tingkat_gula }} Sugar • {{ $item->tingkat_es }} Ice</span>
                </div>
                <span class="text-primary fw-bold" style="font-size: 1rem;">IDR {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <div class="border-top border-2 border-dark border-dashed w-100 my-3"></div>

        <!-- Financials -->
        <div class="d-flex flex-column gap-2">
            <div class="d-flex justify-content-between">
                <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">SUBTOTAL</span>
                <span class="fw-bold" style="font-size: 1rem;">IDR {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">TAX (10%)</span>
                <span class="fw-bold" style="font-size: 1rem;">IDR {{ number_format($order->pajak, 0, ',', '.') }}</span>
            </div>
            <div class="bg-dark text-white p-3 d-flex justify-content-between align-items-center mt-3">
                <span class="text-oswald fw-bold text-uppercase" style="font-size: 1.25rem;">TOTAL</span>
                <span class="text-oswald fw-bold" style="font-size: 1.25rem;">IDR {{ number_format($order->total_keseluruhan, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="border-top border-2 border-dark border-dashed w-100 my-3"></div>

        <!-- Payment Method -->
        <div class="d-flex align-items-center justify-content-between py-2">
            <div class="d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-primary">
                    @switch($order->metode_pembayaran)
                        @case('gopay') account_balance_wallet @break
                        @case('ovo') qr_code_2 @break
                        @case('dana') wallet @break
                        @case('transfer') account_balance @break
                        @case('credit_card') credit_card @break
                        @default payments
                    @endswitch
                </span>
                <div class="d-flex flex-column">
                    <span class="text-uppercase" style="font-size: 0.65rem; font-weight: 600;">PAYMENT METHOD</span>
                    <span class="fw-bold" style="font-size: 0.85rem;">{{ strtoupper($order->metode_pembayaran) }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1 text-success">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 1.2rem;">check_circle</span>
                <span class="text-uppercase fw-bold" style="font-size: 0.75rem;">VERIFIED</span>
            </div>
        </div>

        <div class="border-top border-2 border-dark border-dashed w-100 my-3"></div>

        <!-- Footer Message -->
        <div class="text-center py-3">
            <p class="text-oswald fw-bold text-uppercase mb-1" style="font-size: 1.1rem;">THANK YOU FOR THE BREW</p>
            <p class="text-secondary px-4 mb-0" style="font-size: 0.85rem;">Your support helps us keep the industrial vibe alive. Follow us @kopiancuaharmoni.</p>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="mt-4 row g-2">
        <div class="col-6">
            <button onclick="window.print()" class="btn w-100 text-white rounded-0 text-oswald fw-bold text-uppercase d-flex align-items-center justify-content-center gap-2 py-2 border-2" style="background-color: #291714; border-color: #291714; font-size: 1rem;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">share</span>
                PRINT
            </button>
        </div>
        <div class="col-6">
            <a href="{{ route('home') }}" class="btn btn-outline-dark w-100 rounded-0 text-oswald fw-bold text-uppercase d-flex align-items-center justify-content-center gap-2 py-2 border-2" style="font-size: 1rem;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">home</span>
                HOME
            </a>
        </div>
    </div>
</main>

<!-- Bottom Action -->
<nav class="fixed-bottom fixed-responsive d-flex justify-content-between align-items-stretch p-0 border-start border-end border-dark" style="height: 56px; z-index: 1030; background-color: #a80006;">
    <a href="{{ route('pos.cart') }}" class="d-flex align-items-center justify-content-center gap-2 text-white px-3 py-2 text-decoration-none h-100" style="flex: 1; background-color: #a80006;">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 1.2rem;">shopping_cart</span>
        <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.95rem;">CART</span>
    </a>
    <a href="{{ route('home') }}" class="d-flex align-items-center justify-content-center gap-2 text-white px-3 py-2 text-decoration-none h-100" style="flex: 1; background-color: #291714;">
        <span class="material-symbols-outlined" style="font-size: 1.2rem;">restaurant_menu</span>
        <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.95rem;">ORDER MORE</span>
    </a>
</nav>
@endsection

@push('scripts')
<style>
    .border-dashed {
        border-style: dashed !important;
    }
</style>
@endpush

