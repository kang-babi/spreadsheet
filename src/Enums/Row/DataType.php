<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Row;

use PhpOffice\PhpSpreadsheet\Cell\DataType as DataType_;

enum DataType: string
{
    case STRING = 'string';
    case NUMERIC = 'numeric';
    case DATE = 'date';
    case FORMULA = 'formula';
    case BOOL = 'bool';

    public function get(): string
    {
        return match ($this) {
            self::STRING => DataType_::TYPE_STRING2,
            self::NUMERIC => DataType_::TYPE_NUMERIC,
            self::DATE => DataType_::TYPE_ISO_DATE,
            self::FORMULA => DataType_::TYPE_FORMULA,
            self::BOOL => DataType_::TYPE_BOOL,
        };
    }
}
