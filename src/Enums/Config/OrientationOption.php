<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Enums\Config;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

enum OrientationOption: string
{
  case PORTRAIT = 'portrait';
  case LANDSCAPE = 'landscape';
  case DEFAULT = 'default';

  public function get(): string
  {
    return match ($this) {
      self::PORTRAIT => PageSetup::ORIENTATION_PORTRAIT,
      self::LANDSCAPE => PageSetup::ORIENTATION_LANDSCAPE,
      self::DEFAULT => PageSetup::ORIENTATION_LANDSCAPE,
    };
  }
}
