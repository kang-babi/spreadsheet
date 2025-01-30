<?php

declare(strict_types=1);

namespace KangBabi\Wrappers;

use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Style implements WrapperContract
{
  protected array $styleOptions = [
    'alignment' => [
      'key' => 'alignment',
      'horizontal' => [
        'default' => Alignment::HORIZONTAL_GENERAL,
        'left' => Alignment::HORIZONTAL_LEFT,
        'right' => Alignment::HORIZONTAL_RIGHT,
        'center' => Alignment::HORIZONTAL_CENTER,
        'justify' => Alignment::HORIZONTAL_JUSTIFY,
      ],
      'vertical' => [
        'default' => Alignment::VERTICAL_CENTER,
        'top' => Alignment::VERTICAL_TOP,
        'center' => Alignment::VERTICAL_CENTER,
        'bottom' => Alignment::VERTICAL_BOTTOM,
        'justify' => Alignment::VERTICAL_JUSTIFY,
      ],
    ],
    'border' => [
      'key' => 'borders',
      'location' => [
        'top' => 'top',
        'bottom' => 'bottom',
        'left' => 'left',
        'right' => 'right',
        'allborders' => 'allBorders',
        'outline' => 'outline'
      ],
      'style' => [
        'default' => Border::BORDER_THIN,
        'none' => Border::BORDER_NONE,
        'dash' => Border::BORDER_DASHED,
        'dash-dot' => Border::BORDER_DASHDOT,
        'dot' => Border::BORDER_DOTTED,
        'thin' => Border::BORDER_THIN,
        'thick' => Border::BORDER_THICK,
      ],
    ],
    'font' => [
      'key' => 'font',
      'options' => [
        'name' => 'name',
        'underline' => 'underline',
        'bold' => 'bold',
        'italic' => 'italic',
        'strike' => 'strikethrough',
        'color' => 'color'
      ],
    ]
  ];

  public array $styles = [];

  /**
   * @param array<string, array<string, bool|int|string>> $styles
   */
  public function __construct(
    protected string $cell,
  ) {
    //
  }

  public function alignment(string $axis, string $direction = 'default'): static
  {
    $style = $this->styleOptions['alignment'];

    $this->style($style['key'], [
      $axis => $style[$axis][$direction],
    ]);

    return $this;
  }

  public function border(string $location, string $type = 'default'): static
  {
    $style = $this->styleOptions['border'];

    $this->style($style['key'], [
      $style['location'][$location] => [
        'borderStyle' => $style['style'][$type],
      ],
    ]);

    return $this;
  }

  public function font(string $option, string|int|bool|array $value): static
  {
    $style = $this->styleOptions['font'];

    $this->style($style['key'], [
      $style['options'][$option] => $value,
    ]);

    return $this;
  }



  /**
   * @param array<string, array<string, bool|int|string>> $styles
   */
  public function style(string $key, array $value): static
  {
    $this->styles[$key] = $this->mergeKeyValues($key, $value);

    return $this;
  }

  private function mergeKeyValues($key, $value): array
  {
    return array_merge($this->styles[$key] ?? [], $value);
  }

  public function apply(Worksheet $sheet): int
  {
    $sheet->getStyle($this->cell)
      ->applyFromArray($this->styles);

    return 0;
  }

  /**
   * @return array<string, array<string, bool|int|string>>
   */
  public function getContent(): array
  {
    return $this->styles;
  }
}
