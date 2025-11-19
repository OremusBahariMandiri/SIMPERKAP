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
            'filter' => null // UBAH INI - jangan pass filter ke view untuk mencegah duplikasi
        ]);
    }

    public function title(): string
    {
        return 'Dokumen Kapal';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
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

                $event->sheet->getStyle('A1:K1')->getFont()->getColor()->setRGB('FFFFFF');
                $event->sheet->setAutoFilter('A1:K1');
                $event->sheet->freezePane('A2');

                // Apply row color highlighting dengan logika yang SAMA PERSIS seperti JavaScript
                $rowIndex = 2;

                foreach ($this->dokumenKapal as $dokumen) {
                    $priority = $this->getRowPriorityJS($dokumen);
                    $rowColor = $this->getPriorityColor($priority);

                    if ($rowColor) {
                        $event->sheet->getStyle('A' . $rowIndex . ':K' . $rowIndex)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB($rowColor);
                    }

                    $rowIndex++;
                }

                // PERBAIKAN: Cek apakah ada filter yang aktif
                $hasActiveFilters = false;
                if ($this->filter) {
                    foreach ($this->filter as $key => $value) {
                        if (!empty($value)) {
                            $hasActiveFilters = true;
                            break;
                        }
                    }
                }

                if ($hasActiveFilters) {
                    $filterRowStart = count($this->dokumenKapal) + 3;
                    $event->sheet->setCellValue('A' . $filterRowStart, 'Informasi Filter yang Diterapkan:');
                    $event->sheet->mergeCells('A' . $filterRowStart . ':B' . $filterRowStart);
                    $event->sheet->getStyle('A' . $filterRowStart . ':B' . $filterRowStart)->getFont()->setBold(true);

                    $currentRow = $filterRowStart + 1;

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
                        if (isset($this->filter[$key]) && !empty($this->filter[$key])) {
                            $event->sheet->setCellValue('A' . $currentRow, $label);

                            $value = $this->filter[$key];
                            if (in_array($key, ['tgl_terbit_from', 'tgl_terbit_to', 'tgl_berakhir_from', 'tgl_berakhir_to'])) {
                                try {
                                    $value = \Carbon\Carbon::parse($this->filter[$key])->format('d/m/Y');
                                } catch (\Exception $e) {
                                    $value = $this->filter[$key];
                                }
                            }

                            $event->sheet->setCellValue('B' . $currentRow, $value);
                            $currentRow++;
                        }
                    }

                    $event->sheet->setCellValue('A' . $currentRow, 'Tanggal Export');
                    $event->sheet->setCellValue('B' . $currentRow, now()->format('d/m/Y H:i:s'));

                    $event->sheet->getStyle('A' . $filterRowStart . ':B' . $currentRow)->getBorders()
                        ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                } else {
                    // Hanya tampilkan tanggal export jika tidak ada filter aktif
                    $exportRowStart = count($this->dokumenKapal) + 3;
                    $event->sheet->setCellValue('A' . $exportRowStart, 'Tanggal Export');
                    $event->sheet->setCellValue('B' . $exportRowStart, now()->format('d/m/Y H:i:s'));
                    $event->sheet->getStyle('A' . $exportRowStart . ':B' . $exportRowStart)->getBorders()
                        ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
            },
        ];
    }

    /**
     * EXACT COPY dari JavaScript getRowPriority function - dengan data transformation
     */
    private function getRowPriorityJS($dokumen)
    {
        $statusText = $dokumen->status_dok == 'Berlaku' ? 'Berlaku' : 'Tidak Berlaku';
        $tglBerakhir = $dokumen->tgl_berakhir_dok ?
            \Carbon\Carbon::parse($dokumen->tgl_berakhir_dok)->format('d/m/Y') : '-';
        $tglPengingatStr = $dokumen->tgl_peringatan ?
            \Carbon\Carbon::parse($dokumen->tgl_peringatan)->format('Y-m-d') : null;

        if (str_contains($statusText, "Tidak Berlaku")) {
            return 5;
        }

        if ($tglBerakhir !== '-') {
            $berakhirDate = \Carbon\Carbon::createFromFormat('d/m/Y', $tglBerakhir);
            $today = \Carbon\Carbon::now()->startOf('day');

            if ($berakhirDate->isBefore($today)) {
                return 1;
            }
        }

        if ($tglPengingatStr) {
            $tglPengingat = \Carbon\Carbon::parse($tglPengingatStr);
            $today = \Carbon\Carbon::now()->startOf('day');
            $diffDays = $today->diffInDays($tglPengingat, false);

            if ($diffDays <= 0) {
                return 1;
            } else if ($diffDays <= 7) {
                return 2;
            } else if ($diffDays <= 30) {
                return 3;
            }
        }

        if ($tglBerakhir !== '-') {
            $berakhirDate = \Carbon\Carbon::createFromFormat('d/m/Y', $tglBerakhir);
            $today = \Carbon\Carbon::now()->startOf('day');
            $diffDays = $today->diffInDays($berakhirDate, false);

            if ($diffDays > 0 && $diffDays <= 30) {
                return 2;
            }
        }

        return 4;
    }

    /**
     * Get warna berdasarkan priority
     */
    private function getPriorityColor($priority)
    {
        switch ($priority) {
            case 1:
                return 'FC0000'; // Merah - Expired/Critical
            case 2:
                return 'FFFF00'; // Kuning - Warning urgent (7 hari atau kurang)
            case 3:
                return '00E013'; // Hijau - Warning normal (8-30 hari)
            case 4:
                return null; // Putih - Normal (tidak perlu warna)
            case 5:
                return 'CCCCCC'; // Abu-abu - Tidak berlaku
            default:
                return null;
        }
    }
}