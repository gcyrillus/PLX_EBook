<?php if(!defined('PLX_ROOT')) exit;

$categories=array();
#nombre article par catègorie aprés filtrage
$catnb=array();
$AuthorPublished=array();// recuperation auteurs publiés
$UsersDesc[0]=$plxAdmin->aUsers['001']['infos']; // tableau infos utilisateurs - Defaut:ADMINISTRATEUR


# Liste des langues disponibles et prises en charge par le plugin
$aLangs = array($plxAdmin->aConf['default_lang']);	
	
// mise en place variables par défaut pour premiere utilisation ou fichier parametre inexistant
$var = array();
# initialisation des variables propres à chaque lanque
$langs = array();
foreach($aLangs as $lang) {
	# chargement de chaque fichier de langue
	$langs[$lang] = $plxPlugin->loadLang(PLX_PLUGINS.$plugin.'/lang/'.$lang.'.php');
	$var['mnuName'] =  $plxPlugin->getParam('mnuName')=='' ? 'Ebooks' : $plxPlugin->getParam('mnuName');// pas de gestion de langue sur cette variable
}
{# initialisation des variables 
// catégorie dispo 
		$var['all'] =  $plxPlugin->getParam('all')=='' ? 0 : $plxPlugin->getParam('all');
		foreach ($plxAdmin->aCats as $catNumb => $values) {
			if( $values["articles"] >="1" && $values['active']=='1' ) {// on ne prend que les catégories disposant d'un articles et actives
				// recup catégorie
				$var[$catNumb] =  $plxPlugin->getParam($catNumb)=='' ? 0 : $plxPlugin->getParam($catNumb);
				// variable pour le theme à assigné
				$var[$catNumb.'-th'] =  $plxPlugin->getParam($catNumb.'-th')=='' ? 'th1' : $plxPlugin->getParam($catNumb.'-th');
			}
		}
//variable theme pour ebook complet
$var['all-th'			] = $plxPlugin->getParam('all-th'				)=='' ? 'th1'										  : $plxPlugin->getParam('all-th');
# titre et description auteur
$var['title'			] = $plxPlugin->getParam('title'				)=='' ? $plxAdmin->aConf['title'					] : $plxPlugin->getParam('title');
$var['subtitle'			] = $plxPlugin->getParam('subtitle'				)=='' ? $plxAdmin->aConf['description'				] : $plxPlugin->getParam('subtitle');
$var['author'			] = $plxPlugin->getParam('author'				)=='' ? $plxAdmin->aUsers[$_SESSION['user']]['name'	] : $plxPlugin->getParam('author');

# page annexes	. 
	/*	inclus aux options si:  
	active	==  1
	menu	== 	1
	group	!=  ebookAnnexe 
	*/
	foreach ($plxAdmin->aStats as $k => $v) {
		if ($v['active'] == 1 && $v['menu'] == 'oui' && $v['group'] !='ebookAnnexe' ) {
						$var['stat-'.$k	] =  $plxPlugin->getParam('stat-'.$k)=='' ? 0  : $plxPlugin->getParam('stat-'.$k);	
		}
	}

// $topAnnexe extraites a partir d'une page statique	
$var['pageDedicace'		] =  $plxPlugin->getParam('pageDedicace'		)=='' ? 0  : $plxPlugin->getParam('pageDedicace');
$var['pagededicaceId'	] =  $plxPlugin->getParam('pagededicaceId'		)=='' ? 0  : $plxPlugin->getParam('pagededicaceId');
$var['pagePreface'		] =  $plxPlugin->getParam('pagePreface'			)=='' ? 0  : $plxPlugin->getParam('pagePreface');
$var['pageprefaceId'	] =  $plxPlugin->getParam('pageprefaceId'		)=='' ? 0  : $plxPlugin->getParam('pageprefaceId');
$var['pageForeword'		] =  $plxPlugin->getParam('pageForeword'		)=='' ? 0  : $plxPlugin->getParam('pageForeword');
$var['pageforewordId'	] =  $plxPlugin->getParam('pageforewordId'		)=='' ? 0  : $plxPlugin->getParam('pageforewordId');

// $endAnnexe extraites a partir d'une page statique	
$var['pagePostface'		] =  $plxPlugin->getParam('pagePostface'		)=='' ? 0  : $plxPlugin->getParam('pagePostface');
$var['pagepostfaceId'	] =  $plxPlugin->getParam('pagepostfaceId'		)=='' ? 0  : $plxPlugin->getParam('pagepostfaceId');
$var['pagerRemerciement'] =  $plxPlugin->getParam('pagerRemerciement'	)=='' ? 0  : $plxPlugin->getParam('pagerRemerciement');
$var['pageremerciementId']=  $plxPlugin->getParam('pageremerciementId'	)=='' ? 0  : $plxPlugin->getParam('pageremerciementId');

// pages extraites des données site
$var['pageIndex'		] =  $plxPlugin->getParam('pageIndex'			)=='' ? 0  : $plxPlugin->getParam('pageIndex');
$var['pageCopy'			] =  $plxPlugin->getParam('pageCopy'			)=='' ? 1  : $plxPlugin->getParam('pageCopy');
$var['pageAuteur'		] =  $plxPlugin->getParam('pageAuteur'			)=='' ? 0  : $plxPlugin->getParam('pageAuteur');
$var['pageTemoignage'	] =  $plxPlugin->getParam('pageTemoignage'		)=='' ? 0  : $plxPlugin->getParam('pageTemoignage');
$var['nbCom'			] =  $plxPlugin->getParam('nbCom'				)=='' ? 1  : $plxPlugin->getParam('nbCom');
$var['art-coms'			] =  $plxPlugin->getParam('art-coms'			)=='' ? '' : $plxPlugin->getParam('art-coms');


// etc.

# Mode Pub defaut:blog
$var['epubMode'			] =  $plxPlugin->getParam('epubMode'		)=='' ? 'blog'				: $plxPlugin->getParam('epubMode');

	#periods
	# valeurs mois
		$var['magMM'			] =  $plxPlugin->getParam('magMM'			)=='' ? date('m')			: $plxPlugin->getParam('magMM'); 
		$var['magTM'			] =  $plxPlugin->getParam('magTM'			)=='' ? date('m')			: $plxPlugin->getParam('magTM'); 
		$var['magSM'			] =  $plxPlugin->getParam('magSM'			)=='' ? date('m')			: $plxPlugin->getParam('magSM'); 
	# valeurs Années
		$var['magMY'			] =  $plxPlugin->getParam('magMY'			)=='' ? date('Y')			: $plxPlugin->getParam('magMY'); 
		$var['magTY'			] =  $plxPlugin->getParam('magTY'			)=='' ? date('Y')			: $plxPlugin->getParam('magTY'); 
		$var['magSY'			] =  $plxPlugin->getParam('magSY'			)=='' ? date('Y')			: $plxPlugin->getParam('magSY'); 
		$var['magAY'			] =  $plxPlugin->getParam('magAY'			)=='' ? date('Y')			: $plxPlugin->getParam('magAY');

	# filtering users
	# tri user 
		$var['triAuthors'		] =  $plxPlugin->getParam('triAuthors'		)=='' ? '000'				: $plxPlugin->getParam('triAuthors'); 
		
	# publishedUser 
	# doit être un n° d'utilisateur valide. si pas de filtrage d'auteur, alors c'est le N° de l'admin par défaut (pour récuperation nbr articles publiés $catnb).
		$var['publishedUser'	] =  $plxPlugin->getParam('triAuthors'		)=='000' ? '001'			: $plxPlugin->getParam('triAuthors'); 
		
# mode comics
$var['comicsmedia'		] =  $plxPlugin->getParam('comicsmedia'		)=='' ? 'comics'			: $plxPlugin->getParam('comicsmedia');
	# verif si repertoire existe, sinon le crée
		$mediaComics=  PLX_ROOT.'data/medias/'.$var['comicsmedia'];
		if (!file_exists($mediaComics)) {
			mkdir($mediaComics, 0777, true);
		}
	# todo sub-repertories for each possible comics to manage. here and at $_POST['doComics'] and fieldset#fmode in config.php 
	# $var[] = param == '' ? '' param;

	# infos comics
		$var['titreComics'		 ] =  $plxPlugin->getParam('titreComics'		)=='' ? ''													: $plxPlugin->getParam('titreComics'); 
		$var['descComics'		 ] =  $plxPlugin->getParam('descComics'			)=='' ? ''													: $plxPlugin->getParam('descComics'); 
		$var['auteurComics'		 ] =  $plxPlugin->getParam('auteurComics'		)=='' ? $plxAdmin->aUsers[$_SESSION['user']]['name'	]		: $plxPlugin->getParam('auteurComics'); 
		$var['illustrateurComics'] =  $plxPlugin->getParam('illustrateurComics'	)=='' ? $plxAdmin->aUsers[$_SESSION['user']]['name'	]		: $plxPlugin->getParam('illustrateurComics'); 
		$var['ISSNComics'		 ] =  $plxPlugin->getParam('ISSNComics'			)=='' ? ''													: $plxPlugin->getParam('ISSNComics'); 
		$var['ISBNComics'		 ] =  $plxPlugin->getParam('ISBNComics'			)=='' ? ''													: $plxPlugin->getParam('ISBNComics'); 
	
# Options affichage
	$var['mnuDisplay'		] =  $plxPlugin->getParam('mnuDisplay'			)=='' ? 0 								: $plxPlugin->getParam('mnuDisplay');
	$var['description'		] = $plxPlugin->getParam('description'			)=='' ? '<p>Epubs Disponibles</p>'		: $plxPlugin->getParam('description');
	$var['mnuPos'			] =  $plxPlugin->getParam('mnuPos'				)=='' ? 2 								: $plxPlugin->getParam('mnuPos');
	$var['template'			] = $plxPlugin->getParam('template'				)=='' ? 'static.php'					: $plxPlugin->getParam('template');
	$var['url'				] = $plxPlugin->getParam('url'					)=='' ? 'Ebook' 						: $plxPlugin->getParam('url');
	$var['custom-start'		] = $plxPlugin->getParam('custom-start'			)=='' ? '<p>Tous Droits Reservés</p>'	: $plxPlugin->getParam('custom-start');
	$var['custom-end'		] = $plxPlugin->getParam('custom-end'			)=='' ? '<p>Plugin Ebook</p>'			: $plxPlugin->getParam('custom-end');
	$var['debugme'			] = $plxPlugin->getParam('debugme'				)=='' ? 0								: $plxPlugin->getParam('debugme');

# repertoire reception epubs , voir pour ajouter une page de selection / creation  des liens pour du copier/coller dans article/statique
	$var['epubRepertory'		] = $plxPlugin->getParam('epubRepertory'		)=='' ? $plxPlugin->epubRepertory  : $plxPlugin->getParam('epubRepertory');
	$var['epubRepertoryHisto'	] = $plxPlugin->getParam('epubRepertoryHisto'	)=='' ? $plxPlugin->epubRepertory  : $plxPlugin->getParam('epubRepertoryHisto');
	$loopHisto= explode(' ',$plxPlugin->getParam('epubRepertoryHisto') );
}//fin $var[]

