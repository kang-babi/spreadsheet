<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet;

use Closure;
use KangBabi\Contracts\SpreadsheetContract;
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

    /**
     * @var array<string, Config|Builder> $wrappers
     */
    protected array $wrappers = [];

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function wrap(string $type, Config|Builder $wrapper): static
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

    public function write(string $filename, bool $wrapText = true): string
    {
        foreach ($this->wrappers as $wrapper) {
            $this->currentrow = $wrapper->apply($this->sheet);
        }

        if ($wrapText) {
            $this->wrapText();
        }

        $tempFile = tempnam(sys_get_temp_dir(), $filename) . '.xlsx';
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempFile);

        return $tempFile;
    }

    public function save(string $filename, bool $wrapText = true): void
    {
        $filePath = $this->write($filename, $wrapText);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}.xlsx\"");
        header('Cache-Control: max-age=0');

        readfile($filePath);
        unlink($filePath);
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
        return $this->spreadsheet;
    }

    public function getActiveSheet(): Worksheet
    {
        return $this->sheet;
    }

    public function getConfig(): Config
    {
        if (!isset($this->wrappers['config'])) {
            $this->config(fn (): null => null);
        }

        return $this->wrappers['config'];
    }

    public function getHeader(): Builder
    {
        return $this->wrappers['header'];
    }

    public function getBody(): Builder
    {
        return $this->wrappers['body'];
    }

    public function getFooter(): Builder
    {
        return $this->wrappers['footer'];
    }
}
