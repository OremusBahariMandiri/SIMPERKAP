@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-cubes me-2"></i>Detail Inventaris Kapal</span>
                    <div>
                        <a href="{{ route('inventaris-kapal.edit', $inventarisKapal->id_kode) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('inventaris-kapal.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card border-secondary mb-4">
                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Umum</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="info-label fw-bold">Kapal</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-ship"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->kapal->nama_kpl ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="info-label fw-bold">Nama Barang</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-cube"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->namaBarang->nama_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="info-label fw-bold">Kategori Barang</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->kategoriBarang->kategori_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="info-label fw-bold">Jenis Barang</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->jenisBarang->jenis_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="info-label fw-bold">Golongan Barang</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->golonganBarang->golongan_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="info-label fw-bold">Kode Barang</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->no_kode_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="info-label fw-bold">Kode Barang Substitusi</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-random"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->no_kode_brg_subtitusi ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
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
                                    <label class="info-label fw-bold">Tipe/Model</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->tipe_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="info-label fw-bold">Merek</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-trademark"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->merek_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="info-label fw-bold">Spesifikasi</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                        <div class="form-control bg-light" style="min-height: 80px; white-space: pre-line;">
                                            {{ $inventarisKapal->spesifikasi_brg ?? 'Tidak ada data' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="info-label fw-bold">Satuan</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->satuan_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="info-label fw-bold">Supplier</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->supplier_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="info-label fw-bold">Lokasi</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->lokasi_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
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
                                    <label class="info-label fw-bold">Tanggal Pengadaan</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <div class="form-control bg-light">
                                                @if($inventarisKapal->tgl_pengadaan_brg)
                                                    {{ \Carbon\Carbon::parse($inventarisKapal->tgl_pengadaan_brg)->format('d/m/Y') }}
                                                @else
                                                    Tidak ada data
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="info-label fw-bold">Nomor Pengadaan</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->no_pengadaan_brg ?? 'Tidak ada data' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($inventarisKapal->file_dok)
                                <div class="mb-3">
                                    <label class="info-label fw-bold">Dokumen Pendukung</label>
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="badge bg-info text-dark me-2">
                                            <i class="fas fa-file me-1"></i> {{ basename($inventarisKapal->file_dok) }}
                                        </span>
                                        <a href="{{ Storage::url($inventarisKapal->file_dok) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i> Lihat File
                                        </a>
                                        <a href="{{ Storage::url($inventarisKapal->file_dok) }}" download class="btn btn-sm btn-success ms-2">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card border-secondary mb-4">
                        <div class="card-header bg-secondary bg-opacity-25 text-dark">
                            <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Informasi Stok</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="info-label fw-bold">Stok Awal</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->stock_awal ?? '0' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="info-label fw-bold">Stok Masuk</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-arrow-circle-down"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->stock_masuk ?? '0' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="info-label fw-bold">Stok Keluar</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-arrow-circle-up"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->stock_keluar ?? '0' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="info-label fw-bold">Batas Stok Min.</label>
                                    <div class="info-value">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-exclamation-triangle"></i></span>
                                            <div class="form-control bg-light">
                                                {{ $inventarisKapal->stock_limit ?? '0' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="info-label fw-bold">Stok Akhir</label>
                                <div class="info-value">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                        <div class="form-control
                                            @if($inventarisKapal->stock_akhir <= $inventarisKapal->stock_limit)
                                                bg-danger bg-opacity-25
                                            @else
                                                bg-success bg-opacity-25
                                            @endif">
                                            <span class="fw-bold">{{ $inventarisKapal->stock_akhir ?? '0' }}</span>
                                            @if($inventarisKapal->stock_akhir <= $inventarisKapal->stock_limit)
                                                <span class="ms-2 badge bg-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i> Stok dibawah batas minimum
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($inventarisKapal->keterangan_brg)
                        <div class="card border-secondary mb-4">
                            <div class="card-header bg-secondary bg-opacity-25 text-dark">
                                <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Keterangan Tambahan</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                        <div class="form-control bg-light" style="min-height: 80px; white-space: pre-line;">
                                            {{ $inventarisKapal->keterangan_brg }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data <span id="delete-item-name" class="fw-bold"></span>?</p>
                    <p class="text-danger mb-0"><small>Data yang dihapus tidak dapat dikembalikan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete confirmation
        $('.delete-confirm').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var url = $(this).data('url');

            $('#delete-item-name').text(name);
            $('#delete-form').attr('action', url);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endpush