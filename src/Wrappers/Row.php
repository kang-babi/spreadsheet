<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use Closure;
use InvalidArgumentException;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Enums\Row\DataType;
use KangBabi\Spreadsheet\Options\Row\Height;
use KangBabi\Spreadsheet\Options\Row\Merge;
use KangBabi\Spreadsheet\Options\Row\Value;
use KangBabi\Spreadsheet\Options\Row\ValueExplicit;
use KangBabi\Spreadsheet\Text\RichText;
use KangBabi\Spreadsheet\Traits\HasRowOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Row implements WrapperContract
{
    use HasRowOptions;

    /**
     * Constructor.
     */
    public function __construct(
        protected int $row = 1
    ) {
        //
    }

    /**
     * Style builder for the current row.
     */
    public function style(string $cell, Closure $styles): static
    {
        if (str_contains($cell, ':')) {
            $cells = explode(':', $cell);

            $cell = array_map(fn ($cell): string => "{$cell}{$this->row}", $cells);

            $cell = implode(':', $cell);
        } else {
            $cell .= (string) $this->row;
        }

        $instance = new Style($cell);

        $styles($instance);

        $this->styles[] = $instance;

        return $this;
    }

    /**
     * Set value on the cell.
     */
    public function value(string $cell, string|int|float|RichText $value, ?string $dataType = null): static
    {
        $this->values[] = $dataType !== null ?
          new ValueExplicit(
              "$cell{$this->row}",
              $value,
              DataType::from($dataType)
          ) : new Value(
              "$cell{$this->row}",
              $value
          );

        return $this;
    }

    /**
     * Set row height.
     */
    public function height(int|float $height, ?int $rowLine = null): static
    {
        $rowLine = $rowLine !== null && $rowLine !== 0 ? $rowLine : $this->row;

        $this->heights[] = new Height(
            $rowLine,
            $height,
        );

        return $this;
    }

    /**
     * Merge cells on the current row.
     */
    public function merge(string $cell1, string $cell2, bool $isCustom = false): static
    {
        $this->merges[] = new Merge(
            $isCustom ? $cell1 : "{$cell1}{$this->row}",
            $isCustom ? $cell2 : "{$cell2}{$this->row}",
        );

        return $this;
    }

    /**
     * Merge user defined cells.
     *
     * @param array<int, array{0: string, 1: string}> $cells
     */
    public function customMerge(array $cells): static
    {
        foreach ($cells as $cell) {
            if (count($cell) !== 2) {
                throw new InvalidArgumentException('Invalid cell count.');
            }

            $cell['isCustom'] = true;

            $this->merge(...$cell);
        }

        return $this;
    }

    /**
     * Apply row actions to the sheet.
     */
    public function apply(Worksheet $sheet): int
    {
        foreach ($this->heights as $height) {
            $height->apply($sheet);
        }

        foreach ($this->merges as $merge) {
            $merge->apply($sheet);
        }

        foreach ($this->values as $value) {
            $value->apply($sheet);
        }

        foreach ($this->styles as $style) {
            $style->apply($sheet);
        }

        return $this->row;
    }

    /**
     * Get row actions.
     *
     * @return array<string, mixed>
     */
    public function getContent(): array
    {
        return [
            'heights' => $this->heights,
            'merges' => $this->merges,
            'values' => $this->values,
            'styles' => $this->styles,
        ];
    }
}
