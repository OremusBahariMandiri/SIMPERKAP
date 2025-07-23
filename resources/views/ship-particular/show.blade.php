@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-ship me-2"></i>Detail Ship Particular</span>
                    <div>
                        <a href="{{ route('ship-particular.edit', $shipParticular->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('ship-particular.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card border-secondary mb-3">
                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Ship Particular</h5>
                        </div>
                        <div class="card-body">
                            

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Nama Kapal</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                        <div class="form-control">
                                            @if($shipParticular->kapal)
                                                {{ $shipParticular->kapal->nama_kpl }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Keterangan Ship Particular</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                        <div class="form-control" style="min-height: 80px; white-space: pre-wrap;">
                                            @if($shipParticular->ship_particular_ket)
                                                {{ $shipParticular->ship_particular_ket }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">File Dokumen</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        <div class="form-control d-flex align-items-center justify-content-between">
                                            @if($shipParticular->file_dok)
                                                <span>{{ basename($shipParticular->file_dok) }}</span>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('ship-particular.view', $shipParticular->id) }}"
                                                       class="btn btn-sm btn-outline-info" target="_blank"
                                                       data-bs-toggle="tooltip" title="Lihat File">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                    <a href="{{ route('ship-particular.download', $shipParticular->id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       data-bs-toggle="tooltip" title="Download File">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-muted">Tidak ada file</span>
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
                                        <div class="form-control">{{ $shipParticular->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>

                            @if($shipParticular->creator)
                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Dibuat oleh</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <div class="form-control">{{ $shipParticular->creator->name }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($shipParticular->updated_at != $shipParticular->created_at)
                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Diperbarui pada</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-edit"></i></span>
                                        <div class="form-control">{{ $shipParticular->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($shipParticular->updater && $shipParticular->updated_at != $shipParticular->created_at)
                            <div class="info-group mb-3">
                                <label class="info-label fw-bold">Diperbarui oleh</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-edit"></i></span>
                                        <div class="form-control">{{ $shipParticular->updater->name }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="d-grid">
                                <a href="{{ route('ship-particular.edit', $shipParticular->id) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit me-2"></i>Edit Data
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button type="button" class="btn btn-danger btn-lg"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-trash me-2"></i>Hapus Data
                                </button>
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
                <p>Apakah Anda yakin ingin menghapus data Ship Particular untuk <strong>{{ $shipParticular->kapal->nama_kpl ?? 'kapal ini' }}</strong>?</p>
                <p class="text-danger"><small>Data yang dihapus tidak dapat dikembalikan!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('ship-particular.destroy', $shipParticular->id) }}" method="POST" class="d-inline">
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