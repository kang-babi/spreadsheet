<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Process\Process;

it('delivers xlsx files over HTTP with the correct file lifecycle', function (string $filename, string $fallback, bool $temporary): void {
    $directory = sys_get_temp_dir() . '/' . uniqid('spreadsheet-http-', true);
    mkdir($directory);
    $socket = stream_socket_server('tcp://127.0.0.1:0');

    if ($socket === false) {
        rmdir($directory);
        throw new RuntimeException('Unable to reserve a test server port.');
    }

    $address = stream_socket_get_name($socket, false);
    fclose($socket);
    $server = new Process(
        [PHP_BINARY, '-S', $address, dirname(__DIR__) . '/Fixtures/download.php'],
        dirname(__DIR__, 2),
        ['SPREADSHEET_TEST_EXPORT_DIRECTORY' => $directory],
    );

    try {
        $server->start();
        $ready = $server->waitUntil(fn (string $type, string $output): bool => str_contains($output, 'Development Server'));
        expect($ready)->toBeTrue();

        $response = (new Client(['timeout' => 5]))->get("http://{$address}/", [
            'query' => array_filter(['filename' => $filename, 'temporary' => $temporary]),
        ]);
        $encodedFilename = rawurlencode($filename);

        expect($response->getStatusCode())->toBe(200);
        expect($response->getHeaderLine('Content-Type'))->toBe('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        expect($response->getHeaderLine('Content-Disposition'))->toBe("attachment; filename=\"{$fallback}\"; filename*=UTF-8''{$encodedFilename}");
        expect($response->getHeaderLine('Cache-Control'))->toBe('max-age=0');

        $path = "{$directory}/{$filename}";
        if ($temporary) {
            $temporaryPath = file_get_contents("{$directory}/temporary-path.txt");
            expect(is_file($temporaryPath))->toBeFalse();
            file_put_contents($path, (string) $response->getBody());
        } else {
            expect(is_file($path))->toBeTrue();
            expect((string) $response->getBody())->toBe(file_get_contents($path));
        }

        $export = IOFactory::load($path);
        expect($export->getActiveSheet()->getCell('A1')->getValue())->toBe('Downloaded value');
        $export->disconnectWorksheets();
    } finally {
        $server->stop();

        foreach (glob("{$directory}/*") as $path) {
            unlink($path);
        }

        rmdir($directory);
    }
})->with([
    'temporary export' => ['report.xlsx', 'report.xlsx', true],
    'plain filename' => ['report.xlsx', 'report.xlsx', false],
    'unicode filename' => ['résumé.xlsx', 'r__sum__.xlsx', false],
    'quoted filename' => ['sales "report".xlsx', 'sales__report_.xlsx', false],
]);
