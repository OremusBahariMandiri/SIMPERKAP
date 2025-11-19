<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No. Reg</th>
            <th>Nama Kapal</th>
            <th>Kategori</th>
            <th>Nama Dokumen</th>
            <th>Tgl Terbit</th>
            <th>Tgl Berakhir</th>
            <th>Tgl Peringatan</th>
            <th>Peringatan</th>
            <th>Status</th>
            <th>Dokumen</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dokumenKapal as $index => $dokumen)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $dokumen->no_reg }}</td>
                <td>{{ $dokumen->kapal ? $dokumen->kapal->nama_kpl : '-' }}</td>
                <td>{{ $dokumen->kategoriDokumen ? $dokumen->kategoriDokumen->kategori_dok : '-' }}</td>
                <td>{{ $dokumen->namaDokumen ? $dokumen->namaDokumen->nama_dok : '-' }}</td>
                <td>
                    @if ($dokumen->tgl_terbit_dok)
                        {{ \Carbon\Carbon::parse($dokumen->tgl_terbit_dok)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($dokumen->tgl_berakhir_dok)
                        {{ \Carbon\Carbon::parse($dokumen->tgl_berakhir_dok)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($dokumen->tgl_peringatan)
                        {{ \Carbon\Carbon::parse($dokumen->tgl_peringatan)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $masaPeringatanText = '-';
                        if ($dokumen->tgl_peringatan) {
                            $tglPengingat = \Carbon\Carbon::parse($dokumen->tgl_peringatan);
                            $today = \Carbon\Carbon::now()->startOf('day');
                            $diffDays = $tglPengingat->diffInDays($today, false);

                            if ($diffDays < 0) {
                                $masaPeringatanText = 'Terlambat ' . abs($diffDays) . ' hari';
                            } elseif ($diffDays == 0) {
                                $masaPeringatanText = 'Hari ini';
                            } else {
                                $masaPeringatanText = $diffDays . ' hari lagi';
                            }
                        }
                    @endphp
                    {{ $masaPeringatanText }}
                </td>
                <td>
                    @if ($dokumen->status_dok == 'Berlaku')
                        Berlaku
                    @elseif ($dokumen->status_dok == 'Tidak Berlaku')
                        Tidak Berlaku
                    @else
                        {{ $dokumen->status_dok ?: '-' }}
                    @endif
                </td>
                <td>
                    @if ($dokumen->file_dok)
                        Ada File
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- REMOVE FILTER SECTION FROM TEMPLATE - will be handled by Export class --}}