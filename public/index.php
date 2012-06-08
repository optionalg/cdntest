<?php

require_once __DIR__ . '/../bootstrap.php';

function create_image_elephant($title = null)
{
    $img = imagecreatefromjpeg(ASSETS_DIR . '/elephant.jpg');
    $white = imagecolorallocatealpha($img, 255, 255, 255, 40);
    $black = imagecolorallocate($img, 0, 0, 0);
    $date = date('H:i:s');
    imagefilledrectangle($img, 0, 0, 645, 30, $white);
    $font = ASSETS_DIR . '/PontanoSans-Regular.ttf';
    imagefttext($img, 14, 0, 560, 22, $black, $font, $date);
    if ($title) {
        imagettftext($img, 14, 0, 14, 22, $black, $font, $title);
    }
    return $img;
}

$page = trim(@$_SERVER['REQUEST_URI'], '/');

switch ($page) {
    case 'elephant.jpg':
        header('Content-type: image/jpeg');
        $img = create_image_elephant();
        imagejpeg($img, null, 100);
        break;

    case 'cookies.jpg':
        setcookie('nocache', time());
        header('Content-type: image/jpeg');
        $img = create_image_elephant('Test cookie');
        imagejpeg($img, null, 100);
        break;

    case 'nocache.png':
        header('Pragma: no-cache');
        header('Content-type: image/png');
        $img = create_image_elephant('Test no-cache');
        imagepng($img, null, 100);
        break;

    case '302':
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/elephant.jpg');
        break;

    case '404.gif':
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        header('Content-type: image/gif');
        $img = create_image_elephant('Test 404');
        imagegif($img);
        break;

    case '500.gif':
    default:
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
        header('Content-type: image/gif');
        $img = create_image_elephant('Test HTTP error');
        imagegif($img);
        break;
}

if (isset($img)) {
    imagedestroy($img);
}
