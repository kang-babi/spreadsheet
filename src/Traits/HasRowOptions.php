<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use PhpOffice\PhpSpreadsheet\Cell\DataType;

trait HasRowOptions
{
    /**
     * @var array<string, string>
     */
    protected array $dataTypes = [
        'string'  => DataType::TYPE_STRING2,
        'numeric' => DataType::TYPE_NUMERIC,
        'date'    => DataType::TYPE_ISO_DATE,
        'formula' => DataType::TYPE_FORMULA,
        'bool'    => DataType::TYPE_BOOL,
    ];

    /**
     * @var array<string, array<string, string>>
     */
    protected array $rowOptions = [
        'height' => [
            'method' => 'getRowDimension',
            'option' => 'setRowHeight',
        ],
        'merge' => [
            'method' => 'mergeCells',
        ],
        'value' => [
            'method' => 'setCellValue',
        ],
        'style' => [
            'method' => 'getStyle',
        ],
    ];

    /**
     * @var array<string, array<int, array<string, string|int|null>>>
     */
    protected array $contents = [];
}
