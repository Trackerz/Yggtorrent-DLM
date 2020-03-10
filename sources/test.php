<?php
require('search.php');

$username = '';
$password = '';
$request = 'game of throne';

$ygg = new YGGTorrentDLM();
$curl = curl_init();

if($ygg->VerifyAccount($username, $password)) {

    echo '<p style="color:green">CONNECTED</p>';    

    $ygg->prepare($curl, $request, $username, $password);

    $response = curl_exec($curl);
    curl_close($curl);

    $plugin = new Plugin();
    $count = $ygg->parse($plugin, $response);

    echo '</br>Total results : ' . $plugin->Total() . '</br></br>';
    echo '<pre>';
    var_dump($plugin->results);
    echo '</pre>';
    
} else {
    echo '<p style="color:red">NOT CONNECTED</p>';
}

class Plugin {
        
    public $results;

    public function AddResult($title, $download, $size, $datetime, $page, $hash, $seeds, $leechs, $category) {
        
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

    public function Total() {                
        return count($this->results);
    }
}
