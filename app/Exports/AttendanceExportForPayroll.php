<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AttendanceExportForPayroll implements FromView, WithColumnWidths, WithStyles
{
    protected $attendanceData;
    public $currentMonths;
    public $currentYears;

    public function __construct($attendanceData, $currentMonths, $currentYears)
    {
        $this->attendanceData = $attendanceData;
        $this->currentMonths = $currentMonths;
        $this->currentYears = $currentYears;
    }

    public function view(): View
    {
        return view('exports.attendance_report_payroll', [
            'attendanceData' => $this->attendanceData,
            'currentMonths' => $this->currentMonths,
            'currentYears' => $this->currentYears,
            
        ]);
    }

    public function columnWidths(): array
    {
        // Set all columns to width 33
        return [
            'A' => 12,
            'B' => 31,  // Employee Name
            'C' => 27,  // Total Hours
            'D' =>22,  // Total Hours Worked
            'E' => 22,  // Total Late
            'F' => 22,  // Total Undertime
            'G' => 22,  // Total Absent
            'H' => 22,  // Date Range
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Define border style
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Black color
                ],
            ],
        ];

        // Define center alignment
        $centerAlignment = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => false, // Default alignment
            ],
        ];

        // Define left alignment for column B
        $leftAlignment = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => false, // Default alignment
            ],
        ];

         $redFill = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFF0000'], // Red color
            ],
        ];

        $redFont = [
            'font' => [
                'color' => ['argb' => 'FFFF0000'], // Red text
                'bold' => true,
            ],
        ];


        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2:H2')->applyFromArray(array_merge($centerAlignment, $redFont));
         


        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->applyFromArray($centerAlignment);

        $sheet->getStyle('A2:H2')->getFont()->setBold(true);
        $sheet->getStyle('A3:H3')->getFont()->setBold(true);

        // Apply center alignment to all columns except B
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A3:H' . $highestRow)->applyFromArray($borderStyle);
        $sheet->getStyle('H4:H' . $highestRow)->applyFromArray($centerAlignment);

        $sheet->getStyle('E3')->applyFromArray([
            'alignment' => [
                'wrapText' => true,
            ],
        ]);

        // Set row height for row 3
        $sheet->getRowDimension(3)->setRowHeight(45);

        for ($row = 1; $row <= $highestRow; $row++) {
            if ($row != 3) {
                $sheet->getRowDimension($row)->setRowHeight(20);
            }
        }

    }

}
