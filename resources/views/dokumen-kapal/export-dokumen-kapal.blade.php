<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No. Reg</th>
            <th>Nama Kapal</th>
            <th>Kategori</th>
            <th>Nama Dokumen</th>
            <th>Penerbit</th>
            <th>Tgl Terbit</th>
            <th>Tgl Berakhir</th>
            <th>Tgl Peringatan</th>
            <th>Masa Berlaku</th>
            <th>Status</th>
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
                <td>{{ $dokumen->penerbit_dok ?: '-' }}</td>
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
                <td>{{ $dokumen->masa_berlaku ?: '-' }}</td>
                <td>{{ $dokumen->status_dok ?: '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($filter)
<table style="margin-top: 20px;">
    <tr>
        <td colspan="2"><strong>Informasi Filter yang Diterapkan:</strong></td>
    </tr>
    @if(isset($filter['noreg']) && $filter['noreg'])
    <tr>
        <td>No. Registrasi</td>
        <td>{{ $filter['noreg'] }}</td>
    </tr>
    @endif
    @if(isset($filter['kapal']) && $filter['kapal'])
    <tr>
        <td>Kapal</td>
        <td>{{ $filter['kapal'] }}</td>
    </tr>
    @endif
    @if(isset($filter['kategori']) && $filter['kategori'])
    <tr>
        <td>Kategori</td>
        <td>{{ $filter['kategori'] }}</td>
    </tr>
    @endif
    @if(isset($filter['nama_dok']) && $filter['nama_dok'])
    <tr>
        <td>Nama Dokumen</td>
        <td>{{ $filter['nama_dok'] }}</td>
    </tr>
    @endif
    @if(isset($filter['tgl_terbit_from']) && $filter['tgl_terbit_from'])
    <tr>
        <td>Tanggal Terbit (Dari)</td>
        <td>{{ $filter['tgl_terbit_from'] }}</td>
    </tr>
    @endif
    @if(isset($filter['tgl_terbit_to']) && $filter['tgl_terbit_to'])
    <tr>
        <td>Tanggal Terbit (Sampai)</td>
        <td>{{ $filter['tgl_terbit_to'] }}</td>
    </tr>
    @endif
    @if(isset($filter['tgl_berakhir_from']) && $filter['tgl_berakhir_from'])
    <tr>
        <td>Tanggal Berakhir (Dari)</td>
        <td>{{ $filter['tgl_berakhir_from'] }}</td>
    </tr>
    @endif
    @if(isset($filter['tgl_berakhir_to']) && $filter['tgl_berakhir_to'])
    <tr>
        <td>Tanggal Berakhir (Sampai)</td>
        <td>{{ $filter['tgl_berakhir_to'] }}</td>
    </tr>
    @endif
    @if(isset($filter['status']) && $filter['status'])
    <tr>
        <td>Status</td>
        <td>{{ $filter['status'] }}</td>
    </tr>
    @endif
    <tr>
        <td>Tanggal Export</td>
        <td>{{ now()->format('d/m/Y H:i:s') }}</td>
    </tr>
</table>
@endif