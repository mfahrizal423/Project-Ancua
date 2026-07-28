@extends('layouts.admin')
@section('title', 'Kategori Menu')
@section('page-title', 'Kelola Kategori')

@section('content')
<div class="row g-4">
    
    <!-- Kolom Kiri: Form Tambah -->
    <div class="col-12 col-md-4">
        <div class="bg-white border border-dark p-4 position-sticky" style="top: 1.5rem;">
            <h3 class="text-oswald fw-bold text-uppercase mb-4" style="font-size: 1.1rem; letter-spacing: 1px;">Tambah Kategori</h3>
            <form action="{{ route('admin.category.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Nama Kategori</label>
                    <input type="text" name="nama" required value="{{ old('name') }}"
                        class="form-control rounded-0 border-dark shadow-none" 
                        placeholder="Contoh: Signature Drink">
                    @error('name') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-dark w-100 rounded-0 text-uppercase fw-bold py-2 d-flex justify-content-center align-items-center gap-2 custom-btn-dark" style="font-size: 0.85rem;">
                    <span class="material-symbols-outlined" style="font-size: 1.1rem;">add</span>
                    Tambah
                </button>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Daftar Kategori -->
    <div class="col-12 col-md-8">
        <div class="bg-white border border-dark">
            <div class="table-responsive">
                <table class="table table-hover table-borderless mb-0">
                    <thead class="bg-dark text-white border-bottom border-dark">
                        <tr>
                            <th class="px-4 py-3 text-start text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Nama Kategori</th>
                            <th class="px-4 py-3 text-center text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Jumlah Menu</th>
                            <th class="px-4 py-3 text-end text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($categories as $category)
                        <tr class="border-bottom border-secondary-subtle align-middle">
                            <td class="px-4 py-3">
                                <!-- Tampilan Normal -->
                                <div id="view-{{ $category->id }}" class="d-flex align-items-center">
                                    <span class="text-oswald fw-bold text-uppercase" style="font-size: 0.85rem;">{{ $category->nama }}</span>
                                </div>
                                <!-- Form Edit -->
                                <form id="edit-{{ $category->id }}" action="{{ route('admin.category.update', $category) }}" method="POST" class="d-none gap-2">
                                    @csrf @method('PUT')
                                    <input type="text" name="nama" value="{{ $category->nama }}" required
                                        class="form-control rounded-0 border-dark shadow-none px-2 py-1 flex-grow-1" style="font-size: 0.85rem;">
                                    <button type="submit" class="btn btn-success rounded-0 px-2 py-1 d-flex align-items-center justify-content-center border-0 custom-hover-brightness">
                                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">check</span>
                                    </button>
                                    <button type="button" onclick="toggleEdit({{ $category->id }}, false)" class="btn btn-secondary rounded-0 px-2 py-1 d-flex align-items-center justify-content-center border-0 custom-hover-brightness">
                                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">close</span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-light text-secondary fw-bold px-3 py-1 border border-secondary text-uppercase" style="font-size: 0.75rem;">
                                    {{ $category->menu_count }} Menu
                                </span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2" id="actions-{{ $category->id }}">
                                    <button onclick="toggleEdit({{ $category->id }}, true)"
                                        class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                        <span class="material-symbols-outlined" style="font-size: 1rem;">edit</span>
                                    </button>
                                    <form action="{{ route('admin.category.destroy', $category) }}" method="POST" 
                                        onsubmit="return confirm('Hapus kategori {{ $category->nama }}? Pastikan tidak ada menu di kategori ini.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" {{ $category->menu_count > 0 ? 'disabled' : '' }}
                                            class="btn rounded-0 px-3 py-1 text-uppercase fw-bold d-flex align-items-center gap-1 border transition-colors {{ $category->menu_count > 0 ? 'btn-outline-secondary opacity-50 cursor-not-allowed' : 'btn-outline-danger custom-btn-danger' }}" style="font-size: 0.75rem;">
                                            <span class="material-symbols-outlined" style="font-size: 1rem;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-5 text-center text-secondary" style="font-size: 0.85rem;">
                                Belum ada kategori.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <p class="text-secondary mt-3 d-flex align-items-center gap-1" style="font-size: 0.75rem;">
            <span class="material-symbols-outlined" style="font-size: 1rem;">info</span>
            Kategori yang masih memiliki menu tidak dapat dihapus.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .custom-btn-dark:hover {
        background-color: #a80006 !important;
        border-color: #a80006 !important;
    }
    .custom-hover-brightness:hover {
        filter: brightness(110%);
    }
    .custom-btn-danger:hover {
        background-color: #dc3545 !important;
        color: white !important;
    }
</style>
<script>
    function toggleEdit(id, show) {
        const viewEl = document.getElementById('view-' + id);
        const actionsEl = document.getElementById('actions-' + id);
        const editEl = document.getElementById('edit-' + id);
        
        if(show) {
            viewEl.classList.add('d-none');
            actionsEl.classList.add('d-none');
            editEl.classList.remove('d-none');
            editEl.classList.add('d-flex');
        } else {
            viewEl.classList.remove('d-none');
            actionsEl.classList.remove('d-none');
            editEl.classList.add('d-none');
            editEl.classList.remove('d-flex');
        }
    }
</script>
@endpush

