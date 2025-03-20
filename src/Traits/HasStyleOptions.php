<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Traits;

use KangBabi\Spreadsheet\Options\Style\Border;
use KangBabi\Spreadsheet\Options\Style\Fill;
use KangBabi\Spreadsheet\Options\Style\Font;
use KangBabi\Spreadsheet\Options\Style\HorizontalAlignment;
use KangBabi\Spreadsheet\Options\Style\VerticalAlignment;

trait HasStyleOptions
{
    /**
     * Alignment options.
     *
     * @var array<int, VerticalAlignment|HorizontalAlignment>
     */
    protected array $alignments = [];

    /**
     * Border options.
     *
     * @var array<int, Border>
     */
    protected array $borders = [];

    /**
     * Font styles.
     */
    protected Font $font;

    /**
     * Fill option.
     */
    protected ?Fill $fill = null;
}