# titre-description-auteur pour couverture
	$titreTh = strtoupper($var['title']);
	$descTh  = $var['subtitle'];
	$AuthTh  = $var['author'];
	
#licence
	$var['licence'		] = $plxPlugin->getParam('licence'		)=='' ? 'public' 		: $plxPlugin->getParam('licence');
	$var['urlLicence'	] = $plxPlugin->getParam('urlLicence'	)=='' ? '/' 			: $plxPlugin->getParam('urlLicence');
	$var['descLicence'	] = $plxPlugin->getParam('descLicence'	)=='' ? '' 				: $plxPlugin->getParam('descLicence');
	
	$var['uid'			] = $plxPlugin->getParam('uid'			)=='' ? $var['title'] 	: $plxPlugin->getParam('uid');
	$var['copyrights'	] = $plxPlugin->getParam('copyrights'	)=='' ? $var['author'] 	: $plxPlugin->getParam('copyrights');
	
# si epub seulement une sous-partie du site
	$part='all'; // defaut :tout

# On récupère les templates des pages statiques
	$files = plxGlob::getInstance(PLX_ROOT.$plxAdmin->aConf['racine_themes'].$plxAdmin->aConf['style']);

	if ($array = $files->query('/^static(-[a-z0-9-_]+)?.php$/')) {
		foreach($array as $k=>$v)
			$aTemplates[$v] = $v;
	}
