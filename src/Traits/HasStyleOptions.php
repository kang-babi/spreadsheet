<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use KangBabi\Spreadsheet\Options\Style\Border;
use KangBabi\Spreadsheet\Options\Style\Font;
use KangBabi\Spreadsheet\Options\Style\HorizontalAlignment;
use KangBabi\Spreadsheet\Options\Style\VerticalAlignment;

trait HasStyleOptions
{
    /**
     * Alignment options.
     * @var array<int, VerticalAlignment|HorizontalAlignment>
     */
    public array $alignments = [];

    /**
     * Border options.
     * @var array<int, Border>
     */
    public array $borders = [];

    /**
     * Font styles.
     */
    public Font $font;
}
