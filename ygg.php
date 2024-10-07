<?php
/**
 * /!\ Ne pas modifier ce fichier /!\
 * /!\ Ne pas modifier ce fichier /!\
 * /!\ Ne pas modifier ce fichier /!\
 */

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_HTTPHEADER => [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/png,image/svg+xml,/;q=0.8',
        'TE: trailers',
        'Upgrade-Insecure-Requests: 1',
    ],
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0',
    CURLOPT_URL => $_GET['torrent'],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIEFILE => $_GET['cookie'],
    CURLOPT_COOKIEJAR => $GET['cookie'],
    CURLOPT_COOKIE => 'account_created=true; cf_clearance=; ygg=',
]);

$content = curl_exec($curl);

curl_close($curl);

header('Content-Type: application/x-bittorrent');
header('Content-Disposition: attachment; filename=' . explode('=', $_GET['torrent'])[1] . '.torrent');

echo $content;
