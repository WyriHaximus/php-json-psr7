<?php

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use React\Http\Io\UploadedFile;
use WyriHaximus;
use function RingCentral\Psr7\stream_for;

final class UploadedFileJsonEncodeTest extends TestCase
{
    public function test()
    {
        $beerBottle = new UploadedFile(stream_for('Dark Horizon 5'), 14, UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid');

        $json = WyriHaximus\psr7_uploaded_file_json_encode($beerBottle);
        self::assertSame(
            json_encode([
                'filename' => 'beer.bottle',
                'media_type' => 'earth/liquid',
                'error' => 0,
                'size' => 14,
                'stream' => 'Dark Horizon 5',
            ]),
            $json
        );
    }
}
