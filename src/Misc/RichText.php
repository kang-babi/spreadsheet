<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Misc;

use KangBabi\Spreadsheet\Traits\Instantiable;
use PhpOffice\PhpSpreadsheet\RichText\RichText as RichText_;
use PhpOffice\PhpSpreadsheet\RichText\Run;
use PhpOffice\PhpSpreadsheet\Style\Font;

final class RichText
{
    use Instantiable;

    private readonly RichText_ $richText;
    private Run $text;
    private ?Font $font = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->richText = new RichText_();
    }

    /**
     * Add text.
     */
    public function text(string $text): static
    {
        $this->text = $this->richText->createTextRun($text);

        $this->font = $this->text->getFont() instanceof Font ? $this->text->getFont() : null;

        return $this;
    }

    /**
     * Set the text to italic.
     */
    public function italic(bool $italic = true): static
    {
        if ($this->font instanceof Font) {
            $this->font->setItalic($italic);
        }

        return $this;
    }

    /**
     * Set the text to bold.
     */
    public function bold(bool $bold = true): static
    {
        if ($this->font instanceof Font) {
            $this->font->setBold($bold);
        }

        return $this;
    }

    /**
     * Set the text to strikethrough.
     */
    public function strikethrough(bool $strikethrough = true): static
    {
        if ($this->font instanceof Font) {
            $this->font->setStrikethrough($strikethrough);
        }

        return $this;
    }

    /**
     * Sets the text to underline.
     */
    public function underline(bool $underline = true): static
    {
        if ($this->font instanceof Font) {
            $this->font->setUnderline($underline);
        }

        return $this;
    }

    /**
     * Set the font size.
     */
    public function size(int $size): static
    {
        if ($this->font instanceof Font) {
            $this->font->setSize($size);
        }

        return $this;
    }

    /**
     * Set the font name.
     */
    public function fontName(string $name): static
    {
        if ($this->font instanceof Font) {
            $this->font->setName($name);
        }

        return $this;
    }

    /**
     * Get the RichText instance.
     */
    public function get(): RichText_
    {
        return $this->richText;
    }
}