# tri article
if ($plxPlugin->getParam('epubMode')=='book' ) {$plxAdmin->tri ="asc"  ;}
if ($plxPlugin->getParam('epubMode')=='blog' ) {$plxAdmin->tri ="desc" ;}
if ($plxPlugin->getParam('epubMode')=='alpha') {$plxAdmin->tri ="alpha";}

{// MAJ catégorie et nombre articles
	$categories=array();
	$catnb=array();
    $artsfiles =  $plxAdmin->plxGlob_arts->aFiles;
	foreach ($artsfiles as $key=>$v) { # On parcourt tous les fichiers
// recuperation données	
				$art =  $plxAdmin->parseArticle(PLX_ROOT . $plxAdmin->aConf['racine_articles'] . $v);
				// recuperation catégories
				$catsfound=explode(',',$art['categorie']); 
					 foreach($catsfound as $keycat => $catval){						 
						 if($catval =='draft') { unset($artsfiles[$key]);}// draft to remove
						 if($catval !='draft') {
				if(!isset($catnb[$catval])){$catnb[$catval]=1;}else{$catnb[$catval]=$catnb[$catval] + 1;}
					$categories[$catval]= $catval;
					array_unique($categories);
						 }
					 }
			if( substr($v,5,5) != 'draft') {
				$AuthorPublished[$plxAdmin->aUsers[$art['author']]['name']]=$plxAdmin->aUsers[$art['author']]['name'];
			}
	}
	// maj liste fichiers articles
	$plxAdmin->plxGlob_arts->aFiles =  $artsfiles;
	
	// maj tableau categories.
	foreach ($plxAdmin->aCats as $catNumb => $values) {
		if(!in_array($catNumb, $categories)) {		 
			$plxAdmin->aCats[$catNumb]['active']='0';		 
			$plxAdmin->aCats[$catNumb]['article']='0';
			unset($plxAdmin->aCats[$catNumb]);
		}
	}
		// MAJ categories active
		$plxAdmin->activeCats = implode(' | ' , $categories);
		
		// tri cat et assignation nombre articles dispos
		ksort($catnb);
		foreach($catnb as $mctNb => $vlNm) {
			$plxAdmin->aCats[$mctNb]['articles'] = $vlNm;
		}	
		
		
}//fin MAJ categories/nbr article

	if($plxAdmin->aUsers  && $plxPlugin->getParam('triAuthors') =='000' ){
		foreach($plxAdmin->aUsers as $_userid => $_user)	{
			if(in_array($_user['name'],$AuthorPublished))  
			{$AllUsers[]= $_user['name'];}

		$var['author'] = implode(", ", $AllUsers);
		}
	}
	
