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

it('statically gets a registered color', function (): void {
    Color::make()
        ->set('blue', 'blue');

    expect(Color::color('blue'))->toBe('blue');
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

    Color::flush();

    expect($color->all())->toHaveLength(0);
});

it('magically gets a registered color', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    expect($color->blue)->toBe('blue');
});

it('throws an exception if color is not magically set', function (): void {
    $color = Color::make()
        ->set('blue', 'blue');

    $color->red;
})->throws(Exception::class);
