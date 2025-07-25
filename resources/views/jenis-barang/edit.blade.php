@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-box me-2"></i>Edit Jenis Barang</span>
                        <a href="{{ route('jenis-barang.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('jenis-barang.update', $jenisBarang->id) }}" method="POST"
                            id="jenisBarangForm">
                            @csrf
                            @method('PUT')

                            <div class="card border-secondary mb-4">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Jenis Barang</h5>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control" id="id_kode_display"
                                        value="{{ $jenisBarang->id_kode }}" hidden disabled>

                                    <div class="form-group mb-3">
                                        <label for="jenis_brg" class="form-label fw-bold">Jenis Barang <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                            <input type="text" class="form-control" id="jenis_brg" name="jenis_brg"
                                                value="{{ old('jenis_brg', $jenisBarang->jenis_brg) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="ket_brg" class="form-label fw-bold">Keterangan Barang</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-box-open"></i></span>
                                            <textarea type="text" class="form-control" id="ket_brg" name="ket_brg" >{{ old('ket_brg', $jenisBarang->ket_brg) }}</textarea>
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
            const form = document.getElementById('jenisBarangForm');
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
