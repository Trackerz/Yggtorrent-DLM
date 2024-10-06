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
		const AUTH_PATH = '/auth/process_login';	

    /**
     * @var string Url du fichier ygg.php
     */
    private $downloadUrl = 'https://127.0.0.1/ygg.php?torrent=$1&cookie=$2';

    /**
     * @var string Url de recherche
     */
    private $searchPath = '/engine/search?do=search&sort=publish_date&order=desc&name=$1&page=$2';

    /**
     * @var bool Selectionne la version du build
     */
    private $buildForDsm6 = true;

    /**
     * @var array List des réseaux sociaux
     */
    private $socials;

    /**
     * @var string Nom de domaine
     */
    private $domain = '';

    /**
     * @var string Sous domaine de recherche
     */
    private $subDomain = 'www.';

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
     * @var bool Active/Desactive les logs
     */
    private $debug = false;

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
        $this->document = new DOMDocument();

        $this->socials = array(
             array(
                'name' => 'mastodon',
                'url' => 'mamot.fr/@YggTorrent',
                'class' => 'translate'
            )
        );

        $this->categories = [

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
            2157 => 'Emulateur',
            2158 => 'ROM/ISO',

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

            // Adulte (on dit merci qui? :p)
            2189 => 'Film',
            2190 => 'Hentai',
            2191 => 'Image',

            //Nulled
            2304 => 'Divers',
            2303 => 'Mobile',
            2302 => 'Scripts PHP & CMS',
            2301 => 'Wordpress',

            //Imprimante 3D
            2201 => 'Objets',
            2202 => 'Personnages'
        ];
    }

    /**
     * Synology - Execute la requête de recherche
     *
     * @param \CurlHandle $curl CURL
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
            $url = $this->subDomain . $this->domain . preg_replace(array('/\$1/', '/\$2/'), array(urlencode($this->query), 0), $this->searchPath);
            return $this->Request($url, $curl, true);
        }

        return null;
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
                    $url = $this->subDomain . $this->domain . preg_replace(
                        array('/\$1/', '/\$2/'), 
                        array(urlencode($this->query), $i * 50), 
                        $this->searchPath
                    );
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
        $this->Debug('Verification des identifiants');

        $this->DeleteCookie();        
        $this->GetDomain($this->socials[0]);

        $this->Request(
            $this->subDomain . $this->domain . self::AUTH_PATH, 
            null,
            false, 
            array(
                'id' => $username, 
                'pass' => $password
            )
        );

        $xpath = $this->Request($this->subDomain . $this->domain);
        $ratio = $xpath->query("//*[contains(@class, 'ico_upload')]");

        return $ratio->length > 0;
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
            $i = $this->buildForDsm6 ? 1 : 0;

            $plugin->addResult(
                trim($item->childNodes[3 - $i]->textContent),
                preg_replace(
                    array('/\$1/', '/\$2/'),
                    array(
                        $this->subDomain . $this->domain . self::TORRENT_PATH . $item->childNodes[5 - $i]->firstChild->getAttribute('target'),
                        self::COOKIE
                    ),
                    $this->downloadUrl
                ),
                $this->GetSize($item->childNodes[11 - $i]->textContent),
                (new DateTime())->setTimestamp(explode(' ', trim($item->childNodes[9 - $i]->textContent))[0])->format('Y-m-d H:i:s'),
                $item->childNodes[3 - $i]->firstChild->getAttribute('href'),
                $item->childNodes[5 - $i]->firstChild->getAttribute('target'),
                (int)$item->childNodes[15 - $i]->textContent,
                (int)$item->childNodes[17 - $i]->textContent,
                $this->GetCategory((int)$item->childNodes[1 - $i]->textContent)
            );
        }
    }

    /**
     * Supprime le cookie
     */
    private function DeleteCookie()
    {        
        $this->Debug('Suppression du cookie');

        if (file_exists(self::COOKIE)) 
        {
            unlink(self::COOKIE);
            $this->Debug('Cookie supprimé');
        }
    }

    /**
     * Exécute la requête CURL et retourne la page
     *
     * @param string $url URL de la page
     * @param \CurlHandle|null $curl CURL
     * @param bool $prepare Indique si CURL est initialisé par Synology ou par l'utilisateur
     * @return DOMXPath Retourne la page ou NULL
     */
    private function Request($url, $curl = null, $prepare = false, $credentials = null)
    {
        if ($curl === null)
        {
            $curl = curl_init();
        }

        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/png,image/svg+xml,*/*;q=0.8',
            'TE: trailers',
            'Upgrade-Insecure-Requests: 1',
            'Cookie: account_created=true; cf_clearance=; ygg_='
        ];          
          
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);     
        curl_setopt($curl, CURLOPT_URL, 'https://' . $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, self::COOKIE);
        curl_setopt($curl, CURLOPT_COOKIEJAR, self::COOKIE);

        if($this->debug) {
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_HEADER, true);
        }

        if($credentials) 
        {
            curl_setopt($curl, CURLOPT_POST, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $credentials);
        }

        
        if (!$prepare)
        {					
            $content = curl_exec($curl);
            curl_close($curl);
        }


        if (!isset($content)) {
            return null;
        }

        if($this->debug) {
            echo $content;
        }

        @$this->document->loadHTML('<?xml encoding="utf-8" ?>' . $content);

        return new DOMXpath($this->document);
    }

    /**
     * Récupére le nom de domaine depuis les réseaux sociaux
     *
     * @param array $social Réseau social ou récupérer l'url
     */
    private function GetDomain($social)
    {
        $this->Debug('Recuperation du nom de domaine');

        $xpath = $this->Request($social['url']);
        $data = $xpath->query("//link[@rel='me']/@href");
        preg_match('/([a-zA-Z0-9-]+\.)*([a-zA-Z0-9-]+\.[a-zA-Z0-9-]+)/', $data[0]->textContent, $match);
        $this->domain = $match[2];

        $this->Debug('Nom de domaine : ' . $this->domain);

        $this->GetSubDomain();
    }

    /**
     * Récupére le sous-domaine pour la recherche
     */
    private function GetSubDomain()
    {
        //$this->Debug('Recuperation du sous domaine');

        //$xpath = $this->Request($this->domain);
        //$this->subDomain = $xpath->query("//*[contains(@class, 'search')]");
        //preg_match('/[a-zA-Z0-9-]+\./', $this->subDomain[0]->getAttribute('action'), $match);
        //$this->subDomain = $match[0];
        $this->subDomain = 'www.'; //$match[0];

        $this->Debug('Sous domaine : ' . $this->subDomain);
    }

    /**
     * Retourne le nombre de pages de la recherche
     *
     * @param DOMXpath $xpath Contenu de la page
     * @return int Nombre de pages
     */
    private function GetTotalPages($xpath)
    {        
        $this->Debug('Calcul du nombre de pages');

        $total = $xpath->query("//*[contains(@id, '#torrents')]/h2");

        if ($total->length > 0)
        {
            preg_match('/[0-9]+/', $total[0]->textContent, $match);
            $total = ceil((float)$match[0] / 50);

            $this->Debug('Nombre de pages ' . $total);

            return $total;
        }

        $this->Debug('Nombre de page 1');

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
        preg_match('/([0-9.]+)([a-z]+$)/i', $data, $size);

        switch ($size[2])
        {
            case 'To':
                $size = $size[1] * 1024 * 1024 * 1024 * 1024;
                break;

            case 'Go':
                $size = $size[1] * 1024 * 1024 * 1024;
                break;

            case 'Mo':
                $size = $size[1] * 1024 * 1024;
                break;

            case 'ko':
                $size = $size[1] * 1024;
                break;
        }

        return $size;
    }

    /**
     * Log les actions
     * 
     * @param string $data Log une action7
     */
    private function Debug($data)
    {
        if($this->debug)
        {
            echo $data . '</br>';
            file_put_contents('/tmp/yggtorrent.log', (new Datetime())->format('d-m-Y H:i:s') . ' : ' . $data . PHP_EOL, FILE_APPEND);
        }
    }
}
