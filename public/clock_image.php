<?php

require_once __DIR__ . '/../bootstrap.php';

function create_image_clock()
{
    $width = 150;
    $height = 40;
    $img = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($img, 255, 255, 255);
    $black = imagecolorallocate($img, 0, 0, 0);
    $date = date('H:i:s');
    imagefilledrectangle($img, 0, 0, $width, $height, $white);
    $font = ASSETS_DIR . '/PontanoSans-Regular.ttf';
    imagettftext($img, 24, 0, 5, 32, $black, $font, $date);
    return $img;
}

$img = create_image_clock();

$format = @$_GET['format'] ?: false;

switch ($format) {
    case 'png':
        header('Content-type: image/png');
        imagepng($img);
        break;

    case 'gif':
        header('Content-type: image/gif');
        imagegif($img);
        break;

    default:
        header('Content-type: image/jpeg');
        imagejpeg($img);
        break;
}

imagedestroy($img);
