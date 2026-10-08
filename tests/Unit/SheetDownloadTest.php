<?php

declare(strict_types=1);

use Symfony\Component\Process\Process;

it('downloads the saved bytes without regenerating or deleting the file', function (): void {
    $process = new Process([PHP_BINARY, '-r', <<<'SCRIPT'
require $argv[1];
$sheet = new KangBabi\Spreadsheet\Sheet();
$path = $sheet->write('download-test-', false);
try {
    $expected = file_get_contents($path);
    $sheet->getActiveSheet()->setCellValue('A1', 'Unsaved change');
    ob_start();
    try {
        $sheet->download();
        $output = ob_get_contents();
    } finally {
        ob_end_clean();
    }
    echo json_encode([
        'matches' => $output === $expected,
        'preserved' => is_file($path) && file_get_contents($path) === $expected,
    ]);
} finally {
    unlink($path);
}
SCRIPT, dirname(__DIR__, 2) . '/vendor/autoload.php']);
    $process->mustRun();

    expect(json_decode($process->getOutput(), true))->toBe([
        'matches' => true,
        'preserved' => true,
    ]);
});

it('rejects downloading after headers have been sent', function (): void {
    $process = new Process([PHP_BINARY, '-r', <<<'SCRIPT'
require $argv[1];
$sheet = new KangBabi\Spreadsheet\Sheet();
$path = $sheet->write('download-headers-', false);
try {
    echo 'sent';
    try {
        $sheet->download();
        exit(1);
    } catch (LogicException $exception) {
        echo ':rejected';
    }
} finally {
    unlink($path);
}
SCRIPT, dirname(__DIR__, 2) . '/vendor/autoload.php']);
    $process->mustRun();

    expect($process->getOutput())->toBe('sent:rejected');
});
