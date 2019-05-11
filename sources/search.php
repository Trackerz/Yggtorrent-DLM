<?php

/**
 * YGGTorrentDLM
 * 
 * Parse les résultats de la recherche utilisateur du site YGGTorrent
 * et les affiches dans DownloadStation ce qui permet de visualiser et téléchagrer directement 
 * un torrent depuis le NAS sans jamais passer par le site
 * 
 * /!\ Un compte ACTIF avec un ratio supérieur à 1 est requis /!\
 */
class YGGTorrentDLM {

	/**
	 * @var int MAX_PAGES Nombres de pages maximum pour la recherche
	 * L'application smartphone DS-GET affiche maximum 1000 résultats
	 * 50 résultats par pages * 20 = 1000
	 */
	const MAX_PAGES = 20;

	/**
	 * @var string EXTENSION Extension du nom de domaine
	 */
	const EXTENSION = 'ch';

	/**
	 * @var string BASE_URL Url de la page d'accueil
	 */
	const BASE_URL = 'https://www.yggtorrent.' . self::EXTENSION;
	
	/**
	 * @var string PROXY_URL Url secondaire du site autre que l'accueil
	 */
	const PROXY_URL = 'https://www2.yggtorrent.' . self::EXTENSION;

	/**
	 * @var string DOWNLOAD_URL Url du fichier ygg.php
	 */
	const DOWNLOAD_URL = 'https://127.0.0.1/ygg.php?torrent={$1}&cookie={$2}';

	/**
	 * @var string SEARCH_URL Chemin permetant la recherche
	 */
	const SEARCH_URL = '/engine/search?do=search&sort=publish_date&order=desc&name={$1}&page={$2}';

	/**
	 * @var string TORRENT_URL Chemin permetant de télécharge un .torrent
	 */
	const TORRENT_URL = '/engine/download_torrent?id=';

	/**
	 * @var string AUTH_URL Chemin permetant la connexion
	 */
	const AUTH_URL = '/user/login';

	/**
	 * @var string COOKIE_FILE Emplacement du cookie
	 */
	const COOKIE_FILE = '/tmp/yggtorrent.cookie';
    
    /**
     * @var resource $dom Instance de DOMDocument
     */
	private $dom;
	
    /**
     * @var resource $curl CURL
     */
    private $curl;

    /**
     * @var array $categories Liste des categories
     */
    private $categories;

    /**
     * @var string $query Recherche de l'utilisateur
     */
	private $query;

    /**
     * Constructeur de la classe
     */
    public function __construct() {

		$this->dom = new DOMDocument();

		$this->categories = array(
			// Audio
			2147 => 'Karaoke',
			2148 => 'Musique',
			2149 => 'Samples',
			2150 => 'Podcast Radio',

			// eBook
			2151 => 'Audio',
			2152 => 'Bds',
			2153 => 'Comics',
			2154 => 'Livres',
			2155 => 'Mangas',
			2156 => 'Presse',

			// Emulation
			2157 => 'Emulateurs',
			2158 => 'Roms',
			
			// Jeux
			2159 => 'Linux',
			2160 => 'MacOS',
			2161 => 'Windows',
			2162 => 'Microsoft',
			2163 => 'Nintendo',
			2164 => 'Sony',
			2165 => 'Smartphone',
			2166 => 'Tablette',
			2167 => 'Jeux Autre',

			// GPS
			2168 => 'Applications',
			2169 => 'Cartes',
			2170 => 'Divers',

			// Application
			2171 => 'Linux',
			2172 => 'MacOS',
			2173 => 'Windows',
			2174 => 'Smartphone',
			2175 => 'Tablette',
			2176 => 'Formation',
			2177 => 'Application Autre',
			
			// Film/Video
			2178 => 'Animation',
			2179 => 'Animation Série',
			2180 => 'Concert',
			2181 => 'Documentaire',
			2182 => 'Emission TV',
			2183 => 'Film',
			2184 => 'Série TV',
			2185 => 'Spectacle',
			2186 => 'Sport',
			2187 => 'Vidéo-clips'
		);
    }    
    
    /**
     * Synology
	 * 
	 * Lance la recherche sur le site
     * @param resource $curl CURL
     * @param string $query Recherche de l'utilisateur
     * @param string $username Identifiant
     * @param string $password Mot de passe
     */
    public function prepare($curl, $query, $username, $password) {		

		if ($this->VerifyAccount($username, $password)) {
			$this->query = $query;	
			$url = self::PROXY_URL . preg_replace(array('/{\$1}/', '/{\$2}/'), array(urlencode($this->query), 0), self::SEARCH_URL);
			$content = $this->CurlRequest($url, $curl);
		}
    }

    /**
     * Synology
	 * 
	 * Parse toutes les pages de la recherche
     * @param resource $plugin
     * @param string $response Page au format HTML
     */
    public function parse($plugin, $response) {

		$totalPages = $this->GetTotalPages($response);
		$this->ParseSearchContent($plugin, $response);

		if ($totalPages > 1) {
			if ($totalPages > self::MAX_PAGES - 1)
				$totalPages = self::MAX_PAGES - 1;

			if ($totalPages < self::MAX_PAGES - 1)
				$totalPages--;

			for ($i = 1; $i <= $totalPages; $i++) {
				$this->curl = curl_init();				
				$url = self::PROXY_URL . preg_replace(array('/{\$1}/', '/{\$2}/'), array(urlencode($this->query), $i * 50), self::SEARCH_URL);
				$content = $this->CurlRequest($url);		
				$this->ParseSearchContent($plugin, $content);
			}
		}
	}