// filtrage auteurs
if($plxPlugin->getParam('triAuthors') !=='000') {
			$catnb=array();//reset
			$categories=array();
	foreach ($plxAdmin->plxGlob_arts->aFiles as $key=>$v) { # On parcourt tous les fichiers
	 $art =  $plxAdmin->parseArticle(PLX_ROOT . $plxAdmin->aConf['racine_articles'] . $v);
	 if($plxPlugin->getParam('triAuthors')!= $art['author']) {		 
		unset($plxAdmin->plxGlob_arts->aFiles[$key]); 		
		}
		else {			 
		// comptage occurence article par catégorie
		// recuperation catégories
		$catsfound=explode(',',$art['categorie']); 
			 foreach($catsfound as $keycat => $catval){						 
				 if($catval =='draft') { unset($plxAdmin->plxGlob_arts->aFiles[$key]);}// draft to remove
				 else {
		if(!isset($catnb[$catval])){$catnb[$catval]=1;}else{$catnb[$catval]=$catnb[$catval] + 1;}
			$categories[$catval]= $catval;
			array_unique($categories);
				 }
			 }
		// fin comptage occurences		
		}
	}		
	// maj tableau categories.
	foreach ($plxAdmin->aCats as $catNumb => $values) {
		if(!in_array($catNumb, $categories)) {		 
			//$plxAdmin->aCats[$catNumb]['active']='0';		 
			//$plxAdmin->aCats[$catNumb]['article']='0';
			unset($plxAdmin->aCats[$catNumb]);// finalement on vire 
		}
	}
		// MAJ categories active
		$plxAdmin->activeCats = implode(' | ' , $categories);
		
		// tri cat et assignation nombre articles dispos
		ksort($catnb);
		foreach($catnb as $mctNb => $vlNm) {
			$plxAdmin->aCats[$mctNb]['articles'] = $vlNm;
		}

}


