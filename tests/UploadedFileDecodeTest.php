<?php

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus;

final class UploadedFileDecodeTest extends TestCase
{
    public function test()
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
        self::assertSame('Dark Horizon 5', $file->getStream()->getContents());
    }
}
