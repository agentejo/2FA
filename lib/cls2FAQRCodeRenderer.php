<?php


use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;


class cls2FAQRCodeRenderer implements \RobThree\Auth\Providers\Qr\IQRCodeProvider {

    public function getMimeType() {
        return 'image/svg+xml';
    }

    public function getQRCodeImage($qrtext, $size = 200, $margin = 0) {

        $renderer = new ImageRenderer(
            new RendererStyle($size, $margin),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($qrtext); // Return image
    }

}
