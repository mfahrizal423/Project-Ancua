@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Pendapatan -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="bg-white border-start border-4 border-primary p-4 shadow-sm h-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="text-secondary text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Pendapatan</p>
                    <p class="text-oswald fw-bold text-dark mb-0" style="font-size: 1.5rem;">IDR {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1; font-size: 2rem;">payments</span>
            </div>
        </div>
    </div>
    
    <!-- Total Pesanan -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="bg-white border-start border-4 border-dark p-4 shadow-sm h-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="text-secondary text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Pesanan</p>
                    <p class="text-oswald fw-bold text-dark mb-0" style="font-size: 1.5rem;">{{ $totalOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-dark" style="font-variation-settings:'FILL' 1; font-size: 2rem;">receipt_long</span>
            </div>
        </div>
    </div>
    
    <!-- Pesanan Hari Ini -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="bg-white border-start border-4 border-success p-4 shadow-sm h-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="text-secondary text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Pesanan Hari Ini</p>
                    <p class="text-oswald fw-bold text-dark mb-0 lh-1" style="font-size: 1.5rem;">{{ $todayOrders }}</p>
                    <p class="text-secondary mt-1 mb-0" style="font-size: 0.75rem;">IDR {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                </div>
                <span class="material-symbols-outlined text-success" style="font-variation-settings:'FILL' 1; font-size: 2rem;">today</span>
            </div>
        </div>
    </div>
    
    <!-- Total Menu -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="bg-white border-start border-4 border-secondary p-4 shadow-sm h-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="text-secondary text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Menu</p>
                    <p class="text-oswald fw-bold text-dark mb-0" style="font-size: 1.5rem;">{{ $totalMenus }}</p>
                </div>
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1; font-size: 2rem;">restaurant_menu</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row g-4">
    <div class="col-12 col-lg-6">
        <a href="{{ route('admin.menu.create') }}" class="d-flex align-items-center gap-3 bg-primary text-white p-4 text-decoration-none custom-hover-dark h-100 group">
            <span class="material-symbols-outlined transition-transform" style="font-size: 2.5rem;">add_circle</span>
            <div>
                <p class="text-oswald fw-bold text-uppercase mb-1" style="font-size: 1.1rem;">Tambah Menu Baru</p>
                <p class="mb-0 text-white-50" style="font-size: 0.85rem;">Kelola daftar kopi & makanan</p>
            </div>
        </a>
    </div>
  
    <div class="col-12 col-lg-6">
        <a href="{{ route('admin.report') }}" class="d-flex align-items-center gap-3 bg-success text-white p-4 text-decoration-none custom-hover-opacity h-100 group">
            <span class="material-symbols-outlined transition-transform" style="font-size: 2.5rem;">bar_chart</span>
            <div>
                <p class="text-oswald fw-bold text-uppercase mb-1" style="font-size: 1.1rem;">Laporan Penjualan</p>
                <p class="mb-0 text-white-50" style="font-size: 0.85rem;">Analisis omset & produk terlaris</p>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .transition-transform {
        transition: transform 0.2s ease-in-out;
    }
    .group:hover .transition-transform {
        transform: scale(1.1);
    }
    .custom-hover-dark:hover {
        background-color: #8a0005 !important;
    }
    .custom-hover-opacity:hover {
        filter: brightness(110%);
    }
</style>
@endpush