if ($plxPlugin->getParam('epubMode')=='magM' || 'magT' || 'magS' || 'magA') { 
 $catnb=array();
	// on retire les articles hors dates.
    $magfiles =  $plxAdmin->plxGlob_arts->aFiles;

	foreach ($magfiles as $key=>$v) { # On parcourt tous les fichiers
	 $art =  $plxAdmin->parseArticle(PLX_ROOT . $plxAdmin->aConf['racine_articles'] . $v);
		$testKey= substr($art['date_creation'],0,6);
		// recolte des categorie relié aux articles filtrés
		if ($plxPlugin->getParam('epubMode')=='magM') {	
		 $string = 	$plxPlugin->getParam('magMY').str_pad($plxPlugin->getParam('magMM'), 2, "0", STR_PAD_LEFT);
		 if ($testKey != $string ) {
			 unset($magfiles[$key]);
			} else {
				 $catsfound=explode(',',$art['categorie']); 
				 foreach($catsfound as $keycat => $catval){
					 if($catval =='draft') { unset($magfiles[$key]);}
					 if($catval !='draft') {					 
			if(!isset($catnb[$catval])){$catnb[$catval]=1;}else{$catnb[$catval]=$catnb[$catval] + 1;}
				$categories[$catval]= $catval;
				array_unique($categories);
					 }
				 }
			}			
		}// magM

		if ($plxPlugin->getParam('epubMode')=='magT') {
			$catnb=array();//reset
			$mois=str_pad($plxPlugin->getParam('magTM'), 2, "0", STR_PAD_LEFT);
			$mois2= $mois + 1;
			$mois3= $mois2 + 1;
		 $string  = 	$plxPlugin->getParam('magTY').$mois;
		 $string2 = $plxPlugin->getParam('magTY').str_pad($mois2, 2, "0", STR_PAD_LEFT);
		 $string3 = $plxPlugin->getParam('magTY').str_pad($mois3, 2, "0", STR_PAD_LEFT);
		 $exclude_list = array($string,$string2,$string3);
		 if (!in_array($testKey, $exclude_list) ) {
			 unset($magfiles[$key]);
			} else {
				 $catsfound=explode(',',$art['categorie']); 
				 foreach($catsfound as $keycat => $catval){
					 if($catval =='draft') { unset($magfiles[$key]);}
					 if($catval !='draft') {						 
			if(!isset($catnb[$catval])){$catnb[$catval]=1;}else{$catnb[$catval]=$catnb[$catval] + 1;}
				$categories[$catval]= $catval;
				array_unique($categories);
					 }
				 }
			}			
		}// magT		

		if ($plxPlugin->getParam('epubMode')=='magS') {
			$catnb=array();//reset
			$mois=str_pad($plxPlugin->getParam('magSM'), 2, "0", STR_PAD_LEFT);
			$mois2= $mois + 1;
			$mois3= $mois2 + 1;
			$mois4= $mois3 + 1;
			$mois5= $mois4 + 1;
			$mois6= $mois5 + 1;
		 $string  = 	$plxPlugin->getParam('magTY').$mois;
		 $string2 = $plxPlugin->getParam('magSY').str_pad($mois2, 2, "0", STR_PAD_LEFT);
		 $string3 = $plxPlugin->getParam('magSY').str_pad($mois3, 2, "0", STR_PAD_LEFT);
		 $string4 = $plxPlugin->getParam('magSY').str_pad($mois4, 2, "0", STR_PAD_LEFT);
		 $string5 = $plxPlugin->getParam('magSY').str_pad($mois5, 2, "0", STR_PAD_LEFT);
		 $string6 = $plxPlugin->getParam('magSY').str_pad($mois6, 2, "0", STR_PAD_LEFT); 
		 $exclude_list = array($string,$string2,$string3,$string4,$string5,$string6);
		 if (!in_array($testKey, $exclude_list) ) {
			 unset($magfiles[$key]);
			} else {
				 $catsfound=explode(',',$art['categorie']); 
				 foreach($catsfound as $keycat => $catval){
					 if($catval =='draft') { unset($magfiles[$key]);}
					 if($catval !='draft') {
			if(!isset($catnb[$catval])){$catnb[$catval]=1;}else{$catnb[$catval]=$catnb[$catval] + 1;}
				$categories[$catval]= $catval;
				array_unique($categories);
					 }
				 }
			}			
		}// magT

		if ($plxPlugin->getParam('epubMode')=='magA') {	
			$catnb=array();//reset
		$testKeyY=substr($art['date_creation'],0,4);
		$string = 	$plxPlugin->getParam('magAY');
		 if ($testKeyY != $string ) {
			 unset($magfiles[$key]);
			} else {
				 $catsfound=explode(',',$art['categorie']); 
				 foreach($catsfound as $keycat => $catval){
					 if($catval =='draft') { unset($magfiles[$key]);}
					 if($catval !='draft') {
			if(!isset($catnb[$catval])){$catnb[$catval]=1;}else{$catnb[$catval]=$catnb[$catval] + 1;}
				$categories[$catval]= $catval;
				array_unique($categories);
					 }
				 }
			}			
		}// magA
	
		}
	if ($plxPlugin->getParam('epubMode')=='magM' || $plxPlugin->getParam('epubMode')=='magT'|| $plxPlugin->getParam('epubMode')=='magS'|| $plxPlugin->getParam('epubMode')=='magA') {	
		// maj liste fichiers
			$plxAdmin->plxGlob_arts->aFiles = $magfiles;
		// parcours catégories et maj statut ['active'].
			foreach ($plxAdmin->aCats as $catNumb => $values) {
				if(!in_array($catNumb, $categories)) {		 
					$plxAdmin->aCats[$catNumb]['active']='0';
					unset($plxAdmin->aCats[$catNumb]);
				}
			}
		// maj categories actives.
			$plxAdmin->activeCats = implode(' | ' , $categories);				
		}
		// tri cat et assignation nombre articles dispos
		ksort($catnb);
		foreach($catnb as $mctNb => $vlNm) {
			$plxAdmin->aCats[$mctNb]['articles'] = $vlNm;
		}	
}

// recup profils
$aProfils = array(
	PROFIL_ADMIN => L_PROFIL_ADMIN,
	PROFIL_MANAGER => L_PROFIL_MANAGER,
	PROFIL_MODERATOR => L_PROFIL_MODERATOR,
	PROFIL_EDITOR => L_PROFIL_EDITOR,
	PROFIL_WRITER => L_PROFIL_WRITER
);
///////////////// templates ////////////////
// tpl option Years 
$optionTplY=PHP_EOL;
$yearStart = date('Y');
$yearEnd = $yearStart - 20;
while ($yearStart >= $yearEnd) {
	$optionTplY .='<option value="'.$yearStart.'">'.$yearStart.'</option>'.PHP_EOL;
	$yearStart--;
}

