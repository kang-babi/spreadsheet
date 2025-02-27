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
use KangBabi\Spreadsheet\Traits\HasMacros;
use KangBabi\Spreadsheet\Traits\HasStyleOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Style implements WrapperContract
{
    use HasMacros;
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
    public function alignment(AlignmentOption|string $axis, string $direction = 'default'): static
    {
        $axis = is_string($axis) ? AlignmentOption::from($axis) : $axis;

        $this->alignments[] = $this->resolveAlignment($axis, $direction);

        return $this;
    }

    /**
     * Set horizontal alignment.
     */
    public function horizontal(string $direction = 'default'): static
    {
        return $this->alignment(AlignmentOption::HORIZONTAL, $direction);
    }

    /**
     * Set vertical alignment.
     */
    public function vertical(string $direction = 'default'): static
    {
        return $this->alignment(AlignmentOption::VERTICAL, $direction);
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
        $styles = array_merge(
            $this->parseAlignments($this->alignments),
            $this->parseBorders($this->borders),
            $this->parseFont($this->font),
        );

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

    protected function resolveAlignment(AlignmentOption $axis, string $direction): HorizontalAlignment|VerticalAlignment
    {
        if ($axis === AlignmentOption::HORIZONTAL) {
            return new HorizontalAlignment(
                HorizontalAlignmentOption::from($direction)
            );
        }

        return new VerticalAlignment(
            VerticalAlignmentOption::from($direction)
        );
    }

    /**
     * Extract Alignment options.
     *
     * @param array<int, HorizontalAlignment|VerticalAlignment> $alignments
     *
     * @return array{alignment: array<string, string>}
     */
    protected function parseAlignments(array $alignments): array
    {
        return [
            'alignment' => array_merge(
                ...array_map(fn (VerticalAlignment|HorizontalAlignment $alignment) => [
                    $alignment->alignment => $alignment->option->get()
                ], $alignments)
            )
        ];
    }

    /**
     * Extract Border options.
     *
     * @param array<int, Border> $borders
     *
     * @return array{borders: array<string, array<string, string>>}
     */
    protected function parseBorders(array $borders): array
    {
        return [
            'borders' => array_merge(
                ...array_map(fn (Border $border) => [
                    $border->location->get() => $border->get()
                ], $borders)
            )
        ];
    }

    /**
     * Extract Font options.
     *
     * @return array{font: array<bool|int|string>}
     */
    protected function parseFont(Font $font): array
    {
        return [
            'font' => $font->get()
        ];
    }
}
