<?php

namespace App\Exports;

use App\Models\DokumenKapal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DokumenKapalExport implements FromView, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    protected $dokumenKapal;
    protected $filter;

    public function __construct($dokumenKapal, $filter = null)
    {
        $this->dokumenKapal = $dokumenKapal;
        $this->filter = $filter;
    }

    public function view(): View
    {
        return view('dokumen-kapal.export-dokumen-kapal', [
            'dokumenKapal' => $this->dokumenKapal,
            'filter' => $this->filter
        ]);
    }

    public function title(): string
    {
        return 'Dokumen Kapal';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as headers
            1 => ['font' => ['bold' => true, 'size' => 12]],

            // Add borders to all cells
            'A1:K' . (count($this->dokumenKapal) + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set background color for header row
                $event->sheet->getStyle('A1:K1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4a6fdc');

                // Set text color for header row
                $event->sheet->getStyle('A1:K1')->getFont()->getColor()
                    ->setRGB('FFFFFF');

                // Add filter buttons to headers
                $event->sheet->setAutoFilter('A1:K1');

                // Freeze the first row
                $event->sheet->freezePane('A2');

                // Apply row color highlighting based on document status
                $rowIndex = 2; // Start from row 2 (after header)

                foreach ($this->dokumenKapal as $dokumen) {
                    // Flag untuk menentukan warna baris
                    $rowColor = null;

                    // Langkah 1: Cek status dokumen (Tidak Berlaku = abu-abu)
                    if ($dokumen->status_dok == 'Tidak Berlaku') {
                        $rowColor = 'CCCCCC'; // Abu-abu
                    }
                    // Langkah 2: Cek tanggal kadaluarsa dan tanggal peringatan
                    else {
                        // Periksa apakah sudah kadaluarsa berdasarkan tanggal berakhir
                        $isExpired = false;
                        if ($dokumen->tgl_berakhir_dok) {
                            $tglBerakhir = \Carbon\Carbon::parse($dokumen->tgl_berakhir_dok);
                            if ($tglBerakhir->isPast()) {
                                $isExpired = true;
                            }
                        }

                        // Periksa tanggal peringatan
                        $isWarningPassed = false;
                        if ($dokumen->tgl_peringatan) {
                            $tglPeringatan = \Carbon\Carbon::parse($dokumen->tgl_peringatan);
                            $today = \Carbon\Carbon::now()->startOfDay(); // Reset waktu ke awal hari

                            // PENTING: Bandingkan tanggal yang diformat dengan format yang sama
                            // Ini cara yang paling akurat untuk memeriksa jika dua tanggal sama
                            $peringatanDate = $tglPeringatan->format('Y-m-d');
                            $todayDate = $today->format('Y-m-d');

                            // Jika tanggal peringatan sudah lewat atau sama dengan hari ini, tandai sebagai lewat
                            if ($tglPeringatan->isPast() || $peringatanDate == $todayDate) {
                                $isWarningPassed = true;
                            }
                        }

                        // Tentukan warna berdasarkan status
                        if ($isExpired || $isWarningPassed) {
                            $rowColor = 'FC0000'; // Merah untuk dokumen kadaluarsa atau peringatan lewat/hari ini
                        }
                        // Periksa jika mendekati tanggal peringatan (30 hari)
                        else if ($dokumen->tgl_peringatan) {
                            $tglPeringatan = \Carbon\Carbon::parse($dokumen->tgl_peringatan);
                            $today = \Carbon\Carbon::now();

                            // Hanya jika tanggal peringatan di masa depan
                            if ($tglPeringatan->isFuture()) {
                                $diffDays = $tglPeringatan->diffInDays($today);

                                if ($diffDays <= 7) {
                                    $rowColor = 'FFFF00'; // Kuning untuk 7 hari atau kurang
                                } else if ($diffDays <= 30) {
                                    $rowColor = '00E013'; // Hijau untuk 30 hari atau kurang
                                }
                            }
                        }

                        // Jika tidak ada warna yang ditentukan, cek jika berakhir dalam 30 hari
                        if (!$rowColor && $dokumen->tgl_berakhir_dok) {
                            $tglBerakhir = \Carbon\Carbon::parse($dokumen->tgl_berakhir_dok);
                            $today = \Carbon\Carbon::now();

                            if ($tglBerakhir->isFuture() && $tglBerakhir->diffInDays($today) <= 30) {
                                $rowColor = 'FFFF00'; // Kuning untuk dokumen yang akan berakhir dalam 30 hari
                            }
                        }
                    }

                    // Terapkan warna pada baris jika ada
                    if ($rowColor) {
                        $event->sheet->getStyle('A' . $rowIndex . ':K' . $rowIndex)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB($rowColor);
                    }

                    $rowIndex++;
                }

                // Add filter information section if available
                if ($this->filter) {
                    $filterRowStart = count($this->dokumenKapal) + 3;

                    // Set title for filter info
                    $event->sheet->setCellValue('A' . $filterRowStart, 'Informasi Filter yang Diterapkan:');
                    $event->sheet->mergeCells('A' . $filterRowStart . ':B' . $filterRowStart);
                    $event->sheet->getStyle('A' . $filterRowStart . ':B' . $filterRowStart)->getFont()->setBold(true);

                    $currentRow = $filterRowStart + 1;

                    // Add filter details
                    $filterItems = [
                        'noreg' => 'No. Registrasi',
                        'kapal' => 'Kapal',
                        'kategori' => 'Kategori',
                        'nama_dok' => 'Nama Dokumen',
                        'tgl_terbit_from' => 'Tanggal Terbit (Dari)',
                        'tgl_terbit_to' => 'Tanggal Terbit (Sampai)',
                        'tgl_berakhir_from' => 'Tanggal Berakhir (Dari)',
                        'tgl_berakhir_to' => 'Tanggal Berakhir (Sampai)',
                        'status' => 'Status'
                    ];

                    foreach ($filterItems as $key => $label) {
                        if (isset($this->filter[$key]) && $this->filter[$key]) {
                            $event->sheet->setCellValue('A' . $currentRow, $label);
                            $event->sheet->setCellValue('B' . $currentRow, $this->filter[$key]);
                            $currentRow++;
                        }
                    }

                    // Add export date
                    $event->sheet->setCellValue('A' . $currentRow, 'Tanggal Export');
                    $event->sheet->setCellValue('B' . $currentRow, now()->format('d/m/Y H:i:s'));

                    // Add borders to filter info section
                    $event->sheet->getStyle('A' . $filterRowStart . ':B' . $currentRow)->getBorders()
                        ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
            },
        ];
    }
}