<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class UsersExport implements FromQuery, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithMapping
{
    protected string $search;
    protected string $role;
    protected string $status;

    public function __construct(string $search = '', string $role = '', string $status = '')
    {
        $this->search = $search;
        $this->role   = $role;
        $this->status = $status;
    }

    public function title(): string
    {
        return 'Users';
    }

    public function query()
    {
        return User::query()
            ->when($this->search, fn($q) => $q
                ->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%'))
            ->when($this->status, fn($q) => $q->where('is_active', $this->status === 'active'))
            ->when($this->role, fn($q) => $q->where('role', $this->role))
            ->when(!$this->role, fn($q) => $q->whereIn('role', ['admin', 'user']))
            ->withCount('enrollments')
            ->latest();
    }

    public function headings(): array
    {
        return ['#', 'Full Name', 'Email', 'Role', 'Phone', 'Enrollments', 'Status', 'Joined'];
    }

    public function map($user): array
    {
        static $row = 0;
        $row++;
        return [
            $row,
            $user->name,
            $user->email,
            ucfirst($user->role),
            $user->phone ?? '—',
            $user->enrollments_count,
            $user->is_active ? 'Active' : 'Inactive',
            $user->created_at->format('M d, Y'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 28,
            'C' => 34,
            'D' => 12,
            'E' => 18,
            'F' => 14,
            'G' => 12,
            'H' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // ── Title row above headers ──────────────────────────────────────
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'LMS Platform — Users Export');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 16,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0F043D'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        // Subtitle / date row
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Generated on ' . now()->format('F d, Y  H:i'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size'   => 9,
                'color'  => ['argb' => 'FFAAAACC'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1A1262'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // ── Header row (now row 3) ────────────────────────────────────────
        $sheet->getStyle('A3:H3')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 10,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF930056'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FFFF80C8']],
            ],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(22);

        // ── Data rows ────────────────────────────────────────────────────
        $lastRow = $sheet->getHighestRow();
        for ($i = 4; $i <= $lastRow; $i++) {
            $isOdd = ($i % 2 === 0); // after insert, even sheet rows are "odd" data rows
            $bg    = $isOdd ? 'FFF3F0FA' : 'FFFFFFFF';

            $sheet->getStyle("A{$i}:H{$i}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $bg],
                ],
                'font'  => ['size' => 9, 'color' => ['argb' => 'FF1A1262']],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => [
                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE0D8F5']],
                ],
            ]);

            // Status column (G) — colour code
            $statusVal = $sheet->getCell("G{$i}")->getValue();
            $statusColor = $statusVal === 'Active' ? 'FF16A34A' : 'FF930056';
            $sheet->getStyle("G{$i}")->getFont()->getColor()->setARGB($statusColor);
            $sheet->getStyle("G{$i}")->getFont()->setBold(true);

            // Role column (D) — colour
            $roleVal = $sheet->getCell("D{$i}")->getValue();
            if ($roleVal === 'Admin') {
                $sheet->getStyle("D{$i}")->getFont()->getColor()->setARGB('FF930056');
                $sheet->getStyle("D{$i}")->getFont()->setBold(true);
            }

            $sheet->getRowDimension($i)->setRowHeight(18);
        }

        // ── Outer border ─────────────────────────────────────────────────
        $sheet->getStyle("A1:H{$lastRow}")->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF930056']],
            ],
        ]);

        // ── Center numeric columns ────────────────────────────────────────
        $sheet->getStyle("A4:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F4:F{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G4:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D4:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
