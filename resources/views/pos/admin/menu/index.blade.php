@extends('layouts.admin')
@section('title', 'Daftar Menu')
@section('page-title', 'Kelola Menu')

@section('top-action')
    <a href="{{ route('admin.menu.create') }}" class="btn btn-dark rounded-0 d-flex align-items-center gap-2 text-uppercase fw-bold px-3 py-2 border-0 custom-btn-dark" style="font-size: 0.85rem; letter-spacing: 1px;">
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">add</span>
        Tambah Menu
    </a>
@endsection

@section('content')
<!-- Filter/Search Bar -->
<div class="bg-white border border-dark p-3 mb-4 d-flex gap-3 align-items-center">
    <span class="material-symbols-outlined text-secondary" style="font-size: 1.25rem;">search</span>
    <input type="text" id="search-input" placeholder="Cari nama menu..." 
        class="form-control border-0 rounded-0 shadow-none text-uppercase fw-bold p-0 flex-grow-1" style="font-size: 0.85rem; letter-spacing: 0.5px; background: transparent;">
    <span class="text-oswald fw-bold text-secondary text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">{{ $menus->total() }} Menu</span>
</div>

<!-- Menu Table -->
<div class="bg-white border border-dark overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-borderless mb-0" id="menu-table">
            <thead class="bg-dark text-white border-bottom border-dark">
                <tr>
                    <th class="px-4 py-3 text-start text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Menu</th>
                    <th class="px-4 py-3 text-start text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Kategori</th>
                    <th class="px-4 py-3 text-end text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Harga</th>
                    <th class="px-4 py-3 text-center text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                    <th class="px-4 py-3 text-center text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                @forelse($menus as $menu)
                <tr class="border-bottom border-secondary-subtle align-middle menu-row">
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light border border-secondary flex-shrink-0 d-flex align-items-center justify-content-center overflow-hidden" style="width: 48px; height: 48px;">
                                @if($menu->gambar)
                                    <img src="{{ $menu->gambar }}" alt="{{ $menu->nama }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <span class="material-symbols-outlined text-secondary" style="font-size: 1.25rem;">coffee</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-oswald fw-bold text-uppercase mb-0 menu-name" style="font-size: 0.85rem;">{{ $menu->nama }}</p>
                                <p class="text-secondary mb-0 text-truncate" style="font-size: 0.75rem; max-width: 200px;">{{ $menu->deskripsi }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-light text-secondary fw-bold text-uppercase px-2 py-1 border border-secondary" style="font-size: 0.75rem;">
                            {{ $menu->kategori->nama ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-end">
                        <span class="text-oswald fw-bold" style="font-size: 0.85rem;">IDR {{ number_format($menu->harga, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($menu->tersedia)
                            <span class="badge bg-success rounded-0 text-uppercase fw-bold py-1 px-2">TERSEDIA</span>
                        @else
                            <span class="badge bg-secondary rounded-0 text-uppercase fw-bold py-1 px-2">HABIS</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <a href="{{ route('admin.menu.edit', $menu) }}" 
                                class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold d-flex align-items-center gap-1 border" style="font-size: 0.75rem;">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">edit</span>
                                Edit
                            </a>
                            <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" 
                                onsubmit="return confirm('Hapus menu {{ $menu->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger rounded-0 px-3 py-1 text-uppercase fw-bold d-flex align-items-center gap-1 border custom-btn-danger" style="font-size: 0.75rem;">
                                    <span class="material-symbols-outlined" style="font-size: 1rem;">delete</span>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-5 text-center text-secondary">
                        <span class="material-symbols-outlined d-block mb-2" style="font-size: 2.5rem;">restaurant_menu</span>
                        <span style="font-size: 0.85rem;">Belum ada menu. <a href="{{ route('admin.menu.create') }}" class="text-primary text-decoration-underline custom-link">Tambah sekarang</a></span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($menus->hasPages())
<div class="mt-4">{{ $menus->links() }}</div>
@endif
@endsection

@push('scripts')
<style>
    .custom-btn-dark:hover {
        background-color: #a80006 !important;
        border-color: #a80006 !important;
    }
    .custom-btn-danger:hover {
        background-color: #dc3545 !important;
        color: white !important;
    }
    .custom-link:hover {
        color: #8a0004 !important;
    }
    #search-input:focus {
        outline: none !important;
        box-shadow: none !important;
    }
</style>
<script>
    // Live search filter
    document.getElementById('search-input').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.menu-row').forEach(row => {
            const name = row.querySelector('.menu-name').innerText.toLowerCase();
            row.style.display = name.includes(q) ? '' : 'none';
        });
    });
</script>
@endpush

