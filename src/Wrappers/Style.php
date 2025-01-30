<?php

declare(strict_types=1);

namespace KangBabi\Wrappers;

use KangBabi\Contracts\WrapperContract;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Style implements WrapperContract
{
    /**
     * @param array<string, array<string, bool|int|string>> $styles
     */
    public function __construct(
        protected string $cell,
        protected array $styles = []
    ) {
        //
    }

    /**
     * @param array<string, array<string, bool|int|string>>  $styles
     */
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

    /**
     * @return array<string, array<string, bool|int|string>>
     */
    public function getContent(): array
    {
        return $this->styles;
    }
}
