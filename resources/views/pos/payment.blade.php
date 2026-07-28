@extends('layouts.pos')

@section('title', 'Kopi Ancua - Payment Selection')

@section('content')
<!-- TopAppBar -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('pos.cart') }}" class="btn btn-link text-dark p-1 d-flex align-items-center justify-content-center text-decoration-none">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-oswald fw-bold text-dark text-uppercase mb-0 ms-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">PAYMENT SELECTION</h1>
    </div>
</header>

<main class="flex-grow-1 px-3 pt-5 pb-5 mb-5 mt-5">
    
    
    <!-- Order Summary -->
    <section class="mb-4 bg-white p-3 border border-dark d-flex justify-content-between align-items-center">
        <div>
            <p class="text-secondary text-uppercase fw-bold mb-0" style="font-size: 0.75rem;">Total to Pay</p>
            <p class="text-oswald fw-bold mb-0" style="font-size: 1.25rem;">IDR {{ number_format($grandTotal, 0, ',', '.') }}</p>
        </div>
    </section>
    <!-- Hidden Detail View -->
    <div class="d-none mb-4 border-start border-end border-bottom border-dark p-3 mt-n4 bg-white position-relative" style="top: -1.5rem; z-index: -1; padding-top: 2rem !important;" id="order-details">
        @foreach($cart as $item)
        <div class="d-flex justify-content-between mb-2 text-uppercase" style="font-size: 0.8rem;">
            <span>{{ $item['jumlah'] }}x {{ $item['nama'] }}</span>
            <span>IDR {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</span>
        </div>
        @endforeach
        <div class="d-flex justify-content-between mb-2 text-uppercase border-top border-secondary border-dashed pt-2 mt-2" style="font-size: 0.8rem;">
            <span>TAX (10%)</span>
            <span>IDR {{ number_format($tax, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Payment Methods -->
    <form action="{{ route('pos.checkout') }}" method="POST" id="checkout-form">
        @csrf
        <section class="mb-4 mt-4">
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kasir']))
                <h3 class="text-oswald text-uppercase text-dark mb-2 fw-bold" style="font-size: 1rem; letter-spacing: 1px;">Customer Detail</h3>
                <input type="text" name="nama_pelanggan" class="form-control rounded-0 border-dark shadow-none p-3 mb-4" placeholder="Masukkan Nama Pelanggan" required>
            @else
                <input type="hidden" name="nama_pelanggan" value="{{ auth()->check() ? auth()->user()->name : 'Guest' }}">
            @endif

            <h3 class="text-oswald text-uppercase text-dark mb-3 fw-bold" style="font-size: 1rem; letter-spacing: 1px;">Payment Method</h3>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center p-3 border border-dark bg-white gap-3">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">security</span>
                    <div>
                        <span class="text-oswald fw-bold text-uppercase mb-0 d-block" style="font-size: 1.1rem;">Secure Payment (Duitku)</span>
                        <small class="text-secondary">You will be redirected to choose your payment method (Gopay, OVO, VA, QRIS, dll).</small>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Terms -->
        <p class="text-center px-3 mb-5 text-secondary" style="font-size: 0.8rem;">
            By proceeding, you agree to our <a class="fw-bold text-dark text-decoration-underline" href="#">Terms of Service</a> and <a class="fw-bold text-dark text-decoration-underline" href="#">Privacy Policy</a>.
        </p>
    </form>
</main>

<!-- BottomNavBar -->
<footer class="fixed-bottom fixed-responsive d-flex p-0 border-start border-end border-dark" style="height: 56px; z-index: 1030; background-color: #a80006;">
    <!-- Cart Preview (Left) -->
    <div class="d-flex align-items-center justify-content-center gap-2 text-white px-3 h-100" style="flex: 0.45; background-color: #7a0004;">
        <span class="material-symbols-outlined">shopping_cart</span>
        <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.95rem;">{{ $totalItems }} ITEMS</span>
    </div>
    <!-- Pay Now Action (Right) -->
    <button type="submit" form="checkout-form" class="btn btn-link text-white text-decoration-none d-flex align-items-center justify-content-center gap-2 h-100 m-0 p-0" style="flex: 0.55; border: none; background: transparent;">
        <span class="text-oswald fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 1.1rem;">Pay Now</span>
        <span class="material-symbols-outlined">arrow_forward</span>
    </button>
</footer>
@endsection

@push('scripts')
<style>
    .payment-option:hover .option-container {
        background-color: #f8f9fa !important;
    }
    .payment-option input:checked + .option-container {
        border: 2px solid #a80006 !important;
        background-color: #ffe9e6 !important;
    }
    .payment-option input:checked + .option-container .radio-indicator {
        background-color: #a80006;
        border-color: #a80006 !important;
    }
    .payment-option input:checked + .option-container .radio-indicator::after {
        content: '';
        display: block;
        width: 8px;
        height: 8px;
        background-color: white;
    }
</style>
<script>
    function toggleSummary() {
        const details = document.getElementById('order-details');
        const icon = document.getElementById('summary-icon');
        if (details.classList.contains('d-none')) {
            details.classList.remove('d-none');
            icon.innerText = 'expand_less';
        } else {
            details.classList.add('d-none');
            icon.innerText = 'expand_more';
        }
    }

    // Visual feedback for payment selection
    const options = document.querySelectorAll('.payment-option input');
    options.forEach(option => {
        option.addEventListener('change', (e) => {
            // Remove active classes from all icons
            document.querySelectorAll('.payment-option .material-symbols-outlined').forEach(icon => {
                icon.classList.remove('text-primary');
                icon.classList.add('text-secondary');
                icon.style.fontVariationSettings = "'FILL' 1";
            });
            
            // Add to selected
            const container = e.target.closest('.payment-option');
            const icon = container.querySelector('.material-symbols-outlined');
            icon.classList.add('text-primary');
            icon.classList.remove('text-secondary');
        });
    });
</script>
@endpush

