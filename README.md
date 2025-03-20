# Spreadsheet Wrapper Library

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
            ->alignment('horizontal', 'center') # or use ->horizontal('center')
            ->alignment('vertical', 'top') # or use ->vertical('top')
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

$richText = RichText::make()
  ->text("Initial text ")
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

6. Image (Drawing)

```php
use KangBabi\Spreadsheet\Misc\Image;

$image = Image::make()
  ->source('path/to/img/')
  ->from('A1')
  ->to('B1') # stretch image to B2
  ->padX(10, false) # pads left as default, set true to pad right
  ->padY(10, false) # pads top as default, set true to pad bottom
  ->height(100);

```

7. Macro - You can define reusable macros to streamline repetitive tasks.

- Row

```php
Row::macro('row', function (Row $row): void {
  $row
    ->value('A', 'Has')
    ->value('B', 'Macros');
});

$row = new Row();

# call macro
$row->call('row', $row);

# or

# call macro
Row::staticCall('row', $row);
```

- Builder

```php
Builder::macro('skip', function (Builder $builder): void {
  $builder
    ->jump()
    ->row(function (Row $row) {
      ...
    });
})

$builder = new Builder();

#call macro
$builder->call('skip', $builder);

# or

# call macro
Builder::staticCall('skip', $builder);
```

8. Color & Fill - Save color configurations and apply them to Color Fill

- Color - Color values follow the ARGB format and values will be prefixed with 'F'

```php
use KangBabi\Misc\Color;

$colors = Color::make()
  ->set('primary', '696cff') # results to 'FF696cff'
  ->set('secondary', '8592a3')
  ->set('success', '71dd37')

# access color values
$colors->get('primary'); # returns 'FF696cff'

# or
$colors->success; # returns 'FF71dd37'

# or static
Color::color('secondary'); # returns 'FF8592a3'

# get all colors
$colors->all();
/**
 * returns [
 *  'primary' => 'FF696cff',
 *  'secondary' => 'FF8592a3',
 *  'success' => 'FF71dd37',
 * ]
 */

# or static
Color::colors();
/**
 * returns [
 *  'primary' => 'FF696cff',
 *  'secondary' => 'FF8592a3',
 *  'success' => 'FF71dd37',
 * ]
 */

# Setting default color
Color::default('primary');

# trigger default
$colors->doesNotExist; # returns 'FF696cff'
$colors->get('doesNotExist'); # returns 'FF696cff'

# or static
Color::color('doesNotExist'); # returns 'FF696cff'
```

- Fill - fill cell with predefined colors

```php
# from step 1

...
  $style
    ->horizontal('center')
    ->fill(Color::color('primary')) # default solid
    ->fill($colors->info, 'none') # fills none
...
```

9. Save the Sheet

```php
$wrapText = true;

$sheet->save('my-sheet', $wrapText); # saves sheet as my-sheet.xlsx, wrap text is enabled by default
```
