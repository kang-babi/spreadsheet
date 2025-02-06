<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Config\PaperSizeOption;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaperSize implements OptionContract
{
    protected string $method = 'getPageSetup';
    protected string $action = 'setPaperSize';

    /**
     * Constructor.
     */
    public function __construct(
        public PaperSizeOption $option = PaperSizeOption::DEFAULT,
    ) {
        //
    }

    /**
     * Set sheet paper size.
     */
    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}()
            ->{$this->action}($this->option->get());
    }
}
