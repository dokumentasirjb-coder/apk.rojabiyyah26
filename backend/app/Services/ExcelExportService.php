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

    /**
     * Generate and stream exact LPJ spreadsheet format
     */
    public static function streamLpjExcel(
        string $filename,
        string $orgName,
        string $subTitle,
        array $items,
        float $totalDebet,
        float $totalKredit,
        float $totalSaldo
    ): StreamedResponse {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('LPJ Keuangan');

        $headers = ['No.', 'Tanggal', 'Pelaksana', 'Uraian', 'Jumlah', 'Satuan', 'Debet', 'Kredit', 'Saldo', 'Keterangan', 'Nota'];
        $totalCols = count($headers);
        $lastColLetter = Coordinate::stringFromColumnIndex($totalCols);

        // Row 1: Header Title
        $sheet->setCellValue('A1', 'LAPORAN DETAIL PENGELUARAN');
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true)->getColor()->setRGB('0F172A');
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Row 2: Org Name
        $sheet->setCellValue('A2', strtoupper($orgName));
        $sheet->mergeCells("A2:{$lastColLetter}2");
        $sheet->getStyle('A2')->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('059669');
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Row 3: Subtitle / Event & Timestamp
        $sheet->setCellValue('A3', $subTitle . ' • Dicetak pada: ' . date('d/m/Y H:i') . ' WIB');
        $sheet->mergeCells("A3:{$lastColLetter}3");
        $sheet->getStyle('A3')->getFont()->setSize(9)->setItalic(true)->getColor()->setRGB('64748B');
        $sheet->getRowDimension(3)->setRowHeight(18);

        // Row 4: Spacer
        $sheet->getRowDimension(4)->setRowHeight(8);

        // Row 5: Table Header
        $headerRowIndex = 5;
        $sheet->getRowDimension($headerRowIndex)->setRowHeight(26);

        foreach ($headers as $colIdx => $hText) {
            $colLetter = Coordinate::stringFromColumnIndex($colIdx + 1);
            $sheet->setCellValue("{$colLetter}{$headerRowIndex}", $hText);
        }

        $headerRange = "A{$headerRowIndex}:{$lastColLetter}{$headerRowIndex}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '065F46'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '047857'],
                ],
            ],
        ]);

        $sheet->freezePane('A6');

        $currentRow = 6;
        $no = 1;
        foreach ($items as $item) {
            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $isEven = ($no % 2 === 0);
            $rowBg = $isEven ? 'F8FAFC' : 'FFFFFF';

            $debetVal = (float)($item['debet'] ?? 0);
            $kreditVal = (float)($item['kredit'] ?? 0);
            $saldoVal = (float)($item['saldo'] ?? 0);

            $sheet->setCellValue("A{$currentRow}", $no);
            $sheet->setCellValue("B{$currentRow}", $item['date'] ?? '-');
            $sheet->setCellValue("C{$currentRow}", $item['pelaksana'] ?? '-');
            $sheet->setCellValue("D{$currentRow}", $item['description'] ?? '-');
            $satuanVal = $item['unit_price'] ?? $item['unit'] ?? '';
            $sheet->setCellValue("E{$currentRow}", $item['qty'] ?: '');
            $sheet->setCellValue("F{$currentRow}", $satuanVal ?: '');
            if (is_numeric($satuanVal) && (float)$satuanVal > 0) {
                $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            } else {
                $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            $sheet->setCellValue("G{$currentRow}", $debetVal ?: 0);
            $sheet->setCellValue("H{$currentRow}", $kreditVal ?: 0);
            $sheet->setCellValue("I{$currentRow}", $saldoVal ?: 0);
            $sheet->setCellValue("J{$currentRow}", $item['notes'] ?? '');

            // Nota link
            $receipt = $item['receipt_path'] ?? '';
            if ($receipt) {
                if (filter_var($receipt, FILTER_VALIDATE_URL)) {
                    $sheet->setCellValue("K{$currentRow}", 'Lihat Nota');
                    $sheet->getCell("K{$currentRow}")->getHyperlink()->setUrl($receipt);
                    $sheet->getStyle("K{$currentRow}")->getFont()->setUnderline(true)->getColor()->setRGB('2563EB');
                } else {
                    $sheet->setCellValue("K{$currentRow}", 'Ada Bukti');
                }
            } else {
                $sheet->setCellValue("K{$currentRow}", '-');
            }

            // Alignments
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("I{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("J{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("K{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("H{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("I{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            if (is_numeric($item['qty'] ?? null)) {
                $sheet->getStyle("E{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.##');
            }

            // Row style
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
            $no++;
        }

        // Row TOTAL >>>
        $totalRow = $currentRow;
        $sheet->getRowDimension($totalRow)->setRowHeight(24);

        $sheet->mergeCells("A{$totalRow}:F{$totalRow}");
        $sheet->setCellValue("A{$totalRow}", 'TOTAL >>>');
        $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue("G{$totalRow}", $totalDebet);
        $sheet->setCellValue("H{$totalRow}", $totalKredit);
        $sheet->setCellValue("I{$totalRow}", $totalSaldo);
        $sheet->setCellValue("J{$totalRow}", '');
        $sheet->setCellValue("K{$totalRow}", '');

        $sheet->getStyle("G{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("H{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("I{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');

        $sheet->getStyle("G{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("H{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("I{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $totalRange = "A{$totalRow}:{$lastColLetter}{$totalRow}";
        $sheet->getStyle($totalRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => '064E3B'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D1FAE5'],
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '059669'],
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['rgb' => '059669'],
                ],
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'A7F3D0'],
                ],
            ],
        ]);

        // Auto-fit column widths
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

