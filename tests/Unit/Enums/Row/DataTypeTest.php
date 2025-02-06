<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Enums\Row\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataType as DataType_;

it('parses cell data type option from string', function (): void {
    expect(DataType::from('string')->get())->toBe(DataType_::TYPE_STRING2);
    expect(DataType::from('numeric')->get())->toBe(DataType_::TYPE_NUMERIC);
    expect(DataType::from('date')->get())->toBe(DataType_::TYPE_ISO_DATE);
    expect(DataType::from('formula')->get())->toBe(DataType_::TYPE_FORMULA);
    expect(DataType::from('bool')->get())->toBe(DataType_::TYPE_BOOL);
});
