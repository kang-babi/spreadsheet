<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Misc;

use Exception;
use InvalidArgumentException;
use KangBabi\Spreadsheet\Traits\Instantiable;

final class Color
{
    use Instantiable;

    /**
     * Collection of colors in hex format.
     *
     * @var array<string, string>
     */
    private array $colors = [];

    private static string $default = '';

    /**
     * Constructor.
     */
    public function __construct()
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
            return static::$default !== '' ?
                $this->colors[static::$default] :
                throw new Exception("Color [{$color}] does not exist.");
        }

        return $this->colors[$color];
    }

    /**
     * Get all colors statically.
     *
     * @return array<string, string>
     */
    public static function colors(): array
    {
        return static::$instance->all();
    }

    /**
     * Statically get a registered color.
     */
    public static function color(string $color): string
    {
        return static::$instance->get($color);
    }

    /**
     * Set default color from registered colors.
     *
     * @throws Exception
     */
    public static function default(string $color): static
    {
        if (!array_key_exists($color, static::$instance->colors)) {
            throw new Exception("Color [{$color}] does not exist.");
        }

        static::$default = $color;

        return static::$instance;
    }

    /**
     * Flush all colors.
     */
    public static function flush(): void
    {
        static::$instance->colors = [];
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

        return static::$instance;
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

        return static::$instance;
    }

    /**
     * Get a registered color.
     *
     * @throws InvalidArgumentException
     */
    public function get(string $color): string
    {
        if (!array_key_exists($color, $this->colors)) {
            return static::$default !== '' ?
                $this->colors[static::$default] :
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
