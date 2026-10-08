<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Misc;

use Exception;
use InvalidArgumentException;

final class Color
{
    /**
     * Collection of colors in hex format.
     *
     * @var array<string, string>
     */
    private array $colors = [];

    private string $default = '';

    /**
     * Constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Magically access color.
     *
     * @throws Exception
     */
    public function __get(string $color): string
    {
        if (!array_key_exists($color, $this->colors)) {
            return $this->default !== '' ?
                $this->colors[$this->default] :
                throw new Exception("Color [{$color}] does not exist.");
        }

        return $this->colors[$color];
    }

    public static function make(): self
    {
        return new self();
    }

    /**
     * Get all colors.
     *
     * @return array<string, string>
     */
    public function colors(): array
    {
        return $this->all();
    }

    /**
     * Get a registered color.
     */
    public function color(string $color): string
    {
        return $this->get($color);
    }

    /**
     * Set default color from registered colors.
     *
     * @throws Exception
     */
    public function default(string $color): static
    {
        if (!array_key_exists($color, $this->colors)) {
            throw new Exception("Color [{$color}] does not exist.");
        }

        $this->default = $color;

        return $this;
    }

    /**
     * Flush all colors.
     */
    public function flush(): void
    {
        $this->colors = [];
        $this->default = '';
    }

    /**
     * Register a color.
     *
     * @throws InvalidArgumentException
     */
    public function set(string $color, string $argb): static
    {
        if (array_key_exists($color, $this->colors)) {
            throw new InvalidArgumentException("Color [{$color}] already exists.");
        }

        if (mb_strlen($argb) !== 8) {
            $argb = mb_str_pad($argb, 8, 'F', STR_PAD_LEFT);
        }

        $this->colors[$color] = $argb;

        return $this;
    }

    /**
     * Remove a registered color.
     *
     * @throws InvalidArgumentException
     */
    public function forget(string $color): static
    {
        if (!array_key_exists($color, $this->colors)) {
            throw new InvalidArgumentException("Color [{$color}] does not exist.");
        }

        unset($this->colors[$color]);

        if ($this->default === $color) {
            $this->default = '';
        }

        return $this;
    }

    /**
     * Get a registered color.
     *
     * @throws InvalidArgumentException
     */
    public function get(string $color): string
    {
        if (!array_key_exists($color, $this->colors)) {
            return $this->default !== '' ?
                $this->colors[$this->default] :
                throw new InvalidArgumentException("Color [{$color}] does not exist.");
        }

        return $this->colors[$color];
    }

    /**
     * Get all registered colors.
     *
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->colors;
    }
}
