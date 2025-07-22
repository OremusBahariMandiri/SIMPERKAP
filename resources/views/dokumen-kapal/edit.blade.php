@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Edit Dokumen Kapal</span>
                        <a href="{{ route('dokumen-kapal.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('dokumen-kapal.update', $dokumenKapal->id) }}" method="POST"
                            id="dokumenKapalForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Grouped form sections with cards -->
                            <div class="row g-4">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dokumen</h5>
                                        </div>
                                        <div class="card-body">
                                            <input type="text" class="form-control" id="id_kode" name="id_kode"
                                                value="{{ $dokumenKapal->id_kode }}" hidden readonly>

                                            <div class="form-group mb-3">
                                                <label for="no_reg_display" class="form-label fw-bold">Nomor Registrasi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                    <input type="text" class="form-control" id="no_reg_display"
                                                        value="{{ $dokumenKapal->no_reg }}" readonly disabled>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="nama_kpl" class="form-label fw-bold">Kapal <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                                    <select class="form-select @error('nama_kpl') is-invalid @enderror"
                                                        id="nama_kpl" name="nama_kpl" required>
                                                        <option value="">-- Pilih Kapal --</option>
                                                        @foreach ($kapal as $ship)
                                                            <option value="{{ $ship->id_kode }}"
                                                                {{ old('nama_kpl', $dokumenKapal->nama_kpl) == $ship->id_kode ? 'selected' : '' }}>
                                                                {{ $ship->nama_kpl }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('nama_kpl')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="kategori_dok" class="form-label fw-bold">Kategori Dokumen <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-folder"></i></span>
                                                    <select class="form-select @error('kategori_dok') is-invalid @enderror"
                                                        id="kategori_dok" name="kategori_dok" required>
                                                        <option value="">-- Pilih Kategori --</option>
                                                        @foreach ($kategoriDokumen as $kategori)
                                                            <option value="{{ $kategori->id_kode }}"
                                                                {{ old('kategori_dok', $dokumenKapal->kategori_dok) == $kategori->id_kode ? 'selected' : '' }}>
                                                                {{ $kategori->kategori_dok }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('kategori_dok')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="nama_dok" class="form-label fw-bold">Nama Dokumen <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                    <select class="form-select @error('nama_dok') is-invalid @enderror"
                                                        id="nama_dok" name="nama_dok" required>
                                                        <option value="">-- Pilih Nama Dokumen --</option>
                                                        @foreach ($namaDokumen as $dokumen)
                                                            <option value="{{ $dokumen->id_kode }}"
                                                                {{ old('nama_dok', $dokumenKapal->nama_dok) == $dokumen->id_kode ? 'selected' : '' }}>
                                                                {{ $dokumen->nama_dok }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('nama_dok')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="penerbit_dok" class="form-label fw-bold">Penerbit Dokumen</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text" class="form-control @error('penerbit_dok') is-invalid @enderror"
                                                        id="penerbit_dok" name="penerbit_dok"
                                                        value="{{ old('penerbit_dok', $dokumenKapal->penerbit_dok) }}"
                                                        placeholder="Masukkan penerbit dokumen">
                                                    @error('penerbit_dok')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="validasi_dok" class="form-label fw-bold">Peruntukan Dokumen</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                    <input type="text" class="form-control @error('validasi_dok') is-invalid @enderror"
                                                        id="validasi_dok" name="validasi_dok"
                                                        value="{{ old('validasi_dok', $dokumenKapal->validasi_dok) }}"
                                                        placeholder="Masukkan peruntukan dokumen">
                                                    @error('validasi_dok')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Validity and Date Information -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Masa Berlaku & Tanggal</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-bold">Jenis Masa Berlaku <span class="text-danger">*</span></label>
                                                <div class="bg-light p-2 rounded">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input jenis-masa-berlaku @error('jenis_masa_berlaku') is-invalid @enderror"
                                                            type="radio" name="jenis_masa_berlaku" id="tetap" value="Tetap"
                                                            {{ old('jenis_masa_berlaku', $dokumenKapal->tgl_berakhir_dok ? 'Perpanjangan' : 'Tetap') == 'Tetap' ? 'checked' : '' }} required>
                                                        <label class="form-check-label" for="tetap">
                                                            <i class="fas fa-infinity text-primary me-1"></i>Tetap
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input jenis-masa-berlaku @error('jenis_masa_berlaku') is-invalid @enderror"
                                                            type="radio" name="jenis_masa_berlaku" id="perpanjangan" value="Perpanjangan"
                                                            {{ old('jenis_masa_berlaku', $dokumenKapal->tgl_berakhir_dok ? 'Perpanjangan' : 'Tetap') == 'Perpanjangan' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perpanjangan">
                                                            <i class="fas fa-sync text-success me-1"></i>Perpanjangan
                                                        </label>
                                                    </div>
                                                    @error('jenis_masa_berlaku')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_terbit_dok" class="form-label fw-bold">Tanggal Terbit <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                                            <input type="date" class="form-control tanggal-input @error('tgl_terbit_dok') is-invalid @enderror"
                                                                id="tgl_terbit_dok" name="tgl_terbit_dok"
                                                                value="{{ old('tgl_terbit_dok', $dokumenKapal->tgl_terbit_dok ? date('Y-m-d', strtotime($dokumenKapal->tgl_terbit_dok)) : '') }}" required>
                                                            @error('tgl_terbit_dok')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3 tgl-berakhir-group">
                                                        <label for="tgl_berakhir_dok" class="form-label fw-bold">Tanggal Berakhir</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                                                            <input type="date" class="form-control tanggal-input @error('tgl_berakhir_dok') is-invalid @enderror"
                                                                id="tgl_berakhir_dok" name="tgl_berakhir_dok"
                                                                value="{{ old('tgl_berakhir_dok', $dokumenKapal->tgl_berakhir_dok ? date('Y-m-d', strtotime($dokumenKapal->tgl_berakhir_dok)) : '') }}">
                                                            @error('tgl_berakhir_dok')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="masa_berlaku" class="form-label fw-bold">Masa Berlaku</label>
                                                <input type="text" class="form-control bg-light" id="masa_berlaku"
                                                    name="masa_berlaku" value="{{ old('masa_berlaku', $dokumenKapal->masa_berlaku) }}" readonly>
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Masa berlaku akan dihitung otomatis
                                                </div>
                                            </div>

                                            <div class="form-group mb-3 tgl-peringatan-group">
                                                <label for="tgl_peringatan" class="form-label fw-bold">Tanggal Peringatan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                                    <input type="date" class="form-control tanggal-input @error('tgl_peringatan') is-invalid @enderror"
                                                        id="tgl_peringatan" name="tgl_peringatan"
                                                        value="{{ old('tgl_peringatan', $dokumenKapal->tgl_peringatan ? date('Y-m-d', strtotime($dokumenKapal->tgl_peringatan)) : '') }}">
                                                    @error('tgl_peringatan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="masa_peringatan" class="form-label fw-bold">Peringatan</label>
                                                <input type="text" class="form-control bg-light" id="masa_peringatan"
                                                    name="masa_peringatan" value="{{ old('masa_peringatan', $dokumenKapal->masa_peringatan) }}" readonly>
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Masa peringatan akan dihitung otomatis
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="card border-secondary mt-4">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="file_dok" class="form-label fw-bold">File Dokumen</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-file-upload"></i></span>
                                                    <input type="file" class="form-control @error('file_dok') is-invalid @enderror"
                                                        id="file_dok" name="file_dok">
                                                    @error('file_dok')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @if ($dokumenKapal->file_dok)
                                                    <div class="card mt-2 border-secondary">
                                                        <div class="card-body py-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-file me-2 text-secondary"></i>
                                                                <span class="me-2">{{ $dokumenKapal->file_dok }}</span>
                                                                <div class="btn-group ms-auto">
                                                                    <a href="{{ route('dokumen-kapal.viewDocument', $dokumenKapal->id) }}"
                                                                        class="btn btn-sm btn-secondary me-1" target="_blank">
                                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                                    </a>
                                                                    <a href="{{ route('dokumen-kapal.download', $dokumenKapal->id) }}"
                                                                        class="btn btn-sm btn-success">
                                                                        <i class="fas fa-download me-1"></i>Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Format: PDF, JPG, JPEG, PNG. Maksimal ukuran: 5MB
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group h-100">
                                                <label for="catatan" class="form-label fw-bold">Keterangan</label>
                                                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan"
                                                    name="catatan" rows="4" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('catatan', $dokumenKapal->catatan) }}</textarea>
                                                @error('catatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="status_dok" class="form-label fw-bold">Status Dokumen</label>
                                            <div class="bg-light p-2 rounded">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input @error('status_dok') is-invalid @enderror"
                                                        type="radio" name="status_dok" id="berlaku" value="Berlaku"
                                                        {{ old('status_dok', $dokumenKapal->status_dok) == 'Berlaku' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="berlaku">
                                                        <i class="fas fa-check-circle text-success me-1"></i>Berlaku
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input @error('status_dok') is-invalid @enderror"
                                                        type="radio" name="status_dok" id="tidak_berlaku" value="Tidak Berlaku"
                                                        {{ old('status_dok', $dokumenKapal->status_dok) == 'Tidak Berlaku' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berlaku">
                                                        <i class="fas fa-times-circle text-danger me-1"></i>Tidak Berlaku
                                                    </label>
                                                </div>
                                                @error('status_dok')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
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

        .invalid-feedback {
            display: block;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation with visual feedback
            const form = document.getElementById('dokumenKapalForm');
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

            // Fungsi untuk menghitung dan menampilkan masa berlaku
            function hitungMasaBerlaku() {
                const jenisMasaBerlaku = document.querySelector('input[name="jenis_masa_berlaku"]:checked').value;
                const tglTerbit = document.getElementById('tgl_terbit_dok').value;
                const tglBerakhir = document.getElementById('tgl_berakhir_dok').value;
                const masaBerlakuField = document.getElementById('masa_berlaku');

                if (jenisMasaBerlaku === 'Tetap') {
                    masaBerlakuField.value = 'Tetap';
                    return;
                }

                if (tglTerbit && tglBerakhir) {
                    const startDate = new Date(tglTerbit);
                    const endDate = new Date(tglBerakhir);

                    if (endDate < startDate) {
                        masaBerlakuField.value = 'Tanggal berakhir harus setelah tanggal terbit';
                        return;
                    }

                    // Hitung selisih tahun, bulan, dan hari
                    let years = endDate.getFullYear() - startDate.getFullYear();
                    let months = endDate.getMonth() - startDate.getMonth();
                    let days = endDate.getDate() - startDate.getDate();

                    if (days < 0) {
                        months--;
                        // Tambahkan hari dari bulan sebelumnya
                        const lastDayOfMonth = new Date(endDate.getFullYear(), endDate.getMonth(), 0).getDate();
                        days += lastDayOfMonth;
                    }

                    if (months < 0) {
                        years--;
                        months += 12;
                    }

                    let result = '';
                    if (years > 0) result += years + ' thn ';
                    if (months > 0) result += months + ' bln ';
                    if (days > 0) result += days + ' hri';

                    masaBerlakuField.value = result || '0 hri';
                } else {
                    masaBerlakuField.value = '';
                }
            }

            // Fungsi untuk menghitung dan menampilkan masa peringatan
            function hitungMasaPeringatan() {
                const tglPeringatan = document.getElementById('tgl_peringatan').value;
                const tglBerakhir = document.getElementById('tgl_berakhir_dok').value;
                const masaPengingatanField = document.getElementById('masa_peringatan');

                if (tglPeringatan && tglBerakhir) {
                    const reminderDate = new Date(tglPeringatan);
                    const expiryDate = new Date(tglBerakhir);

                    if (reminderDate > expiryDate) {
                        masaPengingatanField.value = 'Tanggal peringatan harus sebelum tanggal berakhir';
                        return;
                    }

                    // Hitung selisih tahun, bulan, dan hari
                    let years = expiryDate.getFullYear() - reminderDate.getFullYear();
                    let months = expiryDate.getMonth() - reminderDate.getMonth();
                    let days = expiryDate.getDate() - reminderDate.getDate();

                    if (days < 0) {
                        months--;
                        // Tambahkan hari dari bulan sebelumnya
                        const lastDayOfMonth = new Date(expiryDate.getFullYear(), expiryDate.getMonth(), 0).getDate();
                        days += lastDayOfMonth;
                    }

                    if (months < 0) {
                        years--;
                        months += 12;
                    }

                    let result = '';
                    if (years > 0) result += years + ' thn ';
                    if (months > 0) result += months + ' bln ';
                    if (days > 0) result += days + ' hri';

                    masaPengingatanField.value = result || '0 hri';
                } else {
                    masaPengingatanField.value = '-';
                }
            }

            // Fungsi untuk toggle visibilitas field berdasarkan jenis masa berlaku
            function toggleFieldsVisibility() {
                const jenisMasaBerlaku = document.querySelector('input[name="jenis_masa_berlaku"]:checked').value;
                const tglBerakhirGroup = document.querySelector('.tgl-berakhir-group');
                const tglPengingatanGroup = document.querySelector('.tgl-peringatan-group');
                const tglBerakhirInput = document.getElementById('tgl_berakhir_dok');
                const tglBerakhirLabel = tglBerakhirGroup.querySelector('.form-label');
                const tglPengingatanInput = document.getElementById('tgl_peringatan');

                if (jenisMasaBerlaku === 'Tetap') {
                    tglBerakhirGroup.style.display = 'none';
                    tglPengingatanGroup.style.display = 'none';
                    document.getElementById('tgl_berakhir_dok').value = '';
                    document.getElementById('tgl_peringatan').value = '';
                    document.getElementById('masa_berlaku').value = 'Tetap';
                    document.getElementById('masa_peringatan').value = '-';

                    // Hapus atribut required
                    tglBerakhirInput.removeAttribute('required');
                    tglPengingatanInput.removeAttribute('required');
                } else {
                    tglBerakhirGroup.style.display = 'block';
                    tglPengingatanGroup.style.display = 'block';

                    // Tambahkan tanda wajib diisi pada label
                    if (!tglBerakhirLabel.innerHTML.includes('*')) {
                        tglBerakhirLabel.innerHTML = tglBerakhirLabel.innerHTML +
                            ' <span class="text-danger">*</span>';
                    }

                    // Tambahkan atribut required
                    tglBerakhirInput.setAttribute('required', 'required');

                    hitungMasaBerlaku();
                    hitungMasaPeringatan();
                }
            }

            // Tambahkan event listener untuk jenis masa berlaku
            document.querySelectorAll('.jenis-masa-berlaku').forEach(function(radio) {
                radio.addEventListener('change', toggleFieldsVisibility);
            });

            // Tambahkan event listener untuk tanggal
            document.querySelectorAll('.tanggal-input').forEach(function(input) {
                input.addEventListener('change', function() {
                    if (this.id === 'tgl_terbit_dok' || this.id === 'tgl_berakhir_dok') {
                        hitungMasaBerlaku();
                    }
                    if (this.id === 'tgl_peringatan' || this.id === 'tgl_berakhir_dok') {
                        hitungMasaPeringatan();
                    }
                });
            });

            // Initialize form state
            toggleFieldsVisibility();

            // Hitung masa peringatan awal jika field sudah memiliki nilai
            if (document.getElementById('tgl_peringatan').value && document.getElementById('tgl_berakhir_dok').value) {
                hitungMasaPeringatan();
            }
        });
    </script>
@endpush