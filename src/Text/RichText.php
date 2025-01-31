<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Text;

use PhpOffice\PhpSpreadsheet\RichText\RichText as RichText_;

final class RichText
{
    private readonly RichText_ $richText;
    private static self $instance;
    private mixed $text;

    /**
     * Constructor.
     *
     * @param string $text The initial text.
     */
    public function __construct(string $text)
    {
        $this->richText = new RichText_();
        $this->text = $this->richText->createTextRun($text);
    }

    /**
     * Set inital text statically.
     */
    public static function textRun(string $text): static
    {
        static::$instance = new self($text);

        return static::$instance;
    }

    /**
     * Add text.
     */
    public function text(string $text): static
    {
        $this->text = $this->richText->createTextRun($text);

        return $this;
    }

    /**
     * Set the text to italic.
     */
    public function italic(bool $italic = true): static
    {
        $this->text->getFont()->setItalic($italic);

        return $this;
    }

    /**
     * Set the text to bold.
     */
    public function bold(bool $bold = true): static
    {
        $this->text->getFont()->setBold($bold);

        return $this;
    }

    /**
     * Set the text to strikethrough.
     */
    public function strike(bool $strikethrough = true): static
    {
        $this->text->getFont()->setStrikethrough($strikethrough);

        return $this;
    }

    /**
     * Sets the text to underline.
     */
    public function underline(bool $underline = true): static
    {
        $this->text->getFont()->setUnderline($underline);

        return $this;
    }

    /**
     * Set the font size.
     */
    public function size(int $size): static
    {
        $this->text->getFont()->setSize($size);

        return $this;
    }

    /**
     * Set the font name.
     */
    public function fontName(string $name): static
    {
        $this->text->getFont()->setName($name);

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
