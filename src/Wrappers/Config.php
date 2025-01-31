<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Traits\HasConfigOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Config implements WrapperContract
{
    use HasConfigOptions;

    /**
     * Groups config options.
     *
     * @param array<string, int|array<int|string, int|string>|string> $row
     */
    public function row(string $config, array $row): static
    {
        if (!isset($this->rows[$config])) {
            $this->rows[$config] = [];
        }

        $this->rows[$config][] = $row;

        return $this;
    }

    /**
     * Set page orientation.
     */
    public function orientation(string $setup = 'default'): static
    {
        $config = $this->configOptions['orientation'];

        $this->row($config['method'], [
            'action' => $config['action'],
            'value'  => $config['options'][$setup],
        ]);

        return $this;
    }

    /**
     * Set fit on page.
     */
    public function pageFit(string $fit, bool $isFit = true): static
    {
        $config = $this->configOptions['fit'];

        $this->row($config['method'], [
            'action' => $config['options'][$fit],
            'value'  => $config['options'][$fit] === 'setFitToPage' ? $isFit : (int) $isFit,
        ]);

        return $this;
    }

    /**
     * Set page margin.
     */
    public function margin(string $direction, int|float $margin): static
    {
        $config = $this->configOptions['margin'];

        $this->row($config['method'], [
            'action' => $config['options'][$direction],
            'value'  => $margin,
        ]);

        return $this;
    }

    /**
     * Set paper size.
     */
    public function paperSize(string $paperSize = 'legal'): static
    {
        $config = $this->configOptions['paperSize'];

        $this->row($config['method'], [
            'action' => $config['action'],
            'value'  => $config['options'][$paperSize],
        ]);

        return $this;
    }

    /**
     * Set column width.
     */
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

    /**
     * Repeat rows per page.
     */
    public function repeatRows(int $from = 1, int $to = 5): static
    {
        $config = $this->configOptions['repeatRows'];

        $this->row($config['method'], [
            'action' => $config['action'],
            'value'  => [$from, $to],
        ]);

        return $this;
    }

    /**
     * Write on the sheet.
     */
    public function apply(Worksheet $sheet): int
    {
        foreach ($this->rows as $method => $configs) {
            if (is_array($configs)) { # for coverage
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
        }

        return 0;
    }

    /**
     * Get configurations.
     *
     * @return array<array<array<string, string>|bool|int|string|null>|string>
     */
    public function getContent(): array
    {
        return $this->rows;
    }

    /**
     * Get adjusted columns.
     *
     * @return array<int, string>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
