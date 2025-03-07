<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use KangBabi\Spreadsheet\Enums\Config\OrientationOption;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Orientation implements OptionContract
{
    protected string $method = 'getPageSetup';
    protected string $action = 'setOrientation';

    /**
     * Constructor.
     */
    public function __construct(
        public OrientationOption $option = OrientationOption::DEFAULT,
    ) {
        //
    }

    /**
     * Set sheet orientation.
     */
    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}()
            ->{$this->action}($this->option->get());
    }
}