    /**
     * Synology
	 * 
     * Teste la connexion et crée un cookie
     * @param string $username Identifiant
     * @param string $password Mot de passe
     * @return bool Retourne TRUE si la connexion fonctionne sinon FALSE
     */    
    public function VerifyAccount($username, $password) { 
			
		$this->DeleteCookie();

		$data = array(
            'id' => urlencode($username),
            'pass' => urlencode($password)
        );
        
		$this->curl = curl_init();		
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        $this->CurlRequest(self::BASE_URL . self::AUTH_URL);
        
        $this->curl = curl_init();
        $content = $this->CurlRequest(self::BASE_URL);
		
		@$this->dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
		$finder = new DOMXpath($this->dom);
		$ratio = $finder->query("//*[contains(@class, 'ico_upload')]");

        return $ratio->length > 0 ? true : false;
    }

    /**
     * Parse la page de recherche
	 * @param resource $plugin
     * @param string $content Page HTML
     */
    private function ParseSearchContent($plugin, $content) {

		@$this->dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);

		$table = $this->dom->getElementsByTagName('table'); 
		
		if ($table->length > 1) {

			$tbody = $table->item(1)->getElementsByTagName('tbody');
			$rows = $tbody->item(0)->getElementsByTagName('tr');

			foreach($rows as $row) {	

				$item = $row->getElementsByTagName('td'); 
				$a = $item->item(1)->getElementsByTagName('a');
				$url = $a->item(0)->getAttribute('href');

				$torrentId = explode('/', $url);
				$torrentId = explode('-', $torrentId[sizeof($torrentId) - 1])[0];

				$torrent['category'] = $this->GetCategory($item->item(0)->nodeValue);
				$torrent['name'] = $item->item(1)->nodeValue;
				$torrent['url'] = $url;
				$torrent['download'] = preg_replace(array('/{\$1}/', '/{\$2}/'), array(self::PROXY_URL . self::TORRENT_URL . $torrentId, self::COOKIE_FILE), self::DOWNLOAD_URL);
				$torrent['hash'] = $torrentId;
				$torrent['date'] = $this->GetDate($item->item(4)->nodeValue);
				$torrent['size'] = floatval($this->GetSize($item->item(5)->nodeValue));
				$torrent['seeder'] = intval($item->item(7)->nodeValue); 
				$torrent['leecher'] = intval($item->item(8)->nodeValue);   
				
				$this->AddTorrent($plugin, $torrent);
			}	
		}
	}
	
	/**
	 * Ajoute le torrent dans la liste de DownloadStation
	 * @param resource $plugin
	 * @param array $torrent Informations du torrent
	 */
	private function AddTorrent($plugin, $torrent) {
		
		$plugin->addResult(
			$torrent['name'], 
			$torrent['download'], 
			$torrent['size'], 
			$torrent['date'],
			$torrent['url'],
			$torrent['hash'],
			$torrent['seeder'],
			$torrent['leecher'],
			$torrent['category']
		);
	}

	/**
	 * Récupére la date en fonction du timestamp
	 * @param string $time Timestamp
	 * @return string Retourne la date au format 2019-01-01 01:00:00
	 */
	private function GetDate($time) {		

		$timestamp = explode(' ', $time)[0];
		$date = new DateTime();
		$date->setTimestamp($timestamp);
		$date = $date->format('Y-m-d H:i:s');

		return $date;
	}

	/**
	 * Récupére le nombre de pages de la recherche
	 * @param string $page Page au format HTML
	 * @return int Retourne le nombre de pages
	 */
	private function GetTotalPages($content) {

		@$this->dom->loadHTML('<?xml encoding="utf-8" ?>' . $content); 

		$h2 = $this->dom->getElementsByTagName('h2'); 

		$total = explode(' ', $h2->item(1)->nodeValue);
		$total = array_splice($total, 3);
		$total = implode('', $total);
		$total = ceil(intval($total) / 50);

		return $total;
	}
	
	/**
	 * Récupére le nom de la categorie du torrent
	 * @param string $id Identifiant de la categorie
	 * @return string Retourne le nom de la categorie
	 */
	private function GetCategory($id) {

		foreach ($this->categories as $catId => $catName) {
			if ($catId === intval($id)) {
				return $catName;
			}
		}

		return 'Autre';
	}

	/**
	 * Converti la taille en byte
	 * @param string $size Taille du fichier depuis le site
	 * @return float Retourne la taille en byte
	 */
	private function GetSize($size) {

		$unit = substr($size, strlen($size) - 2, strlen($size));
		$size = substr($size, 0, strlen($size) - 2);

		switch ($unit) {
			case 'To': 
				$size = $size * 1024 * 1024 * 1024 * 1024; 
				break;

			case 'Go': 
				$size = $size * 1024 * 1024 * 1024; 
				break;

			case 'Mo': 
				$size = $size * 1024 * 1024; 
				break;
			
			case 'ko': 
				$size = $size * 1024;    
				break;			
		}
		
		return $size;
	}

	/**
	 * Supprime le cookie
	 */
	private function DeleteCookie() {

		if (file_exists(self::COOKIE_FILE)) {
			unlink(self::COOKIE_FILE);
		}
	}

    /**
     * Exécute la requete CURL
     * @param string $url URL de la page
	 * @param resource $curl CURL
     * @return string Retourne la page au format HTML
     */
    private function CurlRequest($url, $curl = null) {

        if (isset($curl))
			$this->curl = $curl;

		curl_setopt_array($this->curl, [
			CURLOPT_URL => $url,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_COOKIEFILE => self::COOKIE_FILE,
			CURLOPT_COOKIEJAR => self::COOKIE_FILE
		]);

        if (!isset($curl)) {
            $content = curl_exec($this->curl);
            curl_close($this->curl);
		}

        return !isset($content) ? null : $content;
	}
}