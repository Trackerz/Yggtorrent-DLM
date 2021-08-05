<?php

/**
 * 
 * YGGTorrentDLM
 * 
 * Parse les résultats de la recherche utilisateur du site YGGTorrent
 * et les affichent dans DownloadStation ce qui permet de visualiser et téléchagrer directement 
 * un torrent depuis le NAS sans passer par le site
 * 
 */

class YGGTorrentDLM
{
	/**
	 * @var int Nombre de pages maximum pour la recherche
	 */
	const MAX_PAGES = 20;

	/**
	 * @var string Chemin du cookie
	 */
	const COOKIE = '/tmp/yggtorrent.cookie';

	/**
	 * @var string Url download des fichiers .torrent
	 */
	const TORRENT_PATH = '/engine/download_torrent?id=';

	/**
	 * @var string Url de connexion
	 */
	const AUTH_PATH = '/user/login';

	/**
	 * @var string Url du fichier ygg.php
	 */
	private $downloadUrl = 'https://127.0.0.1/ygg.php?torrent={$1}&cookie={$2}';

	/**
	 * @var string Url de recherche
	 */
	private $searchPath = '/engine/search?do=search&sort=publish_date&order=desc&name={$1}&page={$2}';

	/**
	 * @var bool Selectionne la version du build
	 */
	private $buildForDsm6 = false;

	/**
	 * @var Array List des réseaux sociaux
	 */
	private $socials;

	/**
	 * @var string Nom de domaine
	 */
	private $domain;

	/**
	 * @var string Sous domaine de recherche
	 */
	private $subDomain;

	/**
	 * @var DOMDocument Instance de DOMDocument
	 */
	private $document;

	/**
	 * @var array Liste des categories
	 */
	private $categories;

	/**
	 * @var string Recherche de l'utilisateur
	 */
	private $query;