// tpl option Months 
$optionTplM=PHP_EOL;
$monthStart = '1';
$monthEnd = '12';
while ($monthStart <= $monthEnd) {	
	$optionTplM .='<option value="'.$monthStart.'">'. str_pad($monthStart, 2, "0", STR_PAD_LEFT).'</option>'.PHP_EOL;
	$monthStart++;
}	

	
// user select tpl
	if($plxAdmin->aUsers) {
		$userTPL=PHP_EOL .'<option value="000">'.$plxPlugin->getLang('L_NO_FILTER').'</option>'.PHP_EOL;
		foreach($plxAdmin->aUsers as $_userid => $_user)	{
		// test sur active="1" profil="0" delete="0" et dans $AuthorPublished 
		 if($_user['active'] ==1  &&  $_user['profil'] ==   max(min($_user['profil'], 4), 0) && $_user['delete'] ==0 && in_array($_user['name'],$AuthorPublished ) ) {// test min/max pour compatibilite plugin vip_zone ou autre ajoutant des profil en dehors des 5 natifs
			$userTPL .='<option value="'.$_userid.'" title="'.$aProfils[$_user['profil']].'">'.$_user['name']. '</option>'.PHP_EOL;
		 }
		}
	}

// tpl select epub dir already created
$existEpubDirTpl ='';
if( count($loopHisto)>0) {
	$existEpubDirTpl = '<p>'. PHP_EOL .'		<label for="epubDirHisto">'.$plxPlugin->getlang('L_BACK_TO_OLD_STORAGE_REPERTORY') .'</label>'. PHP_EOL .'		<select name="epubDirHisto">'.PHP_EOL.'<option>'. $plxPlugin->getLang('L_HISTORY') .'... </option>';
	 foreach($loopHisto as $histoDir) {
		$existEpubDirTpl .='			<option value="'.$histoDir.'" >'.$histoDir. '</option>'.PHP_EOL ;
	 }
	$existEpubDirTpl .='		</select>'. PHP_EOL .'	</p>';
}


	
{// recherche commentaires et pages associées
	$com=$plxAdmin->plxGlob_coms->aFiles;
	$PagesCommentees= array();
	$comNumber=array();
	foreach($com as $num => $val) {
		$comNumber[]=mb_substr($val,0,4);
		$comNumber = array_unique($comNumber); 
	}
	foreach($comNumber as $filter =>$id) {
			$motif = '#^'.$id.'.(home[0-9,],)*(?:[0-9,]|home)(?:\d|home|,)*.\d{3}.\d{12}.[\w-]+.xml$#';
					
			       // if ($aFiles = $plxGlob_arts->query($motif, 'art', $sort, 0, $max, 'before'))
			if($aFiles = $plxAdmin->plxGlob_arts->query($motif,'art',$plxAdmin->tri,0,9999,'all')) {
				foreach($aFiles as $v) {
				$artfound = $plxAdmin->parseArticle(PLX_ROOT . $plxAdmin->aConf['racine_articles'] . $v);
					if(!empty($artfound)) {
						$PagesCommentees[$id]= $artfound["title"];
					}
				}
			}
	}
}
//echo '<hr>'.$plxPlugin->hexTorgb('#123456')[0].'<hr>';// fonction tester et valider pour création  theme perso à venir

# base fichiers

{ 	$containerXML='<?xml version="1.0" encoding="utf-8" standalone="no"?>
		<container xmlns="urn:oasis:names:tc:opendocument:xmlns:container" version="1.0">
			<rootfiles>
				<rootfile full-path="EPUB/package.opf" media-type="application/oebps-package+xml"/>
			</rootfiles>
</container>';
}

{ 	$baseCSS='*{margin:0;box-sizing:border-box;}
/* reset retro compatibilité */
article, aside, details, figure, figcaption, footer, header, nav, section {
	display: block;
	margin: 0;
	padding: 0;
	font-size: 1em;
	line-height: inherit;
}

/* fin retro */
/* harmonie typo */
body {
	font-size: 100%; 
	line-height: 1.5;
}

h1 {
	font-size: 1.5em;
	line-height: 1;
	margin-top: 0; /* = 0 × line-height */
	margin-bottom: 3em; /* = 3 × line-height */
}
h2 {
	font-size: 1.3125em;
	line-height: 1.1429;
	margin-top: 2.2858em; /* = 2 × line-height */
	margin-bottom: 1.1429em; /* = 1 × line-height */
}
h3 {
	font-size: 1.125em;
	line-height: 1.3333;
	margin-top: 1.3333em; /* = 1 × line-height */
	margin-bottom: 0; /* = 0 × line-height */
}
p {
	font-size: 1em;
	line-height: 1.5;
	margin: 0; /* = 0 × line-height */
}
.legende {
	font-size: 0.875em;
	line-height: 1.7143;
	margin: 0; /* = 0 × line-height */
}
blockquote {
	font-size: 1em;
	line-height: 1.5;
	margin-top: 1.5em; /* = 1 × line-height */
	margin-bottom: 1.5em; /* = 1 × line-height */
	margin-left: 5%; /* = 5\% de la largeur de l\'élément parent */
	margin-right: 5%; /* = 5\% de la largeur de l\'élément parent */
}
/* fin harmonie typo */
/* cesure */
body {
	adobe-hyphenate-limit-lines: 3;
	-moz-hyphenate-limit-lines: 3;
	-epub-hyphenate-limit-lines: 3;
	-ms-hyphenate-limit-lines: 3;
	-webkit-hyphenate-limit-lines: 3;
	hyphenate-limit-lines: 3;
}
h1, h2, h3, h4, h5, h6,
.centre, .droite {
	adobe-hyphenate: none;
	-ms-hyphens: none; /* Trident (Windows) */
	-moz-hyphens: none; /* Gecko (Firefox) */
	-webkit-hyphens: none; /* Webkit */
	-epub-hyphens: none; /* EPUB 3 */
	hyphens: none; /* Futur standard */
}
/* fin cesure */';	
}

