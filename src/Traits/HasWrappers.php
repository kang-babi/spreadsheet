<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;

trait HasWrappers
{
    /**
     * Config builder.
     */
    protected ?Config $config = null;

    /**
     * Header builder.
     */
    protected ?Builder $header = null;

    /**
     * Body builder.
     */
    protected ?Builder $body = null;

    /**
     * Footer builder.
     */
    protected ?Builder $footer = null;
}
