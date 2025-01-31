<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Traits\HasStyleOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Style implements WrapperContract
{
    use HasStyleOptions;

    public function __construct(
        protected string $cell,
    ) {
        //
    }

    public function alignment(string $axis, string $direction = 'default'): static
    {
        $style = $this->styleOptions['alignment'];

        $this->style($style['key'], [
            $axis => $style[$axis][$direction],
        ]);

        return $this;
    }

    public function border(string $location, string $type = 'default'): static
    {
        $style = $this->styleOptions['border'];

        $this->style($style['key'], [
            $style['location'][$location] => [
                'borderStyle' => $style['style'][$type],
            ],
        ]);

        return $this;
    }

    public function fontName(string $name): static
    {
        return $this->font('name', $name);
    }

    public function size(int $size): static
    {
        return $this->font('size', $size);
    }

    public function bold(bool $bold = true): static
    {
        return $this->font('bold', $bold);
    }

    public function italic(bool $italic = true): static
    {
        return $this->font('italic', $italic);
    }

    public function underline(string $underline = 'default'): static
    {
        $style = $this->styleOptions['font']['underline'];

        return $this->font('underline', $style[$underline]);
    }

    public function strikethrough(bool $strikethrough = true): static
    {
        return $this->font('strike', $strikethrough);
    }

    public function font(string $option, string|int|bool $value): static
    {
        $style = $this->styleOptions['font'];

        $this->style($style['key'], [
            $style['options'][$option] => $value,
        ]);

        return $this;
    }

    /**
     * @param array<string, bool|int|string|array<string, int|string>> $value
     */
    public function style(string $key, array $value): static
    {
        $this->styles[$key] = $this->mergeKeyValues($key, $value);

        return $this;
    }

    /**
     * @param array<string, array<string, string|bool>|string|bool> $value
     * @return array<string, array<string, string|bool>|string|bool>
     */
    private function mergeKeyValues(string $key, array $value): array
    {
        return array_merge($this->styles[$key] ?? [], $value);
    }

    public function apply(Worksheet $sheet): int
    {
        $sheet->getStyle($this->cell)
            ->applyFromArray($this->styles);

        return 0;
    }

    /**
     * @return array<string, array<string, bool|int|string>>
     */
    public function getContent(): array
    {
        return $this->styles;
    }

    public function getCell(): string
    {
        return $this->cell;
    }
}
