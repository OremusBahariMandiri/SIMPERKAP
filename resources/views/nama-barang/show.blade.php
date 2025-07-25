@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-boxes me-2"></i>Detail Nama Barang</span>
                    <div>
                        <a href="{{ route('nama-barang.edit', $namaBarang->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('nama-barang.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card border-secondary mb-3">
                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Nama Barang</h5>
                        </div>
                        <div class="card-body">

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Kode Barang</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        <div class="form-control">
                                            @if($namaBarang->no_kode_brg)
                                                {{ $namaBarang->no_kode_brg }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Nama Barang</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                        <div class="form-control">{{ $namaBarang->nama_brg }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Kategori Barang</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                        <div class="form-control">
                                            @if($namaBarang->kategoriBarang)
                                                {{ $namaBarang->kategoriBarang->kategori_brg }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Jenis Barang</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                        <div class="form-control">
                                            @if($namaBarang->jenisBarang)
                                                {{ $namaBarang->jenisBarang->jenis_brg }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Golongan Barang</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        <div class="form-control">
                                            @if($namaBarang->golonganBarang)
                                                {{ $namaBarang->golonganBarang->golongan_brg }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Keterangan Barang</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                        <div class="form-control" style="min-height: 80px">
                                            @if($namaBarang->keterangan_brg)
                                                {{ $namaBarang->keterangan_brg }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data Nama Barang <strong>{{ $namaBarang->nama_brg }}</strong>?</p>
                <p class="text-danger"><small>Data yang dihapus tidak dapat dikembalikan!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('nama-barang.destroy', $namaBarang->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
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
    .info-label {
        margin-bottom: 0.3rem;
        display: block;
    }
    .info-group {
        margin-bottom: 1rem;
    }
    .info-value .form-control {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        min-height: 38px;
    }
    .card {
        margin-bottom: 1rem;
        transition: all 0.3s;
    }
    .card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .text-muted {
        color: #6c757d !important;
    }
    .bg-light {
        background-color: #f8f9fa;
    }
    .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
        min-width: 45px;
        justify-content: center;
    }
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1.125rem;
        border-radius: 0.3rem;
    }
    .modal-content {
        border-radius: 0.5rem;
    }
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
        keyboard: false
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush