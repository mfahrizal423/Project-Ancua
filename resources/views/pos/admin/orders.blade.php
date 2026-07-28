@extends('layouts.pos')

@section('title', 'Admin Panel - Kopi Ancua')

@section('content')
<!-- Header -->
<header class="fixed-top w-100 bg-dark text-white border-bottom border-secondary shadow-sm" style="height: 64px; z-index: 1030;">
    <div class="container-fluid px-3 px-lg-5 h-100 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-white p-0 text-decoration-none custom-opacity" title="Ke Dashboard Admin">
                <span class="material-symbols-outlined" style="font-size: 1.5rem;">dashboard</span>
            </a>
            @endif
            
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none custom-opacity d-flex align-items-center justify-content-center" title="Logout">
                    <span class="material-symbols-outlined" style="font-size: 1.5rem;">logout</span>
                </button>
            </form>

            <div class="ms-2 d-none d-sm-block">
                <h1 class="text-oswald fw-bold text-white text-uppercase mb-0 lh-1" style="font-size: 1.1rem; letter-spacing: -0.5px;">KOPI ANCUA HARMONI</h1>
                <span class="text-primary text-uppercase fw-bold" style="font-size: 0.65rem;">KASIR</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-outline-light rounded-pill btn-sm d-flex align-items-center gap-1 px-3 py-1 text-decoration-none custom-hover-opacity shadow-sm" style="font-size: 0.75rem; border-color: rgba(255,255,255,0.2);">
                <span class="material-symbols-outlined" style="font-size: 1.1rem;">add_shopping_cart</span>
                <span class="fw-bold text-uppercase d-none d-sm-inline">Buat Pesanan</span>
            </a>
            <div class="d-flex align-items-center gap-1 border-start border-secondary ps-3 ms-1">
                <span class="bg-success rounded-circle animate-pulse d-inline-block" style="width: 10px; height: 10px;"></span>
                <span class="text-success text-uppercase fw-bold" style="font-size: 0.8rem;">LIVE</span>
            </div>
        </div>
    </div>
</header>

