<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Misc;

use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class Image
{
    private static self $instance;
    private readonly Drawing $drawing;

    /**
     * Constructor.
     *
     * @param string $cell The cell the image will be placed
     */
    public function __construct(
        public string $cell,
    ) {
        $this->drawing = new Drawing();

        $this->drawing->setCoordinates($this->cell);
    }

    /**
     * Statically initialize.
     */
    public static function from(string $cell): static
    {
        static::$instance = new self($cell);

        return static::$instance;
    }

    /**
     * Set name.
     */
    public function name(string $name): static
    {
        $this->drawing->setName($name);

        return $this;
    }

    /**
     * Set description.
     */
    public function description(string $description): static
    {
        $this->drawing->setDescription($description);

        return $this;
    }

    /**
     * Set image source.
     *
     * @param string $source The path to the image file
     *
     * @throws InvalidArgumentException
     */
    public function source(string $source): static
    {
        if (!file_exists($source)) {
            throw new InvalidArgumentException("Image file does not exist: $source");
        }

        $this->drawing->setPath($source);

        return $this;
    }

    /**
     * Overwrite cell coordinate.
     */
    public function cell(string $cell): static
    {
        $this->drawing->setCoordinates($cell);

        return $this;
    }

    /**
     * Make the image span to this cell.
     */
    public function extend(string $cell): static
    {
        $this->drawing->setCoordinates2($cell);

        return $this;
    }

    /**
     * Pad image horizontally.
     *
     * @param bool $isFlipped Pad on the left or right side
     */
    public function padX(int $pad, bool $isFlipped = false): static
    {
        $isFlipped ? $this->drawing->setOffsetX($pad) : $this->drawing->setOffsetX2($pad);

        return $this;
    }

    /**
     * Pad image vertically.
     *
     * @param bool $isFlipped Pad image on the top or bottom side
     */
    public function padY(int $pad, bool $isFlipped = false): static
    {
        $isFlipped ? $this->drawing->setOffsetY($pad) : $this->drawing->setOffsetY2($pad);

        return $this;
    }

    /**
     * Set height.
     */
    public function height(int $height): static
    {
        $this->drawing->setHeight($height);

        return $this;
    }

    /**
     * Set width.
     */
    public function width(int $width): static
    {
        $this->drawing->setWidth($width);

        return $this;
    }

    /**
     * Set width and height.
     */
    public function square(int $dimension): static
    {
        $this->drawing->setWidthAndHeight($dimension, $dimension);

        return $this;
    }

    /**
     * Insert the image to the sheet.
     */
    public function apply(Worksheet $sheet): void
    {
        $this->drawing->setWorksheet($sheet);
    }
}
