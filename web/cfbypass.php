<?php

require 'vendor/autoload.php';

use CloudflareBypass\CFCurlImpl;
use CloudflareBypass\Model\UAMOptions;

$cookie = '/tmp/cloudflare.cookie';
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $_GET['domain'] . '/engine/search?do=search&sort=publish_date&order=desc&page=' . $_GET['page'] . '&name=' . urlencode($_GET['query']),
    CURLINFO_HEADER_OUT => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIEFILE => $cookie,
    CURLOPT_COOKIEJAR => $cookie,
    CURLOPT_HTTPHEADER => array(
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
        'Accept-Language: en-US,en;q=0.9'
    )
]);

try 
{
    $cfCurl = new CFCurlImpl();
    $content = $cfCurl->exec($curl, new UAMOptions());
    echo $content;
} 
catch (ErrorException $ex) 
{
    echo 'Error -> ' . $ex->getMessage();
}
