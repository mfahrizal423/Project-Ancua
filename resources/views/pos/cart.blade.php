@extends('layouts.pos')

@section('title', 'KOPI ANCUA - Cart Summary')

@section('content')
<!-- Header: Sticky Top Bar -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('home') }}" class="btn btn-link text-dark p-1 d-flex align-items-center justify-content-center text-decoration-none">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-oswald fw-bold text-dark text-uppercase mb-0 ms-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">CART</h1>
    </div>
    <div class="text-oswald fw-bold text-secondary text-uppercase" style="font-size: 0.85rem;">{{ count($cart) }} ITEMS</div>
</header>

<!-- Main Content -->
<main class="flex-grow-1 px-3 pt-5 pb-5 mb-5 mt-5">
    <!-- Cart Items List -->
    <section class="d-flex flex-column gap-3 mb-4">
        @forelse($cart as $key => $item)
            <!-- Item -->
            <div class="d-flex align-items-center gap-3 border-bottom border-dark pb-3">
                <div class="bg-light flex-shrink-0" style="width: 80px; height: 80px;">
                    <img class="w-100 h-100 object-fit-cover" src="{{ $item['gambar'] }}">
                </div>
                <div class="flex-grow-1 d-flex flex-column justify-content-between h-100" style="min-height: 80px;">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h2 class="text-oswald text-uppercase fw-bold mb-0 lh-sm" style="font-size: 1rem;">{{ $item['nama'] }}</h2>
                        <form action="{{ route('pos.cart.remove') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <input type="hidden" name="cart_key" value="{{ $key }}">
                            <button type="submit" class="btn btn-link text-secondary p-0 text-decoration-none">
                                <span class="material-symbols-outlined" style="font-size: 1.2rem;">close</span>
                            </button>
                        </form>
                    </div>
                    
                    <div class="d-flex flex-column mb-2">
                        <span class="text-secondary text-uppercase" style="font-size: 0.65rem; font-weight: 600;">{{ $item['tingkat_gula'] }} Sugar, {{ $item['tingkat_es'] }} Ice</span>
                        <span class="text-secondary text-uppercase" style="font-size: 0.65rem; font-weight: 600;">{{ $item['nama_kategori'] }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-end">
                        <div class="d-flex flex-column">
                            <span class="text-primary fw-bold" style="font-size: 0.9rem;">IDR {{ number_format($item['harga'], 0, ',', '.') }}</span>
                        </div>
                        <!-- Quantity Controls -->
                        <div class="d-flex border border-dark align-items-center" style="height: 32px;">
                            <form action="{{ route('pos.cart.add') }}" method="POST" class="h-100 m-0 p-0">
                                @csrf
                                <input type="hidden" name="id_menu" value="{{ $item['id_menu'] }}">
                                <input type="hidden" name="jumlah" value="-1">
                                <input type="hidden" name="tingkat_gula" value="{{ $item['tingkat_gula'] }}">
                                <input type="hidden" name="tingkat_es" value="{{ $item['tingkat_es'] }}">
                                <button type="button" class="btn btn-link text-dark p-0 d-flex align-items-center justify-content-center h-100 rounded-0 text-decoration-none" style="width: 32px;" onclick="updateCartQty('{{ $key }}', {{ $item['jumlah'] - 1 }})">
                                    <span class="material-symbols-outlined" style="font-size: 1.1rem;">remove</span>
                                </button>
                            </form>
                            <span class="px-2 text-oswald fw-bold text-center" style="font-size: 0.9rem; min-width: 32px;">{{ str_pad($item['jumlah'], 2, '0', STR_PAD_LEFT) }}</span>
                            <form action="{{ route('pos.cart.add') }}" method="POST" class="h-100 m-0 p-0">
                                @csrf
                                <input type="hidden" name="id_menu" value="{{ $item['id_menu'] }}">
                                <input type="hidden" name="jumlah" value="1">
                                <input type="hidden" name="tingkat_gula" value="{{ $item['tingkat_gula'] }}">
                                <input type="hidden" name="tingkat_es" value="{{ $item['tingkat_es'] }}">
                                <button type="button" class="btn btn-link text-dark p-0 d-flex align-items-center justify-content-center h-100 rounded-0 text-decoration-none" style="width: 32px;" onclick="updateCartQty('{{ $key }}', {{ $item['jumlah'] + 1 }})">
                                    <span class="material-symbols-outlined" style="font-size: 1.1rem;">add</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-secondary py-5 text-oswald text-uppercase fw-bold">Your cart is empty.</div>
        @endforelse
    </section>

       <!-- Summary Section -->
    <section class="d-flex flex-column gap-2 border-top border-dark pt-3 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.85rem;">Subtotal</span>
            <span class="fw-bold" style="font-size: 0.95rem;">IDR {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.85rem;">Tax (10%)</span>
            <span class="fw-bold" style="font-size: 0.95rem;">IDR {{ number_format($tax, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary">
            <span class="text-oswald fw-bold text-uppercase" style="font-size: 1.25rem;">Grand Total</span>
            <span class="text-oswald fw-bold text-primary" style="font-size: 1.5rem;">IDR {{ number_format($grandTotal, 0, ',', '.') }}</span>
        </div>
    </section>

</main>

<!-- Bottom Action Bar -->

<nav class="fixed-bottom fixed-responsive d-flex p-0 border-start border-end border-dark" style="height: 56px; z-index: 1030;">
    <div class="d-flex flex-column justify-content-center px-3 text-white" style="flex: 0.45; background-color: #291714;">
        <span class="text-oswald text-uppercase lh-1 mb-1" style="font-size: 0.65rem; opacity: 0.7; letter-spacing: 1px;">TOTAL</span>
        <span class="text-oswald fw-bold lh-1" style="font-size: 1.1rem;">IDR {{ number_format($grandTotal, 0, ',', '.') }}</span>
    </div>
    <a href="{{ route('pos.payment') }}" class="d-flex align-items-center justify-content-center gap-2 text-decoration-none text-white bg-primary text-oswald fw-bold text-uppercase" style="flex: 0.55; font-size: 0.95rem;">
        PROCEED TO PAYMENT
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">arrow_forward</span>
    </a>
</nav>
@endsection

@push('scripts')
<script>
    function updateCartQty(cartKey, newQuantity) {
        if (newQuantity < 1) return; // Use the remove button to delete

        fetch('{{ route('pos.cart.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                cart_key: cartKey,
                jumlah: newQuantity
            })
        }).then(response => {
            if(response.ok) {
                window.location.reload();
            } else {
                alert('Failed to update quantity');
            }
        });
    }
</script>
@endpush

