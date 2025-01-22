<?php

declare(strict_types = 1);

namespace KangBabi\Contracts;

use Closure;
use KangBabi\Wrappers\Config;
use KangBabi\Wrappers\Header;

interface SpreadsheetContract
{
    public function config(Closure|Config $config): static;

    public function header(Closure|Header $closure): static;

    public function body(Closure $closure): static;

    public function footer(Closure $closure): static;

    public function save(string $filename): never;
}
