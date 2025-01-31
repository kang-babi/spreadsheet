<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use Closure;
use InvalidArgumentException;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Traits\HasRowOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Row implements WrapperContract
{
    use HasRowOptions;

    /**
     * Constructor.
     */
    public function __construct(protected int $row = 1)
    {
        //
    }

    /**
     * Style builder.
     */
    public function style(string $cell, Closure $styles): static
    {

        $row = $this->rowOptions['style'];

        if (str_contains($cell, ':')) {
            $cells = explode(':', $cell);

            $cell = array_map(fn ($cell): string => "{$cell}{$this->row}", $cells);

            $cell = implode(':', $cell);
        } else {
            $cell .= (string) $this->row;
        }

        $instance = new Style($cell);

        $styles($instance);

        $this->row($row['method'], $instance);

        return $this;
    }

    /**
     * Groups actions on the row.
     *
     * @param array<string, string|int|null|Style>|string|Style|null $value
     */
    public function row(string $key, array|string|Style|null $value): static
    {
        $this->contents[$key][] = $value;

        return $this;
    }

    /**
     * Set value on the cell.
     */
    public function value(string $cell, string|int $value, ?string $dataType = null): static
    {
        $row = $this->rowOptions['value'];

        if ($dataType !== null) {
            $dataType = $this->dataTypes[$dataType] ?: $dataType;
        }

        $this->row($row['method'], [
            'cell'     => "{$cell}{$this->row}",
            'value'    => $value,
            'dataType' => $dataType,
        ]);

        return $this;
    }

    /**
     * Set row height.
     */
    public function height(int|float $height, ?int $rowLine = null): static
    {
        $row = $this->rowOptions['height'];

        if ($rowLine === null) {
            $rowLine = $this->row;
        }

        $this->row($row['method'], [
            'action' => $row['option'],
            'row'    => $rowLine,
            'height' => $height,
        ]);

        return $this;
    }

    /**
     * Merge cells on the current row.
     */
    public function merge(string $cell1, string $cell2): static
    {
        $row = $this->rowOptions['merge'];

        $this->row($row['method'], "{$cell1}{$this->row}:{$cell2}{$this->row}");

        return $this;
    }

    /**
     * Merge user defined cells.
     *
     * @param array<int, array{0: string, 1: string}> $cells
     */
    public function customMerge(array $cells): static
    {
        $row = $this->rowOptions['merge'];

        foreach ($cells as $cell) {
            if (count($cell) !== 2 || count(array_filter($cell, fn ($item): bool => trim($item) !== '')) !== 2) {
                throw new InvalidArgumentException('Invalid cell count');
            }

            [$cell1, $cell2] = $cell;

            $this->row($row['method'], "{$cell1}:{$cell2}");
        }

        return $this;
    }

    /**
     * Write row actions to the sheet.
     */
    public function apply(Worksheet $sheet): int
    {
        foreach ($this->contents as $method => $actions) {
            foreach ($actions as $action) {
                if ($method === 'getRowDimension') {
                    $sheet->$method($action['row'])->{$action['action']}($action['height']);

                    continue;
                }

                if ($method === 'mergeCells') {
                    $sheet->$method($action);

                    continue;
                }

                if ($method === 'getStyle') {
                    foreach ($actions as $action) {
                        if ($action instanceof Style) {
                            $action->apply($sheet);
                        }
                    }
                }

                if ($method === 'setCellValue') {
                    if ($action['dataType'] !== null) {
                        $method = "{$method}Explicit";

                        $sheet->$method($action['cell'], $action['value'], $action['dataType']);
                    } else {
                        $sheet->$method($action['cell'], $action['value']);
                    }

                    continue;
                }
            }
        }

        return $this->row;
    }

    /**
     * Get row actions.
     *
     * @return array<string, array<int, array<string, string|int|null>>>
     */
    public function getContent(): array
    {
        return $this->contents;
    }
}
