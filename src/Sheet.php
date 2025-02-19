<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet;

use Closure;
use KangBabi\Spreadsheet\Contracts\SpreadsheetContract;
use KangBabi\Spreadsheet\Traits\HasWrappers;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sheet implements SpreadsheetContract
{
    use HasWrappers;

    /**
     * Spreadsheet container.
     */
    protected Spreadsheet $spreadsheet;

    /**
     * Active sheet.
     */
    protected Worksheet $sheet;

    /**
     * The current row.
     */
    protected int $currentrow = 1;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();

        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    /**
     * Configures the spreadsheet using a Closure.
     *
     * @param Closure $config The configuration closure.
     */
    public function config(Closure $config): static
    {
        $this->config = new Config();

        $config($this->config);

        return $this;
    }

    /**
     * Sets the header using a Closure.
     *
     * @param Closure $header The header closure.
     */
    public function header(Closure $header): static
    {
        $this->header = new Builder($this->currentrow);

        $header($this->header);

        return $this;
    }

    /**
     * Sets the body using a Closure.
     *
     * @param Closure $body The body closure.
     */
    public function body(Closure $body): static
    {
        $this->body = new Builder($this->currentrow);

        $body($this->body);

        return $this;
    }

    /**
     * Sets the footer using a Closure.
     *
     * @param Closure $footer The footer closure.
     */
    public function footer(Closure $footer): static
    {
        $this->footer = new Builder($this->currentrow);

        $footer($this->footer);

        return $this;
    }

    /**
     * Writes the spreadsheet to a file.
     */
    public function write(string $filename, bool $wrapText = true): string
    {
        if ($this->config instanceof Config) {
            $this->currentrow = $this->config->apply($this->sheet);
        }

        if ($this->header instanceof Builder) {
            $this->currentrow = $this->header->apply($this->sheet);
        }

        if ($this->body instanceof Builder) {
            $this->currentrow = $this->body->apply($this->sheet);
        }

        if ($this->footer instanceof Builder) {
            $this->currentrow = $this->footer->apply($this->sheet);
        }

        if ($wrapText) {
            $this->wrapText();
        }

        $tempFile = tempnam(sys_get_temp_dir(), $filename) . '.xlsx';

        $writer = new Xlsx($this->spreadsheet);

        $writer->save($tempFile);

        return $tempFile;
    }

    /**
     * Saves the spreadsheet to a file and sends it to the browser for download.
     */
    public function save(string $filename, bool $wrapText = true): void
    {
        $filePath = $this->write($filename, $wrapText);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}.xlsx\"");
        header('Cache-Control: max-age=0');

        readfile($filePath);

        unlink($filePath);
    }

    /**
     * Wraps text in the cells of the spreadsheet.
     */
    private function wrapText(): void
    {
        $columns = $this->getConfig()?->getColumns() ?? ['A'];

        $start = "{$columns[0]}1";

        $end = end($columns) . $this->currentrow;

        $this->sheet->getStyle("{$start}:{$end}")
            ->getAlignment()
            ->setWrapText(true);
    }

    /**
     * Get the Spreadsheet instance.
     */
    public function getSpreadsheetInstance(): Spreadsheet
    {
        return $this->spreadsheet;
    }

    /**
     * Get the active Worksheet.
     */
    public function getActiveSheet(): Worksheet
    {
        return $this->sheet;
    }

    /**
     * Get the Config instance.
     */
    public function getConfig(): Config|null
    {
        return $this->config;
    }

    /**
     * Get the header Builder instance.
     */
    public function getHeader(): Builder|null
    {
        return $this->header;
    }

    /**
     * Get the body Builder instance.
     */
    public function getBody(): Builder|null
    {
        return $this->body;
    }

    /**
     * Get the footer Builder instance.
     */
    public function getFooter(): Builder|null
    {
        return $this->footer;
    }
}
