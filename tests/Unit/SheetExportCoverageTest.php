<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Unit;

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\SheetFunctionFailures;
use KangBabi\Spreadsheet\Tests\TestCase;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use RuntimeException;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class SheetExportCoverageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require dirname(__DIR__) . '/Support/SheetFunctionFailures.php';
    }

    /** @return array<string, array{string, bool}> */
    public static function exports(): array
    {
        return [
            'persistent export' => ['report.xlsx', false],
            'temporary export' => ['résumé.xlsx', true],
            'temporary export without extension' => ['report', true],
        ];
    }

    #[DataProvider('exports')]
    public function test_download_sends_saved_bytes_and_headers(string $filename, bool $temporary): void
    {
        $sheet = new Sheet();
        $path = $temporary ? $sheet->write($filename, false) : tempnam(sys_get_temp_dir(), 'saved-export-');

        try {
            if (!$temporary) {
                $sheet->save($path, false);
            }

            $expected = file_get_contents($path);
            $sheet->getActiveSheet()->setCellValue('A1', 'Unsaved change');
            ob_start();

            try {
                $sheet->download();
                $actual = ob_get_contents();
            } finally {
                ob_end_clean();
            }

            $downloadName = $temporary ? (str_ends_with($filename, '.xlsx') ? $filename : $filename . '.xlsx') : basename($path);
            $fallback = preg_replace('/[^A-Za-z0-9._-]/', '_', $downloadName);
            $encoded = rawurlencode($downloadName);

            self::assertSame($expected, $actual);
            self::assertSame(!$temporary, is_file($path));
            self::assertSame([
                'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                "Content-Disposition: attachment; filename=\"{$fallback}\"; filename*=UTF-8''{$encoded}",
                'Cache-Control: max-age=0',
            ], SheetFunctionFailures::$headers);
        } finally {
            $sheet->cleanup();

            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function test_temporary_file_creation_failure_is_reported(): void
    {
        SheetFunctionFailures::$operation = 'tempnam';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to create a temporary spreadsheet file.');

        (new Sheet())->write('failed-export-');
    }

    public function test_download_open_failure_cleans_up_the_temporary_export(): void
    {
        $sheet = new Sheet();
        $path = $sheet->write('open-failure-', false);
        SheetFunctionFailures::$operation = 'fopen';

        try {
            $sheet->download();
            self::fail('Expected an open failure.');
        } catch (RuntimeException $exception) {
            self::assertSame('Unable to open the saved spreadsheet.', $exception->getMessage());
            self::assertFileDoesNotExist($path);
            self::assertSame([], SheetFunctionFailures::$headers);
        } finally {
            SheetFunctionFailures::$operation = null;
            $sheet->cleanup();
        }
    }

    public function test_sent_headers_reject_download_and_clean_up(): void
    {
        $sheet = new Sheet();
        $path = $sheet->write('sent-headers-', false);
        SheetFunctionFailures::$operation = 'headers_sent';

        try {
            $sheet->download();
            self::fail('Expected sent headers to reject the download.');
        } catch (LogicException $exception) {
            self::assertSame('Cannot download the spreadsheet after headers have been sent.', $exception->getMessage());
            self::assertFileDoesNotExist($path);
            self::assertSame([], SheetFunctionFailures::$headers);
        } finally {
            $sheet->cleanup();
        }
    }

    public function test_unlink_failure_keeps_ownership_for_a_cleanup_retry(): void
    {
        $sheet = new Sheet();
        $path = $sheet->write('unlink-failure-', false);
        SheetFunctionFailures::$operation = 'unlink';

        try {
            $sheet->cleanup();
            self::fail('Expected deletion to fail.');
        } catch (RuntimeException $exception) {
            self::assertSame('Unable to delete the temporary spreadsheet.', $exception->getMessage());
            self::assertFileExists($path);
        } finally {
            SheetFunctionFailures::$operation = null;
            $sheet->cleanup();
        }

        self::assertFileDoesNotExist($path);
    }

    public function test_regex_failure_does_not_change_row_height(): void
    {
        $sheet = new Sheet();
        $sheet->getActiveSheet()->setCellValue('A1', "First\nSecond");
        SheetFunctionFailures::$operation = 'preg_match_all';
        $path = $sheet->write('regex-failure-');

        try {
            self::assertSame(-1.0, $sheet->getActiveSheet()->getRowDimension(1)->getRowHeight());
            self::assertTrue($sheet->getActiveSheet()->getStyle('A1')->getAlignment()->getWrapText());
            self::assertFileExists($path);
        } finally {
            $sheet->cleanup();
        }
    }
}
