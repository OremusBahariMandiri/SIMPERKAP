@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-ship me-2"></i>Edit Ship Particular</span>
                        <a href="{{ route('ship-particular.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('ship-particular.update', $shipParticular->id) }}" method="POST"
                            id="shipParticularForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card border-secondary mb-4">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Ship Particular</h5>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control" id="id_kode_display"
                                        value="{{ $shipParticular->id_kode }}" hidden disabled>

                                    <div class="form-group mb-3">
                                        <label for="nama_kpl" class="form-label fw-bold">Nama Kapal <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                            <select class="form-select" id="nama_kpl" name="nama_kpl" required>
                                                <option value="">-- Pilih Nama Kapal --</option>
                                                @foreach ($kapal as $ship)
                                                    <option value="{{ $ship->id_kode }}"
                                                        {{ old('nama_kpl', $shipParticular->nama_kpl) == $ship->id_kode ? 'selected' : '' }}>
                                                        {{ $ship->nama_kpl }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="ship_particular_ket" class="form-label fw-bold">Keterangan Ship Particular</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                            <textarea class="form-control" id="ship_particular_ket" name="ship_particular_ket"
                                                    rows="4" placeholder="Masukkan keterangan ship particular...">{{ old('ship_particular_ket', $shipParticular->ship_particular_ket) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="file_dok" class="form-label fw-bold">File Dokumen</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-upload"></i></span>
                                            <input type="file" class="form-control" id="file_dok" name="file_dok"
                                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        </div>
                                        <div class="form-text">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                Format yang didukung: PDF, DOC, DOCX, JPG, JPEG, PNG.
                                            </small>
                                        </div>
                                        @if($shipParticular->file_dok)
                                            <div class="mt-2">
                                                <div class="alert alert-info d-flex align-items-center">
                                                    <i class="fas fa-file me-2"></i>
                                                    <div class="flex-grow-1">
                                                        <strong>File saat ini:</strong> {{ basename($shipParticular->file_dok) }}
                                                        <br>
                                                        <div class="mt-1 d-flex gap-2">
                                                            <a href="{{ route('ship-particular.view', $shipParticular->id) }}"
                                                               class="btn btn-sm btn-outline-info" target="_blank">
                                                                <i class="fas fa-eye"></i> Lihat
                                                            </a>
                                                            <a href="{{ route('ship-particular.download', $shipParticular->id) }}"
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 col-md-4 mx-auto mt-4">
                                <button type="submit" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-save me-2"></i>Update
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
            const form = document.getElementById('shipParticularForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    // Highlight missing required fields
                    document.querySelectorAll('[required]').forEach(function(input) {
                        if (!input.value) {
                            input.classList.add('is-invalid');
                            // Create error message if it doesn't exist
                            if (!input.nextElementSibling || !input.nextElementSibling.classList
                                .contains('invalid-feedback')) {
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
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
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