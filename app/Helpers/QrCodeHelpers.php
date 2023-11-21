<?php

namespace App\Helpers;

use QrCode;

class QrCodeHelpers
{
    public static function generateQrCode($data, $path)
    {
        $image = QrCode::format('png')
            ->merge(public_path('assets/images/logo-qr2.png'), 0.3, true)
            ->size(500)
            ->errorCorrection('H')
            ->generate($data, $path);
        return $path;
    }
}
