<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use BadMethodCallException;
use Closure;
use InvalidArgumentException;

trait HasMacros
{
    /**
     * User predefined configurations.
     * @var array<string, Closure>
     */
    protected static array $macros = [];

    /**
     * Define macro.
     * @throws InvalidArgumentException
     */
    public static function macro(string $key, Closure $closure): void
    {
        if (static::hasMacro($key)) {
            throw new InvalidArgumentException("Macro with key '{$key}' already exists.");
        }

        static::$macros[$key] = $closure;
    }

    /**
     * Flush macros.
     */
    public static function flushMacros(): void
    {
        static::$macros = [];
    }

    /**
     * Execute the macro.
     * @param array<int, mixed> $parameters
     * @throws BadMethodCallException
     */
    public function call(string $key, ...$parameters): mixed
    {
        if (!static::hasMacro($key)) {
            throw new BadMethodCallException("Macro '{$key}' does not exist.");
        }

        return call_user_func_array(static::$macros[$key]->bindTo($this, static::class), $parameters);
    }

    /**
     * Execute the macro statically.
     * @param array<int, mixed> $parameters
     * @throws BadMethodCallException
     */
    public static function staticCall(string $key, ...$parameters): mixed
    {
        if (!static::hasMacro($key)) {
            throw new BadMethodCallException("Macro '{$key}' does not exist.");
        }

        return call_user_func_array(static::$macros[$key], $parameters);
    }

    /**
     * Check if has macro.
     */
    public static function hasMacro(string $key): bool
    {
        return array_key_exists($key, static::$macros);
    }
}
