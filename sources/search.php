<?php
/**
 * YGGTorrentDLM
 * 
 * Parse les résultats de la recherche utilisateur du site YGGTorrent
 * et les affiche dans DownloadStation ce qui permet de visualiser et téléchagrer directement 
 * un torrent depuis le NAS sans jamais passer par le site
 * 
 */

class YGGTorrentDLM {

	/**
	 * @var int MAX_PAGES Nombres de pages maximum pour la recherche
	 */
	const MAX_PAGES = 20;

	/**
	 * @var string TWITTER_URL Url du twitter Yggtorrent
	 */
	const MASTODON_URL = 'https://mamot.fr/@YggTorrent';

	/**
	 * @var string DOWNLOAD_URL Url du fichier ygg.php
	 */
	const DOWNLOAD_URL = 'https://127.0.0.1/ygg.php?torrent={$1}&cookie={$2}';

	/**
	 * @var string SEARCH_PATH Chemin permettant la recherche
	 */
	const SEARCH_PATH = '/engine/search?do=search&sort=publish_date&order=desc&name={$1}&page={$2}';

	/**
	 * @var string TORRENT_PATH Chemin permettant de télécharge un .torrent
	 */
	const TORRENT_PATH = '/engine/download_torrent?id=';

	/**
	 * @var string AUTH_PATH Chemin permettant la connexion
	 */
	const AUTH_PATH = '/user/login';

	/**
	 * @var string COOKIE_FILE Emplacement du cookie
	 */
	const COOKIE = '/tmp/yggtorrent.cookie';
	
	/**
	 * @var string $domain Nom de domaine
	 */
	private $domain;
	
	/**
	 * @var string $subDomainBase sous domaine de base
	 */
	private $subDomainBase;
	
	/**
	 * @var string $subDomainSearch Sous domaine de recherche
	 */
	private $subDomainSearch;
	
	/**
	 * @var DOMDocument $document Instance de DOMDocument
	 */
	private $document;

	/**
	 * @var array $categories Liste des categories
	 */
	private $categories;

	/**
	 * @var string $query Requête de l'utilisateur
	 */
	private $query;

