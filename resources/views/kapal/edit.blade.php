@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-ship me-2"></i>Edit Kapal</span>
                        <a href="{{ route('kapal.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('kapal.update', $kapal->id) }}" method="POST" id="kapalForm">
                            @csrf
                            @method('PUT')

                            <!-- Grouped form sections with cards -->
                            <div class="row g-4">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                                        </div>
                                        <div class="card-body">
                                            <input type="text" class="form-control" id="id_kode_display"
                                                value="{{ $kapal->id_kode }}" hidden disabled>

                                            <div class="form-group mb-3">
                                                <label for="nama_kpl" class="form-label fw-bold">Nama Kapal <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                                    <input type="text" class="form-control" id="nama_kpl"
                                                        name="nama_kpl" value="{{ old('nama_kpl', $kapal->nama_kpl) }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="nama_prsh" class="form-label fw-bold">Perusahaan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <select class="form-select" id="nama_prsh" name="nama_prsh" required>
                                                        <option value="">-- Pilih Perusahaan --</option>
                                                        @foreach ($perusahaan as $company)
                                                            <option value="{{ $company->id_kode }}"
                                                                {{ old('nama_prsh', $kapal->nama_prsh) == $company->id_kode ? 'selected' : '' }}>
                                                                {{ $company->nama_prsh }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="jenis_kpl" class="form-label fw-bold">Jenis Kapal <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                    <select class="form-select" id="jenis_kpl" name="jenis_kpl" required>
                                                        <option value="">-- Pilih Jenis Kapal --</option>
                                                        @foreach ($jenisKapal as $type)
                                                            <option value="{{ $type->id_kode }}"
                                                                {{ old('jenis_kpl', $kapal->jenis_kpl) == $type->id_kode ? 'selected' : '' }}>
                                                                {{ $type->jenis_kpl }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="no_imo" class="form-label fw-bold">Nomor IMO</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                    <input type="text" class="form-control" id="no_imo" name="no_imo"
                                                        value="{{ old('no_imo', $kapal->no_imo) }}">
                                                </div>
                                                <div class="form-text text-muted"><i
                                                        class="fas fa-info-circle me-1"></i>Opsional</div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="bendera_kpl" class="form-label fw-bold">Bendera Kapal</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                                    <input type="text" class="form-control" id="bendera_kpl"
                                                        name="bendera_kpl"
                                                        value="{{ old('bendera_kpl', $kapal->bendera_kpl) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Informasi Tambahan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="tonase_kpl" class="form-label fw-bold">Tonase (GT)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-weight-hanging"></i></span>
                                                    <input type="number" class="form-control" id="tonase_kpl"
                                                        name="tonase_kpl"
                                                        value="{{ old('tonase_kpl', $kapal->tonase_kpl) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="tanda_panggilan_kpl" class="form-label fw-bold">Tanda
                                                    Panggilan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-broadcast-tower"></i></span>
                                                    <input type="text" class="form-control" id="tanda_panggilan_kpl"
                                                        name="tanda_panggilan_kpl"
                                                        value="{{ old('tanda_panggilan_kpl', $kapal->tanda_panggilan_kpl) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="awak_kpl" class="form-label fw-bold">Jumlah Awak</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    <input type="number" class="form-control" id="awak_kpl"
                                                        name="awak_kpl" value="{{ old('awak_kpl', $kapal->awak_kpl) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="penumpang_kpl" class="form-label fw-bold">Kapasitas
                                                    Penumpang</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-user-friends"></i></span>
                                                    <input type="number" class="form-control" id="penumpang_kpl"
                                                        name="penumpang_kpl"
                                                        value="{{ old('penumpang_kpl', $kapal->penumpang_kpl) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="thn_pbtn_kpl" class="form-label fw-bold">Tahun
                                                    Pembuatan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-calendar-alt"></i></span>
                                                    <input type="number" class="form-control" id="thn_pbtn_kpl"
                                                        name="thn_pbtn_kpl"
                                                        value="{{ old('thn_pbtn_kpl', $kapal->thn_pbtn_kpl) }}"
                                                        min="1900" max="{{ date('Y') }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="asal_pbtn_kpl" class="form-label fw-bold">Asal
                                                    Pembuatan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                                    <input type="text" class="form-control" id="asal_pbtn_kpl"
                                                        name="asal_pbtn_kpl"
                                                        value="{{ old('asal_pbtn_kpl', $kapal->asal_pbtn_kpl) }}">
                                                </div>
                                            </div>
                                        </div>
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
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation with visual feedback
            const form = document.getElementById('kapalForm');
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
