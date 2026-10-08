<?php

declare(strict_types=1);

namespace KangBabi\Spreadsheet;

final class SheetFunctionFailures
{
    public static ?string $operation = null;

    /** @var list<string> */
    public static array $headers = [];
}

function tempnam(string $directory, string $prefix): string|false
{
    return SheetFunctionFailures::$operation === 'tempnam' ? false : \tempnam($directory, $prefix);
}

/** @return resource|false */
function fopen(string $filename, string $mode)
{
    return SheetFunctionFailures::$operation === 'fopen' ? false : \fopen($filename, $mode);
}

function unlink(string $filename): bool
{
    return SheetFunctionFailures::$operation === 'unlink' ? false : \unlink($filename);
}

function preg_match_all(string $pattern, string $subject): int|false
{
    return SheetFunctionFailures::$operation === 'preg_match_all' ? false : \preg_match_all($pattern, $subject);
}

function headers_sent(): bool
{
    return SheetFunctionFailures::$operation === 'headers_sent' || \headers_sent();
}

function header(string $header): void
{
    SheetFunctionFailures::$headers[] = $header;
}
