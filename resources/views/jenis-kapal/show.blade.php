@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-ship me-2"></i>Detail Jenis Kapal</span>
                    <div>
                        <a href="{{ route('jenis-kapal.edit', $jenisKapal->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('jenis-kapal.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card border-secondary mb-3">
                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Jenis Kapal</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Jenis Kapal</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                        <div class="form-control">{{ $jenisKapal->jenis_kpl }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Dibuat pada</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                        <div class="form-control">{{ $jenisKapal->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>

                            @if($jenisKapal->updated_at != $jenisKapal->created_at)
                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Diperbarui pada</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-edit"></i></span>
                                        <div class="form-control">{{ $jenisKapal->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Kapal Terkait Section -->
                    {{-- <div class="card border-warning mt-4">
                        <div class="card-header bg-warning bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-link me-2"></i>Kapal Terkait</h5>
                        </div>
                        <div class="card-body">
                            @if ($jenisKapal->kapal && $jenisKapal->kapal->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Nama Kapal</th>
                                                <th width="20%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jenisKapal->kapal as $index => $kapal)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $kapal->nama_kpl }}</td>
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
                                    <span>Tidak ada kapal terkait dengan jenis kapal ini.</span>
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