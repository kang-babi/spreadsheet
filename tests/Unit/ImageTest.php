<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Misc\Image;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

it('sets from', function (): void {
    $image = Image::make()->from('A1');
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets name', function (): void {
    $image = Image::make()->from('A1')->name('Test Image');
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets description', function (): void {
    $image = Image::make()->from('A1')->description('Test Description');
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets source', function (): void {
    $image = Image::make()->from('A1');
    $image->source('/../../misc/qr.php');
})->throws(InvalidArgumentException::class);

it('sets valid source', function (): void {
    $image = Image::make()->from('A1')->source(__DIR__ . '/../../misc/qr.png');
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets cell', function (): void {
    $image = Image::make()->from('A1')->to('B2');
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets extend', function (): void {
    $image = Image::make()->from('A1')->to('B2');
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets padX', function (): void {
    $image = Image::make()->from('A1')->padX(10);
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets padY', function (): void {
    $image = Image::make()->from('A1')->padY(10);
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets height', function (): void {
    $image = Image::make()->from('A1')->height(100);
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets width', function (): void {
    $image = Image::make()->from('A1')->width(100);
    expect($image)->toBeInstanceOf(Image::class);
});

it('sets square', function (): void {
    $image = Image::make()->from('A1')->square(100);
    expect($image)->toBeInstanceOf(Image::class);
});

it('applies image to sheet', function (): void {
    $sheet = $this->createMock(Worksheet::class);
    $image = Image::make()->from('A1');
    $image->apply($sheet);
    expect($image)->toBeInstanceOf(Image::class);
});