{	$communCSS ='body{
  margin:0;
  padding:0 3rem;
  }
body * {
  box-sizing: border-box;
  border: none;
  font-size: inherit;
  line-height: 1.4;
  margin: 0.75rem 0;
}
body>section {
  font-size: 1rem;
  line-height: 1.6;
  display: grid;
  grid-auto-rows: min-content;
  padding: 1rem 3rem;
  min-height:100vh;
  margin:0;
}
body>section.chapter{
	align-content:center;
	text-align:center;
	background:lightyellow;
}
body>section.cover {
  margin:0;
  padding:0;
}
pre {
  white-space: pre-wrap;
  background:ivory;
  border:dotted 1px;
  padding:0.1rem;
  border-radius:4px;
}
h1, section> a:first-child + h2:first-of-type {
  margin: 0.75em -2rem 0.25em;
  font-size: 1.8rem;
  text-transform: uppercase;
  text-align:center;
}
pre {
  line-height: 1.05;
}
h2,
h4,
h3,
h5,
h6 {
  font-size: 1.4rem;
  margin: 0.75em -2rem 0.25em;
}
blockquote:before {
  content: "‟";
  float: left;
  font-size: 4rem;
  line-height: 0.75;
  color: #6aa6ce;
  height: 1.2rem;
  margin-left: -1rem;
  width: 2.5rem;
}
blockquote {
  font-style: italic;
  padding-inline-start: 1rem;
  border-inline-start: solid 0.75rem hotpink;
  background: #efefef;
  margin: 0.5em auto;
  padding: 0.5rem 1rem;
  max-width: 60ch;
  margin: auto;
}
table {
  border: solid 1px;
  border-spacing: 0;
  border-collapse: collapse;
  margin: auto;
}

th,
td {
  border: solid 1px silver;
  padding: 0.25rem;
}
th {
  background: #efefef;
}
dt {
  font-weight:bold;
}
img {
  vertical-align:top;
  box-shadow:1px 1px 2px;
  margin:0.2em;
 }
img#r7 {
    display: block;
    margin: auto auto 0;
	padding-top:1em;
    box-shadow: 0 0;
}
#keywords dt {
  text-transform:uppercase;
  font-size:3rem;
  color:#555;
}
dd{
  padding-inline-start:1.5em;
}
p{
  padding-inline-start:1em;
  text-indent:1em;
}
#keywords dt > i {
    font-size: 0.3em;
    vertical-align: middle;
    padding: 0 .5em;
    text-decoration: underline;
}
#keywords dd {
  display: grid;
  align-items:start;
  grid-template-columns: auto 1fr minmax(35ch,70%);
}
#keywords dd >* {
  margin:0 0.25em;
}
#keywords dd span {
  order:2;
  font-size:0.8em;
  display: grid;
  background: #efefef30;
}
#keywords dd span a {
  color:inherit; 
  text-decoration:none;
  display:inline-block;
  margin:0;
}
#keywords dd span a:hover {
  font-size:1.25em;
  margin:-0.135em 0.0em;
}
#keywords dd:before {
  content:"";
  flex-grow:1;
  order:1;
  border-bottom:dotted 1px;
  margin-top:0.8em;
}
#keywords dd small{
  margin:0.15em;
}
body>section.front {
	grid-auto-rows:1fr;
	text-align:center;
}
h2 span {
	display:block;
	color:gray;
	font-size:0.65em;
}
section.front footer {
	max-width:35ch;
	margin:auto auto 0;
}
section.tdm h1 {
    text-align: center;
    margin:5rem 0;
}


nav#guide > ol {
    list-style: none;
    text-transform: uppercase;
}

li.mother {
  text-indent:-1.2em;
  font-size:1.1em;
}
li.main {
}

nav#guide > ol li li {
    text-transform: none;
}

nav#guide a {
    color: gray;
    text-decoration: none;
}
a#tdmA {
    position:absolute;
    right:0;
    color:gray;
    font-size:0.7em;
}
.fs08 {
	font-size:0.8em;
}
.gray {
	color:gray;
}
a.gray {
	text-decoration:none;
}'
;
	
}

