<?php

declare(strict_types=1);

namespace KangBabi\Contracts;

use Closure;

interface SpreadsheetContract
{
    public function config(Closure $config): static;

    public function header(Closure $closure): static;

    public function body(Closure $closure): static;

    public function footer(Closure $closure): static;

    public function save(string $filename): never;
}
