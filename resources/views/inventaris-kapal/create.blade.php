@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Inventaris Kapal</span>
                        <a href="{{ route('inventaris-kapal.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('inventaris-kapal.store') }}" method="POST" enctype="multipart/form-data"
                            id="inventarisKapalForm">
                            @csrf

                            <div class="card border-secondary mb-4">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Umum</h5>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control" id="id_kode" name="id_kode"
                                        value="{{ old('id_kode', $newId) }}" hidden readonly>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="id_kode_05" class="form-label fw-bold">Kapal <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                                <select class="form-select" id="id_kode_05" name="id_kode_05" required>
                                                    <option value="">-- Pilih Kapal --</option>
                                                    @foreach ($kapal as $item)
                                                        <option value="{{ $item->id_kode }}"
                                                            {{ old('id_kode_05') == $item->id_kode ? 'selected' : '' }}>
                                                            {{ $item->nama_kpl }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="id_kode_11" class="form-label fw-bold">Nama Barang <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-cube"></i></span>
                                                <select class="form-select" id="id_kode_11" name="id_kode_11" required>
                                                    <option value="">-- Pilih Nama Barang --</option>
                                                    @foreach ($namaBarang as $item)
                                                        <option value="{{ $item->id_kode }}"
                                                            {{ old('id_kode_11') == $item->id_kode ? 'selected' : '' }}>
                                                            {{ $item->nama_brg }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="kategori_brg" class="form-label fw-bold">Kategori Barang <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                                <input type="text" class="form-control bg-light" id="kategori_brg"
                                                    readonly>
                                                <input type="hidden" id="id_kode_08" name="id_kode_08"
                                                    value="{{ old('id_kode_08') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="jenis_brg" class="form-label fw-bold">Jenis Barang <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                <input type="text" class="form-control bg-light" id="jenis_brg" readonly>
                                                <input type="hidden" id="id_kode_09" name="id_kode_09"
                                                    value="{{ old('id_kode_09') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="golongan_brg" class="form-label fw-bold">Golongan Barang <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                                <input type="text" class="form-control bg-light" id="golongan_brg"
                                                    readonly>
                                                <input type="hidden" id="id_kode_10" name="id_kode_10"
                                                    value="{{ old('id_kode_10') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="no_kode_brg" class="form-label fw-bold">Kode Barang</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                <input type="text" class="form-control" id="no_kode_brg"
                                                    name="no_kode_brg" value="{{ old('no_kode_brg') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="no_kode_brg_subtitusi" class="form-label fw-bold">Kode Barang
                                                Substitusi</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-random"></i></span>
                                                <input type="text" class="form-control" id="no_kode_brg_subtitusi"
                                                    name="no_kode_brg_subtitusi"
                                                    value="{{ old('no_kode_brg_subtitusi') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-secondary mb-4">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Spesifikasi Barang</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="tipe_brg" class="form-label fw-bold">Tipe/Model</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                <input type="text" class="form-control" id="tipe_brg"
                                                    name="tipe_brg" value="{{ old('tipe_brg') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="merek_brg" class="form-label fw-bold">Merek</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-trademark"></i></span>
                                                <input type="text" class="form-control" id="merek_brg"
                                                    name="merek_brg" value="{{ old('merek_brg') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="spesifikasi_brg" class="form-label fw-bold">Spesifikasi</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                            <textarea class="form-control" id="spesifikasi_brg" name="spesifikasi_brg" rows="3">{{ old('spesifikasi_brg') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="satuan_brg" class="form-label fw-bold">Satuan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                                <input type="text" class="form-control" id="satuan_brg"
                                                    name="satuan_brg" value="{{ old('satuan_brg') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="supplier_brg" class="form-label fw-bold">Supplier</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                                <input type="text" class="form-control" id="supplier_brg"
                                                    name="supplier_brg" value="{{ old('supplier_brg') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="lokasi_brg" class="form-label fw-bold">Lokasi</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-map-marker-alt"></i></span>
                                                <input type="text" class="form-control" id="lokasi_brg"
                                                    name="lokasi_brg" value="{{ old('lokasi_brg') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-secondary mb-4">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Informasi Pengadaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="tgl_pengadaan_brg" class="form-label fw-bold">Tanggal
                                                Pengadaan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                <input type="date" class="form-control" id="tgl_pengadaan_brg"
                                                    name="tgl_pengadaan_brg" value="{{ old('tgl_pengadaan_brg') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="no_pengadaan_brg" class="form-label fw-bold">Nomor
                                                Pengadaan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                                <input type="text" class="form-control" id="no_pengadaan_brg"
                                                    name="no_pengadaan_brg" value="{{ old('no_pengadaan_brg') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="file_dok" class="form-label fw-bold">Dokumen Pendukung</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-upload"></i></span>
                                            <input type="file" class="form-control" id="file_dok" name="file_dok">
                                        </div>
                                        <div class="form-text">Format file: JPG, PNG, PDF, DOC, DOCX. Maksimal 2MB.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-secondary mb-4">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Informasi Stok</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="stock_awal" class="form-label fw-bold">Stok Awal</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-box"></i></span>
                                                <input type="number" class="form-control" id="stock_awal"
                                                    name="stock_awal" value="{{ old('stock_awal', 0) }}" min="0"
                                                    step="1">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="stock_masuk" class="form-label fw-bold">Stok Masuk</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-arrow-circle-down"></i></span>
                                                <input type="number" class="form-control" id="stock_masuk"
                                                    name="stock_masuk" value="{{ old('stock_masuk', 0) }}"
                                                    min="0" step="1">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="stock_keluar" class="form-label fw-bold">Stok Keluar</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-arrow-circle-up"></i></span>
                                                <input type="number" class="form-control" id="stock_keluar"
                                                    name="stock_keluar" value="{{ old('stock_keluar', 0) }}"
                                                    min="0" step="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="stock_limit" class="form-label fw-bold">Batas Stok Minimum</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-exclamation-triangle"></i></span>
                                                <input type="number" class="form-control" id="stock_limit"
                                                    name="stock_limit" value="{{ old('stock_limit', 0) }}"
                                                    min="0" step="1">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="stock_akhir" class="form-label fw-bold">Stok Akhir</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                                <input type="number" class="form-control bg-light" id="stock_akhir"
                                                    name="stock_akhir" value="{{ old('stock_akhir', 0) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-secondary mb-4">
                                <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                    <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Keterangan Tambahan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="keterangan_brg" class="form-label fw-bold">Keterangan</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                            <textarea class="form-control" id="keterangan_brg" name="keterangan_brg" rows="3"
                                                placeholder="Masukkan keterangan tambahan">{{ old('keterangan_brg') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 col-md-4 mx-auto mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .text-danger {
            font-weight: bold;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .loading {
            position: relative;
            cursor: wait;
            opacity: 0.8;
        }

        .loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: calc(50% - 10px);
            right: 10px;
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: #0d6efd;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        select:disabled,
        input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial stock calculation
            calculateStock();

            // Add event listeners for all stock-related fields
            document.getElementById('stock_awal').addEventListener('change', calculateStock);
            document.getElementById('stock_awal').addEventListener('input', calculateStock);
            document.getElementById('stock_masuk').addEventListener('change', calculateStock);
            document.getElementById('stock_masuk').addEventListener('input', calculateStock);
            document.getElementById('stock_keluar').addEventListener('change', calculateStock);
            document.getElementById('stock_keluar').addEventListener('input', calculateStock);
            document.getElementById('stock_limit').addEventListener('change', calculateStock);
            document.getElementById('stock_limit').addEventListener('input', calculateStock);

            // Setup nama barang change event
            setupNamaBarangEvent();

            // Form validation with visual feedback
            const form = document.getElementById('inventarisKapalForm');
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

            // Check if nama barang already has a value on page load (for edit form or form with old values)
            const namaBarangSelect = document.getElementById('id_kode_11');
            if (namaBarangSelect.value) {
                // Trigger the change event to load related data
                const event = new Event('change');
                namaBarangSelect.dispatchEvent(event);
            }
        });

        function setupNamaBarangEvent() {
            const namaBarangSelect = document.getElementById('id_kode_11');

            // Add event listener for change on nama barang select
            namaBarangSelect.addEventListener('change', function() {
                if (this.value) {
                    // Find the selected NamaBarang's data
                    fetchNamaBarangData(this.value);
                } else {
                    // Reset the fields
                    resetFields();
                }
            });

            function fetchNamaBarangData(namaBarangId) {
                // Show loading indicator
                showLoading(true);

                // Use fetch API to get data
                fetch(`/get-nama-barang-data/${namaBarangId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data from server:', data); // Debugging

                        if (data && data.success) {
                            updateFields(data.namaBarang);
                        } else {
                            console.error('Error fetching data:', data.message || 'Unknown error');
                            resetFields();
                        }
                        showLoading(false);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resetFields();
                        showLoading(false);
                    });
            }

            function updateFields(namaBarang) {
                try {
                    // Set hidden inputs for form submission
                    if (namaBarang.id_kode_a08) {
                        document.getElementById('id_kode_08').value = namaBarang.id_kode_a08;

                        // Display the category name in the text field
                        if (namaBarang.kategori_barang && namaBarang.kategori_barang.kategori_brg) {
                            document.getElementById('kategori_brg').value = namaBarang.kategori_barang.kategori_brg;
                        } else if (namaBarang.kategoriBarang && namaBarang.kategoriBarang.kategori_brg) {
                            document.getElementById('kategori_brg').value = namaBarang.kategoriBarang.kategori_brg;
                        }
                    }

                    if (namaBarang.id_kode_a09) {
                        document.getElementById('id_kode_09').value = namaBarang.id_kode_a09;

                        // Display the type name
                        if (namaBarang.jenis_barang && namaBarang.jenis_barang.jenis_brg) {
                            document.getElementById('jenis_brg').value = namaBarang.jenis_barang.jenis_brg;
                        } else if (namaBarang.jenisBarang && namaBarang.jenisBarang.jenis_brg) {
                            document.getElementById('jenis_brg').value = namaBarang.jenisBarang.jenis_brg;
                        }
                    }

                    if (namaBarang.id_kode_a10) {
                        document.getElementById('id_kode_10').value = namaBarang.id_kode_a10;

                        // Display the group name - check all possible property names
                        if (namaBarang.golongan_barang && namaBarang.golongan_barang.golongan_brg) {
                            document.getElementById('golongan_brg').value = namaBarang.golongan_barang.golongan_brg;
                        } else if (namaBarang.golonganBarang && namaBarang.golonganBarang.golongan_brg) {
                            document.getElementById('golongan_brg').value = namaBarang.golonganBarang.golongan_brg;
                        } else if (namaBarang.golonganbarang && namaBarang.golonganbarang.golongan_brg) {
                            document.getElementById('golongan_brg').value = namaBarang.golonganbarang.golongan_brg;
                        }
                    }
                } catch (error) {
                    console.error('Error updating fields:', error);
                    resetFields();
                }
            }

            function resetFields() {
                // Reset text fields
                document.getElementById('kategori_brg').value = '';
                document.getElementById('jenis_brg').value = '';
                document.getElementById('golongan_brg').value = '';

                // Reset hidden inputs
                document.getElementById('id_kode_08').value = '';
                document.getElementById('id_kode_09').value = '';
                document.getElementById('id_kode_10').value = '';
            }

            function showLoading(isLoading) {
                if (isLoading) {
                    namaBarangSelect.classList.add('loading');
                } else {
                    namaBarangSelect.classList.remove('loading');
                }
            }
        }

        function calculateStock() {
            const stockAwal = parseInt(document.getElementById('stock_awal').value) || 0;
            const stockMasuk = parseInt(document.getElementById('stock_masuk').value) || 0;
            const stockKeluar = parseInt(document.getElementById('stock_keluar').value) || 0;
            const stockLimit = parseInt(document.getElementById('stock_limit').value) || 0;

            const stockAkhir = stockAwal + stockMasuk - stockKeluar;
            document.getElementById('stock_akhir').value = stockAkhir >= 0 ? stockAkhir : 0;

            // Visual feedback for stock status
            updateStockVisualFeedback(stockAkhir >= 0 ? stockAkhir : 0, stockLimit);
        }

        function updateStockVisualFeedback(stockAkhir, stockLimit) {
            const stockAkhirInput = document.getElementById('stock_akhir');

            if (stockAkhir <= stockLimit) {
                stockAkhirInput.classList.add('bg-danger', 'bg-opacity-25');
                stockAkhirInput.classList.remove('bg-light', 'bg-success', 'bg-opacity-25');
            } else if (stockAkhir > 0) {
                stockAkhirInput.classList.add('bg-success', 'bg-opacity-25');
                stockAkhirInput.classList.remove('bg-light', 'bg-danger', 'bg-opacity-25');
            } else {
                stockAkhirInput.classList.add('bg-light');
                stockAkhirInput.classList.remove('bg-success', 'bg-danger', 'bg-opacity-25');
            }
        }
    </script>
@endpush