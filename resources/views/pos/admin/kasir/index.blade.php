@extends('layouts.admin')
@section('title', 'Data Kasir')
@section('page-title', 'Kelola Data Kasir')

@section('content')
<div class="row g-4">
    
    <!-- Kolom Kiri: Form Tambah Kasir -->
    <div class="col-12 col-lg-4">
        <div class="bg-white border border-dark p-4 position-sticky" style="top: 1.5rem;">
            <h3 class="text-oswald fw-bold text-uppercase mb-4" style="font-size: 1.1rem; letter-spacing: 1px;">Tambah Kasir Baru</h3>
            <form action="{{ route('admin.kasir.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Nama Lengkap</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                    @error('name') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Email / Username</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                        class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                    @error('email') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Password (Min 6 Karakter)</label>
                    <input type="password" name="password" required minlength="6"
                        class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                    @error('password') <span class="text-danger mt-1 d-block" style="font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-dark w-100 rounded-0 text-uppercase fw-bold py-2 d-flex justify-content-center align-items-center gap-2 custom-btn-dark" style="font-size: 0.85rem;">
                    <span class="material-symbols-outlined" style="font-size: 1.1rem;">person_add</span>
                    Tambah Kasir
                </button>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Daftar Kasir -->
    <div class="col-12 col-lg-8">
        <div class="bg-white border border-dark overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover table-borderless mb-0">
                    <thead class="bg-dark text-white border-bottom border-dark">
                        <tr>
                            <th class="px-4 py-3 text-start text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Nama Kasir</th>
                            <th class="px-4 py-3 text-start text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Email</th>
                            <th class="px-4 py-3 text-center text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="px-4 py-3 text-end text-oswald fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($users as $user)
                        <tr class="border-bottom border-secondary-subtle align-middle">
                            <td class="px-4 py-3 text-oswald fw-bold text-uppercase" style="font-size: 0.85rem;">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-secondary" style="font-size: 0.85rem;">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($user->is_active)
                                    <span class="badge bg-success rounded-0 text-uppercase fw-bold py-1 px-2">AKTIF</span>
                                @else
                                    <span class="badge bg-danger rounded-0 text-uppercase fw-bold py-1 px-2">NONAKTIF</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <button type="button" onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', {{ $user->is_active ? 'true' : 'false' }})"
                                        class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold d-flex align-items-center gap-1 border" style="font-size: 0.75rem;">
                                        <span class="material-symbols-outlined" style="font-size: 1rem;">edit</span>
                                    </button>
                                    <form action="{{ route('admin.kasir.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun kasir ini secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-0 px-3 py-1 text-uppercase fw-bold d-flex align-items-center gap-1 border custom-btn-danger" style="font-size: 0.75rem;">
                                            <span class="material-symbols-outlined" style="font-size: 1rem;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-5 text-center text-secondary" style="font-size: 0.85rem;">
                                Belum ada data kasir.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kasir -->
<div id="edit-modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0 border-dark">
            <div class="modal-header border-bottom border-dark rounded-0 pb-3">
                <h5 class="modal-title text-oswald fw-bold text-uppercase" style="font-size: 1.1rem; letter-spacing: 1px;">Edit Data Kasir</h5>
                <button type="button" class="btn-close shadow-none" onclick="closeEditModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="edit-form" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Nama Lengkap</label>
                        <input type="text" id="edit_name" name="name" required
                            class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Email / Username</label>
                        <input type="email" id="edit_email" name="email" required
                            class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-secondary text-uppercase fw-bold mb-2 d-flex align-items-center gap-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                            Password Baru <span class="text-lowercase fw-normal text-muted" style="font-size: 0.65rem;">(Kosongkan jika tidak ingin ganti)</span>
                        </label>
                        <input type="password" name="password" minlength="6"
                            class="form-control rounded-0 border-dark shadow-none" style="font-size: 0.85rem;">
                    </div>
                    <div class="mb-4">
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input rounded-0 border-dark shadow-none mt-0" type="checkbox" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label text-oswald fw-bold text-uppercase" for="edit_is_active" style="font-size: 0.85rem;">
                                Akun Aktif (Bisa Login)
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="button" onclick="closeEditModal()" class="btn btn-outline-secondary rounded-0 flex-grow-1 text-uppercase fw-bold py-2" style="font-size: 0.85rem;">Batal</button>
                        <button type="submit" class="btn btn-dark rounded-0 flex-grow-1 text-uppercase fw-bold py-2 custom-btn-dark" style="font-size: 0.85rem;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
    .form-check-input:checked {
        background-color: #a80006;
        border-color: #a80006;
    }
</style>
<script>
    let editModal;
    document.addEventListener('DOMContentLoaded', function () {
        editModal = new bootstrap.Modal(document.getElementById('edit-modal'));
    });

    function openEditModal(id, name, email, isActive) {
        document.getElementById('edit-form').action = `/admin/kasir/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_is_active').checked = isActive;
        editModal.show();
    }

    function closeEditModal() {
        editModal.hide();
        document.getElementById('edit-form').reset();
    }
</script>
@endpush

