<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus;

/**
 * @internal
 */
final class UploadedFileDecodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $json = [
            'filename' => 'beer.bottle',
            'media_type' => 'earth/liquid',
            'error' => 0,
            'size' => 14,
            'stream' => 'RGFyayBIb3Jpem9uIDU=',
        ];

        $file = WyriHaximus\psr7_uploaded_file_decode($json);
        self::assertSame(14, $file->getSize());
        self::assertSame(0, $file->getError());
        self::assertSame('earth/liquid', $file->getClientMediaType());
        self::assertSame('beer.bottle', $file->getClientFilename());
        self::assertSame('Dark Horizon 5', (string) $file->getStream());
        self::assertSame('Dark Horizon 5', (string) $file->getStream());
    }

    public function testFailure(): void
    {
        self::expectException(WyriHaximus\NotAnEncodedUploadedFileException::class);
        self::expectExceptionMessage('"[]" is not an encoded PSR-7 uploaded file, field "stream" is missing');

        /** @phpstan-ignore-next-line */
        WyriHaximus\psr7_uploaded_file_decode([]);
    }
}
