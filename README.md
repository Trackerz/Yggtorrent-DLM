# Yggtorrent-DLM

Permet de rechercher et de télécharger des torrents sur **YGGTorrent** directement depuis un **NAS Synology** via l'application **DownloadStation** ou bien sur mobile via **DS-GET**

La recherche est limitée à **1000** résultats ce qui représente **20 pages** sur le site  
Cette limitation est faite pour l'application mobile **DS-GET** qui ne peut pas afficher plus de résultats

La récupération du nom de domaine se fait automatiquement depuis [Mastodon](https://mamot.fr/@YggTorrent) ce qui évite de mettre à jour le code si un changement de domaine est effectué

Version fonctionnelle pour [yggtorrent.se](https://www.yggtorrent.se/)

# Installation :  

- Installer **WebStation**
- Installer **PHP** (activer CURL dans les options)
  - Dans WebStation > Paramètres PHP > Modifier > Cocher l'extension **CURL**
- Placer le fichier **ygg.php** à la racine du dossier web
- Ouvrir **DownloadStation** 
  - Aller dans **Paramétres > Recherche BT > Ajouter** et ouvrir le fichier **yggtorrent.ch.dlm** puis valider
  - Une fois ajouté il faut sélectionner le plugin puis cliquer sur **Modifier** et entrer les identifiants du site puis faire **Vérifier**.  
  - Un message indique que la **connexion est réussie**

# Compilation (facultatif) : 

Cette partie est necessaire uniquement si vous souhaitez modifier le code vous meme et le recompiler

- **Linux** : Commande > tar zcf mymodule.dlm INFO search.php

- **Windows** (non testé) : [Voir ce lien](https://superuser.com/questions/244703/how-can-i-run-the-tar-czf-command-in-windows) 

# Documentation :

- [DLM_Guide.pdf](https://global.download.synology.com/download/Document/DeveloperGuide/DLM_Guide.pdf)

# Résultat : 
  
![Screenshot](https://i.imgur.com/8pmmmfx.png)
