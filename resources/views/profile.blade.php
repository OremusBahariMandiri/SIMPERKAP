@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Alert Success -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-user me-2"></i>Profil Pengguna</span>
                    <div>
                        <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-1"></i>Ubah Password
                        </button>
                        <button type="button" class="btn btn-light btn-sm" onclick="toggleEditMode()">
                            <i class="fas fa-edit me-1"></i>Edit Profil
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card border-secondary mb-3">
                            <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pengguna</h5>
                            </div>
                            <div class="card-body">
                                <div class="info-group mb-3">
                                    <label class="info-label fw-bold">NIK Karyawan</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <div class="form-control">{{ $user->nik_kry }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label fw-bold">Nama Karyawan</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" name="nama_kry" class="form-control editable-field"
                                                   value="{{ old('nama_kry', $user->nama_kry) }}"
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>
                                        @error('nama_kry')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label fw-bold">Departemen</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            <input type="text" name="departemen_kry" class="form-control editable-field"
                                                   value="{{ old('departemen_kry', $user->departemen_kry) }}"
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>
                                        @error('departemen_kry')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label fw-bold">Jabatan</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                            <input type="text" name="jabatan_kry" class="form-control editable-field"
                                                   value="{{ old('jabatan_kry', $user->jabatan_kry) }}"
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>
                                        @error('jabatan_kry')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label fw-bold">Wilayah Kerja</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" name="wilker_kry" class="form-control editable-field"
                                                   value="{{ old('wilker_kry', $user->wilker_kry) }}"
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>
                                        @error('wilker_kry')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label fw-bold">Status Admin</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                            <div class="form-control">
                                                @if($user->is_admin)
                                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Administrator</span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="fas fa-user me-1"></i>User Biasa</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="info-label fw-bold">Bergabung pada</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                            <div class="form-control">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>

                                @if($user->updated_at != $user->created_at)
                                <div class="info-group mb-3">
                                    <label class="info-label fw-bold">Diperbarui pada</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-edit"></i></span>
                                            <div class="form-control">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="d-none" id="saveButtons">
                                    <hr>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-2" onclick="cancelEdit()">
                                            <i class="fas fa-times me-1"></i>Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fas fa-key me-2"></i>Ubah Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="current_password_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye" id="new_password_icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="new_password_error"></div>
                        <small class="form-text text-muted">Password minimal 8 karakter.</small>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="new_password_confirmation_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Ubah Password
                    </button>
                </div>
            </form>
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
    .editable-field[readonly] {
        background-color: #f8f9fa !important;
        cursor: not-allowed;
    }
    .editable-field:not([readonly]) {
        background-color: white !important;
        cursor: text;
    }
    .modal-header.bg-warning {
        color: #212529;
    }
    .invalid-feedback {
        display: block;
    }
    .form-control.is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    let editMode = false;
    let originalValues = {};

    function toggleEditMode() {
        editMode = !editMode;
        const editableFields = document.querySelectorAll('.editable-field');
        const saveButtons = document.getElementById('saveButtons');
        const editButton = document.querySelector('[onclick="toggleEditMode()"]');

        if (editMode) {
            // Store original values
            editableFields.forEach(field => {
                originalValues[field.name] = field.value;
                field.removeAttribute('readonly');
                field.style.backgroundColor = 'white';
                field.style.cursor = 'text';
            });
            saveButtons.classList.remove('d-none');
            editButton.innerHTML = '<i class="fas fa-times me-1"></i>Batal';
        } else {
            cancelEdit();
        }
    }

    function cancelEdit() {
        editMode = false;
        const editableFields = document.querySelectorAll('.editable-field');
        const saveButtons = document.getElementById('saveButtons');
        const editButton = document.querySelector('[onclick="toggleEditMode()"]');

        // Restore original values
        editableFields.forEach(field => {
            field.value = originalValues[field.name] || field.value;
            field.setAttribute('readonly', true);
            field.style.backgroundColor = '#f8f9fa';
            field.style.cursor = 'not-allowed';
        });

        saveButtons.classList.add('d-none');
        editButton.innerHTML = '<i class="fas fa-edit me-1"></i>Edit Profil';

        // Clear validation errors
        document.querySelectorAll('.text-danger').forEach(error => {
            error.remove();
        });
    }

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Handle password change form
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.form-control').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.textContent = '';
        });

        const formData = new FormData(this);

        fetch('{{ route("profile.password") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                modal.hide();

                // Reset form
                document.getElementById('changePasswordForm').reset();

                // Show success message
                const alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                document.querySelector('.container .row .col-md-8').insertAdjacentHTML('afterbegin', alertHtml);
            } else {
                // Show validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const fieldElement = document.getElementById(field);
                        const errorElement = document.getElementById(field + '_error');

                        if (fieldElement && errorElement) {
                            fieldElement.classList.add('is-invalid');
                            errorElement.textContent = data.errors[field][0];
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    });

    // Reset modal when closed
    document.getElementById('changePasswordModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('changePasswordForm').reset();
        document.querySelectorAll('.form-control').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.textContent = '';
        });
        // Reset password visibility
        document.querySelectorAll('input[type="text"]').forEach(field => {
            if (field.name && field.name.includes('password')) {
                field.type = 'password';
            }
        });
        document.querySelectorAll('.fa-eye-slash').forEach(icon => {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        });
    });
</script>
@endpush