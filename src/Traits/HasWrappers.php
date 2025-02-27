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
    protected Config $config;

    /**
     * Header builder.
     */
    protected Builder $header;

    /**
     * Body builder.
     */
    protected Builder $body;

    /**
     * Footer builder.
     */
    protected Builder $footer;
}
