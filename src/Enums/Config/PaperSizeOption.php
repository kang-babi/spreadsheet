<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Config;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

enum PaperSizeOption: string
{
  case DEFAULT = 'default';
  case A4 = 'a4';
  case LEGAL = 'legal';
  case LETTER = 'letter';

  public function get(): int
  {
    return match ($this) {
      self::A4 => PageSetup::PAPERSIZE_A4,
      self::LEGAL => PageSetup::PAPERSIZE_LEGAL,
      self::LETTER => PageSetup::PAPERSIZE_LETTER,
      default => PageSetup::PAPERSIZE_A4
    };
  }
}
