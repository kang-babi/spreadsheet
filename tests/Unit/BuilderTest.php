<?php

declare(strict_types=1);

use KangBabi\Contracts\WrapperContract;
use KangBabi\Wrappers\Builder;
use KangBabi\Wrappers\Row;

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

    expect($builder->getRawContent())->toHaveKey('row');
    expect($builder->getRawContent()['row'])->toBeArray();
    expect($builder->getRawContent()['row'][0])->toBeInstanceOf(Row::class);
});

it('adds a row with a custom increment', function (): void {
    $builder = new Builder();

    $builder->then(2, function ($row): void {
        $row->value('A', 'Test');
    });

    expect($builder->getCurrentRow())->toBe(3);
    expect($builder->getRawContent())->toHaveKey('row');
    expect($builder->getRawContent()['row'])->toBeArray();
    expect($builder->getRawContent()['row'][0])->toBeInstanceOf(Row::class);
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
