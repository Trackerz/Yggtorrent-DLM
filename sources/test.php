<?php
require('search.php');

$ygg = new YGGTorrentDLM();
$curl = curl_init();

$ygg->prepare($curl, 'game of throne', '', '');

$response = curl_exec($curl);
curl_close($curl);

$plugin = new Plugin();
$count = $ygg->parse($plugin, $response);

echo 'TOTAL : ' . $plugin->count() . "\n";

class Plugin 
{
    private $results;

    public function addResult($title, $download, $size, $datetime, $page, $hash, $seeds, $leechs, $category) {
        $this->results[] = array(
            'tite' => $title,
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

    public function count() {        
        return count($this->results);
    }
}