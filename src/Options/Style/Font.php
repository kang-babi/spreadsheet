<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet\Options\Style;

use KangBabi\Spreadsheet\Enums\Style\UnderlineOption;

class Font
{
    public int $size = 11;
    public ?string $name = null;
    public bool $bold = false;
    public bool $italic = false;
    public bool $strikethrough = false;
    public UnderlineOption $underline = UnderlineOption::DEFAULT;

    /**
     * Get Font styles.
     *
     * @return array<string, bool|int|string>
     */
    public function get(): array
    {
        $data =  [
            'size' => $this->size,
            'bold' => $this->bold,
            'italic' => $this->italic,
            'strikethrough' => $this->strikethrough,
            'underline' => $this->underline->get()
        ];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        return $data;
    }
}
