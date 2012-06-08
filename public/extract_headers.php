<?php

require_once __DIR__ . '/../bootstrap.php';

use Guzzle\Service\Client;
use Guzzle\Common\Event;
use Guzzle\Http\Plugin\CookiePlugin;
use Guzzle\Http\CookieJar\ArrayCookieJar;

$url = @$_GET['url'] ?: false;
$debug = (bool) @$_GET['debug'] ?: false;

$client = new Client();
$client->getEventDispatcher()->addListener('request.error', function (Event $event) {
    // don't throw exception on error
    $event->stopPropagation();
});

$cookiePlugin = new CookiePlugin(new ArrayCookieJar());
$client->addSubscriber($cookiePlugin);

$image = $headers = $age = $noCache = $cookie = $statusCode = false;
if (false !== $url) {
    $response = $client->get($url)->send();

    $statusCode = $response->getStatusCode();

    $image = array(
        'source' => chunk_split(base64_encode($response->getBody(true))),
        'mime'   => $response->getHeader('Content-Type'),
    );

    $headers = $response->getHeaders();

    $age = $headers->get('Age');
    $age = $age ? $age[0] : false;

    $noCache = $headers->get('Pragma');
    $noCache = $noCache ? $noCache[0] : false;

    foreach ($cookiePlugin->getCookieJar() as $cookie) {
        if ('nocache' === $cookie->getName()) {
            $cookie = $cookie->getValue();
            break;
        }
    }
}

$currentLocation = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Extraction des headers</title>
        <link rel="stylesheet" href="/css/headers.css" type="text/css" media="all">
    </head>
    <body>
        <div id="wrapper">
            <form method="GET">
                <input type="url" name="url" placeholder="url de l'image" value="<?= $url ?>" />
            </form>

            <div class="container">
                <div id="image">
                    <?php if (false !== $image): ?>
                    <img src="data:<?= $image['mime'] ?>;base64,<?= $image['source'] ?>" />
                    <?php endif ?>
                </div>

                <div id="info">
                    <?php if (false !== $statusCode): ?>
                    <span class="status status-<?= $statusCode ?>"><?= $statusCode ?></span>
                    <?php endif ?>

                    <?php if (false !== $headers): ?>
                    <ul class="infos">
                        <li>Age : <span class="value"><?= $age ?: 'indisponible' ?></span></li>
                        <li>Présence header "Pragma: no-cache" : <span class="value"><?= $noCache ? 'oui' : 'non' ?></span></li>
                        <li>Cookie "nocache" : <span class="value"><?= $cookie ?: 'indisponible' ?></span></li>
                    </ul>
                    <?php endif ?>
                </div>
            </div>
            <?php if ($debug && false !== $headers): ?>
            <?php var_dump($headers) ?>
            <?php endif ?>
        </div>
    </body>
</html>