	/**
	 * Constructeur de la classe
	 */
	public function __construct()
	{
		$this->document = new DOMDocument();

		$this->socials = array(
			'twitter' => array(
				'url' => 'twitter.com/yggtorrent_p2p',
				'class' => 'twitter-timeline-link'
			),
			'mastodon' => array(				
				'url' => 'mamot.fr/@YggTorrent',
				'class' => 'account__header__fields'
			),
			/* Plus mis a jour
			'telegram' => array(				
				'url' => 't.me/yggtorrent',
				'class' => 'tgme_page_description'
			)*/
		);

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

			// Adulte (on dit merci qui? :p )
			2189 => 'Film',
			2190 => 'Hentai',
			2191 => 'Image'
		);
	}

	/**
	 * Synology - Execute la requête de recherche
	 * 
	 * @param resource $curl CURL
	 * @param string $query Recherche de l'utilisateur
	 * @param string $username Identifiant
	 * @param string $password Mot de passe
	 * @return DOMXpath Contenu de la page
	 */
	public function prepare($curl, $query, $username, $password)
	{
		if ($this->VerifyAccount($username, $password)) 
		{
			$this->query = $query;
			$url = $this->subDomain . $this->domain . preg_replace(array('/{\$1}/', '/{\$2}/'), array(urlencode($this->query), 0), $this->searchPath);

			return $this->Request($url, $curl, true);
		}
	}

	/**
	 * Synology - Parse toutes les pages de la recherche
	 * 
	 * @param object $plugin Renvoyé par Synology
	 * @param string $content Page de recherche
	 */
	public function parse($plugin, $content)
	{
		@$this->document->loadHTML('<?xml encoding="utf-8" ?>' . $content);
		$xpath = new DOMXpath($this->document);

		$totalPages = $this->GetTotalPages($xpath);

		if ($totalPages > 0) 
		{
			$this->ParseContent($plugin, $xpath);

			if ($totalPages > 1) 
			{
				if ($totalPages > self::MAX_PAGES - 1)
					$totalPages = self::MAX_PAGES - 1;

				if ($totalPages < self::MAX_PAGES - 1)
					$totalPages--;

				for ($i = 1; $i <= $totalPages; $i++) 
				{
					$url = $this->subDomain . $this->domain . preg_replace(array('/{\$1}/', '/{\$2}/'), array(urlencode($this->query), $i * 50), $this->searchPath);
					$xpath = $this->Request($url);
					$this->ParseContent($plugin, $xpath);
				}
			}
		}
	}

	/**
	 * Synology - Teste les identifiants et crée un cookie
	 * 
	 * @param string $username Identifiant
	 * @param string $password Mot de passe
	 * @return bool TRUE si la connexion a réussie ou FALSE si échec
	 */
	public function VerifyAccount($username, $password)
	{
		$this->DeleteCookie();

		foreach($this->socials as $social) 
		{			
			$this->GetDomain($social);			

			if (strlen($this->domain) > 0)
			{
				break;
			}
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POSTFIELDS, array('id' => urlencode($username), 'pass' => urlencode($password)));
		$this->Request($this->domain . self::AUTH_PATH, $curl);

		$xpath = $this->Request($this->domain);
		$ratio = $xpath->query("//*[contains(@class, 'ico_upload')]");

		return $ratio->length > 0 ? true : false;
	}

	/**
	 * Parse la page de recherche et récupère les informations des torrents
	 * 
	 * @param object $plugin Renvoyé par Synology
	 * @param DOMXpath $xpath Contenu de la page
	 */
	private function ParseContent($plugin, $xpath)
	{
		$items = $xpath->query("//*[contains(@class, 'results')]/table/tbody/tr");

		foreach ($items as $item) 
		{
			if($this->buildForDsm6)		
			{		
				// DSM 6
				preg_match('/\/([0-9]+)-/', $item->childNodes[2]->firstChild->getAttribute('href'), $torrentHash);
				
				$plugin->addResult(
					trim($item->childNodes[2]->textContent),
					preg_replace(
						array('/{\$1}/', '/{\$2}/'), 
						array($this->subDomain . $this->domain . self::TORRENT_PATH . $torrentHash[1], self::COOKIE),
						$this->downloadUrl
					),		
					$this->GetSize($item->childNodes[10]->textContent),
					(new DateTime())->setTimestamp((int)$item->childNodes[8]->textContent)->format('Y-m-d H:i:s'),
					$item->childNodes[2]->firstChild->getAttribute('href'),
					$torrentHash[1],
					(int)$item->childNodes[14]->textContent,
					(int)$item->childNodes[16]->textContent,
					$this->GetCategory((int)$item->childNodes[0]->textContent)
				);
			}
			else 
			{
				// DSM 7			
				$plugin->addResult(
					trim($item->childNodes[3]->textContent),
					preg_replace(
						array('/{\$1}/', '/{\$2}/'),
						array($this->subDomain . $this->domain . self::TORRENT_PATH . $item->childNodes[5]->firstChild->getAttribute('target'), self::COOKIE),
						$this->downloadUrl
					),		
					$this->GetSize($item->childNodes[11]->textContent),
					(new DateTime())->setTimestamp(explode(' ', $item->childNodes[9]->textContent)[0])->format('Y-m-d H:i:s'),
					$item->childNodes[3]->firstChild->getAttribute('href'),
					$item->childNodes[5]->firstChild->getAttribute('target'),
					(int)$item->childNodes[15]->textContent,
					(int)$item->childNodes[17]->textContent,
					$this->GetCategory((int)$item->childNodes[1]->textContent)
				);
			}
		}
	}

	/**
	 * Supprime le cookie
	 */
	private function DeleteCookie()
	{
		if (file_exists(self::COOKIE))
		{
			unlink(self::COOKIE);
		}
	}

	/**
	 * Exécute la requête CURL et retourne la page
	 * 
	 * @param string $url URL de la page
	 * @param resource $curl CURL
	 * @param bool $prepare Indique si CURL est initialisé par Synology ou par l'utilisateur
	 * @return DOMXPath Retourne la page ou NULL
	 */
	private function Request($url, $curl = null, $prepare = false)
	{
		if (!isset($curl))
			$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_USERAGENT => 'Googlebot/2.1',
			CURLOPT_URL => 'https://' . $url,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_COOKIEFILE => self::COOKIE,
			CURLOPT_COOKIEJAR => self::COOKIE
		]);

		if (!$prepare) 
		{
			$content = curl_exec($curl);
			curl_close($curl);
		}

		if (!isset($content))
			return null;

		@$this->document->loadHTML('<?xml encoding="utf-8" ?>' . $content);

		return new DOMXpath($this->document);
	}

	/**
	 * Récupére le nom de domaine depuis les réseaux sociaux
	 */
	private function GetDomain($social)
	{
		$xpath = $this->Request($social['url']);
		$this->domain = $xpath->query("//*[contains(@class, '" . $social['class'] . "')]");
		preg_match('/([a-zA-Z0-9-]+\.)*([a-zA-Z0-9-]+\.[a-zA-Z0-9-]+)/', $this->domain[0]->textContent, $match);
		$this->domain = $match[2];		

		$this->GetSubDomain();
	}

	/**
	 * Récupére le sous-domaine pour la recherche
	 */
	private function GetSubDomain()
	{
		$xpath = $this->Request($this->domain);
		$this->subDomain = $xpath->query("//*[contains(@class, 'search')]");
		preg_match('/[a-zA-Z0-9-]+\./', $this->subDomain[0]->getAttribute('action'), $match);
		$this->subDomain = $match[0];
	}

	/**
	 * Retourne le nombre de pages de la recherche
	 * 
	 * @param DOMXpath $xpath Contenu de la page
	 * @return int Nombre de pages
	 */
	private function GetTotalPages($xpath)
	{
		$total = $xpath->query("//*[contains(@id, '#torrents')]/h2");

		if ($total->length > 0)  
		{
			preg_match('/[0-9]+/', $total[0]->textContent, $match);
			$total = ceil((float)$match[0] / 50);

			return $total;
		}

		return 0;
	}

	/**
	 * Retourne la categorie du torrent
	 * 
	 * @param int $id Numéro de la categorie
	 * @return string Nom de la categorie
	 */
	private function GetCategory($id)
	{
		foreach ($this->categories as $catId => $catName) 
		{
			if ($catId == $id) 
				return $catName;
		}

		return 'Autre';
	}

	/**
	 * Retourne la taille en octets
	 * 
	 * @param string $data Taille du fichier
	 * @return float Taille en octets
	 */
	private function GetSize($data)
	{
		preg_match('/([0-9\.]+)([a-z]+$)/i', $data, $value);

		switch ($value[2]) 
		{
			case 'To':
				$size = $value[1] * 1024 * 1024 * 1024 * 1024;
				break;

			case 'Go':
				$size = $value[1] * 1024 * 1024 * 1024;
				break;

			case 'Mo':
				$size = $value[1] * 1024 * 1024;
				break;

			case 'ko':
				$size = $value[1] * 1024;
				break;
		}

		return $size;
	}
}
