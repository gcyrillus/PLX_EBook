# PLX_EBook  <sub><sup>(runs with PluXml >= 5.8.7. | compatible plx_gc_categorie & vip_zone)</sup><sub>
Turns PluXml into an epub editor , allows you to save entirely or partially your site into epubs and make them avalaible for download.
____
  ### Modifs en cours:
  
  [+] 06/03/2022
- Ajout javascript: désactive le bouton de création si des changement sont effectués dans l'onglet configuration et crèation.
- Maj fichier fr.php/en.php
- warning message if publish mode not yet configured (config.php)
- rename: function listdir_by_date -> listdir_by_natsort (epub.ebook.php)
- initialisation de  $file_array=array(); par defaut si repertoire epubs vide(epub.ebook.php)

[+] 05/03/2022
- bug tri sur multi-utilisateur validé si isset() (config.php)

[+] 04/03/2022
- cover updates on demand only(config.php)

[+] 03/03/2022
-  javascript, historique dossier epub, couleurs(config.php)

[+] 11/2/22
- reformat  le chemin  du repertoire de stockage des epubs(config.php)
- ajout lien direct dans le menu admin(EBook.php)
- valeur $format de la fonction catList() pris en compte(EBook.php)
- n'affiche que les auteurs qui ont au moins une publication(config.php)
- comptage article par categorie selon l'auteur selectionné (categorie vides non affichées)
- ajout du champ description commun aux pages statiques(config.php)
- modif mineures du CSS coté admin
 ____

<div id="help">
<h2>Aide</h2>
<p><small>Fichier d'aide du plugin <b>PLX_EBook</b></small></p>
<div id="intro">
<h3>A quoi sert-il ?</h3>
<p>Il permet de transformer tous ou parties des contenus de votre site en livres electroniques au standard Epub3, c'est un format ouvert et gratuit lisible dans les differents systéme d'exploitation de vos ecrans (Android, Linux, Mac, Windows ...).</p>
<p>Ce livre est contenu dans un seul fichier avec l'extension <code>.epub</code>, il peut-être télecharger et lu localement avec une liseuse. De nombreux programmes gratuits de lecture de Livre electroniques sont disponibles pour chaque systéme d'exploitations. Si votre appareil n'en dispose pas déjà d'un, il y a de nombreux programmes gratuits disponibles. Chaque programmes aura la capacité de lire differents type de Livre électronique (PDF,HTML,MOBI,EPUB,...) et peut avoir des fonctions de classement en extrayant les informations des livres electronique (titre,auteur,ISBN,image de couverture,etc.) . </p>
</div>
<h3>Onglets de configurations</h3>
<p><b>!</b> Chaque onglet doit-être individuellement enregistré pour finaliser les modifications de configuration.</p>
<h4>Options d'affichage</h4>
<p>Cette onglet correspond à la page affichée coté public sur votre site.</p>
<p>Une page public peut être affichée à partir du site pour lister les epubs disponibles et télechargeable.<br>Plusieurs options sont disponibles pour l'affichage de cette page:</p>
<ol>
<li><p><b>Titre du menu:</b> le titre du lien affichée dans le menus des pages statiques du site</p></li>
<li><p><b>Afficher la page E-Book dans le menu :</b> option d'affichage du lien</p></li>
<li><p><b>Paramètre de l'url :</b> libéllé en fin d'adresse de la page </p></li>
<li><p><b>Position du menu :</b> position dans le menu au coté des liens Accueil et autres statiques</p> </li>
<li><p><b>Repértoire de stockage des Epubs:</b>. Un repertoire sera crée a la racine de votre site</p>
<p>Ce repertoire sert a stocker les epubs généré par le plugin et ceux que vous aurez téléversé. La page coté site listera les epubs présents dans ce repertoire et affichera pour chacun :l'image de couverture, le nom de l'auteur, sa description , la date de création ainsi que le lien de télechargement </p>
</li>
<li><p><b>Template </b> Choix du template d'affichage de cette page qui se comporte comme une page statique</p></li>
<li><p><b>Contenu personnalisé sous le titre</b> Ajoute de contenus avant la liste des epubs disponibles. format HTML possible.</p></li>
<li><p><b>Contenu personnalisé fin de liste.</b> ajout d'un element de liste avec un texte ou HTML de votre choix</p></li>
<li><p><b>Option de debogage</b> affiche les eventuelles messages d'erreur à l'enregistrement d'un onglet ou à la création d'un epub pour débusquer d'eventuels bugs ou erreurs. Un lien s'affiche pour retourner sur l'onglet de configuration en cours.</p></li>
</ol>

