<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Misc\Color;

it('instantiates color object', function (): void {
    $color = Color::make();

    expect($color)->toBeInstanceOf(Color::class);
});

it('registers a color', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    expect($color->all())->toHaveLength(1);
});

it('gets a registered color', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    expect($color->color('blue'))->toBe('FFFFblue');
});

it('throws an exception if color is already set', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    $color->set('blue', 'blue');
})->throws(InvalidArgumentException::class);

it('throws an exception if color is not set', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    $color->get('red');
})->throws(InvalidArgumentException::class);

it('gets all registered colors', function (): void {
    $color = Color::make()
        ->set('blue', 'blue')
        ->set('red', 'red');

    expect($color->all())->toHaveLength(2);
});

it('removes a registered color', function (): void {
    $color = Color::make()
        ->set('blue', 'blue')
        ->set('red', 'red');

    $color->forget('blue');

    expect($color->all())->toHaveLength(1);
});

it('throws an exception if color is not removed', function (): void {
    $color = Color::make()
        ->set('blue', 'blue')
        ->set('red', 'red');

    $color->forget('green');
})->throws(InvalidArgumentException::class);

it('flushes all colors', function (): void {
    $color = Color::make()
        ->set('blue', 'blue')
        ->set('red', 'red');

    $color->flush();

    expect($color->all())->toHaveLength(0);
});

it('magically gets a registered color', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    expect($color->blue)->toBe('FFFFblue');
});

it('throws an exception if color is not magically set', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    $color->red;
})->throws(Exception::class);

it('gets all colors', function (): void {
    $color = Color::make()
        ->set('blue', 'blue')
        ->set('red', 'red');

    expect($color->colors())->toBeArray();
    expect($color->colors())->toHaveLength(2);
});

it('sets a default color', function (): void {
    $colors = Color::make()
        ->set('blue', 'blue')
        ->default('blue');

    expect($colors->color('blue'))->toBe('FFFFblue');
    expect($colors->color('default'))->toBe('FFFFblue');
    expect($colors->default)->toBe('FFFFblue');
});
it('throws an exception when setting default color that does not exist', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    $color->default('green');
})->throws(Exception::class);

it('keeps palettes and fluent operations independent', function (): void {
    $first = Color::make()->set('blue', 'FF0000FF')->default('blue');
    $second = Color::make()->set('red', 'FFFF0000')->default('red');

    expect($first->set('green', 'FF00FF00'))->toBe($first);
    expect($first->forget('green'))->toBe($first);
    expect($first->get('missing'))->toBe('FF0000FF');
    expect($second->get('missing'))->toBe('FFFF0000');
    expect($first->all())->toBe(['blue' => 'FF0000FF']);
    expect($second->all())->toBe(['red' => 'FFFF0000']);

    $first->flush();

    expect($second->get('missing'))->toBe('FFFF0000');
    expect(fn () => $first->get('missing'))->toThrow(InvalidArgumentException::class);
});

it('clears the fallback when its default color is removed', function (): void {
    $colors = Color::make()->set('blue', 'FF0000FF')->default('blue');

    $colors->forget('blue');

    expect(fn () => $colors->get('missing'))->toThrow(InvalidArgumentException::class);
    expect(fn () => $colors->missing)->toThrow(Exception::class);
});

it('does not share a default with a new palette', function (): void {
    Color::make()->set('blue', 'FF0000FF')->default('blue');

    $colors = Color::make();

    expect($colors->all())->toBe([]);
    expect(fn () => $colors->get('missing'))->toThrow(InvalidArgumentException::class);
});
