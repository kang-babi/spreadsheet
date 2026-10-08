<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Row;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$filename = basename($_GET['filename'] ?? 'report.xlsx');
$path = getenv('SPREADSHEET_TEST_EXPORT_DIRECTORY') . '/' . $filename;
$sheet = new Sheet();
$sheet->body(fn (Builder $body) => $body->row(fn (Row $row) => $row->value('A', 'Downloaded value')));
$sheet->save($path, false);
$sheet->getActiveSheet()->setCellValue('A1', 'Unsaved change');
$sheet->download();
