<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Wrappers;

use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Enums\Style\AlignmentOption;
use KangBabi\Spreadsheet\Enums\Style\BorderLocationOption;
use KangBabi\Spreadsheet\Enums\Style\BorderStyleOption;
use KangBabi\Spreadsheet\Enums\Style\HorizontalAlignmentOption;
use KangBabi\Spreadsheet\Enums\Style\Underline;
use KangBabi\Spreadsheet\Enums\Style\VerticalAlignmentOption;
use KangBabi\Spreadsheet\Options\Style\Border;
use KangBabi\Spreadsheet\Options\Style\Font;
use KangBabi\Spreadsheet\Options\Style\HorizontalAlignment;
use KangBabi\Spreadsheet\Options\Style\VerticalAlignment;
use KangBabi\Spreadsheet\Traits\HasStyleOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Style implements WrapperContract
{
    use HasStyleOptions;

    /**
     * Constructor.
     */
    public function __construct(
        protected string $cell,
    ) {
        $this->font = new Font();
    }

    /**
     * Set alignment.
     */
    public function alignment(string $axis, string $direction = 'default'): static
    {
        $this->alignments[] = AlignmentOption::from($axis) === AlignmentOption::HORIZONTAL ?
          new HorizontalAlignment(
              HorizontalAlignmentOption::from($direction)
          ) :
          new VerticalAlignment(
              VerticalAlignmentOption::from($direction)
          );

        return $this;
    }

    /**
     * Set border.
     */
    public function border(string $location, string $type = 'default'): static
    {
        $this->borders[] = new Border(
            BorderLocationOption::from($location),
            BorderStyleOption::from($type),
        );

        return $this;
    }

    /**
     * Set font name.
     */
    public function fontName(string $name): static
    {
        $this->font->name = $name;

        return $this;
    }

    /**
     * Set font size.
     */
    public function size(int $size): static
    {
        $this->font->size = $size;

        return $this;
    }

    /**
     * Set bold.
     */
    public function bold(bool $bold = true): static
    {
        $this->font->bold = $bold;

        return $this;
    }

    /**
     * Set italic.
     */
    public function italic(bool $italic = true): static
    {
        $this->font->italic = $italic;

        return $this;
    }

    /**
     * Set underline type.
     */
    public function underline(string $underline = 'default'): static
    {
        $this->font->underline = Underline::from($underline);

        return $this;
    }

    /**
     * Set strikethrough
     */
    public function strikethrough(bool $strikethrough = true): static
    {
        $this->font->strikethrough = $strikethrough;

        return $this;
    }

    /**
     * Write styles to sheet.
     */
    public function apply(Worksheet $sheet): int
    {
        $styles = [
            'alignment' => [],
            'borders' => [],
            'font' => [],
        ];

        foreach ($this->alignments as $alignment) {
            $styles['alignment'][$alignment->alignment] = $alignment->option->get();
        }

        foreach ($this->borders as $border) {
            $styles['borders'][$border->location->get()] = $border->get();
        }

        $styles['font'] = $this->font->get();

        $sheet
            ->getStyle($this->cell)
            ->applyFromArray($styles);

        return 0;
    }

    /**
     * Get styles.
     *
     * @return array<string, mixed>
     */
    public function getContent(): array
    {
        return [
            'alignments' => $this->alignments,
            'borders' => $this->borders,
            'font' => $this->font,
        ];
    }

    /**
     * Get cell.
     */
    public function getCell(): string
    {
        return $this->cell;
    }
}
