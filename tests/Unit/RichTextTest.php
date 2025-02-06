<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Misc\RichText;
use PhpOffice\PhpSpreadsheet\RichText\RichText as RichText_;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use KangBabi\Spreadsheet\Wrappers\Row;

it('creates a new RichText instance', function (): void {
    $richText = RichText::textRun('Initial text');
    expect($richText)->toBeInstanceOf(RichText::class);
});

it('adds text to the RichText instance', function (): void {
    $richText = RichText::textRun('Initial text');
    $richText->text('New text');
    expect($richText->get()->getPlainText())->toBe('Initial textNew text');
});

it('sets the text to italic', function (): void {
    $richText = RichText::textRun('Initial text');
    $richText->italic();
    expect($richText->get()->getRichTextElements()[0]->getFont()->getItalic())->toBeTrue();
});

it('sets the text to bold', function (): void {
    $richText = RichText::textRun('Initial text');
    $richText->bold();
    expect($richText->get()->getRichTextElements()[0]->getFont()->getBold())->toBeTrue();
});

it('sets the text to strikethrough', function (): void {
    $richText = RichText::textRun('Initial text');
    $richText->strikethrough();
    expect($richText->get()->getRichTextElements()[0]->getFont()->getStrikethrough())->toBeTrue();
});

it('sets the text to underline', function (): void {
    $richText = RichText::textRun('Initial text');
    $richText->underline();
    expect($richText->get()->getRichTextElements()[0]->getFont()->getUnderline())->toBe('single');
});

it('sets the font size of the text', function (): void {
    $richText = RichText::textRun('Initial text');
    $richText->size(12);
    expect((int) $richText->get()->getRichTextElements()[0]->getFont()->getSize())->toBe(12);
});

it('sets the font name of the text', function (): void {
    $richText = RichText::textRun('Initial text');
    $richText->fontName('Arial');
    expect($richText->get()->getRichTextElements()[0]->getFont()->getName())->toBe('Arial');
});

it('gets the RichText instance', function (): void {
    $richText = RichText::textRun('Initial text');
    expect($richText->get())->toBeInstanceOf(RichText_::class);
});

it('adds a RichText value on cell', function (): void {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    $richText = RichText::textRun('Initial text')->bold()->italic()->size(11)->fontName('Arial');
    $worksheet->getCell('A1')->setValue($richText->get());
    expect($worksheet->getCell('A1')->getValue()->getPlainText())->toBe('Initial text');
    expect($worksheet->getCell('A1')->getValue()->getRichTextElements()[0]->getFont()->getBold())->toBeTrue();
    expect($worksheet->getCell('A1')->getValue()->getRichTextElements()[0]->getFont()->getItalic())->toBeTrue();
    expect((int) $worksheet->getCell('A1')->getValue()->getRichTextElements()[0]->getFont()->getSize())->toBe(11);
    expect($worksheet->getCell('A1')->getValue()->getRichTextElements()[0]->getFont()->getName())->toBe('Arial');
});

it('adds RichText value from Row', function (): void {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    $row = new Row(1);
    $richText = RichText::textRun('Initial text')->bold()->italic()->size(11)->fontName('Arial');
    $row->value('A', $richText);
    $row->apply($worksheet);
    expect($worksheet->getCell('A1')->getValue()->getPlainText())->toBe('Initial text');
    expect($worksheet->getCell('A1')->getValue()->getRichTextElements()[0]->getFont()->getBold())->toBeTrue();
    expect($worksheet->getCell('A1')->getValue()->getRichTextElements()[0]->getFont()->getItalic())->toBeTrue();
    expect((int) $worksheet->getCell('A1')->getValue()->getRichTextElements()[0]->getFont()->getSize())->toBe(11);
    expect($worksheet->getCell('A1')->getValue()->getRichTextElements()[0]->getFont()->getName())->toBe('Arial');
});
