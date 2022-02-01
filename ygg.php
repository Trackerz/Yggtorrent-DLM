<?php

/**
 * /!\ Ne pas modifier ce fichier /!\
 * /!\ Ne pas modifier ce fichier /!\
 * /!\ Ne pas modifier ce fichier /!\
 */

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_USERAGENT => 'Googlebot/2.1',
    CURLOPT_URL => $_GET['torrent'],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,      
    CURLOPT_COOKIEFILE => $_GET['cookie'],
    CURLOPT_COOKIEJAR => $_GET['cookie']
]);

$content = curl_exec($curl);

curl_close($curl);

header('Content-Type: application/x-bittorrent');
header('Content-Disposition: attachment; filename=' . explode('=', $_GET['torrent'])[1] . '.torrent');

echo $content;