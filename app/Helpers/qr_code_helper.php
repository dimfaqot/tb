<?php

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

function set_qr_code($url, $logo, $text)
{
    $writer = new PngWriter();
    $qrCode = QrCode::create($url)
        // ->setEncoding(new Encoding('UTF-8'))
        // ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
        ->setSize(100)
        ->setMargin(($logo == 'ekstra' ? 5 : 0))
        // ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->setForegroundColor(new Color(0, 0, 0));
    // ->setBackgroundColor(new Color(255, 255, 255));

    $logo = Logo::create($logo . '.png')
        ->setResizeToWidth(($text == 'Ppdb' ? 5 : 25))
        ->setPunchoutBackground(false);

    $label = Label::create($text)
        ->setTextColor(new Color(99, 99, 99));

    $result = $writer->write($qrCode, $logo);


    $qr = $result->getDataUri();


    return $qr;
}
