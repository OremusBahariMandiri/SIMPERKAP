@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-box me-2"></i>Detail Kategori Barang</span>
                    <div>
                        <a href="{{ route('kategori-barang.edit', $kategoriBarang->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('kategori-barang.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card border-secondary mb-3">
                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Kategori Barang</h5>
                        </div>
                        <div class="card-body">

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Kategori Barang</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        <div class="form-control">{{ $kategoriBarang->kategori_brg }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Keterangan Barang</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-box-open"></i></span>
                                        <div class="form-control">{{ $kategoriBarang->ket_brg }}</div>
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
    }
</style>
@endpush