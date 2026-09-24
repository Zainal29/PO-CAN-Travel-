<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public function dataUri(string $content): string
    {
        return Builder::create()
            ->writer(new PngWriter())
            ->data($content)
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(320)
            ->margin(12)
            ->build()
            ->getDataUri();
    }

    public function png(string $content): string
    {
        return Builder::create()
            ->writer(new PngWriter())
            ->data($content)
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(640)
            ->margin(16)
            ->build()
            ->getString();
    }
}
