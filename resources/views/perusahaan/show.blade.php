@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-building me-2"></i>Detail Perusahaan</span>
                    <div>
                        <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('perusahaan.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Grouped information sections with cards -->
                    <div class="row g-4">
                        <!-- Company Information -->
                        <div class="col-md-6">
                            <div class="card h-100 border-secondary">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Perusahaan</h5>
                                </div>
                                <div class="card-body">

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Nama Perusahaan</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                <div class="form-control">{{ $perusahaan->nama_prsh }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Alamat Perusahaan</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                <div class="form-control" style="min-height: 100px; white-space: pre-wrap;">{{ $perusahaan->alamat_prsh }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Tanggal Berdiri</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                                <div class="form-control">
                                                    @if ($perusahaan->tgl_berdiri)
                                                        {{ $perusahaan->tgl_berdiri->format('d/m/Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Website</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                <div class="form-control">
                                                    @if ($perusahaan->web_prsh)
                                                        <a href="{{ $perusahaan->web_prsh }}" target="_blank" class="text-decoration-none">
                                                            {{ $perusahaan->web_prsh }}
                                                        </a>
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

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="card h-100 border-secondary">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-address-book me-2"></i>Informasi Kontak</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Telepon 1</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <div class="form-control">{{ $perusahaan->telp_prsh }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Telepon 2</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <div class="form-control">
                                                    @if ($perusahaan->telp_prsh2)
                                                        {{ $perusahaan->telp_prsh2 }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Email 1</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <div class="form-control">{{ $perusahaan->email_prsh }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Email 2</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <div class="form-control">
                                                    @if ($perusahaan->email_prsh2)
                                                        {{ $perusahaan->email_prsh2 }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Dibuat pada</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                                <div class="form-control">{{ $perusahaan->created_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--
                    <!-- Kapal Terkait Section -->
                    <div class="card border-warning mt-4">
                        <div class="card-header bg-warning bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-ship me-2"></i>Kapal Terkait</h5>
                        </div>
                        <div class="card-body">
                            @if ($perusahaan->kapal && $perusahaan->kapal->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">ID Kode</th>
                                                <th>Nama Kapal</th>
                                                <th>Jenis Kapal</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($perusahaan->kapal as $index => $kapal)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $kapal->id_kode }}</td>
                                                    <td>{{ $kapal->nama_kpl }}</td>
                                                    <td>
                                                        @if($kapal->jenisKapal)
                                                            {{ $kapal->jenisKapal->jenis_kpl }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('kapal.show', $kapal->id) }}"
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye me-1"></i>Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>Tidak ada kapal terkait dengan perusahaan ini.</span>
                                </div>
                            @endif
                        </div>
                    </div> --}}
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
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.05);
    }
    .badge {
        font-size: 0.75em;
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
    .alert {
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }
</style>
@endpush