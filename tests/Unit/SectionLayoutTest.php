<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Row;

it('accounts for gaps and rows that do not increment the cursor', function (): void {
    $sheet = new Sheet();
    $sheet->header(fn (Builder $header) => $header->jump(2)->row(fn (Row $row) => $row->value('A', 'Heading'), false));
    $sheet->body(fn (Builder $body) => $body->then(2, fn (Row $row) => $row->value('A', 'Body')));
    $sheet->footer(fn (Builder $footer) => $footer->row(fn (Row $row) => $row->value('A', 'Footer')));

    expect($sheet->getHeader()->getRawContent()[0]->getRow())->toBe(3);
    expect($sheet->getBody()->getRawContent()[0]->getRow())->toBe(6);
    expect($sheet->getFooter()->getRawContent()[0]->getRow())->toBe(7);
});

it('rejects configuring an earlier section after a later one', function (string $earlier, string $later): void {
    $sheet = new Sheet();
    $sheet->{$later}(fn (Builder $builder) => $builder->row(fn (Row $row) => $row->value('A', 'Existing')));

    expect(fn () => $sheet->{$earlier}(fn (Builder $builder) => null))->toThrow(LogicException::class);
})->with([['header', 'body'], ['header', 'footer'], ['body', 'footer']]);

it('allows replacing the latest section without shifting its starting row', function (): void {
    $sheet = new Sheet();
    $sheet->header(fn (Builder $header) => $header->row(fn (Row $row) => $row->value('A', 'Heading')));
    $sheet->body(fn (Builder $body) => $body->jump(4)->row(fn (Row $row) => $row->value('A', 'Old')));
    $sheet->body(fn (Builder $body) => $body->row(fn (Row $row) => $row->value('A', 'Replacement')));
    $sheet->footer(fn (Builder $footer) => $footer->row(fn (Row $row) => $row->value('A', 'Footer')));

    expect($sheet->getBody()->getRawContent()[0]->getRow())->toBe(2);
    expect($sheet->getFooter()->getRawContent()[0]->getRow())->toBe(3);
});

it('rejects overlap introduced by mutating an earlier builder', function (): void {
    $sheet = new Sheet();
    $sheet->header(fn (Builder $header) => $header->row(fn (Row $row) => $row->value('A', 'Heading')));
    $sheet->body(fn (Builder $body) => $body->row(fn (Row $row) => $row->value('A', 'Body')));
    $sheet->getHeader()->row(fn (Row $row) => $row->value('A', 'Late heading'));

    expect(fn () => $sheet->write('overlapping-sections-', false))->toThrow(LogicException::class);
});

it('keeps the section and cursor unchanged when a callback fails', function (): void {
    $sheet = new Sheet();
    $sheet->header(fn (Builder $header) => $header->row(fn (Row $row) => $row->value('A', 'Heading')));

    expect(fn () => $sheet->body(function (Builder $body): void {
        $body->jump(10);
        throw new RuntimeException('Callback failed.');
    }))->toThrow(RuntimeException::class);

    $sheet->body(fn (Builder $body) => $body->row(fn (Row $row) => $row->value('A', 'Body')));
    expect($sheet->getBody()->getRawContent()[0]->getRow())->toBe(2);
});