// on récupere le nom du plugin appelé (nom de class).		
$plugin = isset($_GET['p'])?urldecode($_GET['p']):'';

// initialisation du repertoire de stockage - premiere utilisation = EPUBS
$repertoire = $plxPlugin->getParam('epubRepertory')=='' ? $plxPlugin->epubRepertory  : $plxPlugin->getParam('epubRepertory');
/*$histRepertoire= function (){
	$histo[]= $plxPlugin->epubRepertory;
	if(isset($plxPlugin->getParam('epubRepertoryHisto'))) {
		$loopHisto= explode(' ',$plxPlugin->getParam('epubRepertoryHisto') );
	}		//&& $plxPlugin->getParam('epubRepertory') != $plxPlugin->epubRepertory){$histo[]=$plxPlugin->getParam('epubRepertory');}
	
};*/


// création du repertoire de stockage si inexistant
if (!file_exists($repertoire)) {
	mkdir($repertoire, 0777, true);
}
// images cover
$width='1200';
$height = $width * '1.4' ;
$imgPath = PLX_ROOT.'plugins/'.$plugin.'/covers/';
$titreTh = $plxAdmin->aConf['title'];
//$descTh  = $plxAdmin->aConf['description'];
$AuthTh  = $plxAdmin->aUsers[$_SESSION['user']]['name'];
$dcId =  $plxPlugin->getParam('isbn')=='' ? $titreTh : $plxPlugin->getParam('isbn');
if(isset($_POST['doComics'])) { 	$dcId=$plxPlugin->getParam('titreComics');}
$ISBN = $plxPlugin->getParam('isbn')=='' ? null : $plxPlugin->getParam('isbn');
$ISSN = $plxPlugin->getParam('issn')=='' ? null : $plxPlugin->getParam('issn');

//aperçus themes 
$themesList = glob($imgPath.'th*', GLOB_ONLYDIR);
 natcasesort($themesList);
// chemin vers le theme en cours 
$themePath = PLX_ROOT.$plxAdmin->aConf['racine_themes'].$plxAdmin->style;
// base fichier toc 
{ $tocNcx ='<ncx xmlns="http://www.daisy.org/z3986/2005/ncx/" version="2005-1" xml:lang="'.$plxAdmin->aConf['default_lang'].'">
  <head>
    <meta name="dtb:uid" content="'.$dcId.'"/>
    <meta name="dtb:depth" content="0"/>
    <meta name="dtb:generator" content="'.$plugin.'"/>
    <meta name="dtb:totalPageCount" content="0"/>
    <meta name="dtb:maxPageNumber" content="0"/>
  </head>
  <docTitle>
    <text>'.$titreTh.'</text>
  </docTitle>
  <docAuthor>
	<text>'.$AuthTh.'</text>
  </docAuthor>
  <navMap>
  </navMap>
</ncx>';
}

// structure de base de la page.  - container for an <?xml 1.0 utf8 ? >
$xhtml     ='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xml:lang="'.$plxAdmin->aConf['default_lang'].'" lang="'.$plxAdmin->aConf['default_lang'].'"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link rel="stylesheet" type="text/css" href="CSS/epub.css" /><link rel="stylesheet" type="text/css" href="CSS/commun.css" /><link rel="stylesheet" type="text/css" href="CSS/fonts.css" /><link rel="stylesheet" type="text/css" href="CSS/theme.css" /><script src="JS/script.js"></script></head><body></body></html>'; 
$xhtmlcomics='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xml:lang="'.$plxAdmin->aConf['default_lang'].'" lang="'.$plxAdmin->aConf['default_lang'].'"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link rel="stylesheet" type="text/css" href="CSS/epub.css" /></head><body></body></html>'; 

// Chemins vers  fichier de police - generation de l'image de couverture
$ubuntuMono    = realpath(PLX_ROOT.'plugins/'.$plugin.'/fonts/ubuntu/UbuntuMono-B.ttf');
$freeSansB     = realpath(PLX_ROOT.'plugins/'.$plugin.'/fonts/freeserif/FreeSansBold.otf');
$freeSerif     = realpath(PLX_ROOT.'plugins/'.$plugin.'/fonts/freeserif/FreeSerif.otf');
$LatoRegular   = realpath(PLX_ROOT.'plugins/'.$plugin.'/fonts/lato/Lato-Regular.ttf');
$RobotoBold    = realpath(PLX_ROOT.'plugins/'.$plugin.'/fonts/roboto/Roboto-Bold.ttf');
$dislexia      = realpath(PLX_ROOT.'plugins/'.$plugin.'/fonts/opendyslexic/OpenDyslexicAlta-Regular.otf'); 
$fontawesome   = realpath(PLX_ROOT.'plugins/'.$plugin.'/fonts/fontawesome-webfont.ttf'); 


?>
