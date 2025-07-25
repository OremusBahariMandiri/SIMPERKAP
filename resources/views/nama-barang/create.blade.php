@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-boxes me-2"></i>Tambah Nama Barang</span>
                    <a href="{{ route('nama-barang.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('nama-barang.store') }}" method="POST" id="namaBarangForm">
                        @csrf

                        <div class="card border-secondary mb-4">
                            <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Nama Barang</h5>
                            </div>
                            <div class="card-body">
                                <input type="text" class="form-control" id="id_kode" name="id_kode" value="{{ old('id_kode', $newId) }}" hidden readonly>

                                <div class="form-group mb-3">
                                    <label for="no_kode_brg" class="form-label fw-bold">Kode Barang</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        <input type="text" class="form-control" id="no_kode_brg" name="no_kode_brg"
                                            value="{{ old('no_kode_brg') }}" placeholder="Masukkan kode barang...">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="nama_brg" class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                        <input type="text" class="form-control" id="nama_brg" name="nama_brg"
                                            value="{{ old('nama_brg') }}" placeholder="Masukkan nama barang..." required>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="id_kode_a08" class="form-label fw-bold">Kategori Barang <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                        <select class="form-select" id="id_kode_a08" name="id_kode_a08" required>
                                            <option value="">-- Pilih Kategori Barang --</option>
                                            @foreach ($kategoriBarangs as $kategori)
                                                <option value="{{ $kategori->id_kode }}" {{ old('id_kode_a08') == $kategori->id_kode ? 'selected' : '' }}>
                                                    {{ $kategori->kategori_brg }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="id_kode_a09" class="form-label fw-bold">Jenis Barang <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                        <select class="form-select" id="id_kode_a09" name="id_kode_a09" required>
                                            <option value="">-- Pilih Jenis Barang --</option>
                                            @foreach ($jenisBarangs as $jenis)
                                                <option value="{{ $jenis->id_kode }}" {{ old('id_kode_a09') == $jenis->id_kode ? 'selected' : '' }}>
                                                    {{ $jenis->jenis_brg }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="id_kode_a10" class="form-label fw-bold">Golongan Barang <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        <select class="form-select" id="id_kode_a10" name="id_kode_a10" required>
                                            <option value="">-- Pilih Golongan Barang --</option>
                                            @foreach ($golonganBarangs as $golongan)
                                                <option value="{{ $golongan->id_kode }}" {{ old('id_kode_a10') == $golongan->id_kode ? 'selected' : '' }}>
                                                    {{ $golongan->golongan_brg }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="keterangan_brg" class="form-label fw-bold">Keterangan Barang</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                        <textarea class="form-control" id="keterangan_brg" name="keterangan_brg"
                                                rows="4" placeholder="Masukkan keterangan barang...">{{ old('keterangan_brg') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 col-md-4 mx-auto mt-4">
                            <button type="submit" class="btn btn-secondary btn-lg">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        font-weight: 600;
    }
    .form-label {
        margin-bottom: 0.3rem;
    }
    .card {
        margin-bottom: 1rem;
        transition: all 0.3s;
    }
    .card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .text-danger {
        font-weight: bold;
    }
    .bg-light {
        background-color: #f8f9fa;
    }
    .form-text {
        margin-top: 0.25rem;
    }
    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation with visual feedback
    const form = document.getElementById('namaBarangForm');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();

            // Highlight missing required fields
            document.querySelectorAll('[required]').forEach(function(input) {
                if (!input.value) {
                    input.classList.add('is-invalid');
                    // Create error message if it doesn't exist
                    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('invalid-feedback')) {
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = 'Field ini wajib diisi';
                        input.parentNode.insertBefore(feedback, input.nextElementSibling);
                    }
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Remove invalid class when input changes
    document.querySelectorAll('[required]').forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endpush