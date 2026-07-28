@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('top-action')
    <!-- Period Selector -->
    <form action="{{ route('admin.report') }}" method="GET" class="d-flex gap-2 m-0">
        <select name="period" onchange="this.form.submit()" class="form-select rounded-0 border-dark shadow-none text-oswald text-uppercase fw-bold" style="font-size: 0.85rem; width: auto; cursor: pointer;">
            <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
        </select>
        <button type="button" onclick="window.print()" class="btn btn-dark rounded-0 d-flex align-items-center justify-content-center px-3 border-0 custom-btn-dark">
            <span class="material-symbols-outlined" style="font-size: 1.1rem;">print</span>
        </button>
    </form>
@endsection

@section('content')
<div class="print-container">
    <!-- Header Print Only -->
    <div class="d-none d-print-block text-center mb-4 border-bottom border-dark border-2 pb-3">
        <h1 class="text-oswald fw-bold text-uppercase mb-1" style="font-size: 1.5rem; letter-spacing: -0.5px;">KOPI ANCUA HARMONI</h1>
        <p class="text-oswald text-uppercase fw-bold mb-1" style="font-size: 1rem;">Laporan Penjualan ({{ strtoupper($period) }})</p>
        <p class="mb-0" style="font-size: 0.75rem;">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
    </div>

    <!-- SUMMARY METRICS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-6">
            <div class="bg-white border-top border-4 border-primary p-3 border shadow-sm h-100">
                <p class="text-secondary text-uppercase fw-bold mb-1 lh-1" style="font-size: 0.65rem; letter-spacing: 1px;">Total Omset</p>
                <p class="text-oswald fw-bold text-dark mb-0" style="font-size: 1.25rem;">IDR {{ number_format($summary->total_revenue ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="bg-white border-top border-4 border-dark p-3 border shadow-sm h-100">
                <p class="text-secondary text-uppercase fw-bold mb-1 lh-1" style="font-size: 0.65rem; letter-spacing: 1px;">Pesanan Selesai</p>
                <p class="text-oswald fw-bold text-dark mb-0" style="font-size: 1.25rem;">{{ number_format($summary->total_orders ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- TOP PRODUCTS -->
        <div class="col-12 col-lg-8">
            <div class="bg-white border border-dark d-flex flex-column h-100">
                <div class="bg-dark text-white p-3">
                    <h3 class="text-oswald fw-bold text-uppercase mb-0 d-flex align-items-center gap-2" style="font-size: 0.95rem; letter-spacing: 1px;">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">local_fire_department</span>
                        Produk Terlaris
                    </h3>
                </div>
                <div class="p-3 flex-grow-1">
                    @forelse($topProducts as $idx => $prod)
                    <div class="d-flex align-items-center gap-3 mb-3 last-mb-0">
                        <span class="text-oswald fw-bold text-secondary text-center" style="font-size: 1.25rem; width: 1.5rem;">{{ $idx + 1 }}</span>
                        <div class="bg-light border border-secondary flex-shrink-0" style="width: 48px; height: 48px;">
                            @if($prod->gambar) <img src="{{ $prod->gambar }}" class="w-100 h-100 object-fit-cover"> @endif
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-oswald fw-bold text-uppercase mb-0" style="font-size: 0.95rem;">{{ $prod->nama }}</p>
                            <p class="text-secondary mb-0" style="font-size: 0.75rem;">{{ $prod->total_qty }} Terjual</p>
                        </div>
                        <p class="text-oswald fw-bold text-primary mb-0" style="font-size: 0.95rem;">IDR {{ number_format($prod->total_revenue, 0, ',', '.') }}</p>
                    </div>
                    @empty
                    <p class="text-secondary text-center py-4 mb-0" style="font-size: 0.85rem;">Belum ada data penjualan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- PAYMENT METHODS -->
        <div class="col-12 col-lg-4">
            <div class="bg-white border border-dark d-flex flex-column h-100">
                <div class="bg-dark text-white p-3">
                    <h3 class="text-oswald fw-bold text-uppercase mb-0 d-flex align-items-center gap-2" style="font-size: 0.95rem; letter-spacing: 1px;">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">account_balance_wallet</span>
                        Metode Pembayaran
                    </h3>
                </div>
                <div class="p-3 flex-grow-1 d-flex flex-column justify-content-center gap-3">
                    @forelse($paymentMethods as $pay)
                    <div>
                        <div class="d-flex justify-content-between text-oswald fw-bold text-uppercase mb-1" style="font-size: 0.85rem;">
                            <span>{{ $pay->metode_pembayaran }}</span>
                            <span>{{ $pay->count }}x</span>
                        </div>
                        <div class="w-100 bg-light position-relative" style="height: 8px;">
                            <!-- Hitung persentase sederhana untuk bar -->
                            @php $pct = $summary->total_orders > 0 ? ($pay->count / $summary->total_orders) * 100 : 0; @endphp
                            <div class="bg-primary h-100 position-absolute start-0 top-0" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="text-secondary text-end mt-1 mb-0" style="font-size: 0.75rem;">IDR {{ number_format($pay->total, 0, ',', '.') }}</p>
                    </div>
                    @empty
                    <p class="text-secondary text-center mb-0" style="font-size: 0.85rem;">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT TRANSACTIONS (LATEST 10) -->
    <div class="bg-white border border-dark">
        <div class="bg-dark text-white p-3 d-flex justify-content-between align-items-center">
            <h3 class="text-oswald fw-bold text-uppercase mb-0 d-flex align-items-center gap-2" style="font-size: 0.95rem; letter-spacing: 1px;">
                <span class="material-symbols-outlined" style="font-size: 1.1rem;">receipt_long</span>
                10 Transaksi Terakhir Selesai
            </h3>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0">
                <thead class="bg-light border-bottom border-dark">
                    <tr>
                        <th class="px-3 py-2 text-start text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Waktu</th>
                        <th class="px-3 py-2 text-start text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Order ID</th>
                        <th class="px-3 py-2 text-start text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Metode</th>
                        <th class="px-3 py-2 text-end text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($recentOrders->where('status','completed')->take(10) as $ord)
                    <tr class="border-bottom border-secondary-subtle">
                        <td class="px-3 py-2 text-secondary" style="font-size: 0.85rem; vertical-align: middle;">{{ $ord->created_at->format('d M H:i') }}</td>
                        <td class="px-3 py-2 text-oswald fw-bold text-uppercase" style="font-size: 0.85rem; vertical-align: middle;">{{ $ord->nomor_pesanan }}</td>
                        <td class="px-3 py-2 text-uppercase" style="font-size: 0.75rem; vertical-align: middle;"><span class="bg-light border px-2 py-1">{{ $ord->metode_pembayaran }}</span></td>
                        <td class="px-3 py-2 text-end text-oswald fw-bold text-primary" style="font-size: 0.85rem; vertical-align: middle;">IDR {{ number_format($ord->total_keseluruhan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-3 py-4 text-center text-secondary" style="font-size: 0.85rem;">Tidak ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .last-mb-0:last-child {
        margin-bottom: 0 !important;
    }
    .custom-btn-dark:hover {
        background-color: #a80006 !important;
    }
    @media print {
        body { background-color: #fff !important; }
        aside, .navbar, form, button, select { display: none !important; }
        main { margin-left: 0 !important; padding: 0 !important; }
        .print-container { padding: 20px; max-width: 100%; }
        .border-dark { border-color: #000 !important; }
        .bg-dark { background-color: #000 !important; -webkit-print-color-adjust: exact; color: #fff !important; }
        .text-white { color: #fff !important; -webkit-print-color-adjust: exact; }
        .bg-primary { background-color: #a80006 !important; -webkit-print-color-adjust: exact; }
        .bg-light { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }
        .shadow-sm { box-shadow: none !important; }
    }
</style>
@endpush

