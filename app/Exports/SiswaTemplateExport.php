<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected ?string $defaultRombelName;

    public function __construct(?string $defaultRombelName = null)
    {
        $this->defaultRombelName = $defaultRombelName;
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap*',
            'NISN',
            'NIK',
            'Nama Panggilan',
            'Jenis Kelamin* (L/P)',
            'Nama Rombel / Kelas',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Nama Orang Tua / Wali',
            'No HP WA Orang Tua',
            'Alamat',
        ];
    }

    public function array(): array
    {
        $rombel = $this->defaultRombelName ?: 'Kelompok A';

        return [
            [
                'Ahmad Fauzan Pratama',
                '0012345678',
                '1801012345670001',
                'Fauzan',
                'L',
                $rombel,
                'Bandar Lampung',
                '2020-05-12',
                'Bambang Pratama',
                '081234567890',
                'Jl. Melati No. 12, Sukajadi',
            ],
            [
                'Aisyah Rahma Putri',
                '0012345679',
                '1801012345670002',
                'Aisyah',
                'P',
                $rombel,
                'Jakarta',
                '2020-08-20',
                'Siti Rahayu',
                '081987654321',
                'Jl. Mawar Indah Blok B3',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF16A34A'], // SekolahKu Green
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Template Import Siswa';
    }
}
