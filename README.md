# Sreadsheet Wrapper Library

This library is built on top of [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet), serving as a wrapper to simplify the creation and manipulation of spreadsheet files in PHP.
It provides a fluent interface utilizing method chaining, closures, and dependency injection to enhance usability and readability.

## Limitation

> While the library is fully tested and suitable for production use, it offers limited functionality compared to the underlying PhpSpreadsheet library. For more advanced features, consider using PhpSpreadsheet directly.

## Installation

Ensure you have [Composer](https://getcomposer.org/) installed. Then, add the library to your project:

```bash
composer require kang-babi/spreadsheet
```

## Usage

1. Initialize the Sheet

```php
use KangBabi\Spreadsheet\Sheet;

$sheet = new Sheet();

$sheet->getSpreadsheetInstance(); # returns \PhpOffice\PhpSpreadsheet\Spreadsheet instance
$sheet->getActiveSheet(); # returns \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet instance
```

2. Configure Sheet

```php
use KangBabi\Spreadsheet\Wrappers\Config;

$sheet->config(function (Config $config): void {
  ->orientation('portrait')
  ->pageFit('page') # fits to page
  ->repeatRows(1, 5) # repeats row 1 to 5
  ->paperSize('a4')
  ->columnWidth('A', 13)
});
```

3. Header

```php
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Row;

$sheet->header(function (Builder $header): void {
  $header
    ->row(function (Row $row): void { # rows start at 1 and increments per row chain
      $row
        ->merge('A', 'H') # merges columns A to H at the current row
        ->value('A', 'THIS IS MY HEADER')
        ->style('A:H', function (Style $style) { # targets A to H at the current row
          $style
            ->fontName('Times New Roman')
            ->alignment('horizontal', 'center')
            ->alignment('vertical', 'top')
            ->border('all')
            ->border('bottom', 'none')
            ->strikethrough()
            ->italic();
        });
  })
  ->jump(2) # skips 2 rows and do nothing
  ->then(1, function (Row $row): void {...}); # jumps 1 row and builds row
});
```

4. Body and Footer is wrapped with Builder instance.

5. Rich Text

```php
use KangBabi\Spreadsheet\Misc\RichText;

$richText = RichText::textRun("Initial text ")
  ->bold()
  ->italic()
  ->size(11)
  ->fontName('Times New Roman')
  ->text('additional text ')
  ->size(8)
  ->fontName('Georgia')
  ->strikethrough()
  ->underline();

...
$row
  ->value('A', $richText)
...
```

6. Save the Sheet

```php
$wrapText = true;

$sheet->save('my-sheet', $wrapText); # saves sheet as my-sheet.xlsx, wrap text is enabled by default
```
