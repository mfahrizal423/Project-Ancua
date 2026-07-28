@extends('layouts.pos')

@section('title', 'Order Status | KOPI ANCUA HARMONI')

@section('content')
<!-- TopAppBar -->
<header class="fixed-top fixed-responsive d-flex justify-content-between align-items-center px-3 bg-white border-bottom border-start border-end border-dark" style="height: 64px; z-index: 1030;">
    <div class="d-flex align-items-center">
        <a href="{{ route('home') }}" class="btn btn-link text-primary p-0 text-decoration-none">
            <span class="material-symbols-outlined" style="font-size: 1.5rem;">menu</span>
        </a>
    </div>
    <h1 class="text-oswald fw-bold text-primary text-uppercase mb-0" style="font-size: 1.25rem; letter-spacing: -0.5px;">ORDER STATUS</h1>
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

<!-- Notification Permission Banner -->
<div id="notif-banner" class="d-flex align-items-center gap-2 px-3 py-2 text-white text-center justify-content-center" style="margin-top: 64px; background-color: #291714; cursor: pointer;" onclick="requestNotifPermission()">
    <span class="material-symbols-outlined" style="font-size: 1.1rem;">notifications</span>
    <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.85rem;">AKTIFKAN NOTIFIKASI HP — TAP DISINI</span>
</div>

