<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CoursesExport implements FromQuery, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithMapping
{
    protected string $search;
    protected string $status;
    protected string $category;

    public function __construct(string $search = '', string $status = '', string $category = '')
    {
        $this->search   = $search;
        $this->status   = $status;
        $this->category = $category;
    }

    public function title(): string
    {
        return 'Courses';
    }

    public function query()
    {
        return Course::with(['category'])
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->withCount('enrollments')
            ->latest();
    }

    public function headings(): array
    {
        return ['#', 'Title', 'Category', 'Instructor', 'Level', 'Status', 'Enrolled', 'Certificate', 'Created'];
    }

    public function map($course): array
    {
        static $row = 0;
        $row++;
        return [
            $row,
            $course->title,
            $course->category->name ?? '—',
            $course->instructor_name,
            ucfirst($course->level),
            ucfirst($course->status),
            $course->enrollments_count,
            $course->has_certificate ? 'Yes' : 'No',
            $course->created_at->format('M d, Y'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 40,
            'C' => 18,
            'D' => 22,
            'E' => 14,
            'F' => 14,
            'G' => 12,
            'H' => 14,
            'I' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Title banner
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'LMS Platform — Courses Export');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0F043D']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'Generated on ' . now()->format('F d, Y  H:i'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FFAAAACC']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1A1262']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // Header row
        $sheet->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF045592']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF5BB8FF']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(22);

        $lastRow = $sheet->getHighestRow();
        for ($i = 4; $i <= $lastRow; $i++) {
            $bg = ($i % 2 === 0) ? 'FFF0F6FF' : 'FFFFFFFF';
            $sheet->getStyle("A{$i}:I{$i}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                'font' => ['size' => 9, 'color' => ['argb' => 'FF0F043D']],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD8E8F5']]],
            ]);

            // Status colour
            $sv = $sheet->getCell("F{$i}")->getValue();
            $sc = $sv === 'Published' ? 'FF16A34A' : 'FF94A3B8';
            $sheet->getStyle("F{$i}")->getFont()->getColor()->setARGB($sc);
            $sheet->getStyle("F{$i}")->getFont()->setBold(true);

            // Certificate
            $cv = $sheet->getCell("H{$i}")->getValue();
            if ($cv === 'Yes') {
                $sheet->getStyle("H{$i}")->getFont()->getColor()->setARGB('FF16A34A');
                $sheet->getStyle("H{$i}")->getFont()->setBold(true);
            }

            $sheet->getRowDimension($i)->setRowHeight(18);
        }

        $sheet->getStyle("A1:I{$lastRow}")->applyFromArray([
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF045592']]],
        ]);

        $sheet->getStyle("A4:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G4:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H4:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
