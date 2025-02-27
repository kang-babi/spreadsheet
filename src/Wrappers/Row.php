<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use Closure;
use InvalidArgumentException;
use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Enums\Row\DataType;
use KangBabi\Spreadsheet\Options\Row\Height;
use KangBabi\Spreadsheet\Options\Row\Merge;
use KangBabi\Spreadsheet\Options\Row\RowBreak;
use KangBabi\Spreadsheet\Options\Row\Value;
use KangBabi\Spreadsheet\Options\Row\ValueExplicit;
use KangBabi\Spreadsheet\Misc\RichText;
use KangBabi\Spreadsheet\Traits\HasMacros;
use KangBabi\Spreadsheet\Traits\HasRowOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Row implements WrapperContract
{
    use HasMacros;
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
        $cell = $this->parseCell($cell);

        $instance = new Style($cell);

        $styles($instance);

        $this->styles[] = $instance;

        return $this;
    }

    /**
     * Set page break
     */
    public function break(): static
    {
        $this->break = true;

        return $this;
    }

    /**
     * Set value on the cell.
     */
    public function value(string $cell, string|int|float|RichText $value, ?string $dataType = null): static
    {
        $this->values[] = $dataType !== null ?
          new ValueExplicit(
              $this->cellReference($cell),
              $value,
              DataType::from($dataType)
          ) : new Value(
              $this->cellReference($cell),
              $value
          );

        return $this;
    }

    /**
     * Set row height.
     */
    public function height(int|float $height, ?int $rowLine = null): static
    {
        $rowLine = $this->parseRow($rowLine ??= $this->row);

        $this->heights[] = new Height(
            $rowLine,
            $height,
        );

        return $this;
    }

    /**
     * Merge cells on the corresponding row.
     */
    public function merge(string $cell1, string $cell2, bool $isCustom = false): static
    {
        $this->merges[] = new Merge(
            $this->cellReference($cell1, $isCustom),
            $this->cellReference($cell2, $isCustom),
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
            $this->validateCell($cell);

            [$cell1, $cell2] = $cell;

            $this->merge($cell1, $cell2, true);
        }

        return $this;
    }

    /**
     * Apply row actions to the sheet.
     */
    public function apply(Worksheet $sheet): int
    {
        $this->applyHeights($sheet);

        $this->applyMerges($sheet);

        $this->applyValues($sheet);

        $this->applyStyles($sheet);

        if ($this->break) {
            (new RowBreak($this->row))->apply($sheet);
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
            'break' => $this->break,
        ];
    }

    /**
     * Get the row.
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * Parses cell reference.
     */
    protected function parseCell(string $cell): string
    {
        return str_contains($cell, ':') ?
          $this->cellRangeReference($cell) :
          $this->cellReference($cell);
    }

    /**
     * Adds current row to cell group.
     */
    protected function cellRangeReference(string $cell): string
    {
        $cells = explode(':', $cell);

        $cell = array_map(fn ($cell): string => $this->cellReference($cell), $cells);

        return implode(':', $cell);
    }

    /**
     * Get the row line.
     */
    protected function parseRow(int $rowLine): int
    {
        return $rowLine !== 0 ? $rowLine : $this->row;
    }

    /**
     * Parse cell reference.
     */
    protected function cellReference(string $cell, bool $isCustom = false): string
    {
        return $isCustom ? $cell : "{$cell}{$this->row}";
    }

    /**
     * Validate cell count.
     *
     * @param array<int, string> $cell
     *
     * @throws InvalidArgumentException
     */
    protected function validateCell(array $cell): void
    {
        if (count($cell) !== 2) {
            throw new InvalidArgumentException('Invalid cell count.');
        }
    }

    /**
     * Apply Heights.
     */
    protected function applyHeights(Worksheet $sheet): void
    {
        array_map(fn (Height $height) => $height->apply($sheet), $this->heights);
    }

    /**
     * Apply Merges.
     */
    protected function applyMerges(Worksheet $sheet): void
    {
        array_map(fn (Merge $merge) => $merge->apply($sheet), $this->merges);
    }

    /**
     * Apply Values.
     */
    protected function applyValues(Worksheet $sheet): void
    {
        array_map(fn (Value|ValueExplicit $value) => $value->apply($sheet), $this->values);
    }

    /**
     * Apply Styles.
     */
    protected function applyStyles(Worksheet $sheet): void
    {
        array_map(fn (Style $style): int => $style->apply($sheet), $this->styles);
    }
}
