@extends('layouts.app')

@section('content')
    <div class="container-fluid dokumenKapalPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Manajemen Dokumen Kapal</span>
                        <div>
                            <button type="button" class="btn btn-light me-2" id="filterButton">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <button type="button" class="btn btn-light me-2" id="exportButton">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                            <a href="{{ route('dokumen-kapal.create') }}" class="btn btn-light">
                                <i class="fas fa-plus-circle me-1"></i> Tambah
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-3 document-status-summary">
                            <span id="expiredDocsBadge" class="badge bg-danger me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle me-1"></i> Dokumen Expired : <span
                                    id="expiredDocsCount">0</span>
                            </span>
                            <span id="warningDocsBadge" class="badge text-dark me-2"
                                style="font-size: 0.9rem; background-color:#ffff66">
                                <i class="fas fa-exclamation-triangle me-1"></i>Dokumen Akan Expired : <span
                                    id="warningDocsCount">0</span>
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table id="dokumenKapalTable" class="table table-bordered table-striped data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">No. Reg</th>
                                        <th>Nama Kapal</th>
                                        <th>Kategori</th>
                                        <th>Nama Dokumen</th>
                                        <th>Tgl Terbit</th>
                                        <th>Tgl Berakhir</th>
                                        <th>Tgl Peringatan</th>
                                        <th>Peringatan</th>
                                        <th width="8%" class="text-center">Status</th>
                                        <th width="8%" class="text-center">Dokumen</th>
                                        <th class="text-center" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dokumenKapal as $index => $dokumen)
                                        <tr
                                            data-tgl-peringatan="{{ $dokumen->tgl_peringatan ? \Carbon\Carbon::parse($dokumen->tgl_peringatan)->format('Y-m-d') : '' }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $dokumen->no_reg }}</td>
                                            <td>{{ $dokumen->kapal ? $dokumen->kapal->nama_kpl : '-' }}</td>
                                            <td>{{ $dokumen->kategoriDokumen ? $dokumen->kategoriDokumen->kategori_dok : '-' }}
                                            </td>
                                            <td>{{ $dokumen->namaDokumen ? $dokumen->namaDokumen->nama_dok : '-' }}</td>
                                            <td>
                                                @if ($dokumen->tgl_terbit_dok)
                                                    {{ \Carbon\Carbon::parse($dokumen->tgl_terbit_dok)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dokumen->tgl_berakhir_dok)
                                                    {{ \Carbon\Carbon::parse($dokumen->tgl_berakhir_dok)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dokumen->tgl_peringatan)
                                                    {{ \Carbon\Carbon::parse($dokumen->tgl_peringatan)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="sisa-peringatan-col">
                                                @if ($dokumen->masa_peringatan)
                                                    {{ $dokumen->masa_peringatan }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dokumen->status_dok == 'Berlaku')
                                                    <span class="badge" style="background-color: blue">Berlaku</span>
                                                @elseif ($dokumen->status_dok == 'Tidak Berlaku')
                                                    <span class="badge bg-danger">Tidak Berlaku</span>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $dokumen->no_reg }}</td> --}}
                                            <td class="text-center">
                                                @if ($dokumen->file_dok)
                                                    <div class="btn-group">
                                                        <a href="{{ route('dokumen-kapal.viewDocument', $dokumen->id) }}"
                                                            target="_blank" class="btn btn-sm btn-info"
                                                            data-bs-toggle="tooltip" title="Lihat Dokumen">
                                                            <i class="fas fa-eye"></i> Lihat
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('dokumen-kapal.show', $dokumen->id) }}"
                                                        class="btn btn-sm text-white" data-bs-toggle="tooltip"
                                                        title="Detail" style="background-color: #4a90e2;">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('dokumen-kapal.edit', $dokumen->id) }}"
                                                        class="btn btn-sm text-white" data-bs-toggle="tooltip"
                                                        title="Edit" style="background-color: #8e44ad;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    @if ($dokumen->file_dok)
                                                        <a href="{{ route('dokumen-kapal.download', $dokumen->id) }}"
                                                            class="btn btn-sm text-white" data-bs-toggle="tooltip"
                                                            title="Download" style="background-color: #2980b9;">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif

                                                    <button type="button" class="btn btn-sm text-white delete-confirm"
                                                        data-bs-toggle="tooltip" title="Hapus"
                                                        style="background-color: #f700ff;" data-id="{{ $dokumen->id }}"
                                                        data-name="Dokumen {{ $dokumen->namaDokumen ? $dokumen->namaDokumen->nama_dok : 'No.' . $dokumen->no_reg }}"
                                                        data-url="{{ route('dokumen-kapal.destroy', $dokumen->id) }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fas fa-filter me-2"></i>Filter Dokumen Kapal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="filter_noreg" class="form-label">Nomor Registrasi</label>
                                    <input type="text" class="form-control" id="filter_noreg"
                                        placeholder="Masukan No Registrasi">
                                </div>

                                <div class="mb-3">
                                    <label for="filter_kapal" class="form-label">Kapal</label>
                                    <select class="form-select" id="filter_kapal">
                                        <option value="">Semua Kapal</option>
                                        @foreach ($dokumenKapal->pluck('kapal.nama_kpl')->unique() as $namaKapal)
                                            @if ($namaKapal)
                                                <option value="{{ $namaKapal }}">{{ $namaKapal }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="filter_kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="filter_kategori">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($dokumenKapal->pluck('kategoriDokumen.kategori_dok')->unique() as $kategori)
                                            @if ($kategori)
                                                <option value="{{ $kategori }}">{{ $kategori }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="filter_nama_dok" class="form-label">Nama Dokumen</label>
                                    <select class="form-select" id="filter_nama_dok">
                                        <option value="">Semua Dokumen</option>
                                        @foreach ($dokumenKapal->pluck('namaDokumen.nama_dok')->unique() as $namaDok)
                                            @if ($namaDok)
                                                <option value="{{ $namaDok }}">{{ $namaDok }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="filter_tgl_terbit" class="form-label">Tanggal Terbit</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="filter_tgl_terbit_from"
                                            placeholder="Dari">
                                        <span class="input-group-text">s/d</span>
                                        <input type="date" class="form-control" id="filter_tgl_terbit_to"
                                            placeholder="Sampai">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="filter_tgl_berakhir" class="form-label">Tanggal Berakhir</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="filter_tgl_berakhir_from"
                                            placeholder="Dari">
                                        <span class="input-group-text">s/d</span>
                                        <input type="date" class="form-control" id="filter_tgl_berakhir_to"
                                            placeholder="Sampai">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="filter_status" class="form-label">Status Dokumen</label>
                                    <select class="form-select" id="filter_status">
                                        <option value="">Semua Status</option>
                                        <option value="Berlaku">Berlaku</option>
                                        <option value="Tidak Berlaku">Tidak Berlaku</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="resetFilter">
                        <i class="fas fa-undo me-1"></i>Reset Filter
                    </button>
                    <button type="button" class="btn btn-primary" id="applyFilter">
                        <i class="fas fa-check me-1"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus dokumen <strong id="dokNoRegToDelete"></strong>?
                    </p>
                    <p class="text-danger"><i class="fas fa-info-circle me-1"></i>Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="fas fa-download me-2"></i>Ekspor Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" id="exportExcel">
                            <i class="fas fa-file-excel me-2"></i> Ekspor ke Excel
                        </button>
                        {{-- <button type="button" class="btn btn-danger" id="exportPdf">
                            <i class="fas fa-file-pdf me-2"></i> Ekspor ke PDF
                        </button>
                        <button type="button" class="btn btn-secondary" id="exportPrint">
                            <i class="fas fa-print me-2"></i> Print
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.4/css/select.bootstrap5.min.css">
    <style>
        /* CSS dengan spesifisitas tinggi untuk DataTables */
        .dokumenKapalPage .dataTables_wrapper .dataTables_length,
        .dokumenKapalPage .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem !important;
        }

        .dokumenKapalPage .dataTables_wrapper .dataTables_filter {
            text-align: right !important;
            margin-right: 0 !important;
        }

        .dokumenKapalPage .dataTables_wrapper .dataTables_filter label {
            display: inline-flex !important;
            align-items: center !important;
            margin-bottom: 0 !important;
            font-weight: normal !important;
        }

        .dokumenKapalPage .dataTables_wrapper .dataTables_filter input {
            margin-left: 5px !important;
            border-radius: 4px !important;
            border: 1px solid #ced4da !important;
            padding: 0.375rem 0.75rem !important;
            width: 200px !important;
            max-width: 100% !important;
        }

        .dokumenKapalPage table.dataTable thead th {
            position: relative;
            background-image: none !important;
        }

        .dokumenKapalPage table.dataTable thead th.sorting:after,
        .dokumenKapalPage table.dataTable thead th.sorting_asc:after,
        .dokumenKapalPage table.dataTable thead th.sorting_desc:after {
            position: absolute;
            top: 12px;
            right: 8px;
            display: block;
            font-family: "Font Awesome 5 Free";
        }

        .dokumenKapalPage table.dataTable thead th.sorting:after {
            content: "\f0dc";
            color: #ddd;
            font-size: 0.8em;
            opacity: 0.5;
        }

        .dokumenKapalPage table.dataTable thead th.sorting_asc:after {
            content: "\f0de";
        }

        .dokumenKapalPage table.dataTable thead th.sorting_desc:after {
            content: "\f0dd";
        }

        /* ===== HIGHLIGHT ROWS STYLING ===== */
        /* Custom CSS untuk highlight rows */
        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-red {
            background-color: #fc0000 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-yellow {
            background-color: #ffff00 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-orange {
            background-color: #00e013 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-gray {
            background-color: #cccccc !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        /* Ensure hover states don't override highlight colors */
        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-red:hover {
            background-color: #ff3333 !important;
        }

        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-yellow:hover {
            background-color: #ffff66 !important;
        }

        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-orange:hover {
            background-color: #00e013 !important;
        }

        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-gray:hover {
            background-color: #dddddd !important;
        }

        /* Override Bootstrap's striped table styles */
        .dokumenKapalPage .table-striped>tbody>tr:nth-of-type(odd).highlight-red,
        .dokumenKapalPage .table-striped>tbody>tr:nth-of-type(even).highlight-red {
            background-color: #fc0000 !important;
        }

        .dokumenKapalPage .table-striped>tbody>tr:nth-of-type(odd).highlight-yellow,
        .dokumenKapalPage .table-striped>tbody>tr:nth-of-type(even).highlight-yellow {
            background-color: #ffff00 !important;
        }

        .dokumenKapalPage .table-striped>tbody>tr:nth-of-type(odd).highlight-orange,
        .dokumenKapalPage .table-striped>tbody>tr:nth-of-type(even).highlight-orange {
            background-color: #00e013 !important;
        }

        .dokumenKapalPage .table-striped>tbody>tr:nth-of-type(odd).highlight-gray,
        .dokumenKapalPage .table-striped>tbody>tr:nth-of-type(even).highlight-gray {
            background-color: #cccccc !important;
        }

        /* Memastikan kolom tabel tetap terlihat meskipun dalam baris yang di-highlight */
        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-red>td,
        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-yellow>td,
        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-orange>td,
        .dokumenKapalPage table#dokumenKapalTable tbody tr.highlight-gray>td {
            background-color: inherit !important;
        }

        /* Add hover effect to action buttons */
        .dokumenKapalPage .btn-sm {
            transition: transform 0.2s;
        }

        .dokumenKapalPage .btn-sm:hover {
            transform: scale(1.1);
        }

        /* Hover effect for table rows */
        .dokumenKapalPage #dokumenKapalTable tbody tr {
            transition: all 0.2s ease;
        }

        .dokumenKapalPage #dokumenKapalTable tbody tr:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        /* Flash effect when hovering */
        @keyframes flashBorder {
            0% {
                box-shadow: 0 0 0 rgba(13, 110, 253, 0);
            }

            50% {
                box-shadow: 0 0 8px rgba(13, 110, 253, 0.5);
            }

            100% {
                box-shadow: 0 0 0 rgba(13, 110, 253, 0);
            }
        }

        .dokumenKapalPage #dokumenKapalTable tbody tr.row-hover-active {
            animation: flashBorder 1s ease infinite;
        }

        /* Highlight filter active state */
        .filter-active {
            background-color: #e8f4ff !important;
            border-left: 3px solid #0d6efd !important;
        }

        /* Hidden buttons untuk export */
        .dt-buttons {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            // Indonesian language configuration for DataTables
            const indonesianLanguage = {
                "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Sedang memuat...",
                "processing": "Sedang memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": aktifkan untuk mengurutkan kolom ke atas",
                    "sortDescending": ": aktifkan untuk mengurutkan kolom ke bawah"
                }
            };

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // First check if DataTable already exists, and destroy it if it does
            if ($.fn.dataTable.isDataTable('#dokumenKapalTable')) {
                $('#dokumenKapalTable').DataTable().destroy();
            }

            // Function to calculate document stats from ALL data (not just visible)
            function calculateAllDocumentStats() {
                let expiredCount = 0;
                let warningCount = 0;

                const allRows = table.rows().nodes();

                $(allRows).each(function() {
                    const row = $(this);

                    // 1. Check document status first
                    const statusText = row.find('td:eq(9)').text().trim();

                    // Skip non-valid documents
                    if (statusText.includes("Tidak Berlaku")) {
                        return true;
                    }

                    // 2. Check if document is expired based on TglBerakhir
                    const tglBerakhir = row.find('td:eq(6)').text().trim();
                    if (tglBerakhir !== '-') {
                        const berakhirDate = moment(tglBerakhir, 'DD/MM/YYYY');
                        const today = moment().startOf('day');

                        if (berakhirDate.isBefore(today)) {
                            expiredCount++;
                            return true;
                        }
                    }

                    // 3. Check TglPengingat for expired/warning
                    const tglPengingatStr = row.data('tgl-peringatan');
                    if (tglPengingatStr) {
                        const tglPengingat = moment(tglPengingatStr);
                        const today = moment().startOf('day');
                        const diffDays = tglPengingat.diff(today, 'days');

                        if (diffDays <= 0) {
                            expiredCount++;
                            return true;
                        } else if (diffDays <= 30) {
                            warningCount++;
                            return true;
                        }
                    }

                    // 4. Check TglBerakhir for warning (30 days)
                    if (tglBerakhir !== '-') {
                        const berakhirDate = moment(tglBerakhir, 'DD/MM/YYYY');
                        const today = moment().startOf('day');

                        if (berakhirDate.isAfter(today) && berakhirDate.diff(today, 'days') <= 30) {
                            warningCount++;
                        }
                    }
                });

                // Update counter badges
                $('#expiredDocsCount').text(expiredCount);
                $('#warningDocsCount').text(warningCount);
            }

            // Function to highlight visible rows
            function applyRowHighlighting() {
                $('#dokumenKapalTable tbody tr').removeClass(
                    'highlight-red highlight-yellow highlight-orange highlight-gray');

                $('#dokumenKapalTable tbody tr').each(function() {
                    const row = $(this);

                    // 1. Check document status first
                    const statusText = row.find('td:eq(9)').text().trim();
                    if (statusText.includes("Tidak Berlaku")) {
                        row.addClass('highlight-gray');
                        return true;
                    }

                    // 2. Check if expired based on TglBerakhir
                    const tglBerakhir = row.find('td:eq(6)').text().trim();
                    if (tglBerakhir !== '-') {
                        const berakhirDate = moment(tglBerakhir, 'DD/MM/YYYY');
                        const today = moment().startOf('day');

                        if (berakhirDate.isBefore(today)) {
                            row.addClass('highlight-red');
                            return true;
                        }
                    }

                    // 3. Check TglPengingat for warning/expired status
                    const tglPengingatStr = row.data('tgl-peringatan');
                    if (tglPengingatStr) {
                        const tglPengingat = moment(tglPengingatStr);
                        const today = moment().startOf('day');
                        const diffDays = tglPengingat.diff(today, 'days');

                        if (diffDays <= 0) {
                            row.addClass('highlight-red');
                            return true;
                        } else if (diffDays <= 7) {
                            row.addClass('highlight-yellow');
                            return true;
                        } else if (diffDays <= 30) {
                            row.addClass('highlight-orange');
                            return true;
                        }
                    }

                    // 4. Check TglBerakhir for warning (within 30 days)
                    if (tglBerakhir !== '-') {
                        const berakhirDate = moment(tglBerakhir, 'DD/MM/YYYY');
                        const today = moment().startOf('day');
                        const diffDays = berakhirDate.diff(today, 'days');

                        if (diffDays > 0 && diffDays <= 30) {
                            row.addClass('highlight-yellow');
                        }
                    }
                });
            }

            // Function to update warning text
            function updateMasaPeringatanText() {
                const today = moment().startOf('day');

                $('#dokumenKapalTable tbody tr').each(function() {
                    const tglPengingatStr = $(this).data('tgl-peringatan');
                    const $masaPeringatanCol = $(this).find('.sisa-peringatan-col');

                    if (!tglPengingatStr) {
                        $masaPeringatanCol.text('-');
                        return true;
                    }

                    const tglPengingat = moment(tglPengingatStr);
                    const diffDays = tglPengingat.diff(today, 'days');

                    let masaPeringatanText = '';
                    if (diffDays < 0) {
                        masaPeringatanText = 'Terlambat ' + Math.abs(diffDays) + ' hari';
                    } else if (diffDays === 0) {
                        masaPeringatanText = 'Hari ini';
                    } else {
                        masaPeringatanText = diffDays + ' hari lagi';
                    }

                    $masaPeringatanCol.text(masaPeringatanText);
                });
            }

            // CUSTOM PRIORITY SORTING FUNCTION
            function getRowPriority(row) {
                const statusText = $(row).find('td:eq(9)').text().trim();
                const tglBerakhir = $(row).find('td:eq(6)').text().trim();
                const tglPengingatStr = $(row).data('tgl-peringatan');

                if (statusText.includes("Tidak Berlaku")) {
                    return 5;
                }

                if (tglBerakhir !== '-') {
                    const berakhirDate = moment(tglBerakhir, 'DD/MM/YYYY');
                    const today = moment().startOf('day');

                    if (berakhirDate.isBefore(today)) {
                        return 1;
                    }
                }

                if (tglPengingatStr) {
                    const tglPengingat = moment(tglPengingatStr);
                    const today = moment().startOf('day');
                    const diffDays = tglPengingat.diff(today, 'days');

                    if (diffDays <= 0) {
                        return 1;
                    } else if (diffDays <= 7) {
                        return 2;
                    } else if (diffDays <= 30) {
                        return 3;
                    }
                }

                if (tglBerakhir !== '-') {
                    const berakhirDate = moment(tglBerakhir, 'DD/MM/YYYY');
                    const today = moment().startOf('day');
                    const diffDays = berakhirDate.diff(today, 'days');

                    if (diffDays > 0 && diffDays <= 30) {
                        return 2;
                    }
                }

                return 4;
            }

            // ADD CUSTOM SORTING PLUGIN TO DATATABLES
            $.fn.dataTable.ext.order['dom-priority'] = function(settings, col) {
                return this.api().column(col, {
                    order: 'index'
                }).nodes().map(function(td, i) {
                    return getRowPriority($(td).closest('tr'));
                });
            };

            // Remove any existing search function
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            // Format date for filtering
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    // Issue date filter
                    let terbitFrom = $('#filter_tgl_terbit_from').val();
                    let terbitTo = $('#filter_tgl_terbit_to').val();
                    let terbitDate = data[5] !== '-' ? moment(data[5], 'DD/MM/YYYY') : null;

                    // Filter by issue date
                    let terbitCondition = true;
                    if (terbitDate !== null && (terbitFrom !== '' || terbitTo !== '')) {
                        terbitCondition = false;

                        if (terbitFrom === '' && terbitDate.isSameOrBefore(moment(terbitTo))) {
                            terbitCondition = true;
                        } else if (terbitTo === '' && terbitDate.isSameOrAfter(moment(terbitFrom))) {
                            terbitCondition = true;
                        } else if (terbitDate.isBetween(moment(terbitFrom), moment(terbitTo), null, '[]')) {
                            terbitCondition = true;
                        }
                    }

                    if (!terbitCondition) return false;

                    // Expiration date filter
                    let berakhirFrom = $('#filter_tgl_berakhir_from').val();
                    let berakhirTo = $('#filter_tgl_berakhir_to').val();
                    let berakhirDate = data[6] !== '-' ? moment(data[6], 'DD/MM/YYYY') : null;

                    // Filter by expiration date
                    let berakhirCondition = true;
                    if (berakhirDate !== null && (berakhirFrom !== '' || berakhirTo !== '')) {
                        berakhirCondition = false;

                        if (berakhirFrom === '' && berakhirDate.isSameOrBefore(moment(berakhirTo))) {
                            berakhirCondition = true;
                        } else if (berakhirTo === '' && berakhirDate.isSameOrAfter(moment(berakhirFrom))) {
                            berakhirCondition = true;
                        } else if (berakhirDate.isBetween(moment(berakhirFrom), moment(berakhirTo), null,
                                '[]')) {
                            berakhirCondition = true;
                        }
                    }

                    if (!berakhirCondition) return false;

                    // Status filter - HANYA BERLAKU DAN TIDAK BERLAKU
                    let status = $('#filter_status').val();
                    if (status === '') {
                        return true;
                    } else {
                        // Extract status text, remove whitespace and HTML elements
                        const statusElement = $(data[9]);
                        const rowStatus = statusElement.length > 0 ?
                            statusElement.text().trim() :
                            data[9].replace(/<[^>]*>/g, '').trim();

                        // Exact match untuk status
                        return rowStatus === status;
                    }
                }
            );

            // Initialize DataTable with custom sorting
            var table = $('#dokumenKapalTable').DataTable({
                responsive: true,
                language: indonesianLanguage,
                columnDefs: [{
                        targets: 0,
                        orderDataType: 'dom-priority'
                    },
                    {
                        responsivePriority: 1,
                        targets: [0, 1, 2, 9]
                    },
                    {
                        responsivePriority: 2,
                        targets: [3, 4]
                    },
                    {
                        responsivePriority: 3,
                        targets: [5, 6, 7]
                    },
                    {
                        responsivePriority: 4,
                        targets: [8, 10, 11]
                    },
                    {
                        orderable: false,
                        targets: [11]
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                drawCallback: function() {
                    this.api().column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });

                    applyRowHighlighting();
                    updateMasaPeringatanText();
                },
                buttons: [{
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn btn-sm btn-success d-none excel-export-btn',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn btn-sm btn-danger d-none pdf-export-btn',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn btn-sm btn-secondary d-none print-export-btn',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    applyRowHighlighting();

                    setTimeout(function() {
                        calculateAllDocumentStats();
                    }, 500);
                }
            });

            // Event handlers for filter and export buttons
            $('#filterButton').on('click', function() {
                $('#filterModal').modal('show');
            });

            $('#exportButton').on('click', function() {
                $('#exportModal').modal('show');
            });

            // Apply Filter button event handler - DIPERBARUI
            $('#applyFilter').on('click', function() {
                // Apply filters to columns
                table.column(1).search($('#filter_noreg').val()); // No Reg
                table.column(2).search($('#filter_kapal').val()); // Kapal
                table.column(3).search($('#filter_kategori').val()); // Kategori
                table.column(4).search($('#filter_nama_dok').val()); // Nama Dokumen

                // Refresh table to apply all filters
                table.draw();
                $('#filterModal').modal('hide');

                // Highlight filter button if any filter is active
                highlightFilterButton();

                // Recalculate stats after filter
                setTimeout(function() {
                    calculateAllDocumentStats();
                }, 100);
            });

            // Reset Filter button event handler - DIPERBARUI
            $('#resetFilter').on('click', function() {
                // Reset form fields
                $('#filterForm')[0].reset();

                // Remove active class from filter button
                $('#filterButton').removeClass('filter-active');

                // Reset table filters
                table.search('').columns().search('').draw();

                // Recalculate stats after reset
                setTimeout(function() {
                    calculateAllDocumentStats();
                }, 100);
            });

            // Table draw event to update styling
            table.on('draw.dt', function() {
                applyRowHighlighting();
                updateMasaPeringatanText();
            });

            // Highlight filter button if any filter is active
            function highlightFilterButton() {
                if ($('#filter_noreg').val() ||
                    $('#filter_kapal').val() ||
                    $('#filter_kategori').val() ||
                    $('#filter_nama_dok').val() ||
                    $('#filter_tgl_terbit_from').val() ||
                    $('#filter_tgl_terbit_to').val() ||
                    $('#filter_tgl_berakhir_from').val() ||
                    $('#filter_tgl_berakhir_to').val() ||
                    $('#filter_status').val()) {
                    $('#filterButton').addClass('filter-active');
                } else {
                    $('#filterButton').removeClass('filter-active');
                }
            }

            // Export button handlers
            $('#exportExcel').on('click', function() {
                $('#export_filter_noreg').val($('#filter_noreg').val());
                $('#export_filter_kapal').val($('#filter_kapal').val());
                $('#export_filter_kategori').val($('#filter_kategori').val());
                $('#export_filter_nama_dok').val($('#filter_nama_dok').val());
                $('#export_filter_tgl_terbit_from').val($('#filter_tgl_terbit_from').val());
                $('#export_filter_tgl_terbit_to').val($('#filter_tgl_terbit_to').val());
                $('#export_filter_tgl_berakhir_from').val($('#filter_tgl_berakhir_from').val());
                $('#export_filter_tgl_berakhir_to').val($('#filter_tgl_berakhir_to').val());
                $('#export_filter_status').val($('#filter_status').val());

                $('#exportForm').submit();
                $('#exportModal').modal('hide');
            });

            $('#exportPdf').on('click', function() {
                $('.pdf-export-btn').trigger('click');
                $('#exportModal').modal('hide');
            });

            $('#exportPrint').on('click', function() {
                $('.print-export-btn').trigger('click');
                $('#exportModal').modal('hide');
            });

            // Delete confirmation handler
            $(document).on('click', '.delete-confirm', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var id = $(this).data('id');
                var name = $(this).data('name');
                var url = $(this).data('url');

                $('#dokNoRegToDelete').text(name);
                $('#deleteForm').attr('action', url);
                $('#deleteConfirmationModal').modal('show');
            });

            // Add row click effect for detail page
            $('#dokumenKapalTable tbody').on('click', 'tr', function(e) {
                if ($(e.target).is('button') || $(e.target).is('a') || $(e.target).is('i') ||
                    $(e.target).closest('button').length || $(e.target).closest('a').length) {
                    return;
                }

                var detailLink = $(this).find('a[title="Detail"]').attr('href');
                if (detailLink) {
                    window.location.href = detailLink;
                }
            });

            // Add hover effects
            $('#dokumenKapalTable tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('row-hover-active');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('row-hover-active');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);

            // Force initial sort to apply custom ordering
            table.order([0, 'asc']).draw();

            // Add export form to page
            $(`
<form id="exportForm" action="{{ route('dokumen-kapal.export-excel') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="filter_noreg" id="export_filter_noreg">
    <input type="hidden" name="filter_kapal" id="export_filter_kapal">
    <input type="hidden" name="filter_kategori" id="export_filter_kategori">
    <input type="hidden" name="filter_nama_dok" id="export_filter_nama_dok">
    <input type="hidden" name="filter_tgl_terbit_from" id="export_filter_tgl_terbit_from">
    <input type="hidden" name="filter_tgl_terbit_to" id="export_filter_tgl_terbit_to">
    <input type="hidden" name="filter_tgl_berakhir_from" id="export_filter_tgl_berakhir_from">
    <input type="hidden" name="filter_tgl_berakhir_to" id="export_filter_tgl_berakhir_to">
    <input type="hidden" name="filter_status" id="export_filter_status">
</form>
`).insertAfter('#dokumenKapalTable');
        });
    </script>
@endpush
