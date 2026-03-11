<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UsersImportTemplate implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        // One example row so the admin knows the expected format
        return [
            ['John Doe', 'john@example.com', '+1 234 567 890', 'user', 'password123'],
        ];
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Role', 'Password'];
    }

    public function styles(Worksheet $sheet): array
    {
        // Style the header row
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF930056']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Light style for the example row
        $sheet->getStyle('A2:E2')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFF0F7']],
        ]);

        return [];
    }
}
