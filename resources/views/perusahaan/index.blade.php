@extends('layouts.app')

@section('content')
    <div class="container-fluid perusahaanPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-building me-2"></i>Manajemen Perusahaan</span>
                        <a href="{{ route('perusahaan.create') }}" class="btn btn-light">
                            <i class="fas fa-plus-circle me-1"></i> Tambah
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="perusahaanTable" class="table table-bordered table-striped data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Telepon</th>
                                        <th>Email</th>
                                        <th>Website</th>
                                        <th>Tanggal Berdiri</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perusahaan as $company)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $company->nama_prsh }}</td>
                                            <td>{{ $company->telp_prsh }}</td>
                                            <td>{{ $company->email_prsh }}</td>
                                            <td>
                                                @if ($company->web_prsh)
                                                    <a href="{{ $company->web_prsh }}" target="_blank" class="text-decoration-none">
                                                        <i class="fas fa-globe me-1"></i>{{ $company->web_prsh }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($company->tgl_berdiri)
                                                    {{ \Carbon\Carbon::parse($company->tgl_berdiri)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('perusahaan.show', $company->id) }}"
                                                        class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('perusahaan.edit', $company->id) }}"
                                                        class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-confirm"
                                                        data-bs-toggle="tooltip" title="Hapus"
                                                        data-id="{{ $company->id }}"
                                                        data-name="{{ $company->nama_prsh }}"
                                                        data-url="{{ route('perusahaan.destroy', $company->id) }}">
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
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
    <style>
        /* CSS dengan spesifisitas tinggi untuk DataTables */
        .perusahaanPage .dataTables_wrapper .dataTables_length,
        .perusahaanPage .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem !important;
        }

        .perusahaanPage .dataTables_wrapper .dataTables_filter {
            text-align: right !important;
            margin-right: 0 !important;
        }

        .perusahaanPage .dataTables_wrapper .dataTables_filter label {
            display: inline-flex !important;
            align-items: center !important;
            margin-bottom: 0 !important;
            font-weight: normal !important;
        }

        .perusahaanPage .dataTables_wrapper .dataTables_filter input {
            margin-left: 5px !important;
            border-radius: 4px !important;
            border: 1px solid #ced4da !important;
            padding: 0.375rem 0.75rem !important;
            width: 200px !important;
            max-width: 100% !important;
        }

        .perusahaanPage table.dataTable thead th {
            position: relative;
            background-image: none !important;
        }

        .perusahaanPage table.dataTable thead th.sorting:after,
        .perusahaanPage table.dataTable thead th.sorting_asc:after,
        .perusahaanPage table.dataTable thead th.sorting_desc:after {
            position: absolute;
            top: 12px;
            right: 8px;
            display: block;
            font-family: "Font Awesome 5 Free";
        }

        .perusahaanPage table.dataTable thead th.sorting:after {
            content: "\f0dc";
            color: #ddd;
            font-size: 0.8em;
            opacity: 0.5;
        }

        .perusahaanPage table.dataTable thead th.sorting_asc:after {
            content: "\f0de";
        }

        .perusahaanPage table.dataTable thead th.sorting_desc:after {
            content: "\f0dd";
        }

        /* Add hover effect to action buttons */
        .perusahaanPage .btn-sm {
            transition: transform 0.2s;
        }
        .perusahaanPage .btn-sm:hover {
            transform: scale(1.1);
        }

        /* Hover effect for table rows */
        .perusahaanPage #perusahaanTable tbody tr {
            transition: all 0.2s ease;
        }

        .perusahaanPage #perusahaanTable tbody tr:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        /* Flash effect when hovering */
        @keyframes flashBorder {
            0% { box-shadow: 0 0 0 rgba(13, 110, 253, 0); }
            50% { box-shadow: 0 0 8px rgba(13, 110, 253, 0.5); }
            100% { box-shadow: 0 0 0 rgba(13, 110, 253, 0); }
        }

        .perusahaanPage #perusahaanTable tbody tr.row-hover-active {
            animation: flashBorder 1s ease infinite;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#perusahaanTable').DataTable(
            //     {
            //     responsive: true,
            //     columnDefs: [{
            //         responsivePriority: 1,
            //         targets: [0, 1, 6] // Priority on first, name and action column
            //     },
            //     {
            //         orderable: false,
            //         targets: [6] // Action column not sortable
            //     }]
            // }
        );


            // Add flash effect when hovering over rows
            $('#perusahaanTable tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('row-hover-active');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('row-hover-active');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);
        });
    </script>
@endpush