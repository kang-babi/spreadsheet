<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

trait Instantiable
{
    protected static self $instance;

    /**
     * Statically create instance.
     */
    public static function make(): static
    {
        static::$instance = new self();

        return static::$instance;
    }
}