	/**
	 * Constructeur de la classe
	 */
	public function __construct() {

		$this->document = new DOMDocument();

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
			
			// Film / Video
			2178 => 'Animation',
			2179 => 'Animation Série',
			2180 => 'Concert',
			2181 => 'Documentaire',
			2182 => 'Emission TV',
			2183 => 'Film',
			2184 => 'Série TV',
			2185 => 'Spectacle',
			2186 => 'Sport',
			2187 => 'Vidéo-clips',

			// Adulte
			2189 => 'Film',
			2190 => 'Hentai',
			2191 => 'Image'
		);
	}    
	
	/**
	 * Synology
	 * 
	 * Rrecherche les résultats de la requête sur le site
	 * @param resource $curl CURL
	 * @param string $query Recherche de l'utilisateur
	 * @param string $username Identifiant
	 * @param string $password Mot de passe
	 */
	public function prepare($curl, $query, $username, $password) {	
		
		if ($this->VerifyAccount($username, $password)) {
			$this->query = $query;	
			$url = $this->subDomainSearch . $this->domain . preg_replace(array('/{\$1}/', '/{\$2}/'), array(urlencode($this->query), 0), self::SEARCH_PATH);
			return $this->Request($url, $curl, true);
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
		$this->ParseContent($plugin, $response);

		if ($totalPages > 1) {
			if ($totalPages > self::MAX_PAGES - 1)
				$totalPages = self::MAX_PAGES - 1;

			if ($totalPages < self::MAX_PAGES - 1)
				$totalPages--;

			for ($i = 1; $i <= $totalPages; $i++) {
				$url = $this->subDomainSearch . $this->domain . preg_replace(array('/{\$1}/', '/{\$2}/'), array(urlencode($this->query), $i * 50), self::SEARCH_PATH);
				$content = $this->Request($url);		
				$this->ParseContent($plugin, $content);
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
		$this->GetDomain();
		
		$curl = curl_init();		
		curl_setopt($curl, CURLOPT_POSTFIELDS, array('id' => urlencode($username), 'pass' => urlencode($password)));
		$this->Request($this->subDomainBase . $this->domain . self::AUTH_PATH, $curl);
		
		$content = $this->Request($this->subDomainBase . $this->domain);
				
		@$this->document->loadHTML('<?xml encoding="utf-8" ?>' . $content);
		$xpath = new DOMXpath($this->document);
		$ratio = $xpath->query("//*[contains(@class, 'ico_upload')]");

		return $ratio->length > 0 ? true : false;
	}

	/**
	 * Parse la page de recherche et récupére les informations des torrents
	 * @param resource $plugin
	 * @param string $content Page HTML
	 */
	private function ParseContent($plugin, $content) {

		@$this->document->loadHTML('<?xml encoding="utf-8" ?>' . $content);

		$table = $this->document->getElementsByTagName('table'); 
		
		if ($table->length > 1) {

			$tbody = $table->item(1)->getElementsByTagName('tbody');
			$rows = $tbody->item(0)->getElementsByTagName('tr');

			foreach($rows as $row) {	

				$item = $row->getElementsByTagName('td'); 
				$a = $item->item(1)->getElementsByTagName('a');
				$url = $a->item(0)->getAttribute('href');
				
				$torrentId = explode('/', $url);
				$torrentId = explode('-', $torrentId[sizeof($torrentId) - 1])[0];

				$torrent = [
					'category' => $this->GetCategory($item->item(0)->nodeValue),
					'name' => trim(preg_replace('/\s+/', ' ', $item->item(1)->nodeValue)),
					'url' => $url,
					'download' => preg_replace(array('/{\$1}/', '/{\$2}/'), array($this->subDomainSearch . $this->domain . self::TORRENT_PATH . $torrentId, self::COOKIE), self::DOWNLOAD_URL),
					'hash' => $torrentId,
					'date' => $this->GetDate($item->item(4)->nodeValue),
					'size' => (float)$this->GetSize($item->item(5)->nodeValue),
					'seeder' => (int)$item->item(7)->nodeValue, 
					'leecher' => (int)$item->item(8)->nodeValue
				];
				
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
	 * Supprime le cookie
	 */
	private function DeleteCookie() {

		if (file_exists(self::COOKIE)) {
			unlink(self::COOKIE);
		}
	}

	/**
	 * Exécute la requête CURL
	 * @param string $url URL de la page
	 * @param resource $curl CURL
	 * @param bool $prepare Identifie si curl est initialisé par synology ou non
	 * @return string Retourne la page au format HTML
	 */
	private function Request($url, $curl = null, $prepare = false) {

		if (!isset($curl))
			$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_COOKIEFILE => self::COOKIE,
			CURLOPT_COOKIEJAR => self::COOKIE
		]);

		if(!$prepare) {
			$content = curl_exec($curl);
			curl_close($curl);
		}

		return !isset($content) ? null : $content;
	}

	/**
	 * Récupére le nom de domaine
	 */
	private function GetDomain() {
				
		$content = $this->Request(self::MASTODON_URL);
		@$this->document->loadHTML('<?xml encoding="utf-8" ?>' . $content);
		$xpath = new DOMXpath($this->document);

		$this->domain = $xpath->query("//*[contains(@class, 'account__header__fields')]");
		$this->domain = str_replace(array(' ', '/', PHP_EOL), array('', '', ''), $this->domain[0]->textContent);
		$this->domain = explode('.', $this->domain);
		array_shift($this->domain);
		$this->domain = implode('.', $this->domain);

		$this->GetSubDomain();
	}

	/**
	 * Récupére les sous-domaine
	 */
	private function GetSubDomain() {
				
		$content = $this->Request($this->domain);
		@$this->document->loadHTML('<?xml encoding="utf-8" ?>' . $content);
		$xpath = new DOMXpath($this->document);

		$this->subDomainBase = $xpath->query("//*[contains(@class, 'logotype')]");		
		$this->subDomainBase = explode('/', $this->subDomainBase[0]->attributes[0]->nextSibling->value)[2];
		$this->subDomainBase = 'https://' . explode('.', $this->subDomainBase)[0] . '.';
		
		$this->subDomainSearch = $xpath->query("//*[contains(@class, 'search')]");
		$this->subDomainSearch = explode('/', $this->subDomainSearch[0]->attributes[0]->nextSibling->value)[2];
		$this->subDomainSearch = 'https://' . explode('.', $this->subDomainSearch)[0] . '.';
	}

	/**
	 * Retourne la date en fonction du timestamp
	 * @param string $time Timestamp
	 * @return string Retourne la date au format 2019-01-01 01:00:00
	 */
	private function GetDate($time) {		

		$timestamp = explode(' ', $time)[0];
		$date = new DateTime();
		$date->setTimestamp($timestamp);
		
		return $date->format('Y-m-d H:i:s');
	}

	/**
	 * Retourne le nombre de pages de la recherche
	 * @param string $content Page au format HTML
	 * @return int Retourne le nombre de pages
	 */
	private function GetTotalPages($content) {

		@$this->document->loadHTML('<?xml encoding="utf-8" ?>' . $content); 

		$h2 = $this->document->getElementsByTagName('h2'); 

		$total = explode(' ', $h2->item(1)->nodeValue);
		$total = array_splice($total, 3);
		$total = (float)implode('', $total);
		$total = ceil($total / 50);

		return $total;
	}
	
	/**
	 * Retourne la categorie du torrent
	 * @param string $id Identifiant de la categorie
	 * @return string Retourne le nom de la categorie
	 */
	private function GetCategory($id) {

		foreach ($this->categories as $catId => $catName) {
			if ($catId === (int)$id) {
				return $catName;
			}
		}

		return 'Autre';
	}

	/**
	 * Retourne la taille en octets
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
		
		return (float)$size;
	}
}
