<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use KangBabi\Spreadsheet\Wrappers\Row;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

test('instantiates a sheet', function (): void {
    $sheet = new Sheet();

    expect($sheet)->toBeInstanceOf(Sheet::class);
});

it('sets config', function (): void {
    $sheet = new Sheet();

    $sheet->config(function (Config $config): void {
        $config->orientation('landscape');
    });

    expect($sheet->getConfig())->toBeInstanceOf(Config::class);
});

it('sets header', function (): void {
    $sheet = new Sheet();

    $sheet->header(function (Builder $builder): void {
        $builder->row(function (Row $row): void {
            $row->value('A2', 'Header');
        });
    });

    expect($sheet->getHeader())->toBeInstanceOf(Builder::class);
});

it('sets body', function (): void {
    $sheet = new Sheet();

    $sheet->body(function (Builder $builder): void {
        $builder->row(function (Row $row): void {
            $row->value('A2', 'Body');
        });
    });

    expect($sheet->getBody())->toBeInstanceOf(Builder::class);
});

it('sets footer', function (): void {
    $sheet = new Sheet();

    $sheet->footer(function (Builder $builder): void {
        $builder->row(function (Row $row): void {
            $row->value('A3', 'Footer');
        });
    });

    expect($sheet->getFooter())->toBeInstanceOf(Builder::class);
});

it('generates a readable temporary xlsx file', function (): void {
    $sheet = new Sheet();
    $sheet->body(fn (Builder $body) => $body->row(fn (Row $row) => $row->value('A', 'Exported value')));

    $path = $sheet->write('spreadsheet-test-', false);

    try {
        $export = IOFactory::load($path);

        expect($export->getActiveSheet()->getCell('A1')->getValue())->toBe('Exported value');
        expect($export->getActiveSheet()->getStyle('A1')->getAlignment()->getWrapText())->toBeFalse();
    } finally {
        unlink($path);
    }
});

it('saves an xlsx file to the requested path without output', function (): void {
    $directory = sys_get_temp_dir() . '/' . uniqid('spreadsheet-save-', true);
    mkdir($directory);
    $path = $directory . '/report.xlsx';
    $sheet = new Sheet();
    $sheet->body(fn (Builder $body) => $body->row(fn (Row $row) => $row->value('A', 'Saved value')));

    try {
        ob_start();

        try {
            $sheet->save($path);
            $output = ob_get_contents();
        } finally {
            ob_end_clean();
        }

        $export = IOFactory::load($path);

        expect($output)->toBe('');
        expect($export->getActiveSheet()->getCell('A1')->getValue())->toBe('Saved value');
        expect($export->getActiveSheet()->getStyle('A1')->getAlignment()->getWrapText())->toBeTrue();
    } finally {
        if (is_file($path)) {
            unlink($path);
        }

        rmdir($directory);
    }
});

it('writes xlsx bytes to the output stream', function (): void {
    ob_start();

    try {
        (new Sheet())->save('php://output', false);
        $output = ob_get_contents();
    } finally {
        ob_end_clean();
    }

    expect($output)->toStartWith('PK');
});

it('removes its temporary file when exporting fails', function (): void {
    $prefix = uniqid('export-failure-');
    $sheet = new class extends Sheet
    {
        public function save(string $path, bool $wrapText = true): void
        {
            throw new RuntimeException('Export failed.');
        }
    };

    expect(fn () => $sheet->write($prefix))->toThrow(RuntimeException::class, 'Export failed.');
    expect(glob(sys_get_temp_dir() . '/' . $prefix . '*'))->toBe([]);
});

it('wraps text', function (): void {
    $sheet = new Sheet();

    $sheet->config(function (Config $config): void {
        $config->columnWidth('A', 20);
    });

    $reflection = new ReflectionClass($sheet);
    $method = $reflection->getMethod('wrapText');
    $method->setAccessible(true);
    $method->invoke($sheet);

    expect(true)->toBeTrue(); // Just to ensure the method runs without errors
});

it('gets spreadsheet instance', function (): void {
    $sheet = new Sheet();

    expect($sheet->getSpreadsheetInstance())->toBeInstanceOf(Spreadsheet::class);
});

it('gets active sheet', function (): void {
    $sheet = new Sheet();

    expect($sheet->getActiveSheet())->toBeInstanceOf(Worksheet::class);
});

it('gets config', function (): void {
    $sheet = new Sheet();

    $sheet->config(function (Config $config): void {
        $config->orientation('landscape');
    });

    expect($sheet->getConfig())->toBeInstanceOf(Config::class);
});

it('gets header', function (): void {
    $sheet = new Sheet();

    $sheet->header(function (Builder $builder): void {
        $builder->row(function (Row $row): void {
            $row->value('A2', 'Header');
        });
    });

    expect($sheet->getHeader())->toBeInstanceOf(Builder::class);
});

it('gets body', function (): void {
    $sheet = new Sheet();

    $sheet->body(function (Builder $builder): void {
        $builder->row(function (Row $row): void {
            $row->value('A2', 'Body');
        });
    });

    expect($sheet->getBody())->toBeInstanceOf(Builder::class);
});

it('gets footer', function (): void {
    $sheet = new Sheet();

    $sheet->footer(function (Builder $builder): void {
        $builder->row(function (Row $row): void {
            $row->value('A3', 'Footer');
        });
    });

    expect($sheet->getFooter())->toBeInstanceOf(Builder::class);
});
