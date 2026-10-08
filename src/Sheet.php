<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet;

use Closure;
use LogicException;
use KangBabi\Spreadsheet\Contracts\SpreadsheetContract;
use KangBabi\Spreadsheet\Traits\HasWrappers;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RuntimeException;
use Throwable;

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

    private ?string $savedPath = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();

        $this->sheet = $this->spreadsheet->getActiveSheet();

        $this->config = new Config();

        $this->header = new Builder();

        $this->body = new Builder();

        $this->footer = new Builder();
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
     * Write a temporary XLSX file. The caller is responsible for deleting it.
     */
    public function write(string $filename, bool $wrapText = true): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), $filename);

        if ($tempFile === false) {
            throw new RuntimeException('Unable to create a temporary spreadsheet file.');
        }

        try {
            $this->save($tempFile, $wrapText);
        } catch (Throwable $exception) {
            unlink($tempFile);

            throw $exception;
        }

        return $tempFile;
    }

    /**
     * Save an XLSX file to the given path or writable stream URI.
     */
    public function save(string $path, bool $wrapText = true): static
    {
        $this->savedPath = null;

        $this->currentrow = $this->config->apply($this->sheet);

        $this->currentrow = $this->header->apply($this->sheet);

        $this->currentrow = $this->body->apply($this->sheet);

        $this->currentrow = $this->footer->apply($this->sheet);

        if ($wrapText) {
            $this->wrapText();
        }

        $writer = new Xlsx($this->spreadsheet);

        $writer->save($path);

        $this->savedPath = realpath($path) ?: null;

        return $this;
    }

    /**
     * Download the saved file without deleting it or regenerating the spreadsheet.
     */
    public function download(): void
    {
        if ($this->savedPath === null) {
            throw new LogicException('Save the spreadsheet to a local file before downloading it.');
        }

        if (!is_file($this->savedPath) || !is_readable($this->savedPath)) {
            throw new RuntimeException('The saved spreadsheet is missing or unreadable.');
        }

        if (headers_sent()) {
            throw new LogicException('Cannot download the spreadsheet after headers have been sent.');
        }

        $file = fopen($this->savedPath, 'rb');

        if ($file === false) {
            throw new RuntimeException('Unable to open the saved spreadsheet.');
        }

        try {
            $filename = basename($this->savedPath);
            $fallback = preg_replace('/[^A-Za-z0-9._-]/', '_', $filename);
            $encodedFilename = rawurlencode($filename);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"{$fallback}\"; filename*=UTF-8''{$encodedFilename}");
            header('Cache-Control: max-age=0');

            if (fpassthru($file) === false) {
                throw new RuntimeException('Unable to output the saved spreadsheet.');
            }
        } finally {
            fclose($file);
        }
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
}
