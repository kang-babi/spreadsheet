<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet;

use Closure;
use KangBabi\Spreadsheet\Contracts\SpreadsheetContract;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sheet implements SpreadsheetContract
{
    /**
     * Spreadsheet container.
     */
    protected ?Spreadsheet $spreadsheet = null;

    /**
     * Active sheet.
     */
    protected ?Worksheet $sheet = null;

    /**
     * The current row
     */
    protected int $currentrow = 1;

    /**
     * Container for wrappers.
     *
     * @var array<string, Config|Builder> $wrappers
     */
    protected array $wrappers = [];

    /**
     * Constructor.
     * Initializes a new Spreadsheet and sets the active sheet.
     */
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    /**
     * Wraps a given type with a Config or Builder instance.
     *
     * @param string $type The type of wrapper.
     * @param Config|Builder $wrapper The wrapper instance.
     */
    public function wrap(string $type, Config|Builder $wrapper): static
    {
        $this->wrappers[$type] = $wrapper;

        return $this;
    }

    /**
     * Configures the spreadsheet using a Closure.
     *
     * @param Closure $config The configuration closure.
     */
    public function config(Closure $config): static
    {
        $instance = new Config();

        $config($instance);

        $this->wrap('config', $instance);

        return $this;
    }

    /**
     * Sets the header using a Closure.
     *
     * @param Closure $header The header closure.
     */
    public function header(Closure $header): static
    {
        $instance = new Builder($this->currentrow);

        $header($instance);

        $this->wrap('header', $instance);

        return $this;
    }

    /**
     * Sets the body using a Closure.
     *
     * @param Closure $body The body closure.
     */
    public function body(Closure $body): static
    {
        $instance = new Builder($this->currentrow);

        $body($instance);

        $this->wrap('body', $instance);

        return $this;
    }

    /**
     * Sets the footer using a Closure.
     *
     * @param Closure $footer The footer closure.
     */
    public function footer(Closure $footer): static
    {
        $instance = new Builder($this->currentrow);

        $footer($instance);

        $this->wrap('footer', $instance);

        return $this;
    }

    /**
     * Writes the spreadsheet to a file.
     *
     * @param string $filename The name of the file.
     * @param bool $wrapText Whether to wrap text in cells.
     * @return string The path to the temporary file.
     */
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

    /**
     * Saves the spreadsheet to a file and sends it to the browser for download.
     *
     * @param string $filename The name of the file.
     * @param bool $wrapText Whether to wrap text in cells.
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
        $columns = $this->getConfig()->getColumns();

        $start = ($columns[0] ?: 'A') . '1';
        $end = end($columns) . $this->currentrow;

        $this->sheet->getStyle("{$start}:{$end}")->getAlignment()->setWrapText(true);
    }

    /**
     * Gets the Spreadsheet instance.
     */
    public function getSpreadsheetInstance(): Spreadsheet
    {
        return $this->spreadsheet;
    }

    /**
     * Gets the active Worksheet.
     */
    public function getActiveSheet(): Worksheet
    {
        return $this->sheet;
    }

    /**
     * Gets the Config instance.
     */
    public function getConfig(): Config
    {
        if (!isset($this->wrappers['config'])) {
            $this->config(fn (): null => null);
        }

        return $this->wrappers['config'];
    }

    /**
     * Gets the header Builder instance.
     */
    public function getHeader(): Builder
    {
        return $this->wrappers['header'];
    }

    /**
     * Gets the body Builder instance.
     */
    public function getBody(): Builder
    {
        return $this->wrappers['body'];
    }

    /**
     * Gets the footer Builder instance.
     */
    public function getFooter(): Builder
    {
        return $this->wrappers['footer'];
    }
}
