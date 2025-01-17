<?php

namespace KangBabi\Contracts;

use Closure;
use KangBabi\Wrappers\Config;

interface SpreadsheetContract
{
  public function config(Closure|Config $config): static;

  public function header(Closure $closure): static;

  public function body(Closure $closure): static;

  public function footer(Closure $closure): static;

  public function save(string $filename, ?Closure $closure): never;
}
