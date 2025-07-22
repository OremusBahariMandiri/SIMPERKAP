@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Detail Dokumen Kapal</span>
                        <div>
                            <a href="{{ route('dokumen-kapal.edit', $dokumenKapal->id) }}"
                                class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('dokumen-kapal.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Grouped information sections with cards -->
                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="card h-100 border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Nomor Registrasi</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                    <div class="form-control">{{ $dokumenKapal->no_reg }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Kapal</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->kapal)
                                                            {{ $dokumenKapal->kapal->nama_kpl }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Kategori Dokumen</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-folder"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->kategoriDokumen)
                                                            {{ $dokumenKapal->kategoriDokumen->kategori_dok }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Nama Dokumen</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->namaDokumen)
                                                            {{ $dokumenKapal->namaDokumen->nama_dok }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Penerbit Dokumen</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->penerbit_dok)
                                                            {{ $dokumenKapal->penerbit_dok }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Validasi Dokumen</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-check-circle"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->validasi_dok)
                                                            {{ $dokumenKapal->validasi_dok }}
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

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <div class="card h-100 border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Masa Berlaku dan Status
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Tanggal Terbit</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-calendar-plus"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->tgl_terbit_dok)
                                                            {{ \Carbon\Carbon::parse($dokumenKapal->tgl_terbit_dok)->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Tanggal Berakhir</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-calendar-times"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->tgl_berakhir_dok)
                                                            {{ \Carbon\Carbon::parse($dokumenKapal->tgl_berakhir_dok)->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Masa Berlaku</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->masa_berlaku)
                                                            {{ $dokumenKapal->masa_berlaku }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Tanggal Peringatan</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->tgl_peringatan)
                                                            {{ \Carbon\Carbon::parse($dokumenKapal->tgl_peringatan)->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Masa Peringatan</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-hourglass-half"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->masa_peringatan)
                                                            {{ $dokumenKapal->masa_peringatan }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Status Dokumen</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-info-circle"></i></span>
                                                    <div class="form-control">
                                                        @if ($dokumenKapal->status_dok)
                                                            @if ($dokumenKapal->status_dok == 'Berlaku')
                                                                <span class="badge bg-success">Berlaku</span>
                                                            @elseif($dokumenKapal->status_dok == 'Tidak Berlaku')
                                                                <span class="badge bg-danger">Tidak Berlaku</span>
                                                            @elseif($dokumenKapal->status_dok == 'Warning')
                                                                <span class="badge bg-warning">Segera Habis</span>
                                                            @else
                                                                {{ $dokumenKapal->status_dok }}
                                                            @endif
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

                        <!-- Informasi Tambahan Section -->
                        <div class="card border-secondary mt-4">
                            <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Updated file section from detail.blade.php -->
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">File Dokumen</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    <div class="form-control d-flex align-items-center">
                                                        @if ($dokumenKapal->file_dok)
                                                            <span class="me-2">{{ $dokumenKapal->file_dok }}</span>
                                                            <div class="btn-group">
                                                                <a href="{{ route('dokumen-kapal.viewDocument', $dokumenKapal->id) }}"
                                                                    class="btn btn-sm btn-secondary me-2" target="_blank">
                                                                    <i class="fas fa-eye me-1"></i>Lihat
                                                                </a>
                                                                <a href="{{ route('dokumen-kapal.download', $dokumenKapal->id) }}"
                                                                    class="btn btn-sm btn-success me-2">
                                                                    <i class="fas fa-download me-1"></i>Download
                                                                </a>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="info-group">
                                            <label class="info-label fw-bold">Keterangan</label>
                                            <div class="info-value h-100">
                                                <div class="input-group h-100">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-sticky-note"></i></span>
                                                    <div class="form-control" style="min-height: 100px;">
                                                        @if ($dokumenKapal->catatan)
                                                            {{ $dokumenKapal->catatan }}
                                                        @else
                                                            <span class="text-muted">Tidak ada keterangan</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kapal Section -->
                        {{-- @if ($dokumenKapal->kapal)
                    <div class="card border-warning mt-4">
                        <div class="card-header bg-warning bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-ship me-2"></i>Informasi Kapal</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Nama Kapal</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                                <div class="form-control">{{ $dokumenKapal->kapal->nama_kpl }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Jenis Kapal</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                <div class="form-control">
                                                    @if ($dokumenKapal->kapal->jenisKapal)
                                                        {{ $dokumenKapal->kapal->jenisKapal->jenis_kpl }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Perusahaan</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                <div class="form-control">
                                                    @if ($dokumenKapal->kapal->perusahaan)
                                                        {{ $dokumenKapal->kapal->perusahaan->nama_prsh }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">No. IMO</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                <div class="form-control">
                                                    @if ($dokumenKapal->kapal->no_imo)
                                                        {{ $dokumenKapal->kapal->no_imo }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <a href="{{ route('kapal.show', $dokumenKapal->kapal->id) }}" class="btn btn-info">
                                        <i class="fas fa-external-link-alt me-1"></i>Lihat Detail Kapal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif --}}
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
    </style>
@endpush
