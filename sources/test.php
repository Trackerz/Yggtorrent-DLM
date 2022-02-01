<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('search.php');

$username = '';
$password = '';
$request  = 'Game of Thrones';

$ygg = new YGGTorrentDLM();

if ($ygg->VerifyAccount($username, $password)) 
{
    echo '<p style="font-family:sans-serif;font-size:14px;color:green">CONNECTE</p>';

    $curl = curl_init();
    $ygg->prepare($curl, $request, $username, $password);

    $response = curl_exec($curl);
    curl_close($curl);

    $plugin = new Plugin();
    $ygg->parse($plugin, $response);

    echo '<p style="font-family:sans-serif;font-size:14px">Nombre de torrent : <b>' . $plugin->Total() . '</b><p>';
    echo '<pre>';
    var_dump($plugin->results);
    echo '</pre>';
} 
else
{
    echo '<p style="font-family:sans-serif;font-size:14px;color:red">DECONNECTE</p>';
}

class Plugin
{
    public $results;

    public function AddResult($title, $download, $size, $datetime, $page, $hash, $seeds, $leechs, $category)
    {
        $this->results[] = array(
            'title' => $title,
            'download' => $download,
            'size' => $size,
            'datetime' => $datetime,
            'page' => $page,
            'hash' => $hash,
            'seeds' => $seeds,
            'leechs' => $leechs,
            'category' => $category
        );
    }

    public function Total()
    {
        return count($this->results);
    }
}
