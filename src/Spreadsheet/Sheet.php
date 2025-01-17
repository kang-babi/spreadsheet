<?php

namespace KangBabi\Spreadsheet;

use Closure;
use InvalidArgumentException;
use KangBabi\Contracts\SpreadsheetContract;
use KangBabi\Contracts\WrapperContract;
use KangBabi\Wrappers\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sheet implements SpreadsheetContract
{
  protected ?Spreadsheet $spreadsheet = null;
  protected ?Worksheet $sheet = null;

  protected array $wrappers = [];

  public function __construct()
  {
    $this->spreadsheet = new Spreadsheet();
    $this->sheet = $this->spreadsheet->getActiveSheet();
  }

  public function wrap(string $type, Closure|WrapperContract $wrapper): static
  {
    $this->wrappers[$type][] = $wrapper;

    return $this;
  }

  public function config(Closure|Config $config): static
  {
    if ($config instanceof Closure) {
      $config = $config(new Config);
    }

    $this->wrap('config', $config);

    return $this;
  }

  public function header(Closure $header): static
  {
    $this->wrap('header', $header);

    return $this;
  }

  public function body(Closure $body): static
  {
    $this->wrap('body', $body);

    return $this;
  }

  public function footer(Closure $footer): static
  {
    $this->wrap('footer', $footer);

    return $this;
  }

  public function save(string $filename, ?Closure $closure = null): never
  {
    foreach ($this->wrappers as $type => $wrapperGroup) {
      foreach ($wrapperGroup as $wrapper) {
        if (is_callable($wrapper)) {
          $wrapper($this->sheet);
        } elseif ($wrapper instanceof WrapperContract) {
          $wrapper->apply($this->sheet);
        } else {
          throw new InvalidArgumentException(
            "Wrapper of type '{$type}' must be callable or implement WrapperContract."
          );
        }
      }
    }

    if ($closure instanceof Closure) {
      $closure($this->spreadsheet);
    }

    $writer = new Xlsx($this->spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '".xlsx');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');

    exit;
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
}
