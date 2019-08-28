<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use React\Http\Io\UploadedFile;
use function RingCentral\Psr7\stream_for;
use WyriHaximus;

/**
 * @internal
 */
final class UploadedFileJsonEncodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $beerBottle = new UploadedFile(stream_for('Dark Horizon 5'), 14, \UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid');

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
