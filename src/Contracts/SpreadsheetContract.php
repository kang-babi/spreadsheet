<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Contracts;

use Closure;

interface SpreadsheetContract
{
    /**
     * Configures the spreadsheet.
     *
     * @param Closure $config The configuration closure.
     */
    public function config(Closure $config): static;

    /**
     * Sets the header.
     *
     * @param Closure $closure The header closure.
     */
    public function header(Closure $closure): static;

    /**
     * Sets the body.
     *
     * @param Closure $closure The body closure.
     */
    public function body(Closure $closure): static;

    /**
     * Sets the footer.
     *
     * @param Closure $closure The footer closure.
     */
    public function footer(Closure $closure): static;

    /**
     * Save an XLSX file to the given path or writable stream URI.
     */
    public function save(string $path, bool $wrapText = true): void;
}
