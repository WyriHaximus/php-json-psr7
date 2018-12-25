<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use React\Http\Io\UploadedFile;
use WyriHaximus;
use function RingCentral\Psr7\stream_for;

/**
 * @internal
 */
final class UploadedFileEncodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $beerBottle = new UploadedFile(stream_for('Dark Horizon 5'), 14, \UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid');

        $json = WyriHaximus\psr7_uploaded_file_encode($beerBottle);
        self::assertSame(
            [
                'filename' => 'beer.bottle',
                'media_type' => 'earth/liquid',
                'error' => 0,
                'size' => 14,
                'stream' => 'RGFyayBIb3Jpem9uIDU=',
            ],
            $json
        );
    }
}
