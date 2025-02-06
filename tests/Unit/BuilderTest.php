<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Contracts\WrapperContract;
use KangBabi\Spreadsheet\Wrappers\Builder;
use KangBabi\Spreadsheet\Wrappers\Row;

it('instantiates a builder', function (): void {
    $builder = new Builder();
    expect($builder)->toBeInstanceOf(Builder::class);
    expect($builder)->toBeInstanceOf(WrapperContract::class);
});

it('adds a row', function (): void {
    $builder = new Builder();

    $builder->row(function ($row): void {
        $row->value('A', 'Test');
    });

    expect($builder->getRawContent())->toBeArray();
    expect($builder->getRawContent()[0])->toBeInstanceOf(Row::class);
});

it('adds a row with a custom increment', function (): void {
    $builder = new Builder();

    $builder->then(2, function ($row): void {
        $row->value('A', 'Test');
    });

    expect($builder->getCurrentRow())->toBe(3);
    expect($builder->getRawContent())->toBeArray();
    expect($builder->getRawContent()[0])->toBeInstanceOf(Row::class);
});

it('jumps to a row', function (): void {
    $builder = new Builder();

    $builder->jump(2);

    expect($builder->getCurrentRow())->toBe(3);
});

it('returns the current row', function (): void {
    $builder = new Builder();

    expect($builder->getCurrentRow())->toBe(1);

    $builder->jump(2);

    expect($builder->getCurrentRow())->toBe(3);
});

it('gets the row contents', function (): void {
    $builder = new Builder();

    $builder
        ->row(function (Row $row): void {})
        ->row(function (Row $row): void {});

    expect($builder->getContent())->toBeArray();
    expect($builder->getContent())->toHaveCount(2);
});
