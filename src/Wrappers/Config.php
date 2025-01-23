<?php

declare(strict_types=1);

namespace KangBabi\Wrappers;

use Closure;
use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Config implements WrapperContract
{
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

    protected array $columns = ['A'];

    protected array $rows = [];

    public function row(string $config, array|string|Closure|null|int|bool $row): static
    {
        $this->rows[$config][] = $row;

        return $this;
    }

    public function orientation(string $setup = 'default'): static
    {
        $config = $this->configOptions['orientation'];

        $this->row($config['method'], [
            'action' => $config['action'],
            'value'  => $config['options'][$setup],
        ]);

        return $this;
    }

    public function pageFit(string $fit, bool $isFit = true): static
    {
        $config = $this->configOptions['fit'];

        $this->row($config['method'], [
            'action' => $config['options'][$fit],
            'value'  => $config['options'][$fit] === 'setFitToPage' ? $isFit : (int) $isFit,
        ]);

        return $this;
    }

    public function margin(string $direction, int|float $margin): static
    {
        $config = $this->configOptions['margin'];

        $this->row($config['method'], [
            'action' => $config['options'][$direction],
            'value'  => $margin,
        ]);

        return $this;
    }

    public function paperSize(string $paperSize = 'legal'): static
    {
        $config = $this->configOptions['paperSize'];

        $this->row($config['method'], [
            'action' => $config['action'],
            'value'  => $config['options'][$paperSize],
        ]);

        return $this;
    }

    public function columnWidth(string $column, int|float $width): static
    {
        $config = $this->configOptions['columnWidth'];

        $this->columns[] = $column;

        $this->columns = array_unique($this->columns);

        sort($this->columns);

        $this->row($config['method'], [
            'action' => $config['action'],
            'column' => $column,
            'value'  => $width,
        ]);

        return $this;
    }

    public function repeatRows(int $from = 1, int $to = 5): static
    {
        $config = $this->configOptions['repeatRows'];

        $this->row($config['method'], [
            'action' => $config['action'],
            'value'  => [$from, $to],
        ]);

        return $this;
    }

    public function apply(Worksheet $sheet): int
    {
        foreach ($this->rows as $method => $configs) {
            foreach ($configs as $config) {
                if ($method === 'getPageSetup') {
                    if (is_array($config['value'])) {
                        $sheet->$method()->{$config['action']}(...$config['value']);
                    } else {
                        $sheet->$method()->{$config['action']}($config['value']);
                    }

                    continue;
                }

                if ($method === 'getPageMargins') {
                    $sheet->$method()->{$config['action']}($config['value']);

                    continue;
                }

                if ($method === 'getColumnDimension') {
                    $sheet->$method($config['column'])->{$config['action']}($config['value']);

                    continue;
                }
            }
        }

        return 0;
    }

    public function getContent(): array
    {
        return $this->rows;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}
