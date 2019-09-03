<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use WyriHaximus;

/**
 * @internal
 */
final class UploadedFileJsonEncodeTest extends TestCase
{
    /**
     * @dataProvider \WyriHaximus\Tests\Provider::uploadedFileBeerBottle
     */
    public function testSuccess(UploadedFileInterface $beerBottle): void
    {
        $json = WyriHaximus\psr7_uploaded_file_json_encode($beerBottle);
        self::assertSame(
            \json_encode([
                'filename' => 'beer.bottle',
                'media_type' => 'earth/liquid',
                'error' => 0,
                'size' => 14,
                'stream' => 'RGFyayBIb3Jpem9uIDU=',
            ]),
            $json
        );

        self::assertSame('Dark Horizon 5', (string)$beerBottle->getStream());
    }
}
