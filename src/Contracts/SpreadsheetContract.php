<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Contracts;

use Closure;

interface SpreadsheetContract
{
    /**
     * Configures the spreadsheet using a Closure.
     *
     * @param Closure $config The configuration closure.
     */
    public function config(Closure $config): static;

    /**
     * Sets the header using a Closure.
     *
     * @param Closure $closure The header closure.
     */
    public function header(Closure $closure): static;

    /**
     * Sets the body using a Closure.
     *
     * @param Closure $closure The body closure.
     */
    public function body(Closure $closure): static;

    /**
     * Sets the footer using a Closure.
     *
     * @param Closure $closure The footer closure.
     */
    public function footer(Closure $closure): static;

    /**
     * Saves the spreadsheet to a file and sends it to the browser for download.
     *
     * @param string $filename The name of the file.
     */
    public function save(string $filename): void;
}
