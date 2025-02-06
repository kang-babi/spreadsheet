<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;

trait HasWrappers
{
    protected ?Config $config = null;

    protected ?Builder $header = null;

    protected ?Builder $body = null;

    protected ?Builder $footer = null;
}
