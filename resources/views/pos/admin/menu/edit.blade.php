@extends('layouts.admin')
@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu: ' . $menu->nama)

@section('top-action')
    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-dark rounded-0 d-flex align-items-center gap-2 text-uppercase fw-bold px-3 py-2 border-0 custom-hover-text" style="font-size: 0.85rem; letter-spacing: 1px;">
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">arrow_back</span>
        Kembali
    </a>
@endsection

@section('content')
<div class="bg-white border border-dark mx-auto" style="max-width: 48rem;">
    <form action="{{ route('admin.menu.update', $menu) }}" method="POST" class="p-4 p-md-5">
        @csrf @method('PUT')
        
        <!-- Grid 2 Column -->
        <div class="row g-4 mb-4">
            <!-- Nama Menu -->
            <div class="col-12">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Nama Menu <span class="text-danger">*</span></label>
                <input type="text" name="nama" required value="{{ old('name', $menu->nama) }}"
                    class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                @error('name') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Kategori -->
            <div class="col-12 col-md-6">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Kategori <span class="text-danger">*</span></label>
                <select name="id_kategori" required class="form-select rounded-0 border-dark shadow-none cursor-pointer" style="font-size: 0.85rem;">
                    <option value="" disabled>-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $menu->id_kategori) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Harga -->
            <div class="col-12 col-md-6">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Harga (IDR) <span class="text-danger">*</span></label>
                <input type="number" name="harga" required min="0" value="{{ old('price', $menu->harga) }}"
                    class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                @error('price') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Image URL -->
            <div class="col-12">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">URL Gambar</label>
                <div class="d-flex gap-3 align-items-start">
                    @if($menu->gambar)
                    <img src="{{ $menu->gambar }}" alt="Preview" class="border border-dark object-fit-cover" style="width: 64px; height: 64px;">
                    @endif
                    <div class="flex-grow-1">
                        <input type="url" name="gambar" value="{{ old('image', $menu->gambar) }}"
                            class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                        <p class="text-secondary mt-1 mb-0" style="font-size: 0.75rem;">Biarkan kosong jika tidak ada gambar.</p>
                        @error('image') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="col-12">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem; resize: vertical;">{{ old('description', $menu->deskripsi) }}</textarea>
                @error('description') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            
            <!-- Status Ketersediaan -->
            <div class="col-12 mt-3">
                <div class="form-check form-switch d-flex align-items-center gap-2">
                    <input class="form-check-input rounded-0 border-dark shadow-none mt-0 custom-switch" type="checkbox" role="switch" name="tersedia" value="1" id="is_available_switch" {{ old('is_available', $menu->tersedia) ? 'checked' : '' }} style="width: 2.5rem; height: 1.25rem; cursor: pointer;">
                    <label class="form-check-label text-dark text-uppercase fw-bold ms-2 cursor-pointer" for="is_available_switch" style="font-size: 0.85rem; letter-spacing: 1px;">Menu Tersedia untuk Dipesan</label>
                </div>
            </div>
        </div>

        <div class="border-top border-dark pt-4 mt-4 d-flex justify-content-end gap-3">
            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary rounded-0 text-uppercase fw-bold py-2 px-4 border custom-btn-outline" style="font-size: 0.85rem;">
                Batal
            </a>
            <button type="submit" class="btn btn-dark rounded-0 text-uppercase fw-bold py-2 px-4 d-flex align-items-center gap-2 custom-btn-dark" style="font-size: 0.85rem;">
                <span class="material-symbols-outlined" style="font-size: 1.1rem;">save</span>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<style>
    .custom-hover-text:hover {
        color: #000 !important;
        background-color: transparent !important;
    }
    .custom-btn-outline:hover {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
    }
    .custom-btn-dark:hover {
        background-color: #a80006 !important;
        border-color: #a80006 !important;
    }
    .custom-switch:checked {
        background-color: #198754;
        border-color: #198754;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

