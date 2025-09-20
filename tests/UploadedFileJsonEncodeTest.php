<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use WyriHaximus;

use function Safe\json_encode;

final class UploadedFileJsonEncodeTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(Provider::class, 'uploadedFileBeerBottle')]
    public function success(UploadedFileInterface $beerBottle): void
    {
        $json = WyriHaximus\psr7_uploaded_file_json_encode($beerBottle);

        self::assertSame(
            json_encode(Messages::FILE_BEER_BOTTLE),
            $json,
        );

        self::assertSame('Dark Horizon 5', (string) $beerBottle->getStream());
    }
}
