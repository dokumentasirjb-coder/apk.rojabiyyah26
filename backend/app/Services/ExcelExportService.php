<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    /**
     * Generate and stream a professionally styled .xlsx spreadsheet
     *
     * @param string $filename e.g. "Data_Anggota_Rojabiyyah_2026.xlsx"
     * @param string $sheetTitle e.g. "Data Anggota"
     * @param string $mainTitle e.g. "DATA ANGGOTA & RELAWAN PANITIA ROJABIYYAH 2026"
     * @param string|null $subtitle e.g. "Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad"
     * @param array $headers List of column header titles
     * @param array $rows 2D array of data rows
     * @param array $options [
     *     'header_bg' => '1E293B',
     *     'alignments' => [1 => 'center', 4 => 'right'],
     *     'formats' => [6 => '#,##0', 7 => '#,##0'],
     *     'text_columns' => [2, 4],
     * ]
     * @return StreamedResponse
     */
    public static function streamStyled(
        string $filename,
        string $sheetTitle,
        string $mainTitle,
        ?string $subtitle,
        array $headers,
        array $rows,
        array $options = []
    ): StreamedResponse {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr(preg_replace('/[\\\\\/?*\[\]:]/', '', $sheetTitle), 0, 31));

        $headerBg = $options['header_bg'] ?? '1E293B';
        $alignments = $options['alignments'] ?? [];
        $formats = $options['formats'] ?? [];
        $textColumns = $options['text_columns'] ?? [];

        $totalCols = max(1, count($headers));
        $lastColLetter = Coordinate::stringFromColumnIndex($totalCols);

        // Row 1: Brand / Event Banner
        $sheet->setCellValue('A1', 'ROJABIYYAH 2026 • ADMINISTRASI & DOKUMENTASI RESMI');
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->getStyle('A1')->getFont()->setSize(9)->setBold(true)->getColor()->setRGB('64748B');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Row 2: Main Title
        $sheet->setCellValue('A2', strtoupper($mainTitle));
        $sheet->mergeCells("A2:{$lastColLetter}2");
        $sheet->getStyle('A2')->getFont()->setSize(13)->setBold(true)->getColor()->setRGB('0F172A');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getRowDimension(2)->setRowHeight(22);

        // Row 3: Subtitle / Timestamp
        $subText = ($subtitle ? $subtitle . ' • ' : '') . 'Diunduh pada: ' . date('d/m/Y H:i') . ' WIB';
        $sheet->setCellValue('A3', $subText);
        $sheet->mergeCells("A3:{$lastColLetter}3");
        $sheet->getStyle('A3')->getFont()->setSize(9.5)->setItalic(true)->getColor()->setRGB('64748B');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // Row 4: Spacer row
        $sheet->getRowDimension(4)->setRowHeight(8);

        // Row 5: Table Header
        $headerRowIndex = 5;
        $sheet->getRowDimension($headerRowIndex)->setRowHeight(26);

        foreach ($headers as $colIdx => $headerText) {
            $colLetter = Coordinate::stringFromColumnIndex($colIdx + 1);
            $sheet->setCellValue("{$colLetter}{$headerRowIndex}", $headerText);
        }

        // Style header row
        $headerRange = "A{$headerRowIndex}:{$lastColLetter}{$headerRowIndex}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBg],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '334155'],
                ],
            ],
        ]);

        // Freeze panes so header row stays locked
        $sheet->freezePane('A6');

        // Populate Data Rows
        $currentRow = 6;
        foreach ($rows as $rIndex => $row) {
            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $isEven = ($rIndex % 2 === 0);
            $rowBg = $isEven ? 'FFFFFF' : 'F8FAFC';

            foreach ($row as $colIdx => $val) {
                $colIndex1 = $colIdx + 1;
                $colLetter = Coordinate::stringFromColumnIndex($colIndex1);
                $cellCoordinate = "{$colLetter}{$currentRow}";

                // Clean value if string has quote prefix
                if (is_string($val) && str_starts_with($val, "'")) {
                    $val = substr($val, 1);
                }

                // Explicit string or standard
                if (in_array($colIndex1, $textColumns)) {
                    $sheet->setCellValueExplicit($cellCoordinate, (string)$val, DataType::TYPE_STRING);
                } else {
                    $sheet->setCellValue($cellCoordinate, $val);
                }

                // Alignment
                $align = $alignments[$colIndex1] ?? 'left';
                $horizontalAlign = match ($align) {
                    'center' => Alignment::HORIZONTAL_CENTER,
                    'right' => Alignment::HORIZONTAL_RIGHT,
                    default => Alignment::HORIZONTAL_LEFT,
                };

                $sheet->getStyle($cellCoordinate)->getAlignment()
                    ->setHorizontal($horizontalAlign)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Number formatting
                if (isset($formats[$colIndex1])) {
                    $sheet->getStyle($cellCoordinate)->getNumberFormat()
                        ->setFormatCode($formats[$colIndex1]);
                }
            }

            // Row zebra background and borders
            $sheet->getStyle("A{$currentRow}:{$lastColLetter}{$currentRow}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $rowBg],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E2E8F0'],
                    ],
                ],
                'font' => [
                    'size' => 9.5,
                    'color' => ['rgb' => '1E293B'],
                ],
            ]);

            $currentRow++;
        }

        // Auto-fit all column widths
        foreach (range(1, $totalCols) as $colIdx) {
            $colLetter = Coordinate::stringFromColumnIndex($colIdx);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Stream as .xlsx
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->headers->set('Pragma', 'public');

        return $response;
    }
}