<main class="flex-grow-1 pt-5 pb-5 mb-5 mt-4 w-100">
    <div class="container-fluid px-3 px-lg-5">
        <!-- Stats Row -->
        <div class="row g-0 mb-4 bg-white shadow-sm rounded-4 overflow-hidden border border-light-subtle">
            @php
                $unpaid    = $orders->where('status','unpaid')->count();
                $pending   = $orders->where('status','pending')->count();
                $preparing = $orders->where('status','preparing')->count();
                $completed = $orders->where('status','completed')->count();
            @endphp
            <div class="col-3 d-flex flex-column align-items-center py-3 border-end border-light-subtle">
                <span class="text-oswald fw-bold text-warning" style="font-size: 1.75rem;">{{ $unpaid }}</span>
                <span class="text-secondary text-uppercase fw-bold text-center px-1" style="font-size: 0.65rem;">BELUM BAYAR</span>
            </div>
            <div class="col-3 d-flex flex-column align-items-center py-3 border-end border-light-subtle">
                <span class="text-oswald fw-bold text-primary" style="font-size: 1.75rem;">{{ $pending }}</span>
                <span class="text-secondary text-uppercase fw-bold text-center px-1" style="font-size: 0.70rem;">DITERIMA</span>
            </div>
            <div class="col-3 d-flex flex-column align-items-center py-3 border-end border-light-subtle">
                <span class="text-oswald fw-bold text-dark" style="font-size: 1.75rem;">{{ $preparing }}</span>
                <span class="text-secondary text-uppercase fw-bold text-center px-1" style="font-size: 0.70rem;">DIPROSES</span>
            </div>
            <div class="col-3 d-flex flex-column align-items-center py-3">
                <span class="text-oswald fw-bold text-success" style="font-size: 1.75rem;">{{ $completed }}</span>
                <span class="text-secondary text-uppercase fw-bold text-center px-1" style="font-size: 0.70rem;">SELESAI</span>
            </div>
        </div>

        <!-- Order List -->
        <div class="row g-3 pb-4">
            @forelse($orders as $order)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3" id="order-card-{{ $order->id }}">
                <div class="bg-white rounded-4 shadow-sm border border-light-subtle overflow-hidden h-100 d-flex flex-column">
                    <div class="p-3 flex-grow-1 d-flex flex-column">
                        <!-- Order Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="d-flex align-items-center gap-1 mb-1 text-secondary">
                                    <span class="material-symbols-outlined" style="font-size: 0.9rem;">schedule</span>
                                    <span class="text-uppercase fw-bold" style="font-size: 0.70rem;">{{ $order->created_at->format('H:i') }} • {{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                <h3 class="text-oswald fw-bold text-uppercase mb-1 text-dark" style="font-size: 1.2rem;">{{ $order->nomor_pesanan }}</h3>
                                <div class="d-flex align-items-center gap-1 text-secondary">
                                    <span class="material-symbols-outlined" style="font-size: 1rem;">person</span>
                                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $order->nama_pelanggan ?: 'Tanpa Nama' }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="status-badge-{{ $order->id }} d-inline-block text-uppercase fw-bold px-2 py-1 mb-1 rounded
                                    @switch($order->status)
                                        @case('unpaid') bg-warning-subtle text-warning @break
                                        @case('pending') bg-danger-subtle text-danger @break
                                        @case('preparing') bg-dark text-white @break
                                        @case('completed') bg-success-subtle text-success @break
                                        @default bg-light text-secondary
                                    @endswitch
                                " style="font-size: 0.70rem;">
                                    @switch($order->status)
                                        @case('unpaid') MENUNGGU BAYAR @break
                                        @case('pending') PESANAN DITERIMA @break
                                        @case('preparing') SEDANG DIPROSES @break
                                        @case('completed') PESANAN SELESAI @break
                                        @default {{ strtoupper($order->status) }}
                                    @endswitch
                                </div>
                                <p class="text-oswald fw-bold text-primary mb-0" style="font-size: 1.1rem;">IDR {{ number_format($order->total_keseluruhan, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Items List -->
                        <div class="bg-light rounded-3 p-2 mb-3 flex-grow-1">
                            @foreach($order->detail as $item)
                            <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 0.85rem;">
                                <span><span class="fw-bold text-primary">{{ $item->jumlah }}×</span> <strong class="text-uppercase text-dark">{{ $item->menu->nama ?? 'Item' }}</strong></span>
                                <span class="text-secondary" style="font-size: 0.75rem;">
                                    <span class="bg-white border px-1 rounded">{{ $item->tingkat_gula }} sugar</span>
                                    <span class="bg-white border px-1 rounded">{{ $item->tingkat_es }} ice</span>
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <!-- Payment Info & Receipt -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-secondary text-uppercase fw-bold mb-0 lh-1" style="font-size: 0.65rem;">Metode Pembayaran</p>
                                <p class="text-oswald fw-bold text-uppercase mb-0 mt-1 text-dark" style="font-size: 0.95rem;">{{ $order->metode_pembayaran }}</p>
                            </div>
                            <a href="{{ route('pos.receipt', $order->id) }}" target="_blank" class="btn btn-outline-dark px-3 py-1 text-uppercase fw-bold rounded-pill d-flex align-items-center gap-1 shadow-sm" style="font-size: 0.75rem;">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">print</span> Cetak
                            </a>
                        </div>

                        <!-- Status Action Buttons -->
                        @if(!in_array($order->status, ['completed','cancelled','unpaid']))
                        <div class="d-flex gap-2 border-top pt-3 mt-auto" id="actions-{{ $order->id }}">
                            @if($order->status === 'pending')
                            <button onclick="updateStatus({{ $order->id }}, 'preparing')"
                                class="btn btn-dark text-white rounded-pill flex-grow-1 text-uppercase fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2 custom-btn-dark" style="font-size: 0.85rem;">
                                <span class="material-symbols-outlined" style="font-size: 1.1rem;">coffee</span>
                                MULAI BUAT
                            </button>
                            @endif

                            @if($order->status === 'preparing')
                            <button onclick="updateStatus({{ $order->id }}, 'completed')"
                                class="btn btn-success text-white rounded-pill flex-grow-1 text-uppercase fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2 custom-hover-brightness" style="font-size: 0.85rem;">
                                <span class="material-symbols-outlined" style="font-size: 1.1rem;">done_all</span>
                                PESANAN SELESAI
                            </button>
                            @endif
                        </div>
                        @elseif($order->status === 'unpaid')
                        <div class="border-top pt-3 mt-auto d-flex flex-column gap-2">
                            <div class="d-flex align-items-center gap-2 text-warning" style="font-size: 0.8rem;">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">hourglass_empty</span>
                                <span class="fw-bold text-uppercase">Menunggu Pembayaran Duitku</span>
                            </div>
                            <button onclick="confirmPayment({{ $order->id }})"
                                class="btn btn-warning text-dark rounded-pill flex-grow-1 text-uppercase fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2" style="font-size: 0.80rem;">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">check_circle</span>
                                Konfirmasi Bayar Manual
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 d-flex flex-column align-items-center py-5 mt-5 text-secondary">
                <div class="bg-white rounded-circle p-4 shadow-sm mb-3">
                    <span class="material-symbols-outlined" style="font-size: 3rem;">coffee_maker</span>
                </div>
                <p class="text-uppercase fw-bold text-dark" style="font-size: 0.95rem;">Belum ada pesanan</p>
            </div>
            @endforelse
        </div>
    </div>
</main>

<!-- Bottom Refresh Bar -->
<nav class="fixed-bottom w-100 bg-dark text-white shadow-lg" style="height: 56px; z-index: 1030;">
    <div class="container-fluid px-0 h-100 d-flex">
        <button onclick="window.location.reload()" class="btn btn-link text-white text-decoration-none rounded-0 flex-grow-1 d-flex align-items-center justify-content-center gap-2 text-uppercase fw-bold custom-btn-primary border-0 h-100" style="font-size: 0.85rem;">
            <span class="material-symbols-outlined" style="font-size: 1.1rem;">refresh</span>
            REFRESH ORDERS
        </button>
        <div style="width: 1px; background-color: #495057;"></div>
        <div class="d-flex flex-column align-items-center justify-content-center px-4 h-100" style="min-width: 120px;">
            <span class="text-uppercase fw-bold text-white-50 lh-1 mb-1" style="font-size: 0.65rem;">AUTO REFRESH</span>
            <span class="text-success text-uppercase fw-bold lh-1" id="countdown" style="font-size: 0.85rem;">30s</span>
        </div>
    </div>
</nav>
@endsection

@push('scripts')
<style>
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }
    .custom-opacity {
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .custom-opacity:hover {
        opacity: 1;
    }
    .custom-btn-dark:hover {
        background-color: #a80006 !important;
        border-color: #a80006 !important;
        color: white !important;
    }
    .custom-btn-primary:hover {
        background-color: #a80006 !important;
    }
    .custom-hover-brightness:hover {
        filter: brightness(110%);
    }
</style>
<script>
    const CSRF = '{{ csrf_token() }}';

    // Update status via AJAX
    async function updateStatus(orderId, newStatus) {
        const labels = { pending: 'PESANAN DITERIMA', preparing: 'SEDANG DIPROSES', completed: 'PESANAN SELESAI' };
        const colors = {
            pending: 'bg-danger-subtle text-danger',
            preparing: 'bg-dark text-white',
            completed: 'bg-success-subtle text-success'
        };

        try {
            const res = await fetch(`/kasir/order/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ status: newStatus })
            });

            const data = await res.json();
            if (data.success) {
                // Update badge visual
                const badge = document.querySelector(`.status-badge-${orderId}`);
                if (badge) {
                    badge.className = `status-badge-${orderId} d-inline-block text-uppercase fw-bold px-2 py-1 mb-1 rounded ${colors[newStatus] || ''}`;
                    badge.innerText = labels[newStatus] || newStatus.toUpperCase();
                }
                
                // Update action buttons
                const actions = document.getElementById(`actions-${orderId}`);
                if (actions) {
                    if (newStatus === 'preparing') {
                        actions.innerHTML = `
                            <button onclick="updateStatus(${orderId}, 'completed')"
                                class="btn btn-success text-white rounded-pill flex-grow-1 text-uppercase fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2 custom-hover-brightness" style="font-size: 0.85rem;">
                                <span class="material-symbols-outlined" style="font-size: 1.1rem;">done_all</span>
                                PESANAN SELESAI
                            </button>`;
                    } else if (newStatus === 'completed') {
                        actions.remove();
                        showToast(`✅ Pesanan Selesai!`);
                    }
                }
            }
        } catch (err) {
            showToast('❌ Gagal update status');
        }
    }

    // Konfirmasi pembayaran manual (untuk admin, saat testing lokal)
    async function confirmPayment(orderId) {
        if (!confirm('Konfirmasi pembayaran ini secara manual?')) return;
        try {
            const res = await fetch(`/kasir/order/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ status: 'pending' })
            });
            const data = await res.json();
            if (data.success) {
                showToast('✅ Pembayaran dikonfirmasi! Status → PESANAN DITERIMA');
                setTimeout(() => window.location.reload(), 1500);
            }
        } catch (err) {
            showToast('❌ Gagal konfirmasi');
        }
    }

    // Toast notification
    function showToast(msg) {
        const toast = document.createElement('div');
        toast.className = 'position-fixed start-50 translate-middle-x z-3 bg-dark text-white px-4 py-2 text-uppercase fw-bold shadow-lg custom-bounce rounded-pill';
        toast.style.top = '5rem';
        toast.style.zIndex = '1050';
        toast.innerText = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Auto refresh countdown
    let sec = 30;
    const countdownEl = document.getElementById('countdown');
    setInterval(() => {
        sec--;
        if (countdownEl) countdownEl.innerText = sec + 's';
        if (sec <= 0) window.location.reload();
    }, 1000);
</script>
<style>
    @keyframes bounce {
        0%, 100% {
            transform: translateY(-25%) translateX(-50%);
            animation-timing-function: cubic-bezier(0.8,0,1,1);
        }
        50% {
            transform: translateY(0) translateX(-50%);
            animation-timing-function: cubic-bezier(0,0,0.2,1);
        }
    }
    .custom-bounce {
        animation: bounce 1s infinite;
    }
</style>
@endpush
