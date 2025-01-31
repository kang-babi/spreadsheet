<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

trait HasConfigOptions
{
    /**
     * List of options.
     *
     * @var array<string, array<string, array<string, int|string>|string>> $configOptions
     */
    protected array $configOptions = [
        'orientation' => [
            'method'  => 'getPageSetup',
            'action'  => 'setOrientation',
            'options' => [
                'portrait'  => PageSetup::ORIENTATION_PORTRAIT,
                'landscape' => PageSetup::ORIENTATION_LANDSCAPE,
                'default'   => PageSetup::ORIENTATION_DEFAULT,
            ],
        ],
        'fit' => [
            'method'  => 'getPageSetup',
            'options' => [
                'page'   => 'setFitToPage',
                'width'  => 'setFitToWidth',
                'height' => 'setFitToHeight',
            ],
        ],
        'margin' => [
            'method'  => 'getPageMargins',
            'options' => [
                'top'    => 'setTop',
                'bottom' => 'setBottom',
                'left'   => 'setLeft',
                'right'  => 'setRight',
            ],
        ],
        'paperSize' => [
            'method'  => 'getPageSetup',
            'action'  => 'setPaperSize',
            'options' => [
                'letter' => PageSetup::PAPERSIZE_LETTER,
                'legal'  => PageSetup::PAPERSIZE_LEGAL,
                'a4'     => PageSetup::PAPERSIZE_A4,
            ],
        ],
        'repeatRows' => [
            'method' => 'getPageSetup',
            'action' => 'setRowsToRepeatAtTopByStartAndEnd',
        ],
        'columnWidth' => [
            'method' => 'getColumnDimension',
            'action' => 'setWidth',
        ],
    ];

    /**
     * List of columns to be used.
     *
     * @var array<int, string> $columns
     */
    protected array $columns = ['A'];

    /**
     * Container.
     *
     * @var array<string, string> $rows
     */
    protected array $rows = [];
}
