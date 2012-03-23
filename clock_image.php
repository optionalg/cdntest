<?php

date_default_timezone_set('Europe/Paris');

$format = isset($_GET['format']) ? $_GET['format'] : false;

function create_image_clock()
{
    $width = 150;
    $height = 40;
    $img = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($img, 255, 255, 255);
    $black = imagecolorallocate($img, 0, 0, 0);
    $date = date('H:i:s');
    imagefilledrectangle($img, 0, 0, $width, $height, $white);
    $font = './PontanoSans-Regular.ttf';
    imagettftext($img, 24, 0, 5, 32, $black, $font, $date);
    return $img;
}

$img = create_image_clock();

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

if ($format === 'png') {
    header('Content-type: image/png');
    imagepng($img);
} else if ($format === 'gif') {
    header('Content-type: image/gif');
    imagegif($img);
} else {
    header('Content-type: image/jpeg');
    imagejpeg($img);
}

imagedestroy($img);
