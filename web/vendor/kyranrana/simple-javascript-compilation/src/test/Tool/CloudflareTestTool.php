<?php

function getPage(string $url): string
{
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_close($ch);

    return $page;
}


function getChallengeCode(string $page): string
{
    $code = substr($page, strpos($page, 'setTimeout(function(){') + 22);
    $code = substr($code, 0, strpos($code, "}, 4000);"));

    return trim($code);
}


function transformChallengeCode(string $code): string
{
    $code = str_replace("var s,t,o,p,b,r,e,a,k,i,n,g,f, ", "", $code);
    $code = preg_replace("/e = function\(s\) {(.|\\n)+?};/", "", $code);
    $code = preg_replace("/(t|a|f|r|o|g) = [^;]+;/", "", $code);
    $code = preg_replace("/(t|f|a)\.[^;]+;/", "", $code);
    $code = str_replace("'; 121'", "", $code);

    preg_match("/(\w+)={\"([^\"]+)\":/", $code, $matches);
    $code = preg_replace("/(\w+)={\"([^\"]+)\":([^}]+)};/", "$1$2=$3;", $code);
    $code = str_replace($matches[1] . '.' . $matches[2], $matches[1] . $matches[2], $code);
    $code = str_replace('Function("return escape")()', "escape", $code);

    return $code;
}

function getSubCode(string $page, string $code): string
{
    preg_match('/k = \'([^\']+)\';/', $code, $kMatches);
    preg_match( '/id="' . $kMatches[1] . '">([^<]+)</', $page, $kElemMatches);

    return $kElemMatches[1];
}

$page = getPage("http://predb.me/?search=720p");
$code = transformChallengeCode(getChallengeCode($page));
$subCode = getSubCode($page, $code);

$code = preg_replace("/k = '([^']+)';/", "", $code);
$code = str_replace("\n", "", $code);
$code = preg_replace("/; +;/", ";", $code);


echo "$code\n";
echo '"' . $subCode . '"' . "\n";