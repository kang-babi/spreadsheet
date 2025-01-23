<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet;

use Closure;
use KangBabi\Contracts\SpreadsheetContract;
use KangBabi\Contracts\WrapperContract;
use KangBabi\Wrappers\Builder;
use KangBabi\Wrappers\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sheet implements SpreadsheetContract
{
    protected ?Spreadsheet $spreadsheet = null;

    protected ?Worksheet $sheet = null;

    protected int $currentrow = 1;

    protected array $wrappers = [];

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function wrap(string $type, Closure|WrapperContract $wrapper): static
    {
        $this->wrappers[$type] = $wrapper;

        return $this;
    }

    public function config(Closure $config): static
    {
        $instance = new Config();

        $config($instance);

        $this->wrap('config', $instance);

        return $this;
    }

    public function header(Closure $header): static
    {
        $instance = new Builder($this->currentrow);

        $header($instance);

        $this->wrap('header', $instance);

        return $this;
    }

    public function body(Closure $body): static
    {
        $instance = new Builder($this->currentrow);

        $body($instance);

        $this->wrap('body', $instance);

        return $this;
    }

    public function footer(Closure $footer): static
    {
        $instance = new Builder($this->currentrow);

        $footer($instance);

        $this->wrap('footer', $instance);

        return $this;
    }

    public function save(string $filename, bool $wraptext = true): never
    {
        foreach ($this->wrappers as $wrapper) {
            $this->currentrow = $wrapper->apply($this->sheet);
        }

        if ($wraptext) {
            $this->wrapText();
        }

        $writer = new Xlsx($this->spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}\".xlsx");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        exit;
    }

    private function wrapText(): void
    {
        $columns = $this->getConfig()->getColumns();

        $start = ($columns[0] ?: 'A') . '1';
        $end = end($columns) . $this->currentrow;

        $this->sheet->getStyle("{$start}:{$end}")->getAlignment()->setWrapText(true);
    }

    public function getSpreadsheetInstance(): Spreadsheet
    {
        if (!$this->spreadsheet instanceof Spreadsheet) {
            $this->spreadsheet = new Spreadsheet();
        }

        return $this->spreadsheet;
    }

    public function getActiveSheet(): Worksheet
    {
        if (!$this->sheet instanceof Worksheet) {
            $this->sheet = $this->getSpreadsheetInstance()->getActiveSheet();
        }

        return $this->sheet;
    }

    public function getConfig(): Config
    {
        return $this->wrappers['config'];
    }

    public function getHeader(): Builder
    {
        return $this->wrappers['header'];
    }
}
