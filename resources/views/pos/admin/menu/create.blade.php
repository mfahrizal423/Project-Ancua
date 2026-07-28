@extends('layouts.admin')
@section('title', 'Tambah Menu')
@section('page-title', 'Tambah Menu Baru')

@section('top-action')
    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-dark rounded-0 d-flex align-items-center gap-2 text-uppercase fw-bold px-3 py-2 border-0 custom-hover-text" style="font-size: 0.85rem; letter-spacing: 1px;">
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">arrow_back</span>
        Kembali
    </a>
@endsection

@section('content')
<div class="bg-white border border-dark mx-auto" style="max-width: 48rem;">
    <form action="{{ route('admin.menu.store') }}" method="POST" class="p-4 p-md-5">
        @csrf
        
        <!-- Grid 2 Column -->
        <div class="row g-4 mb-4">
            <!-- Nama Menu -->
            <div class="col-12">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Nama Menu <span class="text-danger">*</span></label>
                <input type="text" name="nama" required value="{{ old('name') }}"
                    class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;" 
                    placeholder="Contoh: Iced Gula Aren">
                @error('name') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Kategori -->
            <div class="col-12 col-md-6">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Kategori <span class="text-danger">*</span></label>
                <select name="id_kategori" required class="form-select rounded-0 border-dark shadow-none cursor-pointer" style="font-size: 0.85rem;">
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Harga -->
            <div class="col-12 col-md-6">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Harga (IDR) <span class="text-danger">*</span></label>
                <input type="number" name="harga" required min="0" value="{{ old('price') }}"
                    class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;" 
                    placeholder="Contoh: 28000">
                @error('price') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Image URL -->
            <div class="col-12">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">URL Gambar</label>
                <input type="url" name="gambar" value="{{ old('image') }}"
                    class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;" 
                    placeholder="https://example.com/image.jpg">
                <p class="text-secondary mt-1 mb-0" style="font-size: 0.75rem;">Biarkan kosong jika tidak ada gambar.</p>
                @error('image') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Deskripsi -->
            <div class="col-12">
                <label class="form-label text-dark text-uppercase fw-bold mb-2" style="font-size: 0.85rem; letter-spacing: 1px;">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem; resize: vertical;" 
                    placeholder="Jelaskan sedikit tentang menu ini...">{{ old('description') }}</textarea>
                @error('description') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            
            <!-- Status Ketersediaan -->
            <div class="col-12 mt-3">
                <div class="form-check form-switch d-flex align-items-center gap-2">
                    <input class="form-check-input rounded-0 border-dark shadow-none mt-0 custom-switch" type="checkbox" role="switch" name="tersedia" value="1" id="is_available_switch" {{ old('is_available', true) ? 'checked' : '' }} style="width: 2.5rem; height: 1.25rem; cursor: pointer;">
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
                Simpan Menu
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

