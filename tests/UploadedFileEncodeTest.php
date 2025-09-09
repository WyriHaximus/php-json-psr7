<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use WyriHaximus;

final class UploadedFileEncodeTest extends TestCase
{
    /**
     * @test
     * @dataProvider \WyriHaximus\Tests\Provider::uploadedFileBeerBottle
     */
    public function success(UploadedFileInterface $beerBottle): void
    {
        $json = WyriHaximus\psr7_uploaded_file_encode($beerBottle);
        self::assertSame(
            [
                'stream' => 'RGFyayBIb3Jpem9uIDU=',
                'size' => 14,
                'error' => 0,
                'filename' => 'beer.bottle',
                'media_type' => 'earth/liquid',
            ],
            $json,
        );

        self::assertSame('Dark Horizon 5', (string) $beerBottle->getStream());
    }
}
