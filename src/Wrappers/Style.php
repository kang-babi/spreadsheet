<?php

declare(strict_types=1);

namespace KangBabi\Wrappers;

use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Style implements WrapperContract
{
    public function __construct(
        protected string $cell,
        protected array $styles = []
    ) {
        //
    }

    public function style(array $styles): static
    {
        $this->styles = $styles;

        return $this;
    }

    public function apply(Worksheet $sheet): int
    {
        $sheet->getStyle($this->cell)
            ->applyFromArray($this->styles);

        return 0;
    }

    public function getContent(): array
    {
        return $this->styles;
    }
}
