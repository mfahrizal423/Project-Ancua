@extends('layouts.pos')

@section('title', 'KOPI ANCUA - Product Detail')

@section('content')
<!-- Top AppBar (Sticky) -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <a href="{{ route('home') }}" aria-label="Go Back" class="btn btn-link text-dark p-0 text-decoration-none">
        <span class="material-symbols-outlined" style="font-size: 1.5rem;">arrow_back</span>
    </a>
    <h1 class="text-oswald fw-bold text-dark text-uppercase mb-0 ms-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">
        KOPI ANCUA HARMONI
    </h1>
    <div class="d-flex align-items-center gap-2">
        
        <a href="{{ route('pos.cart') }}" aria-label="Cart" class="btn btn-link text-primary p-0 position-relative text-decoration-none">
            <span class="material-symbols-outlined" style="font-size: 1.5rem;">shopping_cart</span>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    {{ array_sum(array_column(session('cart'), 'jumlah')) }}
                </span>
            @endif
        </a>
    </div>
</header>

<main class="flex-grow-1 pt-5 pb-5 mb-5 mt-4">
    <div class="d-md-flex">

        <!-- LEFT: Product Image (desktop sticky, mobile full width) -->
        <div class="d-md-none">
            {{-- Mobile: gambar penuh lebar, tinggi 320px --}}
            <section class="w-100 bg-light border-bottom border-dark overflow-hidden" style="height: 320px;">
                <img alt="{{ $menu->nama }}" class="w-100 h-100 object-fit-cover" src="{{ $menu->gambar }}">
            </section>
        </div>
        <div class="d-none d-md-block position-sticky border-end border-dark bg-light" style="top: 64px; width: 50%; height: calc(100vh - 64px);">
            {{-- Desktop: gambar sticky di kiri, tinggi penuh viewport --}}
            <img alt="{{ $menu->nama }}" class="w-100 h-100 object-fit-cover" src="{{ $menu->gambar }}">
        </div>

        <!-- RIGHT: Form & Details -->
        <div class="flex-grow-1">
            <!-- Form to add to cart -->
            <form action="{{ route('pos.cart.add') }}" method="POST" id="add-to-cart-form">
                @csrf
                <input type="hidden" name="id_menu" value="{{ $menu->id }}">

                <!-- Product Content -->
                <div class="px-3 py-4 d-flex flex-column gap-4">
                    <!-- Header Info -->
                    <div class="d-flex flex-column gap-1">
                        <h2 class="text-oswald fw-bold text-uppercase text-dark mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">
                            {{ $menu->nama }}
                        </h2>
                        <div class="text-oswald fw-bold text-primary" style="font-size: 1.25rem;">
                            IDR {{ number_format($menu->harga, 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-secondary mb-0" style="font-size: 0.95rem; line-height: 1.5;">
                        {{ $menu->deskripsi }}
                    </p>

                    <hr class="border-dark my-0">

                    <!-- Customization: Sugar Level -->
                    <div class="d-flex flex-column gap-2">
                        <label class="text-oswald fw-bold text-uppercase text-secondary" style="font-size: 0.85rem;">Sugar Level</label>
                        <input type="hidden" name="tingkat_gula" id="sugar-level-input" value="Normal">
                        <div class="d-flex flex-wrap gap-2 customization-group">
                            <button type="button" class="btn btn-outline-dark rounded-0 text-oswald fw-bold text-uppercase px-3 py-2 custom-btn" onclick="setSugar('Less')">
                                Less
                            </button>
                            <button type="button" class="btn btn-dark rounded-0 text-oswald fw-bold text-uppercase px-3 py-2 custom-btn active-custom-btn" onclick="setSugar('Normal')">
                                Normal
                            </button>
                            <button type="button" class="btn btn-outline-dark rounded-0 text-oswald fw-bold text-uppercase px-3 py-2 custom-btn" onclick="setSugar('Extra')">
                                Extra
                            </button>
                        </div>
                    </div>

                    <!-- Customization: Ice Level -->
                    <div class="d-flex flex-column gap-2">
                        <label class="text-oswald fw-bold text-uppercase text-secondary" style="font-size: 0.85rem;">Ice Level</label>
                        <input type="hidden" name="tingkat_es" id="ice-level-input" value="Normal">
                        <div class="d-flex flex-wrap gap-2 customization-group">
                            <button type="button" class="btn btn-outline-dark rounded-0 text-oswald fw-bold text-uppercase px-3 py-2 custom-btn" onclick="setIce('No Ice')">
                                No Ice
                            </button>
                            <button type="button" class="btn btn-outline-dark rounded-0 text-oswald fw-bold text-uppercase px-3 py-2 custom-btn" onclick="setIce('Less')">
                                Less
                            </button>
                            <button type="button" class="btn btn-dark rounded-0 text-oswald fw-bold text-uppercase px-3 py-2 custom-btn active-custom-btn" onclick="setIce('Normal')">
                                Normal
                            </button>
                        </div>
                    </div>

                    <hr class="border-dark my-0">

                    <!-- Quantity Counter -->
                    <div class="d-flex align-items-center justify-content-between pb-5 mb-5">
                        <span class="text-oswald fw-bold text-uppercase text-secondary" style="font-size: 0.85rem;">Quantity</span>
                        <input type="hidden" name="jumlah" id="quantity-input" value="1">
                        <div class="d-flex align-items-center border border-dark" style="height: 48px;">
                            <button type="button" class="btn btn-link text-dark p-0 d-flex align-items-center justify-content-center h-100 rounded-0 text-decoration-none" style="width: 48px;" onclick="decrement()">
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                            <div class="d-flex align-items-center justify-content-center border-start border-end border-dark text-oswald fw-bold h-100" style="width: 48px; font-size: 1.25rem;" id="quantity">
                                1
                            </div>
                            <button type="button" class="btn btn-link text-dark p-0 d-flex align-items-center justify-content-center h-100 rounded-0 text-decoration-none" style="width: 48px;" onclick="increment()">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                    </div>
                </div>

    <!-- Bottom Action Bar (Sticky) -->
    <div class="fixed-bottom fixed-responsive d-flex p-0 border-start border-end border-dark" style="height: 64px; z-index: 1030;">
        <div class="d-flex flex-column justify-content-center px-3 text-white" style="flex: 0.45; background-color: #291714;">
            <span class="text-oswald text-uppercase lh-1 mb-1" style="font-size: 0.65rem; opacity: 0.7; letter-spacing: 1px;">TOTAL</span>
            <div class="text-oswald fw-bold lh-1" style="font-size: 1.1rem;" id="total-price">IDR {{ number_format($menu->harga, 0, ',', '.') }}</div>
        </div>
        <button type="submit" class="d-flex align-items-center justify-content-center gap-2 text-decoration-none text-white bg-primary text-oswald fw-bold text-uppercase border-0 rounded-0 m-0 p-0" style="flex: 0.55; font-size: 0.95rem;">
            ADD TO CART
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 1.1rem;">shopping_cart</span>
        </button>
    </div>
</form>

@endsection

@push('scripts')
<style>
    .custom-btn {
        background-color: transparent;
        color: #291714;
    }
    .custom-btn:hover {
        background-color: #291714;
        color: white;
    }
    .active-custom-btn {
        background-color: #291714 !important;
        color: white !important;
    }
</style>
<script>
    let quantity = 1;
    const unitPrice = {{ $menu->harga }};
    const quantityEl = document.getElementById('quantity');
    const totalEl = document.getElementById('total-price');

    function updateDisplay() {
        quantityEl.innerText = quantity;
        document.getElementById('quantity-input').value = quantity;
        const total = quantity * unitPrice;
        totalEl.innerText = `IDR ${total.toLocaleString('id-ID')}`;
    }

    function increment() {
        quantity++;
        updateDisplay();
    }

    function decrement() {
        if (quantity > 1) {
            quantity--;
            updateDisplay();
        }
    }

    function setSugar(level) {
        document.getElementById('sugar-level-input').value = level;
    }

    function setIce(level) {
        document.getElementById('ice-level-input').value = level;
    }

    // Micro-interaction for chip selection
    document.querySelectorAll('.customization-group button').forEach(btn => {
        btn.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.querySelectorAll('button').forEach(b => {
                b.classList.remove('active-custom-btn', 'btn-dark');
                b.classList.add('btn-outline-dark');
            });
            this.classList.remove('btn-outline-dark');
            this.classList.add('active-custom-btn', 'btn-dark');
        });
    });
</script>
@endpush

