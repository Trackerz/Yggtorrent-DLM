<?php
require('search.php');

$username = '';
$password = '';
$request = 'better call saul';

$ygg = new YggTorrentDLM();

if ($ygg->VerifyAccount($username, $password)) 
{
    echo '<p style="color:green">CONNECTED</p>';

    $curl = curl_init();
    $ygg->prepare($curl, $request, $username, $password);
    $content = curl_exec($curl);
    curl_close($curl);

    $plugin = new Plugin();
    $count = $ygg->parse($plugin, $content);

    echo '</br>Total results : ' . $plugin->Total() . '</br></br>';
    echo '<pre>';
    var_dump($plugin->torrents);
    echo '</pre>';  
} 
else 
{
    echo '<p style="color:red">NOT CONNECTED</p>';
}

class Plugin
{
    public $torrents;

    public function AddResult($title, $download, $size, $datetime, $page, $hash, $seeds, $leechs, $category)
    {
        $this->torrents[] = array(
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
        return count($this->torrents);
    }
}
