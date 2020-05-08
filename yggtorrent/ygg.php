<?php

require 'vendor/autoload.php';

use CloudflareBypass\CFCurlImpl;
use CloudflareBypass\Model\UAMOptions;

switch ($_GET['type']) {
    case 'search':
        $url = $_GET['url'] . '/engine/search?do=search&sort=publish_date&order=desc&page=' . $_GET['page'] . '&name=' . urlencode($_GET['query']);
        break;

    case 'download':
        $url = $_GET['url'];
        header('Content-Type: application/x-bittorrent');
        header('Content-Disposition: attachment; filename=' . explode('=', $_GET['url'])[1] . '.torrent');
        break;
}

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLINFO_HEADER_OUT => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIEFILE => $_GET['cookie'],
    CURLOPT_COOKIEJAR => $_GET['cookie'],
    CURLOPT_HTTPHEADER => array(
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
        'Accept-Language: en-US,en;q=0.9'
    )
]);

try {
    $cfCurl = new CFCurlImpl();
    $cfOptions = new UAMOptions();
    echo $cfCurl->exec($curl, $cfOptions);
} catch (ErrorException $ex) {
    echo 'Error -> ' . $ex->getMessage();
}
