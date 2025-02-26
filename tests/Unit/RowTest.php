<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Tests\Unit;

use BadMethodCallException;
use InvalidArgumentException;
use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Options\Row\Height;
use KangBabi\Spreadsheet\Options\Row\Merge;
use KangBabi\Spreadsheet\Options\Row\Value;
use KangBabi\Spreadsheet\Options\Row\ValueExplicit;
use KangBabi\Spreadsheet\Wrappers\Row;
use KangBabi\Spreadsheet\Wrappers\Style;

it('instantiates a row', function (): void {
    $row = new Row();
    expect($row)->toBeInstanceOf(Row::class);
    expect($row)->toBeInstanceOf(WrapperContract::class);
});

it('sets style value', function (): void {
    $row = new Row();

    $row->style('A1', function (Style $style): void {
        $style->size(11);
    });

    expect($row->getContent())->toHaveKey('styles');
    expect($row->getContent()['styles'])->toBeArray();
    expect($row->getContent()['styles'][0])->toBeInstanceOf(Style::class);
});

it('sets multiple styles', function (): void {
    $row = new Row();

    $row->style('A1', function (Style $style): void {
        $style
            ->size(11)
            ->bold(true)
            ->alignment('horizontal', 'center');
    });

    expect($row->getContent())->toHaveKey('styles');
    expect($row->getContent()['styles'])->toBeArray();
    expect($row->getContent()['styles'][0])->toBeInstanceOf(Style::class);
});

it('applies multiple styles to worksheet', function (): void {
    $row = new Row();
    $row->style('A1', function (Style $style): void {
        $style
            ->size(11)
            ->bold(true)
            ->alignment('horizontal', 'center');
    });

    $worksheet = (new Sheet())->getActiveSheet();
    $row->apply($worksheet);

    $style = $worksheet->getStyle('A1')->getFont();
    expect((int) $style->getSize())->toBe(11);
    expect($worksheet->getStyle('A1')->getAlignment()->getHorizontal())->toBe('general');
});

it('applies multiple styles to multiple cells to worksheet', function (): void {
    $row = new Row();

    $row->style('A:B', function (Style $style): void {
        $style
            ->size(11)
            ->bold()
            ->alignment('horizontal', 'center');
    });

    $worksheet = (new Sheet())->getActiveSheet();
    $row->apply($worksheet);

    $style = $worksheet->getStyle('A1:B1')->getFont();
    expect((int) $style->getSize())->toBe(11);
    expect($worksheet->getStyle('A1:B1')->getAlignment()->getHorizontal())->toBe('center');
});

it('sets cell value', function (): void {
    $row = new Row();

    $row->value('A', 'Test');

    expect($row->getContent())->toHaveKey('values');
    expect($row->getContent()['values'])->toBeArray();
    expect($row->getContent()['values'][0])->toBeInstanceOf(Value::class);
});

it('sets cell value with data type', function (): void {
    $row = new Row();

    $row
        ->value('A', 'Test A', 'string')
        ->value('B', 123, 'numeric');

    expect($row->getContent())->toHaveKey('values');
    expect($row->getContent()['values'])->toBeArray();
    expect($row->getContent()['values'][0])->toBeInstanceOf(ValueExplicit::class);
});

it('sets height', function (): void {
    $row = new Row();

    $row->height(30);

    expect($row->getContent())->toHaveKey('heights');
    expect($row->getContent()['heights'])->toBeArray();
    expect($row->getContent()['heights'][0])->toBeInstanceOf(Height::class);
});

it('merges cells', function (): void {
    $row = new Row();

    $row->merge('A', 'B');

    expect($row->getContent())->toHaveKey('merges');
    expect($row->getContent()['merges'])->toBeArray();
    expect($row->getContent()['merges'][0])->toBeInstanceOf(Merge::class);
});

it('throws exception when merging invalid cells', function (): void {
    $row = new Row();

    $row->customMerge([['A']]);

    expect($row->getContent()['merges'])->toMatchArray([]);
})->throws(InvalidArgumentException::class);

it('merges custom cells', function (): void {
    $row = new Row();

    $row->customMerge([['A1', 'B2'], ['C3', 'D4']]);

    expect($row->getContent())->toHaveKey('merges');
    expect($row->getContent()['merges'])->toBeArray();
    expect($row->getContent()['merges'])->toHaveCount(2);
});

it('applies row to worksheet', function (): void {
    $row = new Row();
    $row
        ->merge('A', 'B')
        ->value('A', 'Test')
        ->value('C', 3)
        ->break()
        ->value('E', 'Test', 'string')
        ->height(30);

    $worksheet = (new Sheet())->getActiveSheet();
    $row->apply($worksheet);

    expect($worksheet->getCell('A1')->getValue())->toBe('Test');
    expect($worksheet->getRowBreaks()['A1']->getBreakType())->toBe(1);
});

it('sets row break', function (): void {
    $row = new Row();

    $row->break();

    expect($row->getContent())->toHaveKey('break');
    expect($row->getContent()['break'])->toBe(true);
});

it('gets row', function (): void {
    $row = new Row();

    expect($row->getRow())->toBe(1);

    $row = new Row(5);

    expect($row->getRow())->toBe(5);
});

it('macros a row action', function (): void {
    Row::macro('row', function (Row $row): void {
        $row
            ->value('A', 'Has')
            ->value('B', 'Macros');
    });

    expect(Row::hasMacro('row'))->toBe(true);

    Row::flushMacros();
});

it('executes a row macro', function (): void {
    Row::macro('row', function (Row $row): void {
        $row
            ->value('A', 'Has')
            ->value('B', 'Macros');
    });

    $row = new Row();

    $row->call('row', $row);

    expect($row->getContent()['values'])->toHaveCount(2);

    Row::flushMacros();
});

it('flushes macros', function (): void {
    Row::macro('row', function (Row $row): void {
        $row
            ->value('A', 'Has')
            ->value('B', 'Macros');
    });

    expect(Row::hasMacro('row'))->toBe(true);

    Row::flushMacros();

    expect(Row::hasMacro('row'))->toBe(false);
});

it('throws exception if no macro key', function (): void {
    expect((new Row())->call('row'))->toThrow(BadMethodCallException::class);
})->throws(BadMethodCallException::class);

it('throws exception if macro key already exists', function (): void {
    Row::macro('row', function (Row $row): void {
        $row
            ->value('A', 'Has')
            ->value('B', 'Macros');
    });

    Row::macro('row', fn () => true);
})->throws(InvalidArgumentException::class);

it('statically call row macro', function (): void {
    Row::flushMacros();

    Row::macro('row', function (Row $row): void {
        $row
            ->value('A', 'Has')
            ->value('B', 'Macros');
    });

    $row = new Row();

    Row::staticCall('row', $row);

    expect($row->getContent()['values'])->toHaveCount(2);
});

it('throws exception if no static macro key', function (): void {
    Row::flushMacros();

    Row::staticCall('row');
})->throws(BadMethodCallException::class);
