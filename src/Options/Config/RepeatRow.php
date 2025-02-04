<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Config;

use KangBabi\Spreadsheet\Contracts\OptionContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RepeatRow implements OptionContract
{
    protected string $method = 'getPageSetup';
    protected string $action = 'setRowsToRepeatAtTopByStartAndEnd';

    public function __construct(
        public int $from = 1,
        public int $to = 5,
    ) {
        //
    }

    public function apply(Worksheet $sheet): void
    {
        $sheet
            ->{$this->method}()
            ->{$this->action}($this->from, $this->to);
    }
}
