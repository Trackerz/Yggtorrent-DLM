# Yggtorrent-DLM

Permet de rechercher et de télécharger des torrents du site **YggTorrent** directement depuis un **NAS Synology** via l'application **DownloadStation** ou bien sur mobile via **DS-GET**

La recherche est limitée à **1000** résultats ce qui représente **20 pages** sur le site.  
Cette limitation est faite pour l'application mobile qui ne peut pas afficher plus de résultats.

Version à jour pour [yggtorrent.ch](https://www.yggtorrent.ch/)

# Installation :  

- Avoir un compte **ACTIF** sur le site
- Installer **WebStation**
- Installer **PHP** (activer CURL dans les options)
- Placer le fichier **ygg.php** à la racine du dossier web
- Ouvrir **DownloadStation** 
  - Aller dans **Paramétres > Recherche BT > Ajouter** et ouvrir le fichier **yggtorrent.ch.dlm** puis valider
  - Une fois ajouté il faut sélectionner le plugin puis cliquer sur **Modifier** et entrer les identifiants du site puis faire **Vérifier**.  
  - Un message indique que la **connexion est réussie**

# Compilation : 

- **Linux** : Commande > tar zcf mymodule.dlm INFO search.php

- **Windows** (non testé) : [Voir ce lien](https://superuser.com/questions/244703/how-can-i-run-the-tar-czf-command-in-windows) 

# Documentation :

- [DLM_Guide.pdf](https://global.download.synology.com/download/Document/DeveloperGuide/DLM_Guide.pdf)

# Résultat : 
  
![Screenshot](https://i.imgur.com/8pmmmfx.png)