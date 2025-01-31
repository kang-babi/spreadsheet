<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use KangBabi\Spreadsheet\Wrappers\Row;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

test('instantiates a sheet', function (): void {
    $sheet = new Sheet();

    expect($sheet)->toBeInstanceOf(Sheet::class);
});

it('wraps config', function (): void {
    $sheet = new Sheet();
    $config = new Config();

    $sheet->wrap('config', $config);

    expect($sheet->getConfig())->toBeInstanceOf(Config::class);
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

it('generates an xlsx file', function (): void {
    $sheet = new Sheet();
    $tempFile = $sheet->write('test', false);

    $this->assertFileExists($tempFile);

    $fileContent = file_get_contents($tempFile);
    $this->assertStringStartsWith('PK', mb_substr($fileContent, 0, 2));

    unlink($tempFile);
});

it('saves sheet', function (): void {
    $client = new Client();

    $response = $client->get('http://spreadsheet.test');

    $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->getHeaderLine('Content-Type'));
    $this->assertStringContainsString('attachment;filename="COR.xlsx"', $response->getHeaderLine('Content-Disposition'));
});

it('downloads sheet', function (): void {
    $client = new Client();
    $response = $client->get('http://spreadsheet.test');

    ob_start();

    (new Sheet())
        ->header(function (Builder $header): void {
            $header
                ->row(function (Row $row): void {
                    $row->value('A', 'test1');
                });
        })
        ->save('COR', true);

    ob_end_clean();

    $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->getHeaderLine('Content-Type'));
    $this->assertStringContainsString('attachment;filename="COR.xlsx"', $response->getHeaderLine('Content-Disposition'));
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
