<?php

// This script creates the Excel template for book import
// Run: php artisan tinker --execute="include 'scripts/create_template.php'"

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Buku');

// Headers
$headers = ['isbn', 'judul', 'penulis', 'penerbit', 'tahun_terbit', 'kategori', 'deskripsi', 'jumlah_eksemplar'];
$column = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($column . '1', $header);
    $column++;
}

// Style headers
$headerRange = 'A1:H1';
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '10B981'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
]);

// Sample data
$sampleData = [
    ['978-602-1234-56-7', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 'Fiksi', 'Novel tentang pendidikan di Belitung', 3],
    ['978-602-9876-54-3', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 'Fiksi', 'Tetralogi Buru bagian pertama', 2],
];

$row = 2;
foreach ($sampleData as $data) {
    $column = 'A';
    foreach ($data as $value) {
        $sheet->setCellValue($column . $row, $value);
        $column++;
    }
    $row++;
}

// Auto-size columns
foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Save
$templatePath = storage_path('app/templates/book_import_template.xlsx');
$writer = new Xlsx($spreadsheet);
$writer->save($templatePath);

echo "Template created at: {$templatePath}\n";
