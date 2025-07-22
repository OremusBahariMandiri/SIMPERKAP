@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-building me-2"></i>Tambah Perusahaan</span>
                    <a href="{{ route('perusahaan.index') }}" class="btn btn-light btn-sm">
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

                    <form action="{{ route('perusahaan.store') }}" method="POST" id="perusahaanForm">
                        @csrf
                        <input type="text" class="form-control" id="id_kode" name="id_kode" value="{{ old('id_kode') }}" hidden readonly>

                        <!-- Grouped form sections with cards -->
                        <div class="row g-4">
                            <!-- Company Information -->
                            <div class="col-md-6">
                                <div class="card h-100 border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Perusahaan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="nama_prsh" class="form-label fw-bold">Nama Perusahaan <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                <input type="text" class="form-control" id="nama_prsh" name="nama_prsh"
                                                    value="{{ old('nama_prsh') }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="alamat_prsh" class="form-label fw-bold">Alamat Perusahaan <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                <textarea class="form-control" id="alamat_prsh" name="alamat_prsh"
                                                    rows="4" required>{{ old('alamat_prsh') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="tgl_berdiri" class="form-label fw-bold">Tanggal Berdiri</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                                <input type="date" class="form-control" id="tgl_berdiri" name="tgl_berdiri"
                                                    value="{{ old('tgl_berdiri') }}">
                                            </div>
                                            <div class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Opsional</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="web_prsh" class="form-label fw-bold">Website</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                <input type="url" class="form-control" id="web_prsh" name="web_prsh"
                                                    value="{{ old('web_prsh') }}">
                                            </div>
                                            <div class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Format: https://alamat-website (Opsional)</div>
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
                                        <div class="form-group mb-3">
                                            <label for="telp_prsh" class="form-label fw-bold">Telepon 1 <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input type="text" class="form-control" id="telp_prsh" name="telp_prsh"
                                                    value="{{ old('telp_prsh') }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="telp_prsh2" class="form-label fw-bold">Telepon 2</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input type="text" class="form-control" id="telp_prsh2" name="telp_prsh2"
                                                    value="{{ old('telp_prsh2') }}">
                                            </div>
                                            <div class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Opsional</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="email_prsh" class="form-label fw-bold">Email 1 <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" id="email_prsh" name="email_prsh"
                                                    value="{{ old('email_prsh') }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="email_prsh2" class="form-label fw-bold">Email 2</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" id="email_prsh2" name="email_prsh2"
                                                    value="{{ old('email_prsh2') }}">
                                            </div>
                                            <div class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Opsional</div>
                                        </div>
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation with visual feedback
    const form = document.getElementById('perusahaanForm');
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