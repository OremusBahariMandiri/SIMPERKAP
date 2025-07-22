@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-ship me-2"></i>Detail Kapal</span>
                    <div>
                        <a href="{{ route('kapal.edit', $kapal->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('kapal.index') }}" class="btn btn-light btn-sm">
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
                                        <label class="info-label fw-bold">Nama Kapal</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                                <div class="form-control">{{ $kapal->nama_kpl }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Perusahaan</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->perusahaan)
                                                        {{ $kapal->perusahaan->nama_prsh }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Jenis Kapal</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->jenisKapal)
                                                        {{ $kapal->jenisKapal->jenis_kpl }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Nomor IMO</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->no_imo)
                                                        {{ $kapal->no_imo }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Bendera Kapal</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->bendera_kpl)
                                                        {{ $kapal->bendera_kpl }}
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
                                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Informasi Tambahan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Tonase (GT)</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->tonase_kpl)
                                                        {{ number_format($kapal->tonase_kpl, 0, ',', '.') }} GT
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Tanda Panggilan</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-broadcast-tower"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->tanda_panggilan_kpl)
                                                        {{ $kapal->tanda_panggilan_kpl }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Jumlah Awak</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->awak_kpl)
                                                        {{ $kapal->awak_kpl }} orang
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Kapasitas Penumpang</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user-friends"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->penumpang_kpl)
                                                        {{ $kapal->penumpang_kpl }} orang
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Tahun Pembuatan</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->thn_pbtn_kpl)
                                                        {{ $kapal->thn_pbtn_kpl }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-group mb-3">
                                        <label class="info-label fw-bold">Asal Pembuatan</label>
                                        <div class="info-value">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                                <div class="form-control">
                                                    @if($kapal->asal_pbtn_kpl)
                                                        {{ $kapal->asal_pbtn_kpl }}
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

                    <!-- Dokumen Kapal Section -->
                    {{-- <div class="card border-warning mt-4">
                        <div class="card-header bg-warning bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Dokumen Kapal</h5>
                        </div>
                        <div class="card-body">
                            @if ($kapal->dokumenKapal && $kapal->dokumenKapal->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">No. Reg</th>
                                                <th width="15%">Kategori</th>
                                                <th width="20%">Nama Dokumen</th>
                                                <th width="15%">Tanggal Berakhir</th>
                                                <th width="15%">Status</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kapal->dokumenKapal as $index => $dokumen)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $dokumen->no_reg }}</td>
                                                    <td>
                                                        @if($dokumen->kategoriDokumen)
                                                            {{ $dokumen->kategoriDokumen->kategori_dok }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($dokumen->namaDokumen)
                                                            {{ $dokumen->namaDokumen->nama_dok }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($dokumen->tgl_berakhir_dok)
                                                            {{ \Carbon\Carbon::parse($dokumen->tgl_berakhir_dok)->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($dokumen->status_dok == 'Valid')
                                                            <span class="badge bg-success">Valid</span>
                                                        @elseif ($dokumen->status_dok == 'Expired')
                                                            <span class="badge bg-danger">Expired</span>
                                                        @elseif ($dokumen->status_dok == 'Warning')
                                                            <span class="badge bg-warning">Segera Habis</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $dokumen->status_dok ?? 'Tidak Ada' }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('dokumen-kapal.detail', $dokumen->id) }}"
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
                                    <span>Tidak ada dokumen terkait dengan kapal ini.</span>
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