<h4>Mode de publication</h4>
<p>Cette onglet vous permet de selectionner la façon de generer votre epub en collant au plus prés aux type de contenus que vous publiez. Si vous utiliser PluXml comme un blog, les articles les plus récents seront affichés en premier,  l'option de parametrer une période définie permet de générer des epubs de façon periodique pour les integrer en piece jointe à une 'newsletter' par exemple. Si vous ecrivez des petits romans, nouvelles, des tutoriaux, ... alors le mode Livre sera adapté.</p>
<p>Vous pouvez configurer l'ordre d'intégration par date de vos articles dans votre livre electronique et de selectionner une période si vous le souhaitez</p>
<p>En selectionnant le type de publication, une boite s'affiche en dessous indiquant les caractéristique du mode et si necessaire les périodes à selectionnées.Le mode Bande Dessinnée ajoute une fonctionnalité indépendante.</p>
<p><b>Un mode Bande Dessinée</b>, à part, simplifié et indépendant des autres onglets de configuration, vous permet aussi de generer un epub constitué d'images (planches BD, ou juste un album d'images).</p><p> Un dossier spécifique pour les images est créer par défaut et peut-etre modifier. Chaque modification de dossier crée un nouveau dossier sans effacer le précedent, vous pouvez donc configurer et stocké les images pour plusieurs epubs.</p><p> Dans chaque dossier d'images, il vous faudra y inclure une image de couverture au format jpg/jpeg en la renommant <code>cover.jpg</code> Cette image servira de couverture et sera vue dans la partiee public de votre Plugin.</p>
<p><b>Le mode choisi doit-être enregistré pour finalisé la configuration avant de generer l'epub.</b></p>

<h4>Configuration et Creation </h4>
<p>Cet onglet permet de selectionner des pages annexes et statiques à votre epub qui ne sont pas necessairement à l'affichage sur votre site</p>
<p><b><u>Ces pages que vous pouvez inclures sont au choix:</u></b></p>
<ol><li><p><b> En début de livre :</b> page dédicaces, page préface et page avant propos</p></li> 
<li><p><b>Les articles actifs des catégories à inclure</b> dans votre epub. Chaque option selectionné génerera un epub en reprenant le nom de la catégorie, sans espaces et avec un maximum de 12 lettres - en selectionnant <b>Toutes les catégories</b> le titre de votre site sera utilisé pour le nom de votre fichier epub.</p>
<p>Chaque catégorie selectionnée peut se voir attribuée un theme different parmis ceux disponibles</p>
<p>Si vous avez installé le plugin <code><b><a href="https://github.com/gcyrillus/plx-gc-categories">plx-gc-categories </a> - <a href="https://github.com/gcyrillus/plx-gc-categories/archive/refs/heads/master.zip">télécharger</a></b></code> pour chaque catégories mere selectionnée chacune de ses catégories filles seront incluses dans l'epub</p>
<p>Il n'y a encore pas d'option implémenter pour selectionner plusieurs catégories à inclure dans un seul epub (exepté le cas mere/filles si le plugin correspondant est installé et actif). Pour cela, il vous faudra selectionner 'toutes les catégories' et désactiver momentanément les catégories que vous ne souhaitez pas inclure à partir de la page <a href="categories.php" target="_blank">parametres catégories</a>.</p></li>
<li><p><b>Les pages statiques actives</b> sur votre site</p></li>
<li><p><b>La page : auteur(s)</b>, postface et remerciement</p></li>
<li><p><b>Une page remerciement</b> en selectionnant un nombre de commentaire issue d'un seul article.</p> </li>
<li><p><b>Une page Index</b>, généré à partir des mots clés des articles intégrés à l'epub</p></li>
</ol>
<p>Si l'une de ces pages selectionnées est inactive ou vide, elle ne sera pas insérée dans l'epub</p>

<h4>Fiche d'identité</h4>
<p>Les informations essentielles de votre livre, par defaut le titre et la description sont ceux de votre site. Vous pouvez aussi selectionné un type de licence , son url et ses termes au format HTML.</p>

<h4>Crédits</h4>
<p>Credits des differentes contributions, ajoutées en partie au fichier opf de votre epub. (actuellement en cours de devellopement 01/22)</p>

<h4>Themes disponibles</h4>
<p>Slider montrant quelques exemples disponibles de couvertures et styles utilisable pour vos epubs</p>
<p>Chaque théme est composé de :</p>
<ol>
<li><b>une image de couverture</b> sans textes nommée <code>coverX.jpg</code> <small>(X = N° du theme)</small> dans le repertoire <code>/plugins/EBook/covers</code> Cette image est utilisé pour créer l'image de prévisualisation du théme sur laquelle sont ajouté le titre et la description de votre site ainsi qu'un logo PluXml. Elle est inclus dans le dossier du theme</li>
<li>Une image <code>cover.jpg</code> générée à partir de l'image de couverture du theme sur laquelle est ajouté le titre (de la catégorie selectionnée), sa description, le nom de l'auteur et un logo de pluxml. c'est l'image de couverture uniquement insérer dans votre epub.</li>
<li>Un repertoire <code>fonts</code> pour y stockées des polices. Les formats <code>otf</code> et <code>woff</code> sont epub compatible.Ce repertoire est copié dans l'epub</li>
<li>un fichier <code>font.css</code> pour integrer les polices au livre</li>
<li>un fichier <code>theme.css</code> avec les styles particuliers aux differents thémes</p>
<li>un fichier <code>test.html</code> pour visualiser les styles dans l'administration.</li>
</ol>
<p>Chaque théme comprend aussi les fichiers: <code>epub.css</code> (reset epub) et <code>commun.css</code>, ces fichiers sont inserer dans le livre au moment de sa création.</p>
<h4>Dernier onglet</h4>
<p><b>Provisoirement :</b> Affichage de ce fichier d'aide. <small>Réservé pour une fonctionnalité supplementaire prochaine.</small></p>


</div>
