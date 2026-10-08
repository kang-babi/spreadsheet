<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;

it('removes the previous temporary export before writing another', function (): void {
    $sheet = new Sheet();
    $first = $sheet->write('cleanup-first-', false);

    try {
        $second = $sheet->write('cleanup-second-', false);

        expect(is_file($first))->toBeFalse();
        expect(is_file($second))->toBeTrue();
    } finally {
        $sheet->cleanup();
    }
});

it('removes a temporary export when switching to a persistent save', function (): void {
    $sheet = new Sheet();
    $temporary = $sheet->write('cleanup-switch-', false);
    $persistent = tempnam(sys_get_temp_dir(), 'persistent-export-');

    try {
        $sheet->save($persistent, false);
        $sheet->cleanup();

        expect(is_file($temporary))->toBeFalse();
        expect(is_file($persistent))->toBeTrue();
    } finally {
        $sheet->cleanup();
        unlink($persistent);
    }
});

it('allows explicit cleanup of an abandoned export more than once', function (): void {
    $sheet = new Sheet();
    $path = $sheet->write('cleanup-abandoned-', false);

    $sheet->cleanup();
    $sheet->cleanup();

    expect(is_file($path))->toBeFalse();
    expect(fn () => $sheet->download())->toThrow(LogicException::class);
});

it('keeps ownership when deletion fails so cleanup can be retried', function (): void {
    $sheet = new Sheet();
    $path = $sheet->write('cleanup-retry-', false);
    unlink($path);
    mkdir($path);

    try {
        expect(fn () => $sheet->cleanup())->toThrow(RuntimeException::class, 'Unable to delete the temporary spreadsheet.');
        expect(fn () => $sheet->write('replacement-', false))->toThrow(RuntimeException::class);

        rmdir($path);
        file_put_contents($path, 'Restored owned file');
        $sheet->cleanup();

        expect(file_exists($path))->toBeFalse();
    } finally {
        if (is_dir($path)) {
            rmdir($path);
        }

        $sheet->cleanup();
    }
});

it('retains the original download exception when cleanup also fails', function (): void {
    $sheet = new Sheet();
    $path = $sheet->write('cleanup-exception-', false);
    unlink($path);
    mkdir($path);

    try {
        $exception = null;

        try {
            $sheet->download();
        } catch (RuntimeException $caught) {
            $exception = $caught;
        }

        expect($exception)->toBeInstanceOf(RuntimeException::class);
        expect($exception->getMessage())->toBe('Unable to delete the temporary spreadsheet.');
        expect($exception->getPrevious())->toBeInstanceOf(RuntimeException::class);
        expect($exception->getPrevious()->getMessage())->toBe('The saved spreadsheet is missing or unreadable.');
    } finally {
        rmdir($path);
        $sheet->cleanup();
    }
});

it('preserves a persistent export when creating and cleaning a temporary export', function (): void {
    $sheet = new Sheet();
    $persistent = tempnam(sys_get_temp_dir(), 'retained-export-');

    try {
        $sheet->save($persistent, false);
        $path = $sheet->write('new-temporary-', false);
        $sheet->cleanup();

        expect(is_file($persistent))->toBeTrue();
        expect(is_file($path))->toBeFalse();
    } finally {
        $sheet->cleanup();
        unlink($persistent);
    }
});
