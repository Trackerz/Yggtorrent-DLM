# YGGTorrent-DLM

Permet de rechercher et de télécharger des torrents sur **YGGTorrent** directement depuis un **NAS Synology** via **DownloadStation** ou bien sur mobile avec l'application **DS-GET**

La recherche est limitée à **1000** résultats ce qui représente quand meme **20 pages** sur le site  
Cette limitation est faite pour l'application mobile **DS-GET** qui ne peut pas afficher plus de résultats

La récupération du nom de domaine se fait automatiquement depuis [Twitter](https://twitter.com/yggtorrent_p2p) / [Mastodon](https://mamot.fr/@YggTorrent) / [Telegram](https://t.me/yggtorrent) ce qui évite de mettre à jour le code si un changement de nom domaine est effectué

# Installation :  

- Installer **WebStation**
- Installer **PHP** 
- Ouvrir **WebStation** 
  - Aller dans **Paramétres PHP > Modifier** et cocher les extensions **CURL**
- Placer le fichier **ygg.php** à la racine du dossier web sur le NAS
- Ouvrir **DownloadStation** 
  - Aller dans **Paramétres > Recherche BT > Ajouter** et selectionner le fichier **yggtorrent.dlm** puis valider
  - Le plugin doit apparaître dans la liste et il faut simplement cliquer sur **Modifier** et entrer ses identifiants du site puis faire **Vérifier**.  
  - Un message doit indiquer que la **connexion est réussie**

# Compilation (facultatif) : 

Cette partie est necessaire uniquement si vous souhaitez modifier le code vous meme et le recompiler

- **Linux** : 
  - Placer les fichiers INFO et search.php dans le meme dossier 
  - Se placer dans le dossier depuis un terminal puis taper la commande suivante pour générer le fichier dlm -> tar zcf yggtorrent.dlm INFO search.php 

# Documentation :

- [DLM_Guide.pdf](https://global.download.synology.com/download/Document/DeveloperGuide/DLM_Guide.pdf)

# Résultat : 
  
![Screenshot](https://i.imgur.com/8pmmmfx.png)
