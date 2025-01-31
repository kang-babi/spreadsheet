<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

trait HasStyleOptions
{
    /**
     * List of options.
     *
     * @var array<string, array<string, int|string|array<string, string>>>
     */
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
                'all' => 'allBorders',
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
                'size' => 'size',
                'name' => 'name',
                'underline' => 'underline',
                'bold' => 'bold',
                'italic' => 'italic',
                'strike' => 'strikethrough',
                'color' => 'color'
            ],
            'underline' => [
                'default' => Font::UNDERLINE_SINGLE,
                'none' => Font::UNDERLINE_NONE,
                'single' => Font::UNDERLINE_SINGLE,
                'double' => Font::UNDERLINE_DOUBLE,
            ],
        ]
    ];

    /**
     * Container.
     *
     * @var array<string, array<string, array<string, string>|bool|string>>
     */
    public array $styles = [];
}
