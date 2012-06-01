<?php

date_default_timezone_set('Europe/Paris');

define('ASSETS_DIR', __DIR__ . '/assets');

function create_image_elephant()
{
    $img = imagecreatefromjpeg(ASSETS_DIR . '/elephant.jpg');
    $white = imagecolorallocatealpha($img, 255, 255, 255, 40);
    $black = imagecolorallocate($img, 0, 0, 0);
    $date = date('H:i:s');
    imagefilledrectangle($img, 0, 0, 645, 30, $white);
    $font = ASSETS_DIR . '/PontanoSans-Regular.ttf';
    imagettftext($img, 14, 0, 560, 22, $black, $font, $date);
    return $img;
}

$page = trim(@$_SERVER['REQUEST_URI'], '/');

switch ($page) {
    case 'elephant.jpg':
        header('Content-type: image/jpeg');
        $img = create_image_elephant();
        imagejpeg($img);
        break;

    case 'cookies.jpg':
        setcookie('nocache', time());
        header('Content-type: image/jpeg');
        $img = create_image_elephant();
        imagejpeg($img);
        break;

    case 'nocache.png':
        header('Pragma: no-cache');
        header('Content-type: image/png');
        $img = create_image_elephant();
        imagepng($img);
        break;

    case '302.gif':
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/elephant.jpg');
        break;

    case '404.gif':
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        header('Content-type: image/gif');
        $img = create_image_elephant();
        imagegif($img);
        break;

    case '500.gif':
    default:
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
        header('Content-type: image/gif');
        $img = create_image_elephant();
        imagegif($img);
        break;
}

imagedestroy($img);