<main class="flex-grow-1 pb-5 mb-5" style="min-height: calc(100vh - 120px);">
    <!-- Hero Status Section -->
    <section id="hero-section" class="bg-primary text-white px-3 py-5 d-flex flex-column align-items-center text-center">
        <p class="text-oswald text-uppercase fw-bold mb-2" style="font-size: 0.85rem; opacity: 0.8;">ORDER {{ $order->nomor_pesanan }}</p>
        <h2 id="status-label" class="text-oswald fw-bold text-uppercase mb-3 lh-1" style="font-size: 2rem; letter-spacing: -1px;">
            @if($order->status === 'ready') KOPI KAMU SIAP! ☕
            @elseif($order->status === 'completed') ORDER COMPLETE
            @elseif($order->status === 'preparing') SEDANG DIPROSES
            @else MENUNGGU KONFIRMASI
            @endif
        </h2>
        <div class="bg-white mb-4" style="width: 64px; height: 4px;"></div>
        <div class="d-flex flex-column align-items-center">
            <span class="text-uppercase fw-bold" style="font-size: 0.75rem; opacity: 0.9;">ESTIMATED COMPLETION</span>
            <span class="text-oswald fw-bold" style="font-size: 1.75rem;">
                @if($order->status === 'completed') DONE ✓
                @else 10-15 MINS
                @endif
            </span>
        </div>
    </section>

    <!-- Progress Tracker -->
    <section class="px-3 py-5">
        <div class="d-flex justify-content-between align-items-start">
            <!-- Step 1: Done (Diterima) -->
            <div class="d-flex flex-column align-items-center w-100" style="flex: 1;">
                <div class="w-100 bg-primary mb-2" style="height: 8px;"></div>
                <span class="text-oswald fw-bold text-primary text-center" style="font-size: 0.75rem;">DITERIMA</span>
                <span class="material-symbols-outlined text-primary mt-1" style="font-variation-settings: 'FILL' 1; font-size: 1.25rem;">check_circle</span>
            </div>
            <!-- Step 2: Active/Done (Diproses) -->
            <div class="d-flex flex-column align-items-center w-100 px-1" style="flex: 1;">
                <div class="position-relative w-100 mb-2 {{ $order->status === 'completed' ? 'bg-primary' : 'bg-light border border-dark' }}" style="height: 8px;">
                    @if($order->status !== 'completed')
                    <div class="position-absolute top-0 start-0 h-100 bg-primary w-50"></div>
                    @endif
                </div>
                <span class="text-oswald fw-bold text-dark text-center" style="font-size: 0.75rem;">DIPROSES</span>
                @if($order->status === 'completed')
                    <span class="material-symbols-outlined text-primary mt-1" style="font-variation-settings: 'FILL' 1; font-size: 1.25rem;">check_circle</span>
                @else
                    <div class="bg-primary mt-3 animate-pulse" style="width: 12px; height: 12px;"></div>
                @endif
            </div>
            <!-- Step 3: Done or Pending (Selesai) -->
            <div class="d-flex flex-column align-items-center w-100" style="flex: 1;">
                <div class="w-100 mb-2 {{ $order->status === 'completed' ? 'bg-primary' : 'bg-light border border-dark' }}" style="height: 8px;"></div>
                <span class="text-oswald fw-bold text-center {{ $order->status === 'completed' ? 'text-primary' : 'text-secondary' }}" style="font-size: 0.75rem;">SELESAI</span>
                @if($order->status === 'completed')
                    <span class="material-symbols-outlined text-primary mt-1" style="font-variation-settings: 'FILL' 1; font-size: 1.25rem;">check_circle</span>
                @endif
            </div>
        </div>
    </section>

    <!-- Barista Illustration -->
    <section class="px-3 pb-5">
        <div class="border border-dark bg-light position-relative overflow-hidden group" style="aspect-ratio: 16/9;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCQKTlZyY2Z6enCeID11KIaL27HXChv_qYDDPPuD9tE_iAjMw5nVEST855K-cM6IoMmfEgCqAvgASCGJsbCBGg7TwK7PTi4RnSEAGj1ZHN41Makc-aNUrBG05NMYUQsxrCeXK9zr6uKQGqKZoBV7MC0OUQevcuWSRXjX6XySaTHL2-uZqHSXEGV_p6XBbHtThS0RQdbod3oz7fTbO67QQXBoH4VaKLp9lDtKkJTNg0OCiIarxd8TLSA'); background-size: cover; background-position: center; transition: transform 0.7s;">
            </div>
            <div class="position-absolute top-0 end-0 bg-primary text-white px-3 py-1 mt-3 me-3 text-oswald fw-bold" style="font-size: 0.85rem;">
                LIVE TRACKING
            </div>
        </div>
    </section>

    <!-- Order Summary & Action -->
    <section class="px-3 d-flex flex-column gap-4 mb-4">
        <div class="border-top border-bottom border-dark py-3 d-flex justify-content-between align-items-center">
            <div>
                <p class="text-secondary text-oswald fw-bold mb-0" style="font-size: 0.75rem;">ITEMS</p>
                <p class="text-oswald fw-bold text-uppercase mb-1" style="font-size: 1.1rem;">{{ $order->detail->sum('quantity') }} ITEMS TOTAL</p>
                <p class="text-secondary mb-0" style="font-size: 0.85rem;">
                    {{ $order->detail->map(fn($i) => $i->jumlah . 'x ' . ($i->menu->nama ?? 'Item'))->join(', ') }}
                </p>
            </div>
            <div class="text-end">
                <p class="text-secondary text-oswald fw-bold mb-0" style="font-size: 0.75rem;">TOTAL</p>
                <p class="text-oswald fw-bold mb-0" style="font-size: 1.1rem;">IDR {{ number_format($order->total_keseluruhan, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">
            <a href="{{ route('pos.receipt', $order->id) }}" class="btn text-white w-100 rounded-0 text-oswald fw-bold py-3 d-flex justify-content-center align-items-center gap-2" style="background-color: #291714; font-size: 1rem;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">receipt</span>
                VIEW RECEIPT
            </a>
            <button class="btn btn-outline-dark w-100 rounded-0 text-oswald fw-bold py-3 d-flex justify-content-center align-items-center gap-2 border-2" style="font-size: 1rem;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">support_agent</span>
                CONTACT SHOP
            </button>
        </div>
    </section>
</main>


@endsection

@push('scripts')
<style>
    .animate-pulse {
        animation: pulse 1s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.4; }
        100% { opacity: 1; }
    }
</style>
<script>
    // =============================================
    // BROWSER NOTIFICATION + POLLING SYSTEM
    // =============================================

    const ORDER_ID     = {{ $order->id }};
    const ORDER_NUMBER = '{{ $order->nomor_pesanan }}';
    const CHECK_URL    = '{{ route('pos.order-status.check', $order->id) }}';
    let   lastStatus   = '{{ $order->status }}';
    let   notifGranted = false;
    let   pollingTimer = null;

    // Step 1: Minta izin notifikasi dari browser
    function requestNotifPermission() {
        if (!('Notification' in window)) {
            console.log('Browser tidak mendukung notifikasi');
            return;
        }

        if (Notification.permission === 'granted') {
            notifGranted = true;
            startPolling();
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                notifGranted = permission === 'granted';
                updateNotifBanner(permission);
                startPolling();
            });
        } else {
            // Sudah ditolak
            updateNotifBanner('denied');
            startPolling(); // tetap poll walaupun tanpa notif
        }
    }

    // Step 2: Update banner status notifikasi
    function updateNotifBanner(permission) {
        const banner = document.getElementById('notif-banner');
        if (!banner) return;
        if (permission === 'granted') {
            banner.innerHTML = `<span class="material-symbols-outlined me-2" style="font-size: 1.1rem; font-variation-settings:'FILL' 1">notifications_active</span> <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.85rem;">Notifikasi HP aktif</span>`;
            banner.className = 'd-flex align-items-center px-3 py-2 text-white text-center justify-content-center bg-success';
        } else {
            banner.innerHTML = `<span class="material-symbols-outlined me-2" style="font-size: 1.1rem;">notifications_off</span> <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.85rem;">Notifikasi dimatikan</span>`;
            banner.className = 'd-flex align-items-center px-3 py-2 text-white text-center justify-content-center bg-secondary';
        }
    }

    // Step 3: Kirim notifikasi ke browser/HP
    function sendNotification(title, body, icon) {
        if (!notifGranted) return;
        const notif = new Notification(title, {
            body: body,
            icon: icon || '/favicon.ico',
            badge: '/favicon.ico',
            tag: 'kopi-ancua-order-' + ORDER_ID,
            requireInteraction: true // notif tetap tampil sampai user tutup
        });
        notif.onclick = () => {
            window.focus();
            notif.close();
        };
    }

    // Step 4: Update tampilan progress bar di halaman
    function updateStatusUI(newStatus) {
        const steps = {
            pending:   { step: 0, label: 'MENUNGGU KONFIRMASI' },
            preparing: { step: 1, label: 'SEDANG DIPROSES' },
            ready:     { step: 2, label: 'KOPI KAMU SIAP! ☕' },
            completed: { step: 2, label: 'ORDER COMPLETE' },
        };

        const current = steps[newStatus] || steps['pending'];
        
        // Update hero label
        const heroLabel = document.getElementById('status-label');
        if (heroLabel) heroLabel.innerText = current.label;

        // Update hero color kalau ready
        const heroSection = document.getElementById('hero-section');
        if (heroSection && newStatus === 'ready') {
            heroSection.classList.remove('bg-primary');
            heroSection.classList.add('bg-success');
        }

        // Tampilkan popup "PESANAN SIAP" jika status = ready
        if (newStatus === 'ready') {
            showReadyPopup();
            stopPolling();
        } else {
            window.location.reload(); // Reload to update progress bar easily for now
        }
    }

    // Step 5: Popup modal "Pesanan Siap"
    function showReadyPopup() {
        const existing = document.getElementById('ready-popup');
        if (existing) return; // sudah tampil

        const popup = document.createElement('div');
        popup.id = 'ready-popup';
        popup.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center px-3';
        popup.style.zIndex = '2000';
        popup.style.backgroundColor = 'rgba(0,0,0,0.7)';
        popup.innerHTML = `
            <div class="bg-white w-100 border border-2 border-dark" style="max-width: 320px;">
                <div class="bg-success p-3 text-center">
                    <h2 class="text-oswald text-white fw-bold text-uppercase mb-0" style="letter-spacing: 1px; font-size: 1.5rem;">KOPI SIAP! ☕</h2>
                </div>
                <div class="p-4 text-center d-flex flex-column gap-3">
                    <div class="d-flex justify-content-center">
                        <div class="bg-success d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1; font-size: 3rem;">celebration</span>
                        </div>
                    </div>
                    <p class="text-oswald fw-bold text-uppercase mb-0" style="font-size: 1.5rem; line-height: 1.2;">Pesananmu Sudah Siap!</p>
                    <p class="text-secondary mb-0" style="font-size: 0.9rem;">Silakan ambil di counter dan tunjukkan ID pesanan <span class="fw-bold text-dark">${ORDER_NUMBER}</span></p>
                    <div class="pt-3">
                        <button onclick="document.getElementById('ready-popup').remove()"
                            class="btn w-100 text-white rounded-0 text-oswald fw-bold text-uppercase py-3" style="background-color: #291714; font-size: 1.1rem;">
                            AMBIL SEKARANG
                        </button>
                    </div>
                </div>
            </div>`;
        document.body.appendChild(popup);

        // Animasi icon
        const icon = popup.querySelector('.material-symbols-outlined');
        icon.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        icon.style.transform = 'scale(0.5)';
        setTimeout(() => { icon.style.transform = 'scale(1)'; }, 200);
    }

    // Step 6: Polling ke server setiap 10 detik
    function startPolling() {
        pollingTimer = setInterval(async () => {
            try {
                const res  = await fetch(CHECK_URL);
                const data = await res.json();
                
                if (data.status && data.status !== lastStatus) {
                    const oldStatus = lastStatus;
                    lastStatus = data.status;

                    // Update UI
                    updateStatusUI(data.status);

                    // Kirim notifikasi push ke browser/HP
                    if (data.status === 'ready') {
                        sendNotification(
                            '☕ Kopi Ancua — Pesanan Siap!',
                            `Order ${ORDER_NUMBER} sudah siap diambil di counter.`,
                        );
                    } else if (data.status === 'preparing') {
                        sendNotification(
                            '🔥 Kopi Ancua — Sedang Diproses',
                            `Barista sedang membuat pesanan ${ORDER_NUMBER} kamu!`,
                        );
                    }
                }
            } catch (err) {
                console.log('Polling error:', err);
            }
        }, 10000); // cek setiap 10 detik
    }

    function stopPolling() {
        if (pollingTimer) clearInterval(pollingTimer);
    }

    // Jalankan saat halaman dibuka
    document.addEventListener('DOMContentLoaded', () => {
        requestNotifPermission();

        // Jika status sudah ready saat halaman dibuka
        if (lastStatus === 'ready') {
            showReadyPopup();
        }
    });
</script>
@endpush

