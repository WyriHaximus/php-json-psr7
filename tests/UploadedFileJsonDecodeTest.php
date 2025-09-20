<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use WyriHaximus;

use function Safe\json_encode;

final class UploadedFileJsonDecodeTest extends TestCase
{
    #[Test]
    public function success(): void
    {
        $json = json_encode(Messages::FILE_BEER_BOTTLE);

        $file = WyriHaximus\psr7_uploaded_file_json_decode($json);
        self::assertSame(14, $file->getSize());
        self::assertSame(0, $file->getError());
        self::assertSame('earth/liquid', $file->getClientMediaType());
        self::assertSame('beer.bottle', $file->getClientFilename());
        self::assertSame('Dark Horizon 5', (string) $file->getStream());
        self::assertSame('Dark Horizon 5', (string) $file->getStream());
    }

    #[Test]
    public function failure(): void
    {
        self::expectException(WyriHaximus\NotAnEncodedUploadedFileException::class);
        self::expectExceptionMessage('"[]" is not an encoded PSR-7 uploaded file, field "stream" is missing');

        WyriHaximus\psr7_uploaded_file_json_decode('[]');
    }
}
