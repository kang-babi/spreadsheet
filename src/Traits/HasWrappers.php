<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;

trait HasWrappers
{
    /**
     * Config builder.
     * @var
     */
    protected ?Config $config = null;

    /**
     * Header builder.
     * @var
     */
    protected ?Builder $header = null;

    /**
     * Body builder.
     * @var
     */
    protected ?Builder $body = null;

    /**
     * Footer builder.
     * @var
     */
    protected ?Builder $footer = null;
}
