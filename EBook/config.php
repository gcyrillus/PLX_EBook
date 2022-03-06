<?php if(!defined('PLX_ROOT')) exit;


# Control du token du formulaire
plxToken::validateFormToken($_POST);
require('varEbook.php');

    if(!empty($_POST)) {//  création et sauvegarbe Epub(s)
		$backToTab='';

			
		if(isset($_POST['submitmode'])) { //publish mode
			$plxPlugin->setParam('epubMode', 			$_POST['epubMode'			], 'string');	
			$plxPlugin->setParam('comicsmedia', 		$_POST['comicsmedia'		], 'string');
			$plxPlugin->setParam('titreComics', 		$_POST['titreComics'		], 'string');
			$plxPlugin->setParam('descComics', 			$_POST['descComics'			], 'string');
			$plxPlugin->setParam('auteurComics', 		$_POST['auteurComics'		], 'string');
			$plxPlugin->setParam('illustrateurComics', 	$_POST['illustrateurComics'	], 'string');
			$plxPlugin->setParam('ISSNComics', 			$_POST['ISSNComics'			], 'string');
			$plxPlugin->setParam('ISBNComics',			$_POST['ISBNComics'			], 'string');
			//valeurs mois 
			 $plxPlugin->setParam('magMM', $_POST['magMM'], 'numeric'); 
			 $plxPlugin->setParam('magTM', $_POST['magTM'], 'numeric');  
			 $plxPlugin->setParam('magSM', $_POST['magSM'], 'numeric');  
			//valeurs Années
			 $plxPlugin->setParam('magMY', $_POST['magMY'], 'numeric');  
			 $plxPlugin->setParam('magTY', $_POST['magTY'], 'numeric');   
			 $plxPlugin->setParam('magSY', $_POST['magSY'], 'numeric');   
			 $plxPlugin->setParam('magAY', $_POST['magAY'], 'numeric'); 
			 
			$plxPlugin->setParam('triAuthors',$_POST['triAuthors'] , 'string');		
			
			$plxPlugin->saveParams();
			
			$backToTab='&tab=fmode';
			if( $var['debugme'] == 1) {		
				echo ' Mode Publication:  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
				exit;
			}				 
		}
				
		if (isset($_POST['submitA'])) {// option affichage
		$plxPlugin->setParam('mnuDisplay', 				$_POST['mnuDisplay'], 'numeric');
		$plxPlugin->setParam('mnuPos', 					$_POST['mnuPos'], 'numeric');
		$plxPlugin->setParam('template', 				$_POST['template'], 'string');
		$plxPlugin->setParam('url', plxUtils::title2url($_POST['url']), 'string');
		//correction format chemin repertoire
		$postedRepertory = preg_replace("/^(\\.\\.\\/)+/", "", trim($_POST['epubRepertory']));
		$postedRepertory='../../'.$postedRepertory;
		$plxPlugin->setParam('epubRepertory',$postedRepertory , 'string');		
			
		// ajout/verif histo ?		
		//$plxPlugin->setParam('epubRepertoryHisto', $var['epubRepertoryHisto'].' '.trim($_POST['epubRepertory']), 'string');
        $checkHisto =explode(' ',$var['epubRepertoryHisto'].' '.trim($_POST['epubRepertory']));		
		$checkHisto =array_unique($checkHisto);
		$plxPlugin->setParam('epubRepertoryHisto', implode(' ', $checkHisto) , 'string');
		
		// option debogage partiel
		$plxPlugin->setParam('debugme', 				$_POST['debugme'], 'numeric');
		$var['debugme'] = $_POST['debugme'];
			foreach($aLangs as $lang) {
				$plxPlugin->setParam('mnuName', 	$_POST['mnuName'], 'string');
			    //$plxPlugin->setParam('mnuText_'.$lang, 	$_POST['mnuText_'.$lang], 'string');
			}
		$plxPlugin->setParam('description', $_POST['description'], 'cdata');
		$plxPlugin->setParam('custom-start', $_POST['custom-start'], 'cdata');
		$plxPlugin->setParam('custom-end', $_POST['custom-end'], 'cdata');
		$plxPlugin->saveParams();
		
		$backToTab='&tab=fA';
		if($var['debugme'] == 1) {		
			echo 'Options Affichages:  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
			exit;
				}		
		} 	
		
		if (isset($_POST['submitB'])) {// vide
		$plxPlugin->saveParams();
		
		$backToTab='&tab=fB';
			if($var['debugme'] == 1) {		
				echo 'vide:  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
				exit;
			}				 
		} 	
		
		if (isset($_POST['submitC'])) {// choix categorie/annexe
		// catégories à inclure 
		
		$plxPlugin->setParam('all', $_POST['all'], 'numeric');
		$plxPlugin->setParam('all-th', $_POST['all-th'], 'string');
		foreach ($plxAdmin->aCats as $catNumb => $values) {
			if( $values["articles"] >="1" && $values['active']=='1' ) {// on ne prend que les catégories disposant d'un articles et actives
				$plxPlugin->setParam($catNumb, $_POST[$catNumb], 'numeric');
				$plxPlugin->setParam($catNumb.'-th', $_POST[$catNumb.'-th'], 'string');
			}
		}
		
		// pages supplementaire à inclure
		$plxPlugin->setParam('pageIndex', $_POST['pageIndex'], 'numeric');
		if(isset($_POST['pageCopy'])) $plxPlugin->setParam('pageCopy', $_POST['pageCopy'], 'numeric');
			if(isset($_POST['pageCopy']) && $_POST['pageCopy'] =='1' && $plxPlugin->checkMultiArray(@$plxAdmin->aStats,'pagecopy') ===false ) { 
			$plxPlugin->createAnnexeStatique('pagecopy','Ebook Copyrights');
            $plxPlugin->setParam('pageCopyId',$plxPlugin->AnnexeId, 'numeric');
			}
		$plxPlugin->setParam('pageDedicace', $_POST['pageDedicace'], 'numeric');
			if($_POST['pageDedicace'] =='1' && $plxPlugin->checkMultiArray(@$plxAdmin->aStats,'pagededicace') ===false ) { 
			$plxPlugin->createAnnexeStatique('pagededicace','Dédicaces');
            $plxPlugin->setParam('pagededicaceId',$plxPlugin->AnnexeId, 'numeric');
			}
		$plxPlugin->setParam('pageForeword', $_POST['pageForeword'], 'numeric');
			if($_POST['pageForeword'] =='1' && $plxPlugin->checkMultiArray(@$plxAdmin->aStats,'pageforeword') ===false ) { 
			$plxPlugin->createAnnexeStatique('pageforeword','avant Propos');
            $plxPlugin->setParam('pageforewordId',$plxPlugin->AnnexeId, 'numeric');
			}
		$plxPlugin->setParam('pageAuteur', $_POST['pageAuteur'], 'numeric');// recup champ descrition du user
		
		
		$plxPlugin->setParam('pagePreface', $_POST['pagePreface'], 'numeric');
			if($_POST['pagePreface'] =='1' && $plxPlugin->checkMultiArray(@$plxAdmin->aStats,'pagepreface') ===false ) { 
			$plxPlugin->createAnnexeStatique('pagepreface','Préface');
            $plxPlugin->setParam('pageprefaceId',$plxPlugin->AnnexeId, 'numeric');
			}
		$plxPlugin->setParam('pagerRemerciement', $_POST['pagerRemerciement'], 'numeric');
			if($_POST['pagerRemerciement'] =='1' && $plxPlugin->checkMultiArray(@$plxAdmin->aStats,'pageremerciement') ===false ) { 
			$plxPlugin->createAnnexeStatique('pageremerciement','Remerciements');
            $plxPlugin->setParam('pageremerciementId',$plxPlugin->AnnexeId, 'numeric');
			}

		//Selection page static affichées sur le site
		foreach ($plxAdmin->aStats as $k => $v) {
			if ($v['active'] == 1 && $v['menu'] == 'oui' && $v['group'] !='ebookAnnexe' ) {			
				$plxPlugin->setParam('stat-'.$k, $_POST['stat-'.$k], 'numeric');
			}
		}



		
		$plxPlugin->setParam('pagePostface', $_POST['pagePostface'], 'numeric');
			if($_POST['pagePostface'] =='1' && $plxPlugin->checkMultiArray(@$plxAdmin->aStats,'pagepostface') ===false ) { 
			$plxPlugin->createAnnexeStatique('pagepostface','Postface');
            $plxPlugin->setParam('pagepostfaceId',$plxPlugin->AnnexeId, 'numeric');
			}
		$plxPlugin->setParam('pageTemoignage', $_POST['pageTemoignage'], 'numeric'); 
		$plxPlugin->setParam('nbCom', $_POST['nbCom'], 'numeric');   
		$plxPlugin->setParam('art-coms', $_POST['art-coms'], 'numeric'); 

        $plxPlugin->saveParams();
		
		$backToTab='&tab=fC';
			if($var['debugme'] == 1) {		
				echo 'Selection annexe et catégories:  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
				exit;
			}
		} 	
		
		if (isset($_POST['submitD'])) {//fiche epub
		// MAJ metas du bouquin
        $plxPlugin->setParam('uid', $_POST['uid'], 'string');
		if($_POST['title'] == null ) {$_POST['title'] = $plxAdmin->aConf['title'];}
        $plxPlugin->setParam('title', trim($_POST['title']), 'string');
		if($_POST['subtitle'] == null ) {$_POST['subtitle'] = $plxAdmin->aConf['description'];}
        $plxPlugin->setParam('subtitle', $_POST['subtitle'], 'string');
        $plxPlugin->setParam('author', $_POST['author'], 'string');
        $plxPlugin->setParam('issn', $_POST['issn'], 'string');
        $plxPlugin->setParam('isbn', $_POST['isbn'], 'string');
		
		
        $plxPlugin->setParam('publisher', $_POST['publisher'], 'string');
        $plxPlugin->setParam('editor', $_POST['editor'], 'string');
        $plxPlugin->setParam('dateC', $_POST['dateC'], 'string');
        $plxPlugin->setParam('dateM', $_POST['dateM'], 'string');
        $plxPlugin->setParam('copyrights', $_POST['copyrights'], 'string');
        $plxPlugin->setParam('licence', $_POST['licence'], 'string');
        $plxPlugin->setParam('urlLicence', $_POST['urlLicence'], 'string');
        $plxPlugin->setParam('descLicence', $_POST['descLicence'], 'cdata');
		
        $plxPlugin->setParam('ctxt', $_POST['ctxt'], 'string');
        $plxPlugin->setParam('cread', $_POST['cread'], 'string');
        $plxPlugin->setParam('cimg', $_POST['cimg'], 'cdata');
        $plxPlugin->setParam('ctrslt', $_POST['ctrslt'], 'string');
		
        $plxPlugin->setParam('cbiblio', $_POST['cbiblio'], 'string');
        $plxPlugin->setParam('clayout', $_POST['clayout'], 'string');
        $plxPlugin->setParam('ctool', $_POST['ctool'], 'string');	

        $plxPlugin->setParam('cover1', $_POST['cover1'], 'string');		
		$plxPlugin->saveParams();
		$backToTab='&tab=fD';
		
		if($var['debugme'] == 1) {		
			echo 'fiche epub:  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
			exit;
		}				 
		} 	
		
		if (isset($_POST['submitE'])) {// fiche crédits
			$plxPlugin->setParam('ctxt', $_POST['ctxt'], 'string');
			$plxPlugin->setParam('cread', $_POST['cread'], 'string');
			$plxPlugin->setParam('cimg', $_POST['cimg'], 'cdata');
			$plxPlugin->setParam('ctrslt', $_POST['ctrslt'], 'string');
			
			$plxPlugin->setParam('cbiblio', $_POST['cbiblio'], 'string');
			$plxPlugin->setParam('clayout', $_POST['clayout'], 'string');
			$plxPlugin->setParam('ctool', $_POST['ctool'], 'string');	

			$plxPlugin->setParam('cover1', $_POST['cover1'], 'string');		
			
			$plxPlugin->saveParams();
			$backToTab='&tab=fE';
			if($var['debugme'] == 1) {		
				echo 'fiche crédits:  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
				exit;
			}				 
		} 	
		
		if (isset($_POST['submitF'])) {// ajout theme perso : à voir / à faire
		$plxPlugin->saveParams();
		$backToTab='&tab=fE';
			if($var['debugme'] == 1) {		
				echo 'theme :  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
				exit;
			}				 
		}

		if(isset($_POST['doComics'])) { // créations epub à partir d'images		$dcId=$plxPlugin->getParam('titreComics');
		
		// MAJ données
		$plxPlugin->setParam('epubMode', $_POST['epubMode'], 'string');	
		$plxPlugin->setParam('comicsmedia', $_POST['comicsmedia'], 'string');
		$plxPlugin->setParam('titreComics', $_POST['titreComics'], 'string');
		$plxPlugin->setParam('descComics', $_POST['descComics'], 'string');
		$plxPlugin->setParam('auteurComics', $_POST['auteurComics'], 'string');
		$plxPlugin->setParam('ISSNComics', $_POST['ISSNComics'], 'string');
		$plxPlugin->setParam('ISBNComics', $_POST['ISBNComics'], 'string');		
		$plxPlugin->saveParams();
		$dcId=$plxPlugin->getParam('titreComics');
				// epub filename
				$ebook= mb_substr(str_ireplace (' ', '',$plxPlugin->remove_accents(str_replace("'", "", $plxPlugin->getParam('titreComics')))), 0, 12). '.epub';

				//destination
					$ebook = $repertoire.'/'.$ebook;
				
				// generation de l'archive
					$initZip = $plxPlugin->makeEmptyZip('mimetype', 'application/epub+zip', $ebook); 
					$once = $plxPlugin->once($initZip);
					$plxPlugin->addRepertory('META-INF', $ebook);
					$plxPlugin->addFiletxt('META-INF/container.xml', $containerXML,  $ebook);
					
				// création de repertoires de contenus et ressources
					$plxPlugin->addRepertory('EPUB',     $ebook);
					$plxPlugin->addRepertory('EPUB/IMG', $ebook);
					$plxPlugin->addRepertory('EPUB/CSS', $ebook);

				// creation de la page cover  
				$pagecover = new DOMDocument('1.0', 'utf-8'); 
				$pagecover->preserveWhiteSpace = false; 
				$pagecover->formatOutput = true; 
				$pagecover->loadHTML(mb_convert_encoding($xhtmlcomics, 'HTML-ENTITIES', 'UTF-8'));
								
				$title = $pagecover->createElement('title', $plxPlugin->getParam('titreComics') );
				// ajout du titre 
				$xpath = new DOMXPath($pagecover);    
				$results = $xpath->query('/html/head');   
				$head = $results->item(0);
				$head->appendChild($title);
				//ajout contenu	   
				$results = $xpath->query('/html/body');
				$body=$results->item(0); 
				$section= $pagecover->createElement('section');
				$section_attr=$pagecover->createAttribute('epub:type');
				$section_attr->value="cover";
				$section->appendChild($section_attr);
				
				$section_attr_1=$pagecover->createAttribute('title');
				$section_attr_1->value=$plxPlugin->getParam('titreComics');
				$section->appendChild($section_attr_1);
				
				$section_attr_2=$pagecover->createAttribute('class');
				$section_attr_2->value="cover";
				$section->appendChild($section_attr_2);
				
				
				$img=$pagecover->createElement('img');
				$img_attr_1=$pagecover->createAttribute('src');
				$img_attr_1->value='IMG/cover.jpg';
				$img->appendChild($img_attr_1);
				
				$img_attr_2=$pagecover->createAttribute('style');
				$img_attr_2->value='display:block;height:100vh;width:100%;';// à voir
				$img->appendChild($img_attr_2);
				$section->appendChild($img);
				
				$body->appendChild($section);

			// insertion page cover dans archive ebook
				$plxPlugin->addFiletxt('EPUB/cover.xhtml', $pagecover->saveXML(), $ebook);	


			// création fichier opf  en utf-8 
					$opf = new DOMDocument('1.0', 'utf-8'); 
					$opf->preserveWhiteSpace = false; 
					$opf->formatOutput = true;
					
			// tag package + attributes
				//create the main tags, without values  + attributes
				$package = $opf->createElement('package'); 
				{	$package_attr_1 = $opf->createAttribute('xmlns'); 
					$package_attr_1->value="http://www.idpf.org/2007/opf";	
					$package->appendChild($package_attr_1);
					
					$package_attr_2 = $opf->createAttribute('version'); 
					$package_attr_2->value="3.0";
					$package->appendChild($package_attr_2);
					
					$package_attr_3 = $opf->createAttribute('unique-identifier'); 
					$package_attr_3->value="uid";
					$package->appendChild($package_attr_3);
					
					$package_attr_4 = $opf->createAttribute('xml:lang'); 
					$package_attr_4->value=$plxAdmin->aConf['default_lang'];	
					$package->appendChild($package_attr_4);
																		
					$package_attr_5 = $opf->createAttribute('prefix'); 
					$package_attr_5->value="cc: ".$var['urlLicence']; 	// $type_licence
					$package->appendChild($package_attr_5);		
					
					$package_attr_6 = $opf->createAttribute('xmlns:dc');  // bublin core
					$package_attr_6->value="http://purl.org/dc/elements/1.1/"; 	 
					$package->appendChild($package_attr_6);		
					
					$package_attr_7 = $opf->createAttribute('xmlns:dcterms'); 
					$package_attr_7->value="http://purl.org/dc/terms/"; 	 
					$package->appendChild($package_attr_7);
				}

				// metadata
					$metadata = $opf->createElement('metadata');
				{							
					if( $plxPlugin->getParam('ISBNComics') == null) { $dcId= $plxPlugin->getParam('titreComics'); } else {$dcId= $plxPlugin->getParam('ISBNComics');}
						{	$tag_1 = $opf->createElement('dc:identifier', $dcId );
							$tag_1_attr_1 = $opf->createAttribute('id'); 
							$tag_1_attr_1->value="uid";
							$tag_1->appendChild($tag_1_attr_1);
							$metadata->appendChild($tag_1);
						}		
							$tag_2 = $opf->createElement('dc:title', $plxPlugin->getParam('titreComics'));//$title
							$metadata->appendChild($tag_2);

							$tag_3 = $opf->createElement('dc:creator',$plxPlugin->getParam('auteurComics')); // $admin name ?
							$metadata->appendChild($tag_3);
							
							$tag_4 = $opf->createElement('dc:language',$plxAdmin->aConf['default_lang'] ); // langue
							$metadata->appendChild($tag_4);
							
							$tag_5 = $opf->createElement('dc:date', date('Y-m-d\Th:i:s\Z') ); 
							$metadata->appendChild($tag_5);

							$tag_12 = $opf->createElement('meta', date('Y-m-d\Th:i:s\Z')); // 
							$tag_12_attr_1 = $opf->createAttribute('property'); 
							$tag_12_attr_1->value="dcterms:modified";
							$tag_12->appendChild($tag_12_attr_1);
							$metadata->appendChild($tag_12);
							
							$tag_6 = $opf->createElement('dc:description',$plxPlugin->getParam('descComics')); // $subtitle
							$metadata->appendChild($tag_6); 
							
							$tag_7 = $opf->createElement('dc:rights',$var['licence']); // $licence														
							$metadata->appendChild($tag_7);// ou  LicenseDocument  et RightsStatement  ??
							
							$tag_11 = $opf->createElement('link'); // 
							$tag_11_attr_1 = $opf->createAttribute('rel'); 
							$tag_11_attr_1->value="dcterms:rights";
							$tag_11->appendChild($tag_11_attr_1);
							$tag_11_attr_2 = $opf->createAttribute('href'); 
							$tag_11_attr_2->value=$var['urlLicence'];
							$tag_11->appendChild($tag_11_attr_2);
							$metadata->appendChild($tag_11);
							
							$tag_8 = $opf->createElement('link'); // $licence
							$tag_8_attr_1 = $opf->createAttribute('rel'); 
							$tag_8_attr_1->value="cc:licence";
							$tag_8->appendChild($tag_8_attr_1);
							$tag_8_attr_2 = $opf->createAttribute('href'); 
							$tag_8_attr_2->value=$var['urlLicence'];
							$tag_8->appendChild($tag_8_attr_2);
							$metadata->appendChild($tag_8);
							
							$tag_9 = $opf->createElement('meta',$plxAdmin->racine ); // 
							$tag_9_attr_1 = $opf->createAttribute('property'); 
							$tag_9_attr_1->value="cc:attributionURL";
							$tag_9->appendChild($tag_9_attr_1);
							$metadata->appendChild($tag_9);
						
							/*$tag_10 = $opf->createElement('meta'); 
							$tag_10_attr_1 = $opf->createAttribute('name'); 
							$tag_10_attr_1->value="cover";
							$tag_10->appendChild($tag_10_attr_1);
							$tag_10_attr_2 = $opf->createAttribute('content'); 
							$tag_10_attr_2->value=$tag_10_attr_1->value;
							$tag_10->appendChild($tag_10_attr_2);
							$metadata->appendChild($tag_10);*/
							
					 if($plxPlugin->getParam('ISSNComics') != null ) {	
						$tag_13 = $opf->createElement('dc:identifier',$plxPlugin->getParam('ISSNComics')); // 
							$tag_13_attr_1 = $opf->createAttribute('id'); 
							$tag_13_attr_1->value='issn';
							$tag_13->appendChild($tag_13_attr_1);
							$metadata->appendChild($tag_13);
					 }	
					 if($plxPlugin->getParam('ISBNComics') != null ) {	
						$tag_131 = $opf->createElement('dc:identifier',$plxPlugin->getParam('ISBNComics')); // 
							$tag_131_attr_1 = $opf->createAttribute('id'); 
							$tag_131_attr_1->value="isbn";
							$tag_131->appendChild($tag_131_attr_1);
							$metadata->appendChild($tag_131);
					 } 		
							$tag_14 = $opf->createElement('meta'); // pour epub2
							$tag_14_attr_1 = $opf->createAttribute('name'); 
							$tag_14_attr_1->value="cover";
							$tag_14->appendChild($tag_14_attr_1);
							$tag_14_attr_2 = $opf->createAttribute('content'); 
							$tag_14_attr_2->value="IMG/cover.png";
							$tag_14->appendChild($tag_14_attr_2);
							$metadata->appendChild($tag_14); 
							 					
				}	

				// enregistre metadata
					$package->appendChild($metadata);
					
				// alimentation tags manifest , spine, guide, fichier toc et page 
				{
					{ // fichiers communs
						$manifest = $opf->createElement('manifest'); 
						
						// ajout cover.xhtml							
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="cover";	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="cover.xhtml";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="application/xhtml+xml";
							$books->appendChild($book_attr_3);	
						$manifest->appendChild($books);	
						
					
					// image couverture					
					$plxPlugin->addFiles(PLX_ROOT.'/data/medias/'.$plxPlugin->getParam('mediaComics').'/cover.jpg','EPUB/IMG/cover.jpg',$ebook);
					
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="coverImg";	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="IMG/cover.jpg";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="image/jpeg";
							$books->appendChild($book_attr_3);	
							$book_attr_4= $opf->createAttribute('properties'); 
							$book_attr_4->value="cover-image";
							$books->appendChild($book_attr_4);	
						$manifest->appendChild($books);
						
					
					$plxPlugin->addFiles(PLX_ROOT.'data/medias/'.$plxPlugin->getParam('comicsmedia').'/cover.jpg','EPUB/IMG/cover.jpg',$ebook);
					}			

			{// ajout fichier CSS/commun
				$cssID="1";
					$plxPlugin->addFiletxt('EPUB/CSS/epub.css', $baseCSS,   $ebook);
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="CSS-".$cssID;	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="CSS/epub.css";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="text/css";
							$books->appendChild($book_attr_3);
						$manifest->appendChild($books);

			}

			// creation item  nav dans manifest:  
							{		
							$nav =$opf->createElement('item');
							$nav_attr_1= $opf->createAttribute('id'); 
							$nav_attr_1->value="htmldoc";	
							$nav->appendChild($nav_attr_1);
							
							$nav_attr_2= $opf->createAttribute('href'); 
							$nav_attr_2->value="nav.xhtml";
							$nav->appendChild($nav_attr_2);
							
							$nav_attr_3= $opf->createAttribute('media-type'); 
							$nav_attr_3->value="application/xhtml+xml";
							$nav->appendChild($nav_attr_3);
							
							$nav_attr_4= $opf->createAttribute('properties'); 
							$nav_attr_4->value="nav";
							$nav->appendChild($nav_attr_4);	
							$manifest->appendChild($nav);
						$manifest->appendChild($nav);
							}

			//creation spine
						$spine = $opf->createElement('spine');  
						
			// creation item  nav dans spine:  
							{
							$spineItem=$opf->createElement('itemref');
							$spineItem_attr= $opf->createAttribute('idref'); 
							$spineItem_attr->value=$nav_attr_1->value;
							$spineItem->appendChild($spineItem_attr);
							$spineItem_attr_2= $opf->createAttribute('linear'); 
							$spineItem_attr_2->value="no";
							$spineItem->appendChild($spineItem_attr_2);
						$spine->appendChild($spineItem);		
							}
			// préparation creation guide pour liseuse epub2	
						$guide =$opf->createElement('guide');
						
			// Creation reference dans guide
							{
							$ref = $opf->createElement('reference');
							$ref_attr = $opf->createAttribute('href');
							$ref_attr->value="nav.xhtml";
							$ref->appendChild($ref_attr);
							$ref_attr_1 = $opf->createAttribute('title');
							$ref_attr_1->value="navigation";
							$ref->appendChild($ref_attr_1);
							$ref_attr_2 = $opf->createAttribute('type');
							$ref_attr_2->value="text";
							$ref->appendChild($ref_attr_2);
						$guide->appendChild($ref);
					}
						
			//ajout cover
							$spineItem=$opf->createElement('itemref');
							$spineItem_attr= $opf->createAttribute('idref'); 
							$spineItem_attr->value="cover";
							$spineItem->appendChild($spineItem_attr);
							$spineItem_attr_2= $opf->createAttribute('linear'); 
							$spineItem_attr_2->value="no";
							$spineItem->appendChild($spineItem_attr_2);
						$spine->appendChild($spineItem);
						
			
							$ref = $opf->createElement('reference');
							$ref_attr = $opf->createAttribute('href');
							$ref_attr->value="cover.xhtml";
							$ref->appendChild($ref_attr);
							$ref_attr_1 = $opf->createAttribute('title');
							$ref_attr_1->value="cover";
							$ref->appendChild($ref_attr_1);
							$ref_attr_2 = $opf->createAttribute('type');
							$ref_attr_2->value="cover";
							$ref->appendChild($ref_attr_2);
						$guide->appendChild($ref);

//////////////////////

							{
							
									$pagenav = new DOMDocument('1.0', 'utf-8'); 
									$pagenav->loadHTML($xhtmlcomics);
									$title = $pagenav->createElement('title', 'Table Des Matières' );
									// ajout du titre 
									$xpath = new DOMXPath($pagenav);    
									$results = $xpath->query('/html/head');   
									$head = $results->item(0);
									$head->appendChild($title);				
									
									//ajout base contenu	   
									$results = $xpath->query('/html/body');
									$bodynav=$results->item(0); 

									$sectionNav=$pagenav->createElement('section');
									$sectionNav_attr=$pagenav->createAttribute('class');
									$sectionNav_attr->value="tdm";
									$sectionNav->appendChild($sectionNav_attr);
									
									$h1=$pagenav->createElement('h1',$plxPlugin->getParam('titreComics').' -  Table Des Matières' );
									$sectionNav->appendChild($h1);
									
									$nav= $pagenav->createElement('nav');
									$nav_attr=$pagenav->createAttribute('epub:type');
									$nav_attr->value="toc";
									$nav->appendChild($nav_attr);
									$nav_attr_1=$pagenav->createAttribute('id');
									$nav_attr_1->value="guide";
									$nav->appendChild($nav_attr_1);
									
									$h2=$pagenav->createElement('h2',' Table des matières.' );
									$nav->appendChild($h2);
									
									$ol=$pagenav->createElement('ol');
									$ol_attr=$pagenav->createAttribute('epub:type');
									$ol_attr->value="list";
									$ol->appendChild($ol_attr);
									$nav->appendChild($ol); 
									
																		 
									$li=$pagenav->createElement('li');		
										$a=$pagenav->createElement('a',$plxPlugin->getParam('titreComics'));
										$a_attr=$pagenav->createAttribute('href');
										$a_attr->value='page-1.xhtml';
										$a->appendChild($a_attr);
									$li->appendChild($a);								
									$ol->appendChild($li);
									
									
									$sectionNav->appendChild($nav);
									
									$bodynav->appendChild($sectionNav);
									
									$pagenav->preserveWhiteSpace = false; 
									$pagenav->formatOutput = true;	
									$pagenav->xmlStandalone = true;
									// sauvegarde du fichier de navigation dans l'archive
									$plxPlugin->addFiletxt('EPUB/nav.xhtml', $pagenav->saveXML(), $ebook);
									
							}	


//////////////////////////////
							// création et préparation de la table des matieres format epub2   toc.ncx
							{	$play=1;
								$toc= new DOMDocument('1.0','utf-8');
								$toc->loadXML($tocNcx);
								$toc->preserveWhiteSpace = false; 
								$toc->formatOutput = true;
								$navMap = $toc->getElementsbyTagName('navMap')->item(0);
								
								
								
								//ne pas oublier en fin de boucle :			$toc->appendChild($navMap);
								
								// ajout attribut toc="ncx" a spine pour declarer la tdm epub2
								{
								$spine_attr= $opf->createAttribute('toc'); 
								$spine_attr->value="ncx";
								$spine->appendChild($spine_attr);
								}	
								
							}

					
				}
								
				// recuperation images pour insertion dans doc xhtml $xhtml
				$comicDir= PLX_ROOT.'data/medias/'.$plxPlugin->getParam('comicsmedia');
				$filesC = array_unique(array_merge(glob($comicDir.'/*.[jJ][pP][gG]'),glob($comicDir.'/*.[jJ][pP][eE][gG]'),glob($comicDir.'/*.[gG][iI][fF]'), glob($comicDir.'/*.[jJ][pP][gG]')));
				natsort($filesC);
				$Pagecomics=0;
				$play=0;
				foreach ($filesC as $comicsFile ) {
					if(basename($comicsFile)!='cover.jpg'){					
						$Pagecomics++;
						$play++;
						{			 
							$Pagecmx =$opf->createElement('item');
							$Pagecmx_attr_1= $opf->createAttribute('id'); 
							$Pagecmx_attr_1->value="page-".$Pagecomics;
							
							$Pagecmx->appendChild($Pagecmx_attr_1);
							$Pagecmx_attr_2= $opf->createAttribute('href'); 
							$Pagecmx_attr_2->value="page-".$Pagecomics.".xhtml";
							$Pagecmx->appendChild($Pagecmx_attr_2);
							$Pagecmx_attr_3= $opf->createAttribute('media-type'); 
							$Pagecmx_attr_3->value="application/xhtml+xml";
							$Pagecmx->appendChild($Pagecmx_attr_3);
							
							//insertion dans le tag manifest
						$manifest->appendChild($Pagecmx);
							
							// ajout dans toc.ncx
												
							{// creation du point de navigation vers la page annexe
							$nP=$toc->createElement('navPoint');
							$nP_attr=$toc->createAttribute('id');
							$nP_attr->value='num-'.$play;
							$nP->appendChild($nP_attr);
							$nP_attr_1=$toc->createAttribute('playOrder');
							$nP_attr_1->value=$play;
							$nP->appendChild($nP_attr_1);
							
							$nl=$toc->createElement('navLabel');
							$txt=$toc->createElement('text',  $plxPlugin->getParam('titreComics').' - Page '.$Pagecomics);
							$nl->appendChild($txt);
							$nP->appendChild($nl);

							$ct=$toc->createElement('content' );
							$nP->appendChild($ct);
							$ct_attr=$toc->createAttribute('src');
							$ct_attr->value=$Pagecmx_attr_2->value;
							$ct->appendChild($ct_attr);		
							$nP->appendChild($ct);
						// fin premier point de navigation
						
						// ajout à navPoint
							$toc->appendChild($nP);
						
						// ajout navPoint dans navMap
							$navMap->appendChild($nP);
							}
												
							{//CREATION PAGE et injection TITRE ET DESCRIPTION CATEGORIES
							$pageXHTML = new DOMDocument('1.0', 'utf-8'); 
							$pageXHTML->loadHTML($xhtmlcomics);
							$title = $pageXHTML->createElement('title', $plxPlugin->getParam('titreComics').' - page '.$Pagecomics );
							// ajout du titre 
							$xpath = new DOMXPath($pageXHTML);    
							$results = $xpath->query('/html/head');   
							$head = $results->item(0);
							$head->appendChild($title);							
								
							//ajout contenu	   
							$results = $xpath->query('/html/body');
							$body=$results->item(0); 
							$img= $pageXHTML->createElement('img');
							$img_attr=$pageXHTML->createAttribute('src');
							$img_attr->value='IMG/'.basename($comicsFile);
							$img->appendChild($img_attr);
							
							$body->appendChild($img);

								$pageXHTML->preserveWhiteSpace = false; 
								$pageXHTML->formatOutput = true;		
								$pageXHTML->xmlStandalone = false;
							$pageCMX= $pageXHTML->saveXML();							
	
							// sauvegarde fichier.
							$plxPlugin->addFiletxt('EPUB/'.$Pagecmx_attr_2->value, $pageCMX, $ebook);
							$plxPlugin->addFiles(PLX_ROOT.'data/medias/'.$plxPlugin->getParam('comicsmedia').'/'.basename($comicsFile),'EPUB/IMG/'.basename($comicsFile),$ebook);
						//unset($pageXHTML);
							//ajout au manifest
							$mtype=  mime_content_type(PLX_ROOT.'data/medias/'.$plxPlugin->getParam('comicsmedia').'/'.basename($comicsFile));
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="img-".$Pagecomics;	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="IMG/".basename($comicsFile);
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value=$mtype;
							$books->appendChild($book_attr_3);
						$manifest->appendChild($books);
							
							//insertion dans le tag  spine
							$spineItem=$opf->createElement('itemref');
							$spineItem_attr= $opf->createAttribute('idref'); 
							$spineItem_attr->value=$Pagecmx_attr_1->value;
							$spineItem->appendChild($spineItem_attr);
						$spine->appendChild($spineItem);							
							
							// Creation reference dans guide
							$ref = $opf->createElement('reference');
							$ref_attr = $opf->createAttribute('href');
							$ref_attr->value=$Pagecmx_attr_2->value;
							$ref->appendChild($ref_attr);
							$ref_attr_1 = $opf->createAttribute('title');
							$ref_attr_1->value=$plxPlugin->getParam('titreComics').' - Page '. $Pagecomics;
							$ref->appendChild($ref_attr_1);
							$ref_attr_2 = $opf->createAttribute('type');
							$ref_attr_2->value="text";
							$ref->appendChild($ref_attr_2);
						$guide->appendChild($ref);
							}											
						}					
					}
					//unset($pageCMX);
				}
				$books =$opf->createElement('item');
					$book_attr_1= $opf->createAttribute('id'); 
					$book_attr_1->value="ncx";	
					$books->appendChild($book_attr_1);
					
					$book_attr_2= $opf->createAttribute('href'); 
					$book_attr_2->value="toc.ncx";
					$books->appendChild($book_attr_2);
					
					$book_attr_3= $opf->createAttribute('media-type'); 
					$book_attr_3->value="application/x-dtbncx+xml";
					$books->appendChild($book_attr_3);	
					
				$manifest->appendChild($books);		
									
					$package->appendChild($manifest);
					$package->appendChild($spine);
					$package->appendChild($guide);
					// fin package 
				$opf->appendChild($package);	

					// enregistrement du fichier dans l'archive.
					$plxPlugin->addFiletxt('EPUB/package.opf', $opf->saveXML(), $ebook);
	
					// enregistrement toc.ncx			
					//nettoyage et re-indentation
					$toc->preserveWhiteSpace = false;	
					$toc->formatOutput = true;	 
					$tdm = $toc->saveXML();
					$tdm = str_replace('<?xml version="1.0"?>','<?xml version="1.0" encoding="utf-8"?>',$tdm);
					// sauvegarde fichier.
					$plxPlugin->addFiletxt('EPUB/toc.ncx',$tdm, $ebook);
					unset($tdm);
							
				$backToTab='&tab=fmode';
				if($var['debugme'] == 1) {				
					echo 'COMICS : debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
					exit; 
				}
		}
		
		if (isset($_POST['doMake'])) { 
			$ebook="";
			//recup categories
			$MyCats=$plxAdmin->aCats;
			//ajout option selection complete
			$plxAdmin->aCats['all'] = array('articles'=> 0 ,'mother'=> 0,'active'=> 1,'menu'=> 'oui','number'=> 12000);
			// replacement au début pour un traitement par lot.
			$plxAdmin->aCats = array_merge(array_splice($plxAdmin->aCats, -1), $plxAdmin->aCats);
			
			// loop sur selection
			foreach ($plxAdmin->aCats as $catNumb => $values) {
				
				// suivi n° page/cat sauvegardées
				$book="0";// categories
				$page="0";// articles
				$taglink=array(); // tableau tag et titres/pages associés pour l'index
				$artTitleNumbered=array(); //tableau des articles vus
				
				$artTag=array();// id article a comparer aux id des tag
				$indexTag =array(); // tableau des tag 
				$imgToFind=array(); // tableau des images 
				if($plxPlugin->getParam($catNumb) ==='1') {
				// formatage du non de fichier (12caractéres+extension)
				if($catNumb =='all')  {
					$ebook= mb_substr(str_ireplace (' ', '',$plxPlugin->remove_accents(str_replace("'", "", $titreTh))), 0, 12). '.epub';
				} else 	{	
					$ebook= mb_substr(str_ireplace (' ', '', $plxPlugin->remove_accents(str_replace("'", "", $values["name"]))), 0, 12). '.epub';
					$titreTh = $values["name"];
				}
					
				// régéneration image couverture à partir des thémes
				 $themeC = $plxPlugin->getParam($catNumb.'-th');
				 $part = $catNumb; 
				/*
				* function makeThemeImg();
				* $themeC repertoire theme choisi
				* array(x,x,x) text color
				* $fontfamily font
				* $titreTh titre book
				* $descTh description
				* 'x.x' coordonnées placement texte sur image
				* $plugin nom du repertoire du plugin 
				*/
				if ($themeC=='th1')  { $plxPlugin->makeThemeImg('th1/' ,'cover1.jpg',array(90,90,90)     , array(125,125,125) , array(92,126,229) ,$RobotoBold,$LatoRegular,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part); }
				if ($themeC=='th2')  { $plxPlugin->makeThemeImg('th2/' ,'cover2.jpg',array(140,0,0)      , array(125,125,125) , array(92,126,229) ,$RobotoBold,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'3.8' ,'1.6','1.05',$plugin,$part); }
				if ($themeC=='th3')  { $plxPlugin->makeThemeImg('th3/' ,'cover3.jpg',array(255,100,200)  , array(125,125,125) , array(92,126,229) ,$RobotoBold,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part); }
				if ($themeC=='th4')  { $plxPlugin->makeThemeImg('th4/' ,'cover4.jpg',array(255,255,255)  , array(255,230,100) , array(250,126,29) ,$RobotoBold,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part); }
				if ($themeC=='th5')  { $plxPlugin->makeThemeImg('th5/' ,'cover5.jpg',array(255,255,255)  , array(0,0,140)     , array(92,126,229) ,$ubuntuMono,$freeSerif  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'7'   ,'1.8','1.05',$plugin,$part); }
				if ($themeC=='th6')  { $plxPlugin->makeThemeImg('th6/' ,'cover6.jpg',array(192,226,229)  , array(255,255,255) , array(192,226,229),$freeSansB ,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part); }
				if ($themeC=='th7')  { $plxPlugin->makeThemeImg('th7/' ,'cover7.jpg',array(150,200,60)   , array(255,255,255) , array(255,255,255),$dislexia  ,$dislexia   ,$freeSerif ,$titreTh,$descTh,$AuthTh,'3.5' ,'1.8','1.05',$plugin,$part); }
				if ($themeC=='th8')  { $plxPlugin->makeThemeImg('th8/' ,'cover8.jpg',array(192,226,229)  , array(255,255,255) , array(192,226,229),$freeSansB ,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part); }
				if ($themeC=='th9')  { $plxPlugin->makeThemeImg('th9/' ,'cover9.jpg',array(234, 205, 159), array(255,255,255) , array(92,126,229) ,$ubuntuMono,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'1.5','1.05',$plugin,$part); }
				if ($themeC=='th10') { $plxPlugin->makeThemeImg('th10/','cover10.jpg',array(230, 82, 1)   , array(77, 165, 232), array(200,200,200),$freeSansB ,$ubuntuMono ,$ubuntuMono,$titreTh,$descTh,$AuthTh,'2.5','1.25','19',$plugin,$part); }

 				
				//destination
					$ebook = $repertoire.'/'.$ebook;
				
				// generation de l'archive
					$initZip = $plxPlugin->makeEmptyZip('mimetype', 'application/epub+zip', $ebook); 
					$once = $plxPlugin->once($initZip);
					$plxPlugin->addRepertory('META-INF', $ebook);
					$plxPlugin->addFiletxt('META-INF/container.xml', $containerXML,  $ebook);
					
				// création de repertoires de contenus et ressources
					$plxPlugin->addRepertory('EPUB',     $ebook);
					$plxPlugin->addRepertory('EPUB/JS',  $ebook);
					$plxPlugin->addRepertory('EPUB/IMG', $ebook);
					$plxPlugin->addRepertory('EPUB/CSS', $ebook);
					$plxPlugin->addRepertory('EPUB/CSS/fonts', $ebook);
				
				
				// creation de la page cover  
				$pagecover = new DOMDocument('1.0', 'utf-8'); 
				$pagecover->preserveWhiteSpace = false; 
				$pagecover->formatOutput = true; 
				$pagecover->loadHTML(mb_convert_encoding($xhtml, 'HTML-ENTITIES', 'UTF-8'));
								
				$title = $pagecover->createElement('title', $titreTh );
				// ajout du titre 
				$xpath = new DOMXPath($pagecover);    
				$results = $xpath->query('/html/head');   
				$head = $results->item(0);
				$head->appendChild($title);
				//ajout contenu	   
				$results = $xpath->query('/html/body');
				$body=$results->item(0); 
				$section= $pagecover->createElement('section');
				$section_attr=$pagecover->createAttribute('epub:type');
				$section_attr->value="cover";
				$section->appendChild($section_attr);
				
				$section_attr_1=$pagecover->createAttribute('title');
				$section_attr_1->value=$titreTh;
				$section->appendChild($section_attr_1);
				
				$section_attr_2=$pagecover->createAttribute('class');
				$section_attr_2->value="cover";
				$section->appendChild($section_attr_2);
				
				
				$img=$pagecover->createElement('img');
				$img_attr_1=$pagecover->createAttribute('src');
				$img_attr_1->value='IMG/cover.jpg';
				$img->appendChild($img_attr_1);
				
				$img_attr_2=$pagecover->createAttribute('style');
				$img_attr_2->value='height:100vh;width:100%;';// à voir
				$img->appendChild($img_attr_2);
				$section->appendChild($img);


				
				$body->appendChild($section);

		// insertion page cover dans archive ebook
				$plxPlugin->addFiletxt('EPUB/cover.xhtml', $pagecover->saveXML(), $ebook);	
				unset($pagecover);
				// creation de la page de titre  
				
				$pagefront = new DOMDocument('1.0', 'utf-8'); 
				$pagefront->preserveWhiteSpace = false; 
				$pagefront->formatOutput = true; 
				$pagefront->loadHTML(mb_convert_encoding($xhtml, 'HTML-ENTITIES', 'UTF-8'));
					
				$title = $pagefront->createElement('title', $titreTh );
				// ajout du titre 
				$xpath = new DOMXPath($pagefront);    
				$results = $xpath->query('/html/head');   
				$head = $results->item(0);
				$head->appendChild($title);
				//ajout contenu	   
				$results = $xpath->query('/html/body');
				$body=$results->item(0); 
				$section= $pagefront->createElement('section');
				$section_attr=$pagefront->createAttribute('epub:type');
				$section_attr->value="frontmatter";
				$section->appendChild($section_attr);
				
				$section_attr_1=$pagefront->createAttribute('title');
				$section_attr_1->value=$titreTh;
				$section->appendChild($section_attr_1);
				
				$section_attr_2=$pagefront->createAttribute('class');
				$section_attr_2->value="front";
				$section->appendChild($section_attr_2);
				
				//titre
				$h1=$pagefront->createElement('h1',$titreTh );
				$section->appendChild($h1);
				//description
				$h2=$pagefront->createElement('h2',$descTh);  //
				
				$span=$pagefront->createElement('span', PHP_EOL .'Pages extraites du site '.$plxAdmin->aConf['title']);
				$h2->appendChild($span);
				$section->appendChild($h2);
				
				$footer=$pagefront->createElement('footer');
				if($var['descLicence'] !=''){
					$div=$pagefront->createElement('div');
					
					// CDATA pour conserver les balises HTML telle quelle.
					$divContent=$pagefront->createCDATASection( $var['descLicence']);
					$div->appendChild($divContent);					
					
					$footer->appendChild($div);
				}
				if($ISSN != null ) {
					$pdep =$pagefront->createElement('p','ISSN :'.$ISSN);
					$footer->appendChild($pdep);
				}
				if($ISBN != null ) {
					$pdep2=$pagefront->createElement('p','ISBN :'.$ISBN);
					$footer->appendChild($pdep2);
				}
				$section->appendChild($footer);
				$body->appendChild($section);
				
				$final =$pagefront->saveXML();
				$final = $plxPlugin->cleanUp($final);
		// insertion page cover dans archive ebook
				$plxPlugin->addFiletxt('EPUB/titlepage.xhtml', $final , $ebook);	
			
			/////////////////////////////////////
			// preparations fichiers epubs
			// $navFile 	= "EPUB/nav.xhtml";
			// $packFile 	= "EPUB/package.opf";
			// $tocFile 	= "EPUB/toc.ncx";
			// $defJsFile 	= "EPUB/JS/script.js"; #todo insert scripts from a selection - scripts to be made too or selected from active theme ?
			// $coverFile	= "EPUB/cover.xhtml";
			$ISSN = $plxPlugin->getParam('issn');
			$ISBN = $plxPlugin->getParam('isbn');
			// 
			// création fichier opf  en utf-8 
					$opf = new DOMDocument('1.0', 'utf-8'); 
					$opf->preserveWhiteSpace = false; 
					$opf->formatOutput = true;
					
			// tag package + attributes
				//create the main tags, without values  + attributes
				$package = $opf->createElement('package'); 
				{	$package_attr_1 = $opf->createAttribute('xmlns'); 
					$package_attr_1->value="http://www.idpf.org/2007/opf";	
					$package->appendChild($package_attr_1);
					
					$package_attr_2 = $opf->createAttribute('version'); 
					$package_attr_2->value="3.0";
					$package->appendChild($package_attr_2);
					
					$package_attr_3 = $opf->createAttribute('unique-identifier'); 
					$package_attr_3->value="uid";
					$package->appendChild($package_attr_3);
					
					$package_attr_4 = $opf->createAttribute('xml:lang'); 
					$package_attr_4->value=$plxAdmin->aConf['default_lang'];	
					$package->appendChild($package_attr_4);
																		
					$package_attr_5 = $opf->createAttribute('prefix'); 
					$package_attr_5->value="cc: ".plxUtils::strCheck($var['urlLicence']); 	// $type_licence
					$package->appendChild($package_attr_5);		
					
					$package_attr_6 = $opf->createAttribute('xmlns:dc');  // bublin core
					$package_attr_6->value="http://purl.org/dc/elements/1.1/"; 	 
					$package->appendChild($package_attr_6);		
					
					$package_attr_7 = $opf->createAttribute('xmlns:dcterms'); 
					$package_attr_7->value="http://purl.org/dc/terms/"; 	 
					$package->appendChild($package_attr_7);
				}

				// metadata
					$metadata = $opf->createElement('metadata');
				{							
					if( $ISBN == null) { $dcId= $plxAdmin->aConf['title']; } else {$dcId= $ISBN;}
						{	$tag_1 = $opf->createElement('dc:identifier', $dcId );
							$tag_1_attr_1 = $opf->createAttribute('id'); 
							$tag_1_attr_1->value="uid";
							$tag_1->appendChild($tag_1_attr_1);
							$metadata->appendChild($tag_1);
						}		
							$tag_2 = $opf->createElement('dc:title', $var['title']);//$title
							$metadata->appendChild($tag_2);

			if($plxPlugin->getParam('triAuthors') =='000' ){
				$autId=1;
				$UsersDesc=array();//reset
						foreach($plxAdmin->aUsers as $_userid => $_user)	{
							if($_user['profil'] >=0 && $_user['profil'] <= 4  && $_user['active'] == 1  && in_array($_user['name'],$AuthorPublished )) {
								$AllUsers[]= $_user['name'];
								if($_user['infos'] !=''){ $UsersDesc[$_user['name']]= $_user['infos'];}// pour traitement ultérieur
								
								$tag_X = $opf->createElement('dc:creator',$_user['name']); 
								$tag_X_attr=$opf->createAttribute('id');
								$tag_X_attr->value="author_".$autId;
								$tag_X->appendChild($tag_X_attr);
							$metadata->appendChild($tag_X);
								
								// <meta refines="#author" property="file-as">Doe, John</meta>
								$tag_Y=$opf->createElement('meta',$_user['name']);
								$tag_Y_attr=$opf->createAttribute('refines');
								$tag_Y_attr->value=$tag_X_attr->value;
								$tag_Y->appendChild($tag_Y_attr);
								$tag_Y_attr1=$opf->createAttribute('property');
								$tag_Y_attr1->value='file-as';
								$tag_Y->appendChild($tag_Y_attr1);
							$metadata->appendChild($tag_Y);	
						$autId++;	
							}				
						}
			}else {
							$tag_3 = $opf->createElement('dc:creator',$plxPlugin->getParam('author')); // $admin name ?
						$metadata->appendChild($tag_3);
			}
	
							$tag_4 = $opf->createElement('dc:language',$plxAdmin->aConf['default_lang'] ); // langue
							$metadata->appendChild($tag_4);
							
							$tag_5 = $opf->createElement('dc:date', date('Y-m-d\Th:i:s\Z') ); 
							$metadata->appendChild($tag_5);

							$tag_12 = $opf->createElement('meta', date('Y-m-d\Th:i:s\Z')); // 
							$tag_12_attr_1 = $opf->createAttribute('property'); 
							$tag_12_attr_1->value="dcterms:modified";
							$tag_12->appendChild($tag_12_attr_1);
							$metadata->appendChild($tag_12);
							
							$tag_6 = $opf->createElement('dc:description',$var['subtitle']); // $subtitle
							$metadata->appendChild($tag_6); 
							
							$tag_7 = $opf->createElement('dc:rights',$var['licence']); // $licence														
							$metadata->appendChild($tag_7);// ou  LicenseDocument  et RightsStatement  ??
							
							$tag_11 = $opf->createElement('link'); // 
							$tag_11_attr_1 = $opf->createAttribute('rel'); 
							$tag_11_attr_1->value="dcterms:rights";
							$tag_11->appendChild($tag_11_attr_1);
							$tag_11_attr_2 = $opf->createAttribute('href'); 
							$tag_11_attr_2->value=$var['urlLicence'];
							$tag_11->appendChild($tag_11_attr_2);
							$metadata->appendChild($tag_11);
							
							$tag_8 = $opf->createElement('link'); // $licence
							$tag_8_attr_1 = $opf->createAttribute('rel'); 
							$tag_8_attr_1->value="cc:licence";
							$tag_8->appendChild($tag_8_attr_1);
							$tag_8_attr_2 = $opf->createAttribute('href'); 
							$tag_8_attr_2->value=$var['urlLicence'];
							$tag_8->appendChild($tag_8_attr_2);
							$metadata->appendChild($tag_8);
							
							$tag_9 = $opf->createElement('meta',$plxAdmin->racine ); // 
							$tag_9_attr_1 = $opf->createAttribute('property'); 
							$tag_9_attr_1->value="cc:attributionURL";
							$tag_9->appendChild($tag_9_attr_1);
							$metadata->appendChild($tag_9);
						
							$tag_10 = $opf->createElement('meta'); 
							$tag_10_attr_1 = $opf->createAttribute('name'); 
							$tag_10_attr_1->value="cover";
							$tag_10->appendChild($tag_10_attr_1);
							$tag_10_attr_2 = $opf->createAttribute('content'); 
							$tag_10_attr_2->value=$tag_10_attr_1->value;
							$tag_10->appendChild($tag_10_attr_2);
							$metadata->appendChild($tag_10);
							
					 if($ISSN != null ) {	
						$tag_13 = $opf->createElement('dc:identifier',$ISSN); // 
							$tag_13_attr_1 = $opf->createAttribute('id'); 
							$tag_13_attr_1->value='issn';
							$tag_13->appendChild($tag_13_attr_1);
							$metadata->appendChild($tag_13);
					 }	
					 if($ISBN != null ) {	
						$tag_131 = $opf->createElement('dc:identifier',$ISBN); // 
							$tag_131_attr_1 = $opf->createAttribute('id'); 
							$tag_131_attr_1->value="isbn";
							$tag_131->appendChild($tag_131_attr_1);
							$metadata->appendChild($tag_131);
					 } 		
							$tag_14 = $opf->createElement('meta'); // pour epub2
							$tag_14_attr_1 = $opf->createAttribute('name'); 
							$tag_14_attr_1->value="cover";
							$tag_14->appendChild($tag_14_attr_1);
							$tag_14_attr_2 = $opf->createAttribute('content'); 
							$tag_14_attr_2->value="IMG/cover.png";
							$tag_14->appendChild($tag_14_attr_2);
							$metadata->appendChild($tag_14); 
							 					
				}	

				// enregistre metadata
					$package->appendChild($metadata);
					
				// alimentation tags manifest , spine, guide, fichier toc et page 
				{
					{ // fichiers communs
						$manifest = $opf->createElement('manifest'); 
						
						// ajout cover.xhtml							
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="cover";	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="cover.xhtml";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="application/xhtml+xml";
							$books->appendChild($book_attr_3);	
							$book_attr_4= $opf->createAttribute('properties'); 
							$book_attr_4->value="scripted";
							$books->appendChild($book_attr_4);		
						$manifest->appendChild($books);	
						
						// ajout titlepage.xhtml							
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="titlepage";	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="titlepage.xhtml";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="application/xhtml+xml";
							$books->appendChild($book_attr_3);	
							$book_attr_4= $opf->createAttribute('properties'); 
							$book_attr_4->value="scripted";
							$books->appendChild($book_attr_4);	
						$manifest->appendChild($books);
					
					// image couverture					
					$plxPlugin->addFiles(PLX_ROOT.'/plugins/'.$plugin.'/covers/'.$themeC.'/cover.jpg','EPUB/IMG/cover.jpg',$ebook);
					
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="coverImg";	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="IMG/cover.jpg";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="image/jpeg";
							$books->appendChild($book_attr_3);	
							$book_attr_4= $opf->createAttribute('properties'); 
							$book_attr_4->value="cover-image";
							$books->appendChild($book_attr_4);	
						$manifest->appendChild($books);
							
					// ajout font et inscription au manifest
					//addFontDirectories($path, $zipfile, $opf, $id,$manifest) 
					$fontCSS= file_get_contents(PLX_ROOT.'/plugins/'.$plugin.'/covers/'.$themeC.'/fonts.css');
					$plxPlugin->addFontDirectories(PLX_ROOT.'/plugins/'.$plugin.'/covers/'.$themeC.'/fonts', $ebook, $opf, 'font',$manifest, $fontCSS);

{				// ajout fichier CSS/commun
				$cssID="1";
					$plxPlugin->addFiletxt('EPUB/CSS/epub.css', $baseCSS,   $ebook);
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="CSS-".$cssID;	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="CSS/epub.css";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="text/css";
							$books->appendChild($book_attr_3);
						$manifest->appendChild($books);
				$cssID++;
					$plxPlugin->addFiles(PLX_ROOT.'/plugins/'.$plugin.'/covers/'.$themeC.'/fonts.css','EPUB/CSS/fonts.css',$ebook);
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="CSS-".$cssID;	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="CSS/fonts.css";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="text/css";
							$books->appendChild($book_attr_3);
						$manifest->appendChild($books);
				$cssID++;
					$plxPlugin->addFiletxt('EPUB/CSS/commun.css', $communCSS,   $ebook);
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="CSS-".$cssID;	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="CSS/commun.css";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="text/css";
							$books->appendChild($book_attr_3);
						$manifest->appendChild($books);
				$cssID++;
					$plxPlugin->addFiles(PLX_ROOT.'/plugins/'.$plugin.'/covers/'.$themeC.'/theme.css','EPUB/CSS/theme.css',$ebook);
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="CSS-".$cssID;	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="CSS/theme.css";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="text/css";
							$books->appendChild($book_attr_3);
						$manifest->appendChild($books);
				//$cssID++; for next ones if any more CSS file to add
}

					//creation spine
						$spine = $opf->createElement('spine');  
						
						//ajout cover
							$spineItem=$opf->createElement('itemref');
							$spineItem_attr= $opf->createAttribute('idref'); 
							$spineItem_attr->value="cover";
							$spineItem->appendChild($spineItem_attr);
							$spineItem_attr_2= $opf->createAttribute('linear'); 
							$spineItem_attr_2->value="no";
							$spineItem->appendChild($spineItem_attr_2);
						$spine->appendChild($spineItem);
						
						//ajout titlepage
							$spineItem=$opf->createElement('itemref');
							$spineItem_attr= $opf->createAttribute('idref'); 
							$spineItem_attr->value="titlepage";
							$spineItem->appendChild($spineItem_attr);
							$spineItem_attr_2= $opf->createAttribute('linear'); 
							$spineItem_attr_2->value="yes";
							$spineItem->appendChild($spineItem_attr_2);
						$spine->appendChild($spineItem); 


					// préparation creation guide pour liseuse epub2	
						$guide =$opf->createElement('guide');
						
							$ref = $opf->createElement('reference');
							$ref_attr = $opf->createAttribute('href');
							$ref_attr->value="cover.xhtml";
							$ref->appendChild($ref_attr);
							$ref_attr_1 = $opf->createAttribute('title');
							$ref_attr_1->value="cover";
							$ref->appendChild($ref_attr_1);
							$ref_attr_2 = $opf->createAttribute('type');
							$ref_attr_2->value="cover";
							$ref->appendChild($ref_attr_2);
						$guide->appendChild($ref);
						
							$ref = $opf->createElement('reference');
							$ref_attr = $opf->createAttribute('href');
							$ref_attr->value="titlepage.xhtml";
							$ref->appendChild($ref_attr);
							$ref_attr_1 = $opf->createAttribute('title');
							$ref_attr_1->value="title-page";
							$ref->appendChild($ref_attr_1);
							$ref_attr_2 = $opf->createAttribute('type');
							$ref_attr_2->value="title-page";
							$ref->appendChild($ref_attr_2);
						$guide->appendChild($ref);
							

							

							// si le fichier n'existe pas, en mettre un vide // modifier si besion
							// voir si test dans autre sous-dossiers peut-etre pertinent sans en faire de trop 
							if (file_exists($themePath.'/script.js')) {
					$plxPlugin->addFiles($themePath.'/script.js','EPUB/JS/script.js',$ebook);
							} else {
					$plxPlugin->addFiletxt('EPUB/JS/script.js', '', $ebook);
							}
							
							$books =$opf->createElement('item');
							$book_attr_1= $opf->createAttribute('id'); 
							$book_attr_1->value="javascript";	
							$books->appendChild($book_attr_1);
							$book_attr_2= $opf->createAttribute('href'); 
							$book_attr_2->value="JS/script.js";
							$books->appendChild($book_attr_2);
							$book_attr_3= $opf->createAttribute('media-type'); 
							$book_attr_3->value="text/javascript";
							$books->appendChild($book_attr_3);	
					$manifest->appendChild($books);			
								
						}//fin add data/medias
					
					} 
					// fin fichiers communs

							// creation item  nav dans manifest:  
							{		
							$nav =$opf->createElement('item');
							$nav_attr_1= $opf->createAttribute('id'); 
							$nav_attr_1->value="htmldoc";	
							$nav->appendChild($nav_attr_1);
							
							$nav_attr_2= $opf->createAttribute('href'); 
							$nav_attr_2->value="nav.xhtml";
							$nav->appendChild($nav_attr_2);
							
							$nav_attr_3= $opf->createAttribute('media-type'); 
							$nav_attr_3->value="application/xhtml+xml";
							$nav->appendChild($nav_attr_3);
							
							$nav_attr_4= $opf->createAttribute('properties'); 
							$nav_attr_4->value="nav scripted";
							$nav->appendChild($nav_attr_4);	
							$manifest->appendChild($nav);
							}
		 
							// creation item  nav dans spine:  
							{
							$spineItem=$opf->createElement('itemref');
							$spineItem_attr= $opf->createAttribute('idref'); 
							$spineItem_attr->value=$nav_attr_1->value;
							$spineItem->appendChild($spineItem_attr);
							$spineItem_attr_2= $opf->createAttribute('linear'); 
							$spineItem_attr_2->value="no";
							$spineItem->appendChild($spineItem_attr_2);
							$spine->appendChild($spineItem);		
							}
							
							// Creation reference dans guide
							{
							$ref = $opf->createElement('reference');
							$ref_attr = $opf->createAttribute('href');
							$ref_attr->value="nav.xhtml";
							$ref->appendChild($ref_attr);
							$ref_attr_1 = $opf->createAttribute('title');
							$ref_attr_1->value="navigation";
							$ref->appendChild($ref_attr_1);
							$ref_attr_2 = $opf->createAttribute('type');
							$ref_attr_2->value="text";
							$ref->appendChild($ref_attr_2);
							$guide->appendChild($ref);
							}

							// creation et preparation page nav.xhtml  $pagenav
							{
							
									$pagenav = new DOMDocument('1.0', 'utf-8'); 
									$pagenav->loadHTML($xhtml);
									$title = $pagenav->createElement('title', 'Table Des Matières' );
									// ajout du titre 
									$xpath = new DOMXPath($pagenav);    
									$results = $xpath->query('/html/head');   
									$head = $results->item(0);
									$head->appendChild($title);				
									
									//ajout base contenu	   
									$results = $xpath->query('/html/body');
									$bodynav=$results->item(0); 

									$sectionNav=$pagenav->createElement('section');
									$sectionNav_attr=$pagenav->createAttribute('class');
									$sectionNav_attr->value="tdm";
									$sectionNav->appendChild($sectionNav_attr);
									
									$h1=$pagenav->createElement('h1',$titreTh.' -  Table Des Matières' );
									$sectionNav->appendChild($h1);
									
									$nav= $pagenav->createElement('nav');
									$nav_attr=$pagenav->createAttribute('epub:type');
									$nav_attr->value="toc";
									$nav->appendChild($nav_attr);
									$nav_attr_1=$pagenav->createAttribute('id');
									$nav_attr_1->value="guide";
									$nav->appendChild($nav_attr_1);
									
									$h2=$pagenav->createElement('h2',' Table des matières.' );
									$nav->appendChild($h2);
									
									$ol=$pagenav->createElement('ol');
									$ol_attr=$pagenav->createAttribute('epub:type');
									$ol_attr->value="list";
									$ol->appendChild($ol_attr);
									$nav->appendChild($ol);// li generées au cours de la découverte des pages constituant le Epub.
							}	
		
							// création et préparation de la table des matieres format epub2   toc.ncx
							{	$play=1;
								$toc= new DOMDocument('1.0','utf-8');
								$toc->loadXML($tocNcx);

								$navMap = $toc->getElementsbyTagName('navMap')->item(0);
								
								// creation du premier point de navigation vers la page de couverture.
								$nP=$toc->createElement('navPoint');
								$nP_attr=$toc->createAttribute('id');
								$nP_attr->value='num-'.$play;
								$nP->appendChild($nP_attr);
								$nP_attr_1=$toc->createAttribute('playOrder');
								$nP_attr_1->value=$play;
								$nP->appendChild($nP_attr_1);
								
								$nl=$toc->createElement('navLabel');
								$txt=$toc->createElement('text', $titreTh );
								$nl->appendChild($txt);
								$nP->appendChild($nl);

								$ct=$toc->createElement('content' );
								$nP->appendChild($ct);
								$ct_attr=$toc->createAttribute('src');
								$ct_attr->value='titlepage.xhtml';
								$ct->appendChild($ct_attr);		
								$nP->appendChild($ct);
								// fin premier point de navigation
								
								// ajout à navPoint
								$toc->appendChild($nP);
								
								// ajout navPoint dans navMap
								$navMap->appendChild($nP);
								
								//ne pas oublier en fin de boucle :			$toc->appendChild($navMap);
								
							}
			
							// ajout attribut toc="ncx" a spine pour declarer la tdm epub2
							{
							$spine_attr= $opf->createAttribute('toc'); 
							$spine_attr->value="ncx";
							$spine->appendChild($spine_attr);
							}		
					
							{// ajout page annexe en début
							$PageAnnexe=0;
							// parcours config page annexe en début d'epub

							$topAnnexe=array( "pageDedicace" => "pagededicaceId", "pageForeword" => "pageforewordId", "pagePreface" => "pageprefaceId" );
							foreach($topAnnexe as $pageA => $pageA_Id) {	
							if ($plxPlugin->getParam($pageA) == 1 ) {// création page et inscription dans la navigation et au manifest
								$PageAnnexe++;
								$play++;
								{
									
							// ajout references categories 
							$PageAx =$opf->createElement('item');
							$PageAx_attr_1= $opf->createAttribute('id'); 
							$PageAx_attr_1->value="annexe-".$PageAnnexe;
							
							$PageAx->appendChild($PageAx_attr_1);
							$PageAx_attr_2= $opf->createAttribute('href'); 
							$PageAx_attr_2->value="annexe-".$PageAnnexe.".xhtml";
							$PageAx->appendChild($PageAx_attr_2);
							$PageAx_attr_3= $opf->createAttribute('media-type'); 
							$PageAx_attr_3->value="application/xhtml+xml";
							$PageAx->appendChild($PageAx_attr_3);
								
							$PageAx_attr_4= $opf->createAttribute('properties'); 
							$PageAx_attr_4->value="scripted";
							$PageAx->appendChild($PageAx_attr_4);	
							
							//insertion dans le tag manifest
						$manifest->appendChild($PageAx);
							
							// ajout dans toc.ncx
							
							{// creation du point de navigation vers la page annexe
							$nP=$toc->createElement('navPoint');
							$nP_attr=$toc->createAttribute('id');
							$nP_attr->value='num-'.$play;
							$nP->appendChild($nP_attr);
							$nP_attr_1=$toc->createAttribute('playOrder');
							$nP_attr_1->value=$play;
							$nP->appendChild($nP_attr_1);
							
							$nl=$toc->createElement('navLabel');
							$txt=$toc->createElement('text', $plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name']);
							$nl->appendChild($txt);
							$nP->appendChild($nl);

							$ct=$toc->createElement('content' );
							$nP->appendChild($ct);
							$ct_attr=$toc->createAttribute('src');
							$ct_attr->value=$PageAx_attr_2->value;
							$ct->appendChild($ct_attr);		
							$nP->appendChild($ct);
							// fin premier point de navigation
							
							// ajout à navPoint
							$toc->appendChild($nP);
							
							// ajout navPoint dans navMap
							$navMap->appendChild($nP);
							}
							
							{//CREATION PAGE et injection TITRE ET DESCRIPTION CATEGORIES
							$pageXHTML = new DOMDocument('1.0', 'utf-8'); 
							$pageXHTML->loadHTML($xhtml);
							$title = $pageXHTML->createElement('title',$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'] );
							// ajout du titre 
							$xpath = new DOMXPath($pageXHTML);    
							$results = $xpath->query('/html/head');   
							$head = $results->item(0);
							$head->appendChild($title);
							
								
							//ajout contenu	   
							$results = $xpath->query('/html/body');
							$body=$results->item(0); 
							$section= $pageXHTML->createElement('section');
							$section_attr=$pageXHTML->createAttribute('epub:type');
							$section_attr->value=$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'];
							$section->appendChild($section_attr);
							
							$section_attr_1=$pageXHTML->createAttribute('title');
							$section_attr_1->value=$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'];
							$section->appendChild($section_attr_1);
							
							$section_attr_2=$pageXHTML->createAttribute('class');
							$section_attr_2->value="annexe";
							$section->appendChild($section_attr_2);
							
							$h1=$pageXHTML->createElement('h1',$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name']);
							$section->appendChild($h1);
														
							$div=$pageXHTML->createElement('div');
							// recuperation page statique interpretée;
										ob_start();
										require PLX_ROOT.$plxAdmin->aConf['racine_statiques'].str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT).'.'.$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['url'].'.php';
										$output= ob_get_clean();				
							 
							// CDATA pour conserver les balises HTML telle quelle.
							$divContent=$pageXHTML->createCDATASection( $output );
							$div->appendChild($divContent);		
									
							$div_attr_1=$pageXHTML->createAttribute('title');
							$div_attr_1->value='dc: '.$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'];
							$div->appendChild($div_attr_1);
							
							$section->appendChild($div);
							
							$body->appendChild($section);

								$pageXHTML->preserveWhiteSpace = false; 
								$pageXHTML->formatOutput = true;		
								$pageXHTML->xmlStandalone = false;
							$pageAnx= $pageXHTML->saveXML();
								//on nettoie
							$Anxpage = $plxPlugin->cleanUp( $pageAnx);
							
	//look for src image to upload to epub archive. If possible, it will donwload external image.
							$doc = new DOMDocument();
							libxml_use_internal_errors(true);
							$doc->loadHTML( $Anxpage );
							
							$xpath2 = new DOMXPath($doc);

							// passage src pour img
							$imgs = $xpath2->query("//img");

							for ($i=0; $i < $imgs->length; $i++) {
								$img = $imgs->item($i);
								$src = $img->getAttribute("src");
								$extension = pathinfo($src, PATHINFO_EXTENSION);
								if(substr($src, 0, 4) =='http' /*&& $extension !=null*/) {
								$plxPlugin->downloadRessource($src);
								$newSrc = 'data/medias/'.basename($src);
								$img->setAttribute('src', $newSrc); 
								}															
								$imgToFind[]= basename($src);// will overwrite downloaded img if same file's name  found 
							}
							$imgToFind = array_unique($imgToFind);								
							$annexe = $doc->saveXML($doc->documentElement);	
							$finish ='<?xml version="1.0" encoding="utf-8" standalone="no"?>'. PHP_EOL . '<!DOCTYPE html>' . PHP_EOL .  $plxPlugin->cleanUp( $annexe);	
												
							// sauvegarde fichier.
							$plxPlugin->addFiletxt('EPUB/'.$PageAx_attr_2->value, $finish, $ebook);

							//Alimentation nav toc 	
							$li=$pagenav->createElement('li');		
								$a=$pagenav->createElement('a',$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name']);
								$a_attr=$pagenav->createAttribute('href');
								$a_attr->value=$PageAx_attr_2->value;
								$a->appendChild($a_attr);
							$li->appendChild($a);								
							$ol->appendChild($li);			

							
							//insertion dans le tag  spine
							$spineItem=$opf->createElement('itemref');
							$spineItem_attr= $opf->createAttribute('idref'); 
							$spineItem_attr->value=$PageAx_attr_1->value;
							$spineItem->appendChild($spineItem_attr);
						$spine->appendChild($spineItem);
							
							
							// Creation reference dans guide
							$ref = $opf->createElement('reference');
							$ref_attr = $opf->createAttribute('href');
							$ref_attr->value=$PageAx_attr_2->value;
							$ref->appendChild($ref_attr);
							$ref_attr_1 = $opf->createAttribute('title');
							$ref_attr_1->value=$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'];
							$ref->appendChild($ref_attr_1);
							$ref_attr_2 = $opf->createAttribute('type');
							$ref_attr_2->value="text";
							$ref->appendChild($ref_attr_2);
						$guide->appendChild($ref);
								  }											
								}
							  }	
							}	
							}// fin annexe début									
								
							{// ajout categorie et article rattachés ,  enregistrement au fichier opf/toc et ajout fichiers à l'archives.
							
							// preparation  à la boucle de créations des pages et leurs réferences dans l'epub
							$catSort=true;
							foreach ($MyCats as $myCatNumb => $catValues) {
								if (!array_key_exists("mother",reset($MyCats))){$catValues['daughterOf'] = $catNumb;$catValues['mother'] = 0;$catSort=false;}//compatibilité si  Plugin plx-gc-catégorie absent
								// recherche sur categories actives, contenant au moins un article et au menu.
								if(isset($catValues['active'])) {
									if(	$catValues['active']=='1' and $catValues['menu'] == 'oui' and $catValues['articles'] >=1 ) {
										// on verifie si 
										//la boucle en cours est sur la selection 'all' 
										// selection autre que 'all'  mais daughterOf de la boucle en cours 
										// si selection not 'all' et  categorie pas 'mother' mais identique à la boucle en cours 
										if(($catNumb !=='all' && $catValues['daughterOf'] === $catNumb && $catSort === true ) or ($catNumb ==='all') or ($myCatNumb === $catNumb  && $catSort === true) or($catNumb !=='all' && $myCatNumb === $catNumb && $catSort === false)){
									$book++;
									$play++;

										// ajout references categories 
										$books =$opf->createElement('item');
										$book_attr_1= $opf->createAttribute('id'); 
										$book_attr_1->value="chapitre-".$book;
										
										$books->appendChild($book_attr_1);
										$book_attr_2= $opf->createAttribute('href'); 
										$book_attr_2->value="chapitre-".$book.".xhtml";
										$books->appendChild($book_attr_2);
										$book_attr_3= $opf->createAttribute('media-type'); 
										$book_attr_3->value="application/xhtml+xml";
										$books->appendChild($book_attr_3);
											
										$book_attr_4= $opf->createAttribute('properties'); 
										$book_attr_4->value="scripted";
										$books->appendChild($book_attr_4);	
										
										//insertion dans le tag manifest
									$manifest->appendChild($books);
										
										// ajout dans toc.ncx
										
										{// creation du point de navigation vers la page de chapitre.
										$nP=$toc->createElement('navPoint');
										$nP_attr=$toc->createAttribute('id');
										$nP_attr->value='num-'.$play;
										$nP->appendChild($nP_attr);
										$nP_attr_1=$toc->createAttribute('playOrder');
										$nP_attr_1->value=$play;
										$nP->appendChild($nP_attr_1);
										
										$nl=$toc->createElement('navLabel');
										$txt=$toc->createElement('text', $catValues["name"] );
										$nl->appendChild($txt);
										$nP->appendChild($nl);

										$ct=$toc->createElement('content' );
										$nP->appendChild($ct);
										$ct_attr=$toc->createAttribute('src');
										$ct_attr->value=$book_attr_2->value;
										$ct->appendChild($ct_attr);		
										$nP->appendChild($ct);
										// fin premier point de navigation
										
										// ajout à navPoint
										$toc->appendChild($nP);
										
										// ajout navPoint dans navMap
										$navMap->appendChild($nP);

										}			


										//CREATION PAGE et injection TITRE ET DESCRIPTION CATEGORIES
										$pageXHTML = new DOMDocument('1.0', 'utf-8'); 
										$pageXHTML->loadHTML($xhtml);
										$title = $pageXHTML->createElement('title',$catValues["name"] );
										// ajout du titre 
										$xpath = new DOMXPath($pageXHTML);    
										$results = $xpath->query('/html/head');   
										$head = $results->item(0);
										$head->appendChild($title);
										
											
										//ajout contenu	   
										$results = $xpath->query('/html/body');
										$body=$results->item(0); 
										$section= $pageXHTML->createElement('section');
										$section_attr=$pageXHTML->createAttribute('epub:type');
										$section_attr->value="chapter";
										$section->appendChild($section_attr);
										
										$section_attr_1=$pageXHTML->createAttribute('title');
										$section_attr_1->value=$catValues["name"];
										$section->appendChild($section_attr_1);
										
										$section_attr_2=$pageXHTML->createAttribute('class');
										$section_attr_2->value="chapter";
										$section->appendChild($section_attr_2);
										
										$h1=$pageXHTML->createElement('h1',$catValues["name"] );
										$section->appendChild($h1);
										
			
										
										$div=$pageXHTML->createElement('div');
										// CDATA pour conserver les balises HTML telle quelle.
										$divContent=$pageXHTML->createCDATASection( $catValues['description']);
										$div->appendChild($divContent);		
												
										$div_attr_1=$pageXHTML->createAttribute('title');
										$div_attr_1->value='dc: '.$catValues["name"];
										$div->appendChild($div_attr_1);
										$section->appendChild($div);
										
										
										$body->appendChild($section);
										
										$pageXHTML->preserveWhiteSpace = false; 
										$pageXHTML->formatOutput = true;		
										$pageXHTML->xmlStandalone = false;
										
										$pagecat=   $pageXHTML->saveXML();
										//on nettoie
										$catbook = $plxPlugin->cleanUp( $pagecat);
										// sauvegarde fichier.
										$plxPlugin->addFiletxt('EPUB/'.$book_attr_2->value, $catbook, $ebook);

										//Alimentation nav toc 		
										

										$li=$pagenav->createElement('li');
										
										if(isset($catValues['mother']) && $catValues['mother'] =='1') {
											$li_attr=$pagenav->createAttribute('class');
											$li_attr->value="mother";
											$li->appendChild($li_attr);										
										}
										
										$ol->appendChild($li);		
												
											$a=$pagenav->createElement('a',$catValues["name"]);
											$a_attr=$pagenav->createAttribute('href');
											$a_attr->value=$book_attr_2->value;
											$a->appendChild($a_attr);
										$li->appendChild($a);
												



										//insertion dans le tag  spine
										$spineItem=$opf->createElement('itemref');
										$spineItem_attr= $opf->createAttribute('idref'); 
										$spineItem_attr->value=$book_attr_1->value;
										$spineItem->appendChild($spineItem_attr);
									$spine->appendChild($spineItem);
										
										
										// Creation reference dans guide
										$ref = $opf->createElement('reference');
										$ref_attr = $opf->createAttribute('href');
										$ref_attr->value=$book_attr_2->value;
										$ref->appendChild($ref_attr);
										$ref_attr_1 = $opf->createAttribute('title');
										$ref_attr_1->value=$catValues["name"];
										$ref->appendChild($ref_attr_1);
										$ref_attr_2 = $opf->createAttribute('type');
										$ref_attr_2->value="text";
										$ref->appendChild($ref_attr_2);
									$guide->appendChild($ref);
										
										
									if($catValues['mother'] !=='1') {

										$li_attr=$pagenav->createAttribute('class');
										$li_attr->value="main";				
										$li->appendChild($li_attr);

									
										// preparation motif extraction article de la catégorie.
										$motif = '#^\d{4}.(?:\d|,)*(?:'.$myCatNumb.')(?:\d|,)*.\d{3}.\d{12}.[\w-]+.xml$#';

										if($aFiles = $plxAdmin->plxGlob_arts->query($motif,'art',$plxAdmin->tri,0,9999,'all')) {	
										// alimentation nav toc sous niveau articles
										//$details=$pagenav->createElement('details');
										//$sum=$pagenav->createElement('summary', $values["articles"].' page(s)');
										//$details->appendChild($sum);
										$olol=$pagenav->createElement('ol');									
										$olol_attr=$pagenav->createAttribute('epub:type');
										$olol_attr->value="list";
										$olol->appendChild($olol_attr);
															
													# On analyse tous les fichiers
													$artsList = array();
													// on vide le tableau $artTag si déjà alimenté
													//$artTag=array();
													
													foreach($aFiles as $v) {
													$page++;
														$art = $plxAdmin->parseArticle(PLX_ROOT . $plxAdmin->aConf['racine_articles'] . $v);
														if(!empty($art)) {
														 $art["title"] = str_replace(' & ', ' &amp; ', $art["title"]);
														$okay=true;
														// preparation page index
															$artTag[mb_substr($v,0,4)] = mb_substr($v,0,4);// on garde le numero d'article uniquement 
															$artTag = array_unique($artTag); // une seule fois !
															
															
														// recherche info et nommage fichier pour insertion dans manifest 
															$books =$opf->createElement('item');	
															
															$book_attr_1= $opf->createAttribute('id'); 
															$book_attr_1->value="page-".$page;
															$books->appendChild($book_attr_1);
															
															$book_attr_2= $opf->createAttribute('href'); 
															$book_attr_2->value="page-".$page.".xhtml";
															$books->appendChild($book_attr_2);
															
															$book_attr_3= $opf->createAttribute('media-type'); 
															$book_attr_3->value="application/xhtml+xml";
															$books->appendChild($book_attr_3);
																			
															$book_attr_4= $opf->createAttribute('properties'); 
															$book_attr_4->value="scripted";
															$books->appendChild($book_attr_4);	
															
														$manifest->appendChild($books);	
															
														// insertion dans spine pour ordre de lecture	
															$spineItem=$opf->createElement('itemref');
															$spineItem_attr= $opf->createAttribute('idref'); 
															$spineItem_attr->value=$book_attr_1->value;
															$spineItem->appendChild($spineItem_attr);
															
														$spine->appendChild($spineItem);


														// Creation reference dans guide
															$ref = $opf->createElement('reference');
															$ref_attr = $opf->createAttribute('href');
															$ref_attr->value=$book_attr_2->value;
															$ref->appendChild($ref_attr);
															$ref_attr_1 = $opf->createAttribute('title');
															$ref_attr_1->value=$art["title"];
															$ref->appendChild($ref_attr_1);
															$ref_attr_2 = $opf->createAttribute('type');
															$ref_attr_2->value="text";
															$ref->appendChild($ref_attr_2);
														$guide->appendChild($ref);
															
																					
														//CREATION PAGE pour le fichier
																$pageXHTML = new DOMDocument('1.0', 'utf-8'); 
																$pageXHTML->loadHTML($xhtml);
																
																// ajout du titre 
																$title = $pageXHTML->createElement('title');
																$title_data = $pageXHTML->createCDATASection( $art["title"] );
																$title->appendChild($title_data);
																$xpath = new DOMXPath($pageXHTML);    
																$results = $xpath->query('/html/head');   
																$head = $results->item(0);
																$head->appendChild($title);
																
																//ajout contenu	   
																$results = $xpath->query('/html/body');
																$body=$results->item(0); 
														if($page==1) {
															$body_attr=$pageXHTML->createAttribute('epub:type');
															$body_attr->value='bodymatter';
															$body->appendChild($body_attr);														
														}
																$section= $pageXHTML->createElement('section');
																$section_attr=$pageXHTML->createAttribute('epub:type');
																$section_attr->value="division";
																$section->appendChild($section_attr);
																
																$section_attr_1=$pageXHTML->createAttribute('title');
																$section_attr_1->value=$art["title"];
																$section->appendChild($section_attr_1);
																
																$tdm=$pageXHTML->createElement('a','Table des matieres');
																$tdm_attr=$pageXHTML->createAttribute('href');
																$tdm_attr->value='nav.xhtml';
																$tdm->appendChild($tdm_attr);
																$tdm_attr_2=$pageXHTML->createAttribute('id');
																$tdm_attr_2->value='tdmA';
																$tdm->appendChild($tdm_attr_2);
																$section->appendChild($tdm);
																
																$h2=$pageXHTML->createElement('h2');	
																$h2_data=$pageXHTML->createCDATASection($art["title"]);
																$h2->appendChild($h2_data);
																$section->appendChild($h2);
																
																if($art['thumbnail'] !='') {
																
																		$imgthumb=$pageXHTML->createElement('img' );
																		
																			$imgthumb_attr=$pageXHTML->createAttribute('src');
																			$imgthumb_attr->value=$art['thumbnail'];
																			$imgthumb->appendChild($imgthumb_attr);
																			
																			$imgthumb_attr_2=$pageXHTML->createAttribute('title');
																			$imgthumb_attr_2->value=$art['thumbnail_title'];
																			$imgthumb->appendChild($imgthumb_attr_2);
																			
																			$imgthumb_attr_3=$pageXHTML->createAttribute('alt');
																			$imgthumb_attr_3->value=$art['thumbnail_alt'];
																			$imgthumb->appendChild($imgthumb_attr_3);
																
																
																$section->appendChild($imgthumb);	

																}
																
																$div=$pageXHTML->createElement('div');
																// on retire les script embarqués // voir à mettre en option
																$chapo   = preg_replace('#<script(.*?)>(.*?)</script>#is','', $art['chapo'] );// remove <script>
																$content = preg_replace('#<script(.*?)>(.*?)</script>#is','', $art['content']);// remove <script>
																// CDATA pour conserver les balises HTML telle quelle.
																$divContent=$pageXHTML->createCDATASection('<div class="content">'.$chapo.'</div><div class="content">'.$content.'</div>');
																// on insere dans la page
																$div->appendChild($divContent);
																
																$div_attr_1=$pageXHTML->createAttribute('title');
																$div_attr_1->value='dc: '.$art["title"];
																$div->appendChild($div_attr_1);
																
																$section->appendChild($div);

																/*$img=$pageXHTML->createElement('img');
																$img_attr=$pageXHTML->createAttribute('src');
																$img_attr->value='data/medias/grass.png';			
																$img->appendChild($img_attr);
																
																$img_attr_2=$pageXHTML->createAttribute('id');
																$img_attr_2->value='r7';			
																$img->appendChild($img_attr_2);
																$section->appendChild($img);*/

															
															// on verifie si l'on à déja vu cette page dans une autre catégorie, si oui, on l'exclu du tableaux des mots clé pour ne pas avoir de doublon
															if (!array_search($art["title"], $artTitleNumbered)) {
														// stockage titre et numero de page pour retrouver d'eventuels doublon/articles rattachés à plusieurs catégories.
															$artTitleNumbered[$page] =   $art["title"]  ;								
																
																// recherche tag et alimentation tableau $tagLink
																if($art['tags'] !="") {
																	$extract=  explode( ',', $art['tags']);
																		foreach($extract as $k => $v) {
																			$taglink[ strtolower(trim($v))][]= '<small><a href="'.$book_attr_2->value.'" title="'.$art["title"].'" rel="dc:subject:'.$v.'">'.$art["title"].'</a></small>';
																		}
																}
															}

																$body->appendChild($section);
																
															//nettoyage et indentation
															$pageXHTML->preserveWhiteSpace = false; 
															$pageXHTML->formatOutput = true;		
															$pageXHTML->xmlStandalone = false;
															// nettoyage du doctype et  CDATA en trop
															
															
																$pagearticle=   $pageXHTML->saveXML();
																$pagebook = $plxPlugin->cleanUp($pagearticle);
															
	// on recupere les valeur de src dans les balises img et iframe et data dans object 
	// a faire => srcset / iframes / autres ?
	// $imgToFind[]=" ";// ?? array_search skips first key ? || on oublie :=> array() passée en string .
	// $dataObject to base64.
															$doc = new DOMDocument();
															libxml_use_internal_errors(true);
															$doc->loadHTML( $pagebook );
															
															$xpath = new DOMXPath($doc);
															// passage src pour img
															$imgs = $xpath->query("//img");
															for ($i=0; $i < $imgs->length; $i++) {
																$img = $imgs->item($i);
																$src = $img->getAttribute("src");
																$extension = pathinfo($src, PATHINFO_EXTENSION);
																if(substr($src, 0, 4) =='http' /*&& $extension !=null*/) {
																$plxPlugin->downloadRessource($src);
																$newSrc = 'data/medias/'.basename($src);
																$img->setAttribute('src', $newSrc); 
																}															
																$imgToFind[]= basename($src);// will overwrite downloaded img if same file's name  found 
																$srctrouve[]=basename($src);
															}
															$imgToFind = array_unique($imgToFind);
															
														// traitement balise object en data:text	
															
															//$dataObjects=array();
															$dataObjects = $xpath->query("//object"); 
															if($dataObjects->length >0){
																$clean="false";
															for ($i=0; $i < $dataObjects->length; $i++) {
																$dataObject = $dataObjects->item($i);
																$data = $dataObject->getAttribute("data");
																if(substr($data, 0, 15) ==='data:text/html,') {
																	$data= str_ireplace('data:text/html,', '', $data);																 
																	$clean='true';
																$newVal = base64_encode($data);
																$dataObject->setAttribute('data', 'data:text/plain;charset=utf-8;base64,'.$newVal); 
																}
															}

															}

																$pagebook=   $doc->saveXML($doc->documentElement);															
																$pagebook = '<?xml version="1.0" encoding="utf-8" standalone="no"?>'. PHP_EOL . '<!DOCTYPE html>' . PHP_EOL . $plxPlugin->cleanUp($pagebook);
																
															// ajout page dans archive
																$plxPlugin->addFiletxt('EPUB/'.$book_attr_2->value, $pagebook, $ebook);	
																
																
														// alimentation nav toc sous niveau li et liens 
															$lili = $pagenav->createElement('li');
															$olol->appendChild($lili);			
																$lia=$pagenav->createElement('a');
																$lia_data=$pagenav->createCDATASection($art["title"]);
																$lia->appendChild($lia_data);
																$lia_attr=$pagenav->createAttribute('href');
																$lia_attr->value=$book_attr_2->value;
																$lia->appendChild($lia_attr);
															$lili->appendChild($lia);
														}
													}

												 
												 // ajout sous liste 
										if ($okay) $li->appendChild($olol);
												}
											}									
										}							
									}	
								}
							}	// fin ajout categories et articles rattachés 
							
							} // fin ajout catégories et articles
							
							// creation de la page index 
							if($plxPlugin->getParam('pageIndex') =='1') {
								{
								   $taglist = $plxAdmin->aTags;
								if (!empty($taglist)) {
									// creation index.xhtml
										$index = new DOMDocument('1.0', 'utf-8'); 
										$index->preserveWhiteSpace = false; 
										$index->formatOutput = true;				
										$index->loadHTML(mb_convert_encoding($xhtml, 'HTML-ENTITIES', 'UTF-8'));
										
										// on pointe sur <head> $head				
										$xpath = new DOMXPath($index);    
										$results = $xpath->query('/html/head');   
										$head = $results->item(0);
										
										// ajout du titre 	(title document)			
										$headtitle = $index->createElement('title', $titreTh );
										$head->appendChild($headtitle);
										
										// on pointe sur <body>  $body 
										$results = $xpath->query('/html/body');
										$body=$results->item(0);
										
										// conteneur principal 	<section> $container 		
										$container= $index->createElement('section');
										// attribut(s) du conteneur 
										$container_attr=$index->createAttribute('xml:lang');
										$container_attr->value=$plxAdmin->aConf['default_lang'];
										$container->appendChild($container_attr);
										
										$container_attr_1=$index->createAttribute('title');
										$container_attr_1->value='Index - '.$titreTh;
										$container->appendChild($container_attr_1);
										
										$container_attr_2=$index->createAttribute('id');
										$container_attr_2->value='Book_index';
										$container->appendChild($container_attr_2);
										
										$container_attr_3=$index->createAttribute('epub:type');
										$container_attr_3->value='keyword';
										$container->appendChild($container_attr_3);
										
										
										// remplissage <section> $container 
										// titre page 
										$h1=$index->createElement('h1', 'Index des Mots clés');
										$container->appendChild($h1);
										
										// list
										$dl=$index->createElement('dl');
											$dl_attr=$index->createAttribute('id');
											$dl_attr->value="keywords";
											$dl->appendChild($dl_attr);
										$container->appendChild($dl);
										
										
									// recherche tag dans tags.xml
									$indexTag=array();
										foreach ($taglist as $idx => $tag) {
											// filtre sur page actives a partir du fichier tags.xml et verif si correspond aux pages selectionnées 											
											if( $tag['active'] !='0'  && array_search($idx, $artTag ) ) {
												$extract=  explode( ',', $tag['tags']);
												foreach($extract as $k => $v) {
													if($v !="") {
													$indexTag[] = strtolower(trim($v)); 	
													}
												}
											}
										}							
										// pas de doublon svp
										$indexTag = array_unique($indexTag);

										// tri alphabetique 
										if (class_exists('Collator')) {
											$collator = new Collator($plxAdmin->aConf['default_lang']);
											$collator->asort( $indexTag );
										}
										else {
											setlocale(LC_ALL, '');
											array_multisort($indexTag, SORT_ASC, SORT_LOCALE_STRING);
										}
										
										// ressort les tags un à un  et on prend la premiere lettre trouvée
										$cap="";//init 
										foreach($indexTag as $kit => $vit) { 
											$fltr = mb_substr($vit, 0, 1);// prend les accents 
										
											 ///collecte liens pages taguées 
											 $links="";
											 if(isset($taglink[$vit]) && $taglink[$vit] !='') { 
											 
													 if($plxPlugin->remove_accents($fltr) > $cap) { //si  position dans l'alphabet
													$dt=$index->createElement('dt',$fltr);													
													 if ($fltr ==':') {
														 $itag = $index->createElement('i','pseudo-selecteur');
														 $dt->appendChild($itag);
													 }
													$dl->appendChild($dt);
													 $cap= $fltr;
													}
											
												 foreach($taglink[$vit] as $keywords => $link) {	
												
													 $links .= $link;
												 }
												$dd=$index->createCDATASection('<dd><b>'.$vit.'</b><span>'.$links.'</span></dd>'.PHP_EOL);
												$dl->appendChild($dd);
											 }	


										} 

								}

				if(!empty($indexTag)) {	// si l'on trouvé aucun tag												
											
											//$container->appendChild($dl);
											$body->appendChild($container);

											$pageXHMLIndex =$index->saveXML();
											$pageindex = str_replace('<![CDATA[', '', $pageXHMLIndex);
											$pageindex = str_replace(']]>','',$pageindex );
											// ajout index à :
											// epub zip
											$plxPlugin->addFiletxt('EPUB/index.xhtml', $pageindex, $ebook);	
											
											// nav.xhtml 
									
											$liIndex = $pagenav->createElement('li');
											$ol->appendChild($liIndex);				
												$a=$pagenav->createElement('a','Index');
												$a_attr=$pagenav->createAttribute('href');
												$a_attr->value='index.xhtml';
												$a->appendChild($a_attr);
											$liIndex->appendChild($a);
											$ol->appendChild($liIndex);
											
											// manifest
							
											$keywordIndex =$opf->createElement('item');
											$keywordIndex_attr_1= $opf->createAttribute('id'); 
											$keywordIndex_attr_1->value="keywords";	
											$keywordIndex->appendChild($keywordIndex_attr_1);
											
											$keywordIndex_attr_2= $opf->createAttribute('href'); 
											$keywordIndex_attr_2->value="index.xhtml";
											$keywordIndex->appendChild($keywordIndex_attr_2);
											
											$keywordIndex_attr_3= $opf->createAttribute('media-type'); 
											$keywordIndex_attr_3->value="application/xhtml+xml";
											$keywordIndex->appendChild($keywordIndex_attr_3);
											
											$keywordIndex_attr_4= $opf->createAttribute('properties'); 
											$keywordIndex_attr_4->value="scripted";
											$keywordIndex->appendChild($keywordIndex_attr_4);	
											$manifest->appendChild($keywordIndex);
													
											
											//spine					
											$spineItemIndex=$opf->createElement('itemref');
											$spineItemIndex_attr= $opf->createAttribute('idref'); 
											$spineItemIndex_attr->value=$keywordIndex_attr_1->value;
											$spineItemIndex->appendChild($spineItemIndex_attr);
											$spine->appendChild($spineItemIndex);	
											
											// Creation reference dans guide
												$ref = $opf->createElement('reference');
												$ref_attr = $opf->createAttribute('href');
												$ref_attr->value=$keywordIndex_attr_2->value;
												$ref->appendChild($ref_attr);
												$ref_attr_1 = $opf->createAttribute('title');
												$ref_attr_1->value=$keywordIndex_attr_1->value;
												$ref->appendChild($ref_attr_1);
												$ref_attr_2 = $opf->createAttribute('type');
												$ref_attr_2->value="text";
												$ref->appendChild($ref_attr_2);
											$guide->appendChild($ref);	
}											
								}
							
							} // fin page index 			

							
							{// ajout toc.ncx dans manifest (compatibilité epub2)
							//<item id="ncx" href="ncx/toc.ncx" media-type="application/x-dtbncx+xml"/>
								$books =$opf->createElement('item');
								$book_attr_1= $opf->createAttribute('id'); 
								$book_attr_1->value="ncx";	
								$books->appendChild($book_attr_1);
								
								$book_attr_2= $opf->createAttribute('href'); 
								$book_attr_2->value="toc.ncx";
								$books->appendChild($book_attr_2);
								
								$book_attr_3= $opf->createAttribute('media-type'); 
								$book_attr_3->value="application/x-dtbncx+xml";
								$books->appendChild($book_attr_3);	
								
								$manifest->appendChild($books);
							}

							// création page auteur(s)
							if ($plxPlugin->getParam('pageAuteur') == 1 ) {// création page et inscription dans la navigation et au manifest
									$PageAnnexe++;
									$play++;
									{// ajout opf
									$PageAx =$opf->createElement('item');
									$PageAx_attr_1= $opf->createAttribute('id'); 
									$PageAx_attr_1->value="annexe-".$PageAnnexe;
									
									$PageAx->appendChild($PageAx_attr_1);
									$PageAx_attr_2= $opf->createAttribute('href'); 
									$PageAx_attr_2->value="annexe-".$PageAnnexe.".xhtml";
									$PageAx->appendChild($PageAx_attr_2);
									$PageAx_attr_3= $opf->createAttribute('media-type'); 
									$PageAx_attr_3->value="application/xhtml+xml";
									$PageAx->appendChild($PageAx_attr_3);
										
									$PageAx_attr_4= $opf->createAttribute('properties'); 
									$PageAx_attr_4->value="scripted";
									$PageAx->appendChild($PageAx_attr_4);	
									
									//insertion dans le tag manifest
								$manifest->appendChild($PageAx);
// ajout dans toc.ncx
									
									{// creation du point de navigation vers la page annexe
									$nP=$toc->createElement('navPoint');
									$nP_attr=$toc->createAttribute('id');
									$nP_attr->value='num-'.$play;
									$nP->appendChild($nP_attr);
									$nP_attr_1=$toc->createAttribute('playOrder');
									$nP_attr_1->value=$play;
									$nP->appendChild($nP_attr_1);
									
									$nl=$toc->createElement('navLabel');
									$txt=$toc->createElement('text', 'Page '. $plxPlugin->getLang('L_AUTHOR_S'));
									$nl->appendChild($txt);
									$nP->appendChild($nl);

									$ct=$toc->createElement('content' );
									$nP->appendChild($ct);
									$ct_attr=$toc->createAttribute('src');
									$ct_attr->value=$PageAx_attr_2->value;
									$ct->appendChild($ct_attr);		
									$nP->appendChild($ct);
									// fin premier point de navigation
									
									// ajout à navPoint
									$toc->appendChild($nP);
									
									// ajout navPoint dans navMap
									$navMap->appendChild($nP);
									}
									
									{//CREATION PAGE AUTEUR(S)
									$pageXHTML = new DOMDocument('1.0', 'utf-8'); 
									$pageXHTML->loadHTML($xhtml);
									$title = $pageXHTML->createElement('title','Page '. $plxPlugin->getLang('L_AUTHOR_S'));
									// ajout du titre 
									$xpath = new DOMXPath($pageXHTML);    
									$results = $xpath->query('/html/head');   
									$head = $results->item(0);
									$head->appendChild($title);
									
										
									//ajout contenu	   
									$results = $xpath->query('/html/body');
									$body=$results->item(0); 
									if(isset($UsersDesc) && !empty($UsersDesc)) {	
										foreach($UsersDesc as $author => $infos) {							
											$section= $pageXHTML->createElement('section');
											$section_attr=$pageXHTML->createAttribute('epub:type');
											$section_attr->value="Contributors";
											$section->appendChild($section_attr);
											
											$section_attr_1=$pageXHTML->createAttribute('title');
											$section_attr_1->value= $plxPlugin->getLang('L_AUTHOR').' '.$author;
											$section->appendChild($section_attr_1);
											
											$section_attr_2=$pageXHTML->createAttribute('class');
											$section_attr_2->value="annexe";
											$section->appendChild($section_attr_2);
										
											$h1=$pageXHTML->createElement('h1',$author );
											$section->appendChild($h1);
																		
											$div=$pageXHTML->createElement('div');
											// recuperation champ info user;													
											 
											// CDATA pour conserver les balises HTML telle quelle.
											$divContent=$pageXHTML->createCDATASection( $infos );
											$div->appendChild($divContent);		
													
											$div_attr_1=$pageXHTML->createAttribute('title');
											$div_attr_1->value='dc: contributor '. $author;
											$div->appendChild($div_attr_1);
											
											$section->appendChild($div);
											
											$body->appendChild($section);
										}	
									}
									$pageXHTML->preserveWhiteSpace = false; 
										$pageXHTML->formatOutput = true;		
										$pageXHTML->xmlStandalone = false;
									$pageAnx= $pageXHTML->saveXML();
										//on nettoie
									$Anxpage = $plxPlugin->cleanUp( $pageAnx);
									
			//look for src image to upload to epub archive. If possible, it will donwload external image.
									$doc = new DOMDocument();
									libxml_use_internal_errors(true);
									$doc->loadHTML( $Anxpage );
									
									$xpath2 = new DOMXPath($doc);

									// passage src pour img
									$imgs = $xpath2->query("//img");

									for ($i=0; $i < $imgs->length; $i++) {
										$img = $imgs->item($i);
										$src = $img->getAttribute("src");
										$extension = pathinfo($src, PATHINFO_EXTENSION);
										if(substr($src, 0, 4) =='http' /*&& $extension !=null*/) {
										$plxPlugin->downloadRessource($src);
										$newSrc = 'data/medias/'.basename($src);
										$img->setAttribute('src', $newSrc); 
										}															
										$imgToFind[]= basename($src);// will overwrite downloaded img if same file's name  found 
									}
									$imgToFind = array_unique($imgToFind);								
									$annexe = $doc->saveXML($doc->documentElement);	
									$finish ='<?xml version="1.0" encoding="utf-8" standalone="no"?>'. PHP_EOL . '<!DOCTYPE html>' . PHP_EOL .  $plxPlugin->cleanUp( $annexe);	
														
									// sauvegarde fichier.
									$plxPlugin->addFiletxt('EPUB/'.$PageAx_attr_2->value, $finish, $ebook);

									//Alimentation nav toc 	
									$li=$pagenav->createElement('li');		
										$a=$pagenav->createElement('a','Page '. $plxPlugin->getLang('L_AUTHOR_S'));
										$a_attr=$pagenav->createAttribute('href');
										$a_attr->value=$PageAx_attr_2->value;
										$a->appendChild($a_attr);
									$li->appendChild($a);								
									$ol->appendChild($li);			

									
									//insertion dans le tag  spine
									$spineItem=$opf->createElement('itemref');
									$spineItem_attr= $opf->createAttribute('idref'); 
									$spineItem_attr->value=$PageAx_attr_1->value;
									$spineItem->appendChild($spineItem_attr);
								$spine->appendChild($spineItem);
									
									
									// Creation reference dans guide
									$ref = $opf->createElement('reference');
									$ref_attr = $opf->createAttribute('href');
									$ref_attr->value=$PageAx_attr_2->value;
									$ref->appendChild($ref_attr);
									$ref_attr_1 = $opf->createAttribute('title');
									$ref_attr_1->value='Page '. $plxPlugin->getLang('L_AUTHOR_S');
									$ref->appendChild($ref_attr_1);
									$ref_attr_2 = $opf->createAttribute('type');
									$ref_attr_2->value="text";
									$ref->appendChild($ref_attr_2);
								$guide->appendChild($ref);
									}											
				}				
						}
				

							{// ajout selection statiques
							// parcours des pages elligibles
							foreach ($plxAdmin->aStats as $k => $v) {
									if ($v['active'] == 1 && $v['menu'] == 'oui' && $v['group'] !='ebookAnnexe' ) {
										// extraction des pages statiques selectionnées.
								if( $plxPlugin->getParam('stat-'.$k)==1 ) {
								// build and add that static:
									$PageAnnexe++;
									$play++;
									{
				
								// ajout references categories 
									$PageAx =$opf->createElement('item');
									$PageAx_attr_1= $opf->createAttribute('id'); 
									$PageAx_attr_1->value="annexe-".$PageAnnexe;
									
									$PageAx->appendChild($PageAx_attr_1);
									$PageAx_attr_2= $opf->createAttribute('href'); 
									$PageAx_attr_2->value="annexe-".$PageAnnexe.".xhtml";
									$PageAx->appendChild($PageAx_attr_2);
									$PageAx_attr_3= $opf->createAttribute('media-type'); 
									$PageAx_attr_3->value="application/xhtml+xml";
									$PageAx->appendChild($PageAx_attr_3);
										
									$PageAx_attr_4= $opf->createAttribute('properties'); 
									$PageAx_attr_4->value="scripted";
									$PageAx->appendChild($PageAx_attr_4);	
									
									//insertion dans le tag manifest
								$manifest->appendChild($PageAx);
									
									// ajout dans toc.ncx
									
									{// creation du point de navigation vers la page annexe
									$nP=$toc->createElement('navPoint');
									$nP_attr=$toc->createAttribute('id');
									$nP_attr->value='num-'.$play;
									$nP->appendChild($nP_attr);
									$nP_attr_1=$toc->createAttribute('playOrder');
									$nP_attr_1->value=$play;
									$nP->appendChild($nP_attr_1);
									
									$nl=$toc->createElement('navLabel');
									$txt=$toc->createElement('text', $v['name']);
									$nl->appendChild($txt);
									$nP->appendChild($nl);

									$ct=$toc->createElement('content' );
									$nP->appendChild($ct);
									$ct_attr=$toc->createAttribute('src');
									$ct_attr->value=$PageAx_attr_2->value;
									$ct->appendChild($ct_attr);		
									$nP->appendChild($ct);
									// fin premier point de navigation
									
									// ajout à navPoint
									$toc->appendChild($nP);
									
									// ajout navPoint dans navMap
									$navMap->appendChild($nP);
									}
									
									{//CREATION PAGE et injection TITRE ET DESCRIPTION CATEGORIES
									$pageXHTML = new DOMDocument('1.0', 'utf-8'); 
									$pageXHTML->loadHTML($xhtml);
									$title = $pageXHTML->createElement('title',$v['name'] );
									// ajout du titre 
									$xpath = new DOMXPath($pageXHTML);    
									$results = $xpath->query('/html/head');   
									$head = $results->item(0);
									$head->appendChild($title);
									
										
									//ajout contenu	   
									$results = $xpath->query('/html/body');
									$body=$results->item(0); 
									$section= $pageXHTML->createElement('section');
									$section_attr=$pageXHTML->createAttribute('epub:type');
									$section_attr->value='text' ;
									$section->appendChild($section_attr);
									
									$section_attr_1=$pageXHTML->createAttribute('title');
									$section_attr_1->value=$v['name'];
									$section->appendChild($section_attr_1);
									
									$section_attr_2=$pageXHTML->createAttribute('class');
									$section_attr_2->value="annexe";
									$section->appendChild($section_attr_2);
									
									$h1=$pageXHTML->createElement('h1',$v['name']);
									$section->appendChild($h1);
																
									$div=$pageXHTML->createElement('div');
									// recuperation page statique interpretée;
												ob_start();
												require PLX_ROOT.$plxAdmin->aConf['racine_statiques'].$k.'.'.$v['url'].'.php';
												$output= ob_get_clean();				
									 
									// CDATA pour conserver les balises HTML telle quelle.
									$divContent=$pageXHTML->createCDATASection( $output );
									$div->appendChild($divContent);		
											
									$div_attr_1=$pageXHTML->createAttribute('title');
									$div_attr_1->value='dc: '.$v['name'];
									$div->appendChild($div_attr_1);
									
									$section->appendChild($div);
									
									$body->appendChild($section);

										$pageXHTML->preserveWhiteSpace = false; 
										$pageXHTML->formatOutput = true;		
										$pageXHTML->xmlStandalone = false;
									$pageAnx= $pageXHTML->saveXML();
										//on nettoie
									$Anxpage = $plxPlugin->cleanUp( $pageAnx);
									
			//look for src image to upload to epub archive. If possible, it will donwload external image.
									$doc = new DOMDocument();
									libxml_use_internal_errors(true);
									$doc->loadHTML( $Anxpage );
									
									$xpath2 = new DOMXPath($doc);

									// passage src pour img
									$imgs = $xpath2->query("//img");

									for ($i=0; $i < $imgs->length; $i++) {
										$img = $imgs->item($i);
										$src = $img->getAttribute("src");
										$extension = pathinfo($src, PATHINFO_EXTENSION);
										if(substr($src, 0, 4) =='http'/* && $extension !=null*/) {
										$plxPlugin->downloadRessource($src);
										$newSrc = 'data/medias/'.basename($src);
										$img->setAttribute('src', $newSrc); 
										}															
										$imgToFind[]= basename($src);// will overwrite downloaded img if same file's name  found 
									}
									$imgToFind = array_unique($imgToFind);								
									$annexe = $doc->saveXML($doc->documentElement);	
									$finish ='<?xml version="1.0" encoding="utf-8" standalone="no"?>'. PHP_EOL . '<!DOCTYPE html>' . PHP_EOL .  $plxPlugin->cleanUp( $annexe);	
														
									// sauvegarde fichier.
									$plxPlugin->addFiletxt('EPUB/'.$PageAx_attr_2->value, $finish, $ebook);

									//Alimentation nav toc 	
									$li=$pagenav->createElement('li');		
										$a=$pagenav->createElement('a',$v['name']);
										$a_attr=$pagenav->createAttribute('href');
										$a_attr->value=$PageAx_attr_2->value;
										$a->appendChild($a_attr);
									$li->appendChild($a);								
									$ol->appendChild($li);			

									
									//insertion dans le tag  spine
									$spineItem=$opf->createElement('itemref');
									$spineItem_attr= $opf->createAttribute('idref'); 
									$spineItem_attr->value=$PageAx_attr_1->value;
									$spineItem->appendChild($spineItem_attr);
								$spine->appendChild($spineItem);
									
									
									// Creation reference dans guide
									$ref = $opf->createElement('reference');
									$ref_attr = $opf->createAttribute('href');
									$ref_attr->value=$PageAx_attr_2->value;
									$ref->appendChild($ref_attr);
									$ref_attr_1 = $opf->createAttribute('title');
									$ref_attr_1->value=$v['name'];
									$ref->appendChild($ref_attr_1);
									$ref_attr_2 = $opf->createAttribute('type');
									$ref_attr_2->value="text";
									$ref->appendChild($ref_attr_2);
								$guide->appendChild($ref);
									}											
									}
								  }							
							   }
							 }
							}
							
							{// ajout page annexe en fin


							$endAnnexe=array( "pagePostface" => "pagepostfaceId", "pagerRemerciement" => "pageremerciementId" );
							foreach($endAnnexe as $pageA => $pageA_Id) {	
								if ($plxPlugin->getParam($pageA) == 1 ) {// création page et inscription dans la navigation et au manifest
								$PageAnnexe++;
								$play++;
								{
				
								// ajout references categories 
									$PageAx =$opf->createElement('item');
									$PageAx_attr_1= $opf->createAttribute('id'); 
									$PageAx_attr_1->value="annexe-".$PageAnnexe;
									
									$PageAx->appendChild($PageAx_attr_1);
									$PageAx_attr_2= $opf->createAttribute('href'); 
									$PageAx_attr_2->value="annexe-".$PageAnnexe.".xhtml";
									$PageAx->appendChild($PageAx_attr_2);
									$PageAx_attr_3= $opf->createAttribute('media-type'); 
									$PageAx_attr_3->value="application/xhtml+xml";
									$PageAx->appendChild($PageAx_attr_3);
										
									$PageAx_attr_4= $opf->createAttribute('properties'); 
									$PageAx_attr_4->value="scripted";
									$PageAx->appendChild($PageAx_attr_4);	
									
									//insertion dans le tag manifest
								$manifest->appendChild($PageAx);
									
									// ajout dans toc.ncx
									
									{// creation du point de navigation vers la page annexe
									$nP=$toc->createElement('navPoint');
									$nP_attr=$toc->createAttribute('id');
									$nP_attr->value='num-'.$play;
									$nP->appendChild($nP_attr);
									$nP_attr_1=$toc->createAttribute('playOrder');
									$nP_attr_1->value=$play;
									$nP->appendChild($nP_attr_1);
									
									$nl=$toc->createElement('navLabel');
									$txt=$toc->createElement('text', $plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name']);
									$nl->appendChild($txt);
									$nP->appendChild($nl);

									$ct=$toc->createElement('content' );
									$nP->appendChild($ct);
									$ct_attr=$toc->createAttribute('src');
									$ct_attr->value=$PageAx_attr_2->value;
									$ct->appendChild($ct_attr);		
									$nP->appendChild($ct);
									// fin premier point de navigation
									
									// ajout à navPoint
									$toc->appendChild($nP);
									
									// ajout navPoint dans navMap
									$navMap->appendChild($nP);
									}
									
									{//CREATION PAGE et injection TITRE ET DESCRIPTION CATEGORIES
									$pageXHTML = new DOMDocument('1.0', 'utf-8'); 
									$pageXHTML->loadHTML($xhtml);
									$title = $pageXHTML->createElement('title',$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'] );
									// ajout du titre 
									$xpath = new DOMXPath($pageXHTML);    
									$results = $xpath->query('/html/head');   
									$head = $results->item(0);
									$head->appendChild($title);
									
										
									//ajout contenu	   
									$results = $xpath->query('/html/body');
									$body=$results->item(0); 
									$section= $pageXHTML->createElement('section');
									$section_attr=$pageXHTML->createAttribute('epub:type');
									$section_attr->value=$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'] ;
									$section->appendChild($section_attr);
									
									$section_attr_1=$pageXHTML->createAttribute('title');
									$section_attr_1->value=$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'];
									$section->appendChild($section_attr_1);
									
									$section_attr_2=$pageXHTML->createAttribute('class');
									$section_attr_2->value="annexe";
									$section->appendChild($section_attr_2);
									
									$h1=$pageXHTML->createElement('h1',$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name']);
									$section->appendChild($h1);
																
									$div=$pageXHTML->createElement('div');
									// recuperation page statique interpretée;
												ob_start();
												require PLX_ROOT.$plxAdmin->aConf['racine_statiques'].str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT).'.'.$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['url'].'.php';
												$output= ob_get_clean();				
									 
									// CDATA pour conserver les balises HTML telle quelle.
									$divContent=$pageXHTML->createCDATASection( $output );
									$div->appendChild($divContent);		
											
									$div_attr_1=$pageXHTML->createAttribute('title');
									$div_attr_1->value='dc: '.$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'];
									$div->appendChild($div_attr_1);
									
									$section->appendChild($div);
									
									$body->appendChild($section);

										$pageXHTML->preserveWhiteSpace = false; 
										$pageXHTML->formatOutput = true;		
										$pageXHTML->xmlStandalone = false;
									$pageAnx= $pageXHTML->saveXML();
										//on nettoie
									$Anxpage = $plxPlugin->cleanUp( $pageAnx);
									
								//look for src image to upload to epub archive. If possible, it will donwload external image.
									$doc = new DOMDocument();
									libxml_use_internal_errors(true);
									$doc->loadHTML( $Anxpage );
									
									$xpath2 = new DOMXPath($doc);

									// passage src pour img
									$imgs = $xpath2->query("//img");

									for ($i=0; $i < $imgs->length; $i++) {
										$img = $imgs->item($i);
										$src = $img->getAttribute("src");
										$extension = pathinfo($src, PATHINFO_EXTENSION);
										if(substr($src, 0, 4) =='http' /*&& $extension !=null*/) {
										$plxPlugin->downloadRessource($src);
										$newSrc = 'data/medias/'.basename($src);
										$img->setAttribute('src', $newSrc); 
										}															
										$imgToFind[]= basename($src);// will overwrite downloaded img if same file's name  found 
									}
									$imgToFind = array_unique($imgToFind);								
									$annexe = $doc->saveXML($doc->documentElement);	
									$finish ='<?xml version="1.0" encoding="utf-8" standalone="no"?>'. PHP_EOL . '<!DOCTYPE html>' . PHP_EOL .  $plxPlugin->cleanUp( $annexe);	
														
									// sauvegarde fichier.
									$plxPlugin->addFiletxt('EPUB/'.$PageAx_attr_2->value, $finish, $ebook);

									//Alimentation nav toc 	
									$li=$pagenav->createElement('li');		
										$a=$pagenav->createElement('a',$plxAdmin->aStats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name']);
										$a_attr=$pagenav->createAttribute('href');
										$a_attr->value=$PageAx_attr_2->value;
										$a->appendChild($a_attr);
									$li->appendChild($a);								
									$ol->appendChild($li);			

									
									//insertion dans le tag  spine
									$spineItem=$opf->createElement('itemref');
									$spineItem_attr= $opf->createAttribute('idref'); 
									$spineItem_attr->value=$PageAx_attr_1->value;
									$spineItem->appendChild($spineItem_attr);
								$spine->appendChild($spineItem);
									
									
									// Creation reference dans guide
									$ref = $opf->createElement('reference');
									$ref_attr = $opf->createAttribute('href');
									$ref_attr->value=$PageAx_attr_2->value;
									$ref->appendChild($ref_attr);
									$ref_attr_1 = $opf->createAttribute('title');
									$ref_attr_1->value=$plxAdmin->aCats[str_pad($plxPlugin->getParam($pageA_Id), 3, "0", STR_PAD_LEFT)]['name'];
									$ref->appendChild($ref_attr_1);
									$ref_attr_2 = $opf->createAttribute('type');
									$ref_attr_2->value="text";
									$ref->appendChild($ref_attr_2);
								$guide->appendChild($ref);
									}											
								 }
								}	
							}	
	}// fin annexes en fin
							
							{//page remerciements
							if ($plxPlugin->getParam('pageTemoignage') == 1 ) {
								$index=1;
								$max= $var['nbCom'];
								$ArtRef =$var['art-coms'];
								$PageAnnexe++;
								$play++;
			{
				
				// ajout references categories 
									$PageAx =$opf->createElement('item');
									$PageAx_attr_1= $opf->createAttribute('id'); 
									$PageAx_attr_1->value="annexe-".$PageAnnexe;
									
									$PageAx->appendChild($PageAx_attr_1);
									$PageAx_attr_2= $opf->createAttribute('href'); 
									$PageAx_attr_2->value="annexe-".$PageAnnexe.".xhtml";
									$PageAx->appendChild($PageAx_attr_2);
									$PageAx_attr_3= $opf->createAttribute('media-type'); 
									$PageAx_attr_3->value="application/xhtml+xml";
									$PageAx->appendChild($PageAx_attr_3);
										
									$PageAx_attr_4= $opf->createAttribute('properties'); 
									$PageAx_attr_4->value="scripted";
									$PageAx->appendChild($PageAx_attr_4);	
									
									//insertion dans le tag manifest
								$manifest->appendChild($PageAx);
									
									// ajout dans toc.ncx
									
									{// creation du point de navigation vers la page annexe
									$nP=$toc->createElement('navPoint');
									$nP_attr=$toc->createAttribute('id');
									$nP_attr->value='num-'.$play;
									$nP->appendChild($nP_attr);
									$nP_attr_1=$toc->createAttribute('playOrder');
									$nP_attr_1->value=$play;
									$nP->appendChild($nP_attr_1);
									
									$nl=$toc->createElement('navLabel');
									$txt=$toc->createElement('text', $plxPlugin->getLang('L_TESTIMONIAL_S'));
									$nl->appendChild($txt);
									$nP->appendChild($nl);

									$ct=$toc->createElement('content' );
									$nP->appendChild($ct);
									$ct_attr=$toc->createAttribute('src');
									$ct_attr->value=$PageAx_attr_2->value;
									$ct->appendChild($ct_attr);		
									$nP->appendChild($ct);
									// fin premier point de navigation
									
									// ajout à navPoint
									$toc->appendChild($nP);
									
									// ajout navPoint dans navMap
									$navMap->appendChild($nP);
									}
									
									{//CREATION PAGE et injection TITRE ET DESCRIPTION CATEGORIES
									$pageXHTML = new DOMDocument('1.0', 'utf-8'); 
									$pageXHTML->loadHTML($xhtml);
									$title = $pageXHTML->createElement('title',$plxPlugin->getLang('L_TESTIMONIAL_S') );
									// ajout du titre 
									$xpath = new DOMXPath($pageXHTML);    
									$results = $xpath->query('/html/head');   
									$head = $results->item(0);
									$head->appendChild($title);
									
										
									//ajout contenu	   
									$results = $xpath->query('/html/body');
									$body=$results->item(0); 
									$section= $pageXHTML->createElement('section');
									$section_attr=$pageXHTML->createAttribute('epub:type');
									$section_attr->value="text";
									$section->appendChild($section_attr);
									
									$section_attr_1=$pageXHTML->createAttribute('title');
									$section_attr_1->value=$plxPlugin->getLang('L_TESTIMONIAL_S');
									$section->appendChild($section_attr_1);
									
									$section_attr_2=$pageXHTML->createAttribute('class');
									$section_attr_2->value="annexe";
									$section->appendChild($section_attr_2);
									
									$h1=$pageXHTML->createElement('h1',$plxPlugin->getLang('L_TESTIMONIAL_S'));
									$section->appendChild($h1);
																
									
									// recuperation page statique interpretée;
									
								foreach($plxAdmin->plxGlob_coms->aFiles as $key => $com ) {	
									if ($index > $max ) {break;}								
									if(substr($com, 0, 4) == str_pad($ArtRef, 4, "0", STR_PAD_LEFT) ){									
										$index++;		
										$comInfos= $plxAdmin->parseCommentaire(PLX_ROOT . $plxAdmin->aConf['racine_commentaires'] . $com);
										$div=$pageXHTML->createElement('div');
										$cite=$pageXHTML->createElement('cite',$comInfos['author']);	
										$div->appendChild($cite);
										$blockquote=$pageXHTML->createElement('blockquote');
										$blockquoteContent =$pageXHTML->createCDATASection( $comInfos['content']);
										$blockquote->appendChild($blockquoteContent);									;
										$div->appendChild($blockquote);									
										$section->appendChild($div);							
									}
								}
									$body->appendChild($section);

										$pageXHTML->preserveWhiteSpace = false; 
										$pageXHTML->formatOutput = true;		
										$pageXHTML->xmlStandalone = false;
									$pageAnx= $pageXHTML->saveXML();
										//on nettoie
									$Anxpage = $plxPlugin->cleanUp( $pageAnx);
									
			//look for src image to upload to epub archive. If possible, it will donwload external image.
									$doc = new DOMDocument();
									libxml_use_internal_errors(true);
									$doc->loadHTML( $Anxpage );
									
									$xpath2 = new DOMXPath($doc);

									// passage src pour img
									$imgs = $xpath2->query("//img");

									for ($i=0; $i < $imgs->length; $i++) {
										$img = $imgs->item($i);
										$src = $img->getAttribute("src");
										$extension = pathinfo($src, PATHINFO_EXTENSION);
										if(substr($src, 0, 4) =='http' /*&& $extension !=null*/) {
										$plxPlugin->downloadRessource($src);
										$newSrc = 'data/medias/'.basename($src);
										$img->setAttribute('src', $newSrc); 
										}															
										$imgToFind[]= basename($src);// will overwrite downloaded img if same file's name  found 
									}
									$imgToFind = array_unique($imgToFind);								
									$annexe = $doc->saveXML($doc->documentElement);	
									$finish ='<?xml version="1.0" encoding="utf-8" standalone="no"?>'. PHP_EOL . '<!DOCTYPE html>' . PHP_EOL .  $plxPlugin->cleanUp( $annexe);	
														
									// sauvegarde fichier.
									$plxPlugin->addFiletxt('EPUB/'.$PageAx_attr_2->value, $finish, $ebook);

									//Alimentation nav toc 	
									$li=$pagenav->createElement('li');		
										$a=$pagenav->createElement('a',$plxPlugin->getLang('L_TESTIMONIAL_S'));
										$a_attr=$pagenav->createAttribute('href');
										$a_attr->value=$PageAx_attr_2->value;
										$a->appendChild($a_attr);
									$li->appendChild($a);								
									$ol->appendChild($li);			

									
									//insertion dans le tag  spine
									$spineItem=$opf->createElement('itemref');
									$spineItem_attr= $opf->createAttribute('idref'); 
									$spineItem_attr->value=$PageAx_attr_1->value;
									$spineItem->appendChild($spineItem_attr);
								$spine->appendChild($spineItem);
									
									
									// Creation reference dans guide
									$ref = $opf->createElement('reference');
									$ref_attr = $opf->createAttribute('href');
									$ref_attr->value=$PageAx_attr_2->value;
									$ref->appendChild($ref_attr);
									$ref_attr_1 = $opf->createAttribute('title');
									$ref_attr_1->value=$plxPlugin->getLang('L_TESTIMONIAL_S');
									$ref->appendChild($ref_attr_1);
									$ref_attr_2 = $opf->createAttribute('type');
									$ref_attr_2->value="text";
									$ref->appendChild($ref_attr_2);
								$guide->appendChild($ref);
									}											
				}
		}	
	
								
							}// end block Aknowledgement

							{// add data/medias folders & Files then register into manifest 
					$plxPlugin->addDirectories(PLX_ROOT.'data/medias',$ebook,$opf,'data-medias',$manifest,$imgToFind);///
					$images = PLX_ROOT.'images';
						if (file_exists($images)) {
					$plxPlugin->addDirectories($images,$ebook,$opf,'images',$manifest,$imgToFind);			
						}	

					$package->appendChild($manifest);
					$package->appendChild($spine);
					$package->appendChild($guide);
					// fin package 
					$opf->appendChild($package);
					
					// on sauvegarde la page nav.xhtml	// ajout liste nav toc 
					$sectionNav->appendChild($nav);
					$bodynav->appendChild($sectionNav);	
					$pagenav->preserveWhiteSpace = false; 
					$pagenav->formatOutput = true;	
					// sauvegarde du fichier de navigation dans l'archive
					$plxPlugin->addFiletxt('EPUB/nav.xhtml', $pagenav->saveXML(), $ebook);
	
	
					// enregistrement du fichier dans l'archive.
					$plxPlugin->addFiletxt('EPUB/package.opf', $opf->saveXML(), $ebook);

	
					// enregistrement toc.ncx			
					//nettoyage et indentation
					$toc->preserveWhiteSpace = false;	
					$toc->formatOutput = true;	 
					$tdm = $toc->saveXML();
					$tdm = str_replace('<?xml version="1.0"?>','<?xml version="1.0" encoding="utf-8"?>',$tdm);
					// sauvegarde fichier.
					$plxPlugin->addFiletxt('EPUB/toc.ncx',$tdm, $ebook);




					} // Fin alimentation pages

					
				}
				
			}//fin loop foreach des epubs a faire

		$backToTab='&tab=fC';
		
			if($var['debugme'] == 1) {		
				echo 'Sauvegarde epubs:  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.$backToTab.'">retour page '.$plugin.'</a>';   
				exit;
			}		
		}// fin sauvegarde fichiers epub 	

		if (isset($_POST['submit'])){
		// MAJ metas du bouquin
        $plxPlugin->setParam('uid', $_POST['uid'], 'string');
        $plxPlugin->setParam('title', trim($_POST['title']), 'string');
        $plxPlugin->setParam('subtitle', $_POST['subtitle'], 'string');
        $plxPlugin->setParam('author', $_POST['author'], 'string');
        $plxPlugin->setParam('issn', $_POST['issn'], 'string');
        $plxPlugin->setParam('isbn', $_POST['isbn'], 'string');
		
		
        $plxPlugin->setParam('publisher', $_POST['publisher'], 'string');
        $plxPlugin->setParam('editor', $_POST['editor'], 'string');
        $plxPlugin->setParam('dateC', $_POST['dateC'], 'string');
        $plxPlugin->setParam('dateM', $_POST['dateM'], 'string');
        $plxPlugin->setParam('copyrights', $_POST['copyrights'], 'string');
        $plxPlugin->setParam('licence', $_POST['licence'], 'string');
        $plxPlugin->setParam('urlLicence', $_POST['urlLicence'], 'string');
        $plxPlugin->setParam('descLicence', $_POST['descLicence'], 'cdata');
		
        $plxPlugin->setParam('ctxt', $_POST['ctxt'], 'string');
        $plxPlugin->setParam('cread', $_POST['cread'], 'string');
        $plxPlugin->setParam('cimg', $_POST['cimg'], 'cdata');
        $plxPlugin->setParam('ctrslt', $_POST['ctrslt'], 'string');
		
        $plxPlugin->setParam('cbiblio', $_POST['cbiblio'], 'string');
        $plxPlugin->setParam('clayout', $_POST['clayout'], 'string');
        $plxPlugin->setParam('ctool', $_POST['ctool'], 'string');	

        $plxPlugin->setParam('cover1', $_POST['cover1'], 'string');			

		// options affichage
		$plxPlugin->setParam('mnuDisplay', $_POST['mnuDisplay'], 'numeric');
		$plxPlugin->setParam('mnuPos', $_POST['mnuPos'], 'numeric');
		$plxPlugin->setParam('template', $_POST['template'], 'string');
		$plxPlugin->setParam('url', plxUtils::title2url($_POST['url']), 'string');
		$plxPlugin->setParam('epubRepertory', trim($_POST['epubRepertory']), 'string');
		
// ajout/verif histo ?		
		$plxPlugin->setParam('epubRepertoryHisto', $var['epubRepertoryHisto'].' '.trim($_POST['epubRepertory']), 'string');
        $checkHisto =explode(' ',$var['epubRepertoryHisto'].' '.trim($_POST['epubRepertory']));		
		array_unique($checkHisto);
		$plxPlugin->setParam('epubRepertoryHisto', implode(' ', $checkHisto) , 'string');
		
		// pages supplementaire à inclure
		$plxPlugin->setParam('pageIndex', $_POST['pageIndex'], 'numeric');
		$plxPlugin->setParam('pageCopy', $_POST['pageCopy'], 'numeric');
		$plxPlugin->setParam('pageDedicace', $_POST['pageDedicace'], 'numeric');
		$plxPlugin->setParam('pageForeword', $_POST['pageForeword'], 'numeric');
		$plxPlugin->setParam('pageAuteur', $_POST['pageAuteur'], 'numeric');
		$plxPlugin->setParam('pagePreface', $_POST['pagePreface'], 'numeric');
		$plxPlugin->setParam('pagerRemerciement', $_POST['pagerRemerciement'], 'numeric');
		$plxPlugin->setParam('pagePostface', $_POST['pagePostface'], 'numeric');
		
		
		// catégories à inclure 
		
		$plxPlugin->setParam('all', $_POST['all'], 'numeric');
		$plxPlugin->setParam('all-th', $_POST['all-th'], 'string');
		foreach ($plxAdmin->aCats as $catNumb => $values) {
			if( $values["articles"] >="1" && $values['active']=='1' ) {// on ne prend que les catégories disposant d'un articles et actives
				$plxPlugin->setParam($catNumb, $_POST[$catNumb], 'numeric');
				$plxPlugin->setParam($catNumb.'-th', $_POST[$catNumb.'-th'], 'string');
			}
		
		}
		
        $plxPlugin->saveParams();

			if($var['debugme'] == 1) {		
				echo 'Submit:  debug affiche les messages et erreurs. redirection manuelle => <a href="parametres_plugin.php?p='.$plugin.'">retour page '.$plugin.'</a>';   
				exit;
			}				 		
		}
		header('Location: plugin.php?p='.$plugin.$backToTab);
	}
?>

<?php  // generation image preview covers
// colors
/*
$black 		= imagecolorallocate($im, 100, 100, 100);
$white 		= imagecolorallocate($im, 255, 255, 255);
$gray 		= imagecolorallocate($im, 125, 125, 125);
$hotpink	= imagecolorallocate($im, 255, 105, 80);
$royalblue 	= imagecolorallocate($im, 92 , 126, 229); 
$color6a6 	= imagecolorallocate($im, 92 , 126, 229);  
*/
		if (isset($_POST['updatecovers'])){
$plxPlugin->makeThemeImg('th1/' ,'cover1.jpg',array(90,90,90)      , array(125,125,125) , array(92,126,229) ,$RobotoBold,$LatoRegular,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th2/' ,'cover2.jpg',array(140,0,0)       , array(125,125,125) , array(92,126,229) ,$RobotoBold,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'3.8' ,'1.6','1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th3/' ,'cover3.jpg',array(255,100,200)   , array(125,125,125) , array(92,126,229) ,$RobotoBold,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th4/' ,'cover4.jpg',array(255,255,255)   , array(255,230,100) , array(250,126,29) ,$RobotoBold,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th5/' ,'cover5.jpg',array(255,255,255)   , array(0,0,140)     , array(92,126,229) ,$ubuntuMono,$freeSerif  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'7'   ,'1.8','1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th6/' ,'cover6.jpg',array(192,226,229)   , array(255,255,255) , array(192,226,229),$freeSansB ,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th7/' ,'cover7.jpg',array(150,200,60)    , array(255,255,255) , array(255,255,255),$dislexia  ,$dislexia   ,$freeSerif ,$titreTh,$descTh,$AuthTh,'3.5' ,'1.8','1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th8/' ,'cover8.jpg',array(192,226,229)   , array(255,255,255) , array(192,226,229),$freeSansB ,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'2'  ,'1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th9/' ,'cover9.jpg',array(234, 205, 159) , array(255,255,255) , array(92,126,229) ,$ubuntuMono,$freeSansB  ,$freeSerif ,$titreTh,$descTh,$AuthTh,'4'   ,'1.5','1.05',$plugin,$part);
$plxPlugin->makeThemeImg('th10/','cover10.jpg',array(230, 82, 1)   , array(77, 165, 232), array(200,200,200),$freeSansB ,$ubuntuMono ,$ubuntuMono,$titreTh,$descTh,$AuthTh,'2.5','1.25','40' ,$plugin,$part);
		}
?>

<form action="?p=EBook" method="post" id="formEpub">				
<?php echo plxToken::getTokenPostMethod() ;
echo '<link rel="stylesheet" type="text/css" href="'.PLX_PLUGINS.'/EBook/css/ebook-admin.css?t='.time().'" media="screen">';
?>
  <input type="radio" name="nav" id="fA"    <?php if(!isset($_GET['tab']) || ( isset($_GET['tab']) && $_GET['tab']=='fA')) {echo ' checked="checked"' ;}?>>
  <input type="radio" name="nav" id="fB"    <?php if( isset($_GET['tab']) && $_GET['tab']=='fB') 	{echo ' checked="checked"';}?>>
  <input type="radio" name="nav" id="fC"    <?php if( isset($_GET['tab']) && $_GET['tab']=='fC') 	{echo ' checked="checked"';}?>>
  <input type="radio" name="nav" id="fD"    <?php if( isset($_GET['tab']) && $_GET['tab']=='fD')	{echo ' checked="checked"';}?>>
  <input type="radio" name="nav" id="fE"    <?php if( isset($_GET['tab']) && $_GET['tab']=='fE') 	{echo ' checked="checked"';}?>>
  <input type="radio" name="nav" id="fF"    <?php if( isset($_GET['tab']) && $_GET['tab']=='fF') 	{echo ' checked="checked"';}?>>
  <input type="radio" name="nav" id="fmode" <?php if( isset($_GET['tab']) && $_GET['tab']=='fmode') {echo ' checked="checked"';}?>>
  <h3><label for="fA"><span><?php echo $plxPlugin->getLang('L_DISPLAY_OPTIONS') ?></span></label></h3>
  <fieldset id="A">
    <legend><?php echo $plxPlugin->getLang('L_DISPLAY_OPTIONS_PAGE') ?></legend>				
    <p class="field"><label for="id_mnuName"><?php $plxPlugin->lang('L_MENU_TITLE') ?></label><?php plxUtils::printInput('mnuName',$var['mnuName'],'text','20-20') ?></p> 
	  <p><label for="description"><?php $plxPlugin->lang('L_DESCRIPTION') ?></label> <textarea name="description"><?php echo $var['description'] ?></textarea></p>
    <p><label for="id_mnuDisplay"><?php echo $plxPlugin->lang('L_MENU_DISPLAY') ?></label><?php plxUtils::printSelect('mnuDisplay',array('1'=>L_YES,'0'=>L_NO),$var['mnuDisplay']); ?></p>
	<p class="field"><label for="id_url"><?php $plxPlugin->lang('L_URL') ?></label><?php plxUtils::printInput('url',$var['url'],'text','20-255') ?></p>
    <p><label for="id_mnuPos"><?php $plxPlugin->lang('L_MENU_POS') ?></label><?php plxUtils::printInput('mnuPos',$var['mnuPos'],'text','2-5') ?></p>
    <p><label for="epubRepertory"><?php $plxPlugin->lang('L_EPUBS_STORAGE_REPERTORY') ?></label><span><input name="epubRepertory" value="<?php echo trim($var['epubRepertory'],' '); ?>"></span></p>
	<?php echo $existEpubDirTpl; ?>	
	<script>(function () {
		// variables 
		const dirNames = [<?php $plxPlugin->forbiddenUriList(PLX_ROOT) ?>]; 			
		const dirValue = '<?php echo preg_replace("/^(\\.\\.\\/)+/", "", trim($var['epubRepertory'],' ')); ?>' ;
		const dirHisto =[<?php echo "'". preg_replace("/(\\.\\.\\/)+/", "",implode("' , '",$loopHisto ))."'";?>];
		// const dirInvalid = ' dirNames - dirHisto ' ;				
		const dirInvalid = dirNames.filter(function(val) {
		  return dirHisto.indexOf(val) == -1;
		});

		let iptUrl = document.querySelector('[name="url"]');
		let iptDir = document.querySelector('[name="epubRepertory"]');
		let histo  = document.querySelector('[name="epubDirHisto"]');
		
		
			
			
		// dir
		iptDir.addEventListener("keyup", function () {
			checkVal(iptDir.value, dirInvalid);
		});
		// use histo dir
		histo.addEventListener('change', function () {
		  iptDir.value = this.value;
		});
		
		// url
		iptUrl.addEventListener("keyup", function () {
			checkDir(iptUrl.value, dirNames);
		});
		
		// comparaisons entre : valeur nettoyée et (valeurs interdites - valeurs déjà utilisées);
		function checkVal(val, dirInvalid ) {
			val=val.replace( '../../', '' );
			strippedVal= iptDir.value;
			strippedVal= strippedVal.replace( '../../', '' );
			dirInvalid.push(iptUrl.value);
			for (let i = 0; i < dirInvalid.length; i++) {
				let name = dirInvalid[i];
				if (name == val) {
					iptDir.value= prompt('<?php $plxPlugin->lang('L_ERR_FORBIDDEN_NAME') ; ?> : ' + strippedVal +' \n\n<?php $plxPlugin->lang('L_NEW_DIR_NAME') .':' ;  ?>', iptDir.defaultValue);
					if (iptDir.value === '') {
							iptDir.value = iptDir.defaultValue;
							return;
					}
					checkVal(iptDir.value, dirInvalid);// juste au cas où pas de chance
					break;
				}
			}
		}
		
		//verifie si le nom de repertoire existe
		function checkDir(value, arr) {
			for (let i = 0; i < arr.length; i++) {
				let name = arr[i];
				if (name == value) {
					iptUrl.value= prompt('<?php $plxPlugin->lang('L_ERR_FORBIDDEN_NAME') ; ?> : ' + iptUrl.value +' \n\n<?php $plxPlugin->lang('L_NEW_DIR_NAME') .':' ;  ?>');
					if (iptUrl.value === '') {
					iptUrl.value = iptUrl.defaultValue;
					return;
					}
					checkDir(iptUrl.value, arr);// juste au cas où c'est pas clair
					break;
				}
			}
		}
})();</script>
    <p><label for="id_template"><?php $plxPlugin->lang('L_TEMPLATE') ?>&nbsp;</label><?php plxUtils::printSelect('template', $aTemplates, $var['template']) ?></p>
	<p><label for="id_debugme"><?php $plxPlugin->lang('L_DEBUGME') ?></label><?php plxUtils::printSelect('debugme',array('1'=>L_YES,'0'=>L_NO),$var['debugme']); ?></p>
    <div>  
	  <p><label for="custom-start"><?php $plxPlugin->lang('L_CUSTOM_CONTENT_TOP') ?></label> <textarea name="custom-start"><?php echo $var['custom-start'] ?></textarea></p>
      <p><label for="custom-end"><?php $plxPlugin->lang('L_CUSTOM_CONTENT_END') ?></label> <textarea name="custom-end"><?php echo $var['custom-end'] ?></textarea></p>
	 </div>
    <input type="submit" name="submitA" value="<?php $plxPlugin->lang('L_SAVE') ?>" />
  </fieldset>
  
<h3><label for="fmode"><span><?php echo $plxPlugin->getLang('L_PUBLISH_MODE') ?></span></label></h3>
  <fieldset id="mode">
    <legend><?php echo $plxPlugin->getLang('L_PUBLISH_TYPE') ?></legend>
	<?php if(!isset($plxAdmin->aUsers[$plxPlugin->getParam('triAuthors')]['name'])) { echo  '<p class="warning fullWidth">'. $plxPlugin->getLang('L_PUBLISH_TYPE').' '. $plxPlugin->getLang('L_NOT_CONFIGURED').'</p>';} ?>
    <div>
		<label for="triAuthors"><?php echo $plxPlugin->getLang('L_AUTHOR_SELECTION') ?></label>
		<select name="triAuthors" id="triAuthors">
		<?php echo $userTPL; ?>
		</select>
		<b id="000" class="fullWidth hide" style="margin-inline-start:auto;width:auto;"><?php echo $plxPlugin->getLang('L_SELECT_ALL_AUTHORS') ?></b>
		<?php foreach($plxAdmin->aUsers as $_userid  => $_user) {			
			echo '<b id="'.$_userid.'" class="fullWidth hide" style="margin-inline-start:auto;width:auto">'. $plxPlugin->getLang('L_SORT_PUBLICATION_OF').' '.$_user['name'].'</b>';			
		} ?>
		<label for="epubMode"><?php echo $plxPlugin->getLang('L_SORT_PUBLICATION') ?></label>
		<select name="epubMode" id="epubMode">
			<option value="book"   <?php if($var['epubMode'] == 'book'  ) {echo 'selected="selected" ';}  ?>><?php echo $plxPlugin->getLang('L_BOOK_MODE') ?></option>
			<option value="blog"   <?php if($var['epubMode'] == 'blog'  ) {echo 'selected="selected" ';}  ?>><?php echo $plxPlugin->getLang('L_BLOG_MODE') ?></option>
			<option value="comics" <?php if($var['epubMode'] == 'comics') {echo 'selected="selected" ';}  ?>><?php echo $plxPlugin->getLang('L_COMICS_MODE') ?></option>
			<optgroup label="<?php echo $plxPlugin->getLang('L_MAG_MODE') ?>">
				<option value="magM" <?php if($var['epubMode'] == 'magM') {echo 'selected="selected" ';}  ?>><?php echo $plxPlugin->getLang('L_MONTHLY') ?></option>
				<option value="magT" <?php if($var['epubMode'] == 'magT') {echo 'selected="selected" ';}  ?>><?php echo $plxPlugin->getLang('L_QUARTELY') ?></option>
				<option value="magS" <?php if($var['epubMode'] == 'magS') {echo 'selected="selected" ';}  ?>><?php echo $plxPlugin->getLang('L_BIANNUAL') ?></option>
				<option value="magA" <?php if($var['epubMode'] == 'magA') {echo 'selected="selected" ';}  ?>><?php echo $plxPlugin->getLang('L_ANNUAL') ?></option>
			</optgroup>
		</select>
		<div class="fullWidth hide"></div>
		<div class="fullWidth hide"  id="book"><h4><?php echo $plxPlugin->getLang('L_BOOK_MODE') ?></h4> <p><?php echo $plxPlugin->getLang('L_OLDEST_TO_RECENT') ?></p></div>
		<div class="fullWidth hide"  id="blog"><h4><?php echo $plxPlugin->getLang('L_BLOG_MODE') ?></h4>  <p><?php echo $plxPlugin->getLang('L_RECENT_TO_OLDEST') ?></p></div>
		<div class="fullWidth hide"  id="magM"><h4>Magazine <?php echo $plxPlugin->getLang('L_MONTHLY') ?></h4> <p><b class="fullWidth block"><?php echo $plxPlugin->getLang('L_PRINT_1_MONTH') ?></b>
		<label for="magMY"><?php echo $plxPlugin->getLang('L_CHOOSE_YEAR') ?></label><select name="magMY" id ="magMY"><?php echo $optionTplY ; ?></select>
		<label for="magMM"><?php echo $plxPlugin->getLang('L_FROM_MONTH') ?></label><select name="magMM" id="magMM"><?php echo $optionTplM ?></select></p>
		</div>
		<div class="fullWidth hide"  id="magT"><h4>Magazine <?php echo $plxPlugin->getLang('L_QUARTELY') ?></h4> <p><b class="fullWidth block"><?php echo $plxPlugin->getLang('L_PRINT_3_MONTH') ?></b>
		<label for="magTY"><?php echo $plxPlugin->getLang('L_CHOOSE_YEAR') ?></label><select name="magTY" id ="magTY"><?php echo $optionTplY ; ?></select>
		<label for="magTM"><?php echo $plxPlugin->getLang('L_FROM_MONTH') ?></label><select name="magTM" id="magTM"><?php echo $optionTplM ?></select></p>
		</div>
		<div class="fullWidth hide"  id="magS"><h4>Magazine <?php echo $plxPlugin->getLang('L_BIANNUAL') ?></h4> <p><b class="fullWidth block"><?php echo $plxPlugin->getLang('L_PRINT_6_MONTH') ?></b>
		<label for="magSY"><?php echo $plxPlugin->getLang('L_CHOOSE_YEAR') ?></label><select name="magSY" id ="magSY"><?php echo $optionTplY ; ?></select>
		<label for="magSM"><?php echo $plxPlugin->getLang('L_FROM_MONTH') ?></label><select name="magSM" id="magSM"><?php echo $optionTplM ?></select></p>
		</div>
		<div class="fullWidth hide"  id="magA"><h4>Magazine <?php echo $plxPlugin->getLang('L_ANNUAL') ?></h4> <p><b class="fullWidth block"><?php echo $plxPlugin->getLang('L_PRINT_1_YEAR') ?></b>
		<label for="magAY"><?php echo $plxPlugin->getLang('L_CHOOSE_YEAR') ?> </label><select name="magAY" id ="magAY"><?php echo $optionTplY ; ?></select></p>
		</div>		
		<div class="fullWidth hide" id="comics"><h4><?php echo $plxPlugin->getLang('L_COMICS_MODE') ?></h4>
		<p class="fullWidth block"><strong><u><?php echo $plxPlugin->getLang('L_SIMPLIFIED_MODE') ?></u>:</strong><?php echo $plxPlugin->getLang('L_LIST_REPERTORY_IMG_COVER') ?> <code>cover.jpg</code>.</p>	
		<p class="fullWidth"><label for="comicsmedia"><?php echo $plxPlugin->getLang('L_COMIC_IMG_REPERTORY') ?></label><span>data/medias/<?php plxUtils::printInput('comicsmedia',$var['comicsmedia'],'text','20-255') ?></span></p>
		<a href="medias.php" target="_blank"  class="fullWidth"><?php echo $plxPlugin->getLang('L_GO_TO_MEDIA_FOLDER').' ';  
		if(empty($_SESSION['medias'])) {
			$_SESSION['medias'] = $plxAdmin->aConf['medias'];
			$_SESSION['folder'] =  $var['comicsmedia'].'/';
			$_SESSION['currentfolder']=$_SESSION['folder'];
			}
			else {				
			$_SESSION['folder'] =  $var['comicsmedia'].'/';
			$_SESSION['currentfolder']=$_SESSION['folder'];
			}
			echo '<code>'.$_SESSION['folder'].'</code>'; 
		?> </a>
		<p class="fullWidth"><label for="titreComics"><?php echo $plxPlugin->getLang('L_TITLE') ?></label><?php plxUtils::printInput('titreComics',$var['titreComics'],'text','20-255') ?></p>
		<p class="fullWidth"p><label for="descComics"><?php echo $plxPlugin->getLang('L_DESC') ?></label><?php plxUtils::printInput('descComics',$var['descComics'],'text','20-255') ?></p>
		<p class="fullWidth"><label for="auteurComics"><?php echo $plxPlugin->getLang('L_AUTHOR') ?></label><?php plxUtils::printInput('auteurComics',$var['auteurComics'],'text','20-255') ?></p>
		<p class="fullWidth"><label for="illustrateurComics"><?php echo $plxPlugin->getLang('L_ILLUSTRATOR') ?></label><?php plxUtils::printInput('illustrateurComics',$var['illustrateurComics'],'text','20-255') ?></p>
		<p class="fullWidth"><label for="ISSNComics">N° ISSN</label><?php plxUtils::printInput('ISSNComics',$var['ISSNComics'],'text','20-255') ?></p>
		<p class="fullWidth"><label for="ISBNComics">N° ISBN</label><?php plxUtils::printInput('ISBNComics',$var['ISBNComics'],'text','20-255') ?></p>		
		    <input type="submit" name="doComics" value="générer l'e-Comics" />
		</div>
	</div>
 <div>
   <h3>Aide</h3>
   <dl>
     <dt><?php echo $plxPlugin->getLang('L_BOOK_MODE') ?></dt>
     <dd><?php echo $plxPlugin->getLang('L_SORT_OLDEST_TO_RECENT') ?></dd>
     <dt><?php echo $plxPlugin->getLang('L_BLOG_MODE') ?></dt>
     <dd><?php echo $plxPlugin->getLang('L_SORT_RECENT_TO_OLDEST') ?></dd>
     <dt><?php echo $plxPlugin->getLang('L_MAG_MODE') ?></dt>
     <dd><?php echo $plxPlugin->getLang('L_ALIKE') ?> <?php echo $plxPlugin->getLang('L_BLOG_ARCHIVE') .','. $plxPlugin->getLang('L_SORT_RECENT_TO_OLDEST'). $plxPlugin->getLang('L_PERIODICITY_CHOSEN'). $plxPlugin->getLang('L_MANUALLY_MADE') ?> </dd>
     <dt><?php echo $plxPlugin->getLang('L_COMICS_MODE') ?></dt>
     <dd><?php echo $plxPlugin->getLang('L_SIMPLIFIED_MODE') ?>: <?php echo $plxPlugin->getLang('L_LIST_REPERTORY_IMG_COVER') ?> <code>cover.jpg</code>.</dd>
     <dt class="strike">FixedLayout</dt>
     <dd class="">NOT AVALAIBLE</dd>
   </dl>
    </div>
    <input type="submit" name="submitmode" value="Enregistrer" />
  </fieldset>
  
  <h3><label for="fC"><span><?php echo $plxPlugin->getLang('L_INIT_&_MAKE') ?></span></label></h3>
  <fieldset class="fullWidth" id="C">
  <legend><?php echo $plxPlugin->getLang('L_SELECTIONS') ?></legend>
		<p class="sticky-top"><?php if(count($plxAdmin->plxGlob_arts->aFiles) >=1)  {?><label for="submitC"><?php echo $plxPlugin->getLang('L_SAVE') . $plxPlugin->getLang('L_CONFIG') ?></label><input type="submit" name="submitC" value="<?php echo $plxPlugin->getLang('L_SAVE') ?>" />
		<label for="doMake"><?php echo $plxPlugin->getLang('L_SAVE_SELECTED_EPUBS') ?></label><input type="submit" name="doMake" style="position:static; margin-inline-end:auto;" value="<?php echo $plxPlugin->getLang('L_SAVE_EPUBS') ?>"><?php } ?>
		<?php  if ($plxPlugin->getParam('epubMode')=='magM' || $plxPlugin->getParam('epubMode')=='magT'|| $plxPlugin->getParam('epubMode')=='magS'|| $plxPlugin->getParam('epubMode')=='magA') {	
		echo '<span class="fullWidth" style="font-size:1.5rem;color:ivory;background:#00BFFF;width:max-content;margin:1em auto;padding:0.15rem 0.5rem;border-radius:5px;box-shadow:2px 2px 5px #333;">Période de Publications: <b style="color:#333">'; 
				if($var['epubMode']=='magM') {echo 'mois '.str_pad( $var['magMM'], 2, "0", STR_PAD_LEFT).' - '.$var['magMY']; }
				if($var['epubMode']=='magT') {echo '3 mois à partir du '.str_pad($var['magTM'], 2, "0", STR_PAD_LEFT).' - '.$var['magTY']; }
				if($var['epubMode']=='magS') {echo '6 mois à partir du '.str_pad($var['magSM'], 2, "0", STR_PAD_LEFT).' - '.$var['magSY']; }
				if($var['epubMode']=='magA') {echo 'L\'année '.$var['magAY']; }
		echo'<br>';echo count($plxAdmin->plxGlob_arts->aFiles) .'</b> article(s) publié(s) sur cette période.</span>';	}	?></p>
	<?php if(count($plxAdmin->plxGlob_arts->aFiles) <=0)  {echo '<p> Auncunes publications trouvées pour ce Mode de tri ou de  publication.</p>';} ?>		
	<?php if(count($plxAdmin->plxGlob_arts->aFiles) >=1)  { ?>		
		<h3><?php echo $plxPlugin->getLang('L_TOP_SPECIFIC') ?></h3>
		<table id="annexe-table">
			<tr>
					<td>
					<label for="pageDedicace"><?php echo $plxPlugin->getLang('L_DEDICATION_PAGE') ?></label>
					<?php plxUtils::printSelect('pageDedicace',array('1'=>L_YES,'0'=>L_NO),$var['pageDedicace']); ?>
					<small <?php if($var['pageDedicace'] =="1") {echo'class="on"';} ?>><?php echo $plxPlugin->getLang('L_INCLUDE_ONE') . $plxPlugin->getLang('L_DEDICATION_PAGE') . $plxPlugin->getLang('L_FROM_STATIC') ?>  
					<?php if($var['pagededicaceId'] > 0) {echo '<a href=" statique.php?p='. str_pad($var['pagededicaceId'], 3, "0", STR_PAD_LEFT) .'">'. $plxPlugin->getLang('L_EDIT_PAGE') .'</a>';}  ?></small>  
				</td>
			</tr>
			<tr>
					<td>
					<label for="pagePreface"><?php echo $plxPlugin->getLang('L_PREFACE_PAGE') ?></label>
					<?php plxUtils::printSelect('pagePreface',array('1'=>L_YES,'0'=>L_NO),$var['pagePreface']); ?>
					<small <?php if($var['pagePreface'] =="1") {echo'class="on"';} ?>><?php echo $plxPlugin->getLang('L_INCLUDE_ONE') . $plxPlugin->getLang('L_PREFACE_PAGE') . $plxPlugin->getLang('L_FROM_STATIC') ?>  
					<?php if($var['pageprefaceId'] > 0) {echo '<a href=" statique.php?p='. str_pad($var['pageprefaceId'], 3, "0", STR_PAD_LEFT) .'">'. $plxPlugin->getLang('L_EDIT_PAGE') .'</a>';}  ?></small>  
				</td>
			</tr>
			<tr>
				<td>
					<label for="pageForeword"><?php echo $plxPlugin->getLang('L_FOREWORD_PAGE') ?></label>
					<?php plxUtils::printSelect('pageForeword',array('1'=>L_YES,'0'=>L_NO),$var['pageForeword']); ?>
					<small <?php if($var['pageForeword'] =="1") {echo'class="on"';} ?>><?php echo $plxPlugin->getLang('L_INCLUDE_ONE') . $plxPlugin->getLang('L_FOREWORD_PAGE') . $plxPlugin->getLang('L_FROM_STATIC') ?>
					<?php if($var['pageforewordId'] > 0) {echo '<a href=" statique.php?p='. str_pad($var['pageforewordId'], 3, "0", STR_PAD_LEFT) .'">'. $plxPlugin->getLang('L_EDIT_PAGE') .'</a>';}  ?> </small> 
				</td>
			</tr>
		</table>		
		

		<h3><?php echo $plxPlugin->getLang('L_SELECTED_CATEGORIES') ?></h3>
		<table>
			<tr style="border-bottom:solid;filter: hue-rotate(240deg);">
				<th class="bigger"><label for="all"><?php echo $plxPlugin->getLang('L_ALL_CATEGORIES') ?> (<?php echo $plxAdmin->nbArticles('published', $var['publishedUser'], '') ?>)</label></th>
				<td><?php plxUtils::printSelect('all',array('1'=>L_YES,'0'=>L_NO),$var['all']); ?></td>
				<td><small <?php if($var['all'] =="1") {echo'class="on"';} ?>><?php echo $plxPlugin->getLang('L_FULL_SITE') ?></small></td>
				
					<?php		echo '<th class="bigger mb-1"><label for="id_all-th">Selection theme</label></th>'. PHP_EOL .'<td><select id="id_all-th" name="all-th">';
						  
						  $i="0";
							foreach($themesList as $themes => $sel) {
								$i++;
								$thi = 'th'.$i;
								 if($var['all-th'] == $thi) {$state='selected="selected" ';} else {$state="" ;} 
								echo '<option value="th'.$i.'" '.$state.'> Theme '.$i.'</option>';
							}
						  echo '</select >';			
						echo'</td>'.PHP_EOL; ?>
			</tr><?php	foreach ($plxAdmin->aCats as $catNumb => $values) {
						if(@$values['active'] =='1') {
					if($plxPlugin->getParam($catNumb) =="1") { $classS='on';} else {$classS='off';}
						if(!isset($values['mother'])){$values['mother']='0';$classS .=" regular";}// compatibilité si plx-gc-categorie absent
						if(!isset($values['daughterOf'])){$values['daughterOf']='000';}// compatibilité si plx-gc-categorie absent
						if( $values["articles"] >="1" && $values['active']=='1' ) {
							if ($values["daughterOf"] =="000" && $values["mother"]=="0") {$classS .=" standalone";}
						echo '<tr class="'.$classS.'">';
							$class="";
							$daught=$plxPlugin->getLang('L_SINGLE_CATEGORIE');
							if ($values["mother"]=="1") {$class="field bigger"; $daught=$plxPlugin->getLang('L_ALL_CATEGORIE_LINKED');} else{$class="field";}
					echo '	<th class="'.$class.'"><label for="id_'.$catNumb.'">'.$values['name'].' ('.$values["articles"].')</label></th>';
					echo '<td>';
					plxUtils::printSelect($catNumb,array('1'=>L_YES,'0'=>L_NO),$var[$catNumb]);
					echo '</td>';
					echo '<td> <small>'.$daught.'</small></td>';
					  echo '<th class="'.$class.' mb-1"><label for="id_'.$catNumb.'-th">Selection theme</label></th>'. PHP_EOL . '<td><select id="id_'.$catNumb.'-th" name="'.$catNumb.'-th">';
					  
					  $i="0";
						foreach($themesList as $themes => $sel) {
							$i++;
							$thi = 'th'.$i;
							 if($plxPlugin->getParam($catNumb.'-th') == $thi) {$state='selected="selected" ';} else {$state="" ;} 
							echo '<option value="th'.$i.'" '.$state.'> Theme '.$i.'</option>';
						}
					  echo '</select >';			
					echo'</td>'.PHP_EOL;
						echo '</tr>';
						}
						}
					}
					?>
				</table>
				
				<h3><?php echo $plxPlugin->getLang('L_SELECTED_STATICS') ?></h3>
				<table>
				
				<?php
				foreach ($plxAdmin->aStats as $k => $v) {
				 if ($v['active'] == 1 && $v['menu'] == 'oui' && $v['group'] !='ebookAnnexe' ) {
					 echo '<tr>';
				 	 if($plxPlugin->getParam('stat-'.$k) =="1") {echo'<td class="on">';} else {echo'<td>';}	
					echo '					
						<div class="gridInTd regStatic">';
					 echo '	<label for="stat-'.$k.'">'.$plxPlugin->getLang('L_INCLUDE').' page "'.$v['name'].'"</label>';
					plxUtils::printSelect('stat-'.$k,array('1'=>L_YES,'0'=>L_NO),$var['stat-'.$k]);
					echo '<small><a href=" statique.php?p='. $k .'">'. $plxPlugin->getLang('L_EDIT_PAGE') .'</a></small>';
					 echo	  '
					 	</div>
					</td>
				</tr>';
					 }
				}
				?>
				</table>
				
				<h3><?php echo $plxPlugin->getLang('L_END_SPECIFIC') ?></h3>
				<table id="annexe-table2">
				<tr>
					<td>
						<label for="pageAuteur"><?php echo $plxPlugin->getLang('L_AUTHOR_PAGE') ?></label><?php plxUtils::printSelect('pageAuteur',array('1'=>L_YES,'0'=>L_NO),$var['pageAuteur']); ?>
						<small <?php if($var['pageAuteur'] =="1") {echo'class="on"';} ?>> <?php echo $plxPlugin->getLang('L_EXTRACT_AUTHOR_INFO') ?></small>
					</td>
				</tr>
				<tr>
					<td>
						<label for="pagePostface"><?php echo $plxPlugin->getLang('L_POSTFACE_PAGE') ?></label>
						<?php plxUtils::printSelect('pagePostface',array('1'=>L_YES,'0'=>L_NO),$var['pagePostface']); ?>
						<small <?php if($var['pagePostface'] =="1") {echo'class="on"';} ?>><?php echo $plxPlugin->getLang('L_INCLUDE_ONE') . $plxPlugin->getLang('L_POSTFACE_PAGE') . $plxPlugin->getLang('L_FROM_STATIC') ?> 
						<?php if($var['pagepostfaceId'] > 0) {echo '<a href=" statique.php?p='. str_pad($var['pagepostfaceId'], 3, "0", STR_PAD_LEFT) .'">'. $plxPlugin->getLang('L_EDIT_PAGE') .'</a>';}  ?></small>  
					</td>
				</tr>
				<tr>
					<td><label for="pagerRemerciement"><?php echo $plxPlugin->getLang('L_AKNOWLEDGE_PAGE') ?></label>
						<?php plxUtils::printSelect('pagerRemerciement',array('1'=>L_YES,'0'=>L_NO),$var['pagerRemerciement']); ?>
						<small <?php if($var['pagerRemerciement'] =="1") {echo'class="on"';} ?>><?php echo $plxPlugin->getLang('L_THANKS_TO_ALL_HELPER') ?>
						<?php if($var['pageremerciementId'] > 0) {echo '<a href=" statique.php?p='. str_pad($var['pageremerciementId'], 3, "0", STR_PAD_LEFT) .'">'. $plxPlugin->getLang('L_EDIT_PAGE') .'</a>';}  ?></small>  
					</td>
				<?php
				if ($PagesCommentees !='') {
				?>
				</tr>
				<tr>
					<td <?php if($plxPlugin->getParam('pageTemoignage') =="1") {echo'class="on"';} ?>>
						<div class="gridInTd">
							<label for="pageTemoignage"><?php echo $plxPlugin->getLang('L_TESTIMONIAL_PAGE') ?></label>
							<?php plxUtils::printSelect('pageTemoignage',	array('1'=>L_YES,'0'=>L_NO),$var['pageTemoignage']); ?>
							
							<label for="nbCom"><?php echo $plxPlugin->getLang('L_SO_MANY_COM_TO_INCLUDE') ?></label>
							<?php plxUtils::printSelect('nbCom',			array(1=>1 , 3=>3 ,5=>5 ,10=>10 ,255=>255 ),$var['nbCom']); ?>
							
							<label for="art-coms" ><?php echo $plxPlugin->getLang('L_INCLUDE_COMMENT_FROM') ?></label>
							<select id="art-coms" name="art-coms">
								<option value=""><?php echo $plxPlugin->getLang('L_CHOICE') ?></option>
							<?php
							foreach($PagesCommentees as $key => $title) {
								echo '<option value="'.$key.'"'; if($var['art-coms'] == $key) {echo 'selected=selected"';} echo'>'.$title.'</option>';			
							}
							?>		
							</select>
						</div>
					</td>
				</tr>
				<!-- double emploi avec  page titre + remerciement ?
				<tr>
					<td>
						<label for="pageCopy"><?php echo $plxPlugin->getLang('L_COPYRIGHTS_PAGE') ?></label><?php plxUtils::printSelect('pageCopy',array('1'=>L_YES,'0'=>L_NO),$var['pageCopy']); ?> 
						<small <?php if($var['pageCopy'] =="1") {echo'class="on"';} ?>><?php echo $plxPlugin->getLang('L_INCLUDE_ID_CREDITS') ?></small>
					</td>
				</tr>-->
				<tr>
					<td>
						<label for="pageIndex"><?php echo $plxPlugin->getLang('L_KEYWORDS_INDEX') ?></label>
						<?php plxUtils::printSelect('pageIndex',array('1'=>L_YES,'0'=>L_NO),$var['pageIndex']); ?> 
						<small <?php if($var['pageIndex'] =="1") {echo'class="on"';} ?>><?php echo $plxPlugin->getLang('L_KEYWORDS_INDEX_FROM_ARTICLE') ?></small>
					</td>
				</tr>
				<?php
				}
				?>
			</table>
	<?php } ?> 
 </fieldset>

  <h3><label for="fD"><span><?php echo $plxPlugin->getLang('L_ID_CARD') ?></span></label></h3>
  <fieldset id="D">
    <legend><?php echo $plxPlugin->getLang('L_BOOK_ID_CARD') ?></legend>
    <div>
      <p><label for="uid"><?php echo $plxPlugin->getLang('L_UID') ?><small class="fs08"><?php echo $plxPlugin->getLang('L_IF_NO_ISSN_ISBN') ?></small></label><input name="uid" value="<?php echo plxUtils::strCheck($var['uid']) ?>"></p>
      <p><label for="title"><?php echo $plxPlugin->getLang('L_BOOK_TITLE') ?></label><input name="title" value="<?php echo plxUtils::strCheck($var['title']) ?>"></p>
      <p><label for="subtitle"><?php echo $plxPlugin->getLang('L_BOOK_DESC') ?></label> <input name="subtitle" value="<?php echo plxUtils::strCheck($var['subtitle']) ?>"></p>
      <p><label for="dateC"><?php echo $plxPlugin->getLang('L_FIRST_DATE_EDITION') ?></label> <input name="dateC" value="<?php echo plxUtils::strCheck($plxPlugin->getParam('dateC')) ?>"></p>
      <p><label for="author"><?php echo $plxPlugin->getLang('L_AUTHOR_S') ?></label> <input name="author" value="<?php echo plxUtils::strCheck($var['author']) ?>"></p>
	  <p><?php echo $plxPlugin->getLang('L_IF_ISSN_ISBN_IS_UID') ?></p>
      <p><label for="issn">N° ISSN </label> <input name="issn" value="<?php echo plxUtils::strCheck($plxPlugin->getParam('issn')) ?>"></p>
      <p><label for="isbn">N° ISBN </label> <input name="isbn" value="<?php echo plxUtils::strCheck($plxPlugin->getParam('isbn')) ?>"></p>
    </div>
    <div>
      <p><label for="publisher"><?php echo $plxPlugin->getLang('L_PUBLISHER') ?></label> <input name="publisher" value="<?php echo plxUtils::strCheck($plxAdmin->aUsers[$_SESSION['user']]['name']) ?>"></p>
      <p><label for="editor"><?php echo $plxPlugin->getLang('L_EDITOR') ?></label> <input name="editor" value="<?php echo plxUtils::strCheck($plugin) ; ?>"></p>
      <p><label for="dateM"><?php echo $plxPlugin->getLang('L_UPDATE_DATE') ?></label> <input name="dateM" value="<?php echo date('Y-m-d\Th:i:s\Z'); ?>"></p>
      <p><label for="copyrights"><?php echo $plxPlugin->getLang('L_COPYRIGHTS') ?></label> <input name="copyrights" value="<?php echo plxUtils::strCheck($var['copyrights']) ?>"></p>
      <p><label for="licence"><?php echo $plxPlugin->getLang('L_LICENCE_TYPE') ?></label> <input name="licence" value="<?php echo $var['licence'] ?>"></p>
      <p><label for="urlLicence"><?php echo $plxPlugin->getLang('L_LICENCE_URL') ?></label> <input name="urlLicence" value="<?php echo $var['urlLicence'] ?>"></p>
      <p><label for="descLicence"><?php echo $plxPlugin->getLang('L_LICENCE_TERMS') ?></label> <textarea name="descLicence"><?php echo $var['descLicence'] ?></textarea></p>
      <!--<p><label for="coverimg">Image de couverture   </label> <input type="file" name="coverimg"></p>-->
    </div>
  <input type="submit" name="submitD" value="Enregistrer" />
  </fieldset>

  <h3><label for="fE"><span><?php echo $plxPlugin->getLang('L_CREDITS') ?></span></label></h3>
  <fieldset id="E">
    <legend><?php echo $plxPlugin->getLang('L_CREDITS') ?></legend>
    <div>
      <p><label for="ctxt"><?php echo $plxPlugin->getLang('L_PREFACE_AUTHOR') ?></label> <input name="ctxt" value="<?php echo plxUtils::strCheck($plxPlugin->getParam('ctxt')) ?>"> </p>
      <p><label for="cread"><?php echo $plxPlugin->getLang('L_PROOFREADER_S') ?></label> <input name="cread" value="<?php echo plxUtils::strCheck($plxPlugin->getParam('cread')) ?>"> </p>
      <p><label for="cimg"><?php echo $plxPlugin->getLang('L_SRC_&_ILLUSTRATION') ?></label> <textarea name="cimg"><?php echo plxUtils::strCheck($plxPlugin->getParam('cimg')) ?></textarea></p>
      <p><label for="ctrslt"><?php echo $plxPlugin->getLang('L_TRANSLATION') ?></label> <input name="ctrslt" value="<?php echo plxUtils::strCheck($plxPlugin->getParam('ctrslt')) ?>"> </p>
    </div>
    <div>
      <p><label for="cbiblio"><?php echo $plxPlugin->getLang('L_BIBLIOGRAPHY') ?></label> <textarea name="cbiblio"><?php echo plxUtils::strCheck($plxPlugin->getParam('cbiblio')) ?></textarea> </p>
      <p><label for="clayout"><?php echo $plxPlugin->getLang('L_DESIGN_&_LAYOUT') ?></label> <input name="clayout" value="<?php echo plxUtils::strCheck($plxPlugin->getParam('clayout')) ?>"> </p>
      <p><label for="ctool"><?php echo $plxPlugin->getLang('L_CREATED_WITHT') ?></label> <input name="ctool" value="<?php echo plxUtils::strCheck($plxPlugin->getParam('ctool')) ?>"> </p>
      <p><label for="ccover"><?php echo $plxPlugin->getLang('L_COVER_IMG') ?></label> <input name="ccover"> </p>
    </div>
  <input type="submit" name="submitE" value="Enregistrer" />
  </fieldset>

  <h3><label for="fF"><span><?php echo $plxPlugin->getLang('L_AVALAIBLE_THEMES') ?></span></label></h3>
  <fieldset id="F">
    <legend><?php echo $plxPlugin->getLang('L_COVER_&_DESIGN') ?></legend>
	  <div id="coverslider">	
	<?php
				$i="0";
				foreach($themesList as $themes => $theme) {
					$i++;
					$thi = 'th'.$i;
					$p= $i - 1;
					$n = $i + 1;
					echo  '<div id="coverth'.$i.'">';
					echo'<h3 style="grid-column:1/-1;"> theme '.$i.'</h3>';
					if($i>1) {echo '<a class="previous" href="#coverth'.$p .'">&#11013;</a>'.PHP_EOL;}
					echo '	<figure>
					<!--<figcaption><label>cover theme '.$i.' <input type="radio" name="coverImage"></label></figcaption>-->
					<img src="'.$imgPath.$thi.'/cover.jpg" style="max-width:800px;width:100%;">';
					echo '	</figure>
					<object data="'.$imgPath.$thi.'/test.html"></object>';
					if($i < sizeof($themesList)) {echo '<a class="next" href="#coverth'.$n .'">&#10145;</a>';} else {echo '<a class="next" href="#coverth1">&#10145;</a>';}
					echo '</div>';
					//echo 'img src="'.$imgPath.$thi.'cover.jpg" - themes' .$themes .' theme - '.$theme.'<br>';
				}
	?>
	<!-- voir pour ajout theme perso / upload img,font + config makeimage()-->
	<!--<input type="submit" name="submitF" value="Enregistrer" />-->
	  <input type="submit" name="updatecovers" value="<?php echo $plxPlugin->getLang('L_UPDATE_COVERS') ?>" />
	</div>
	<style>
	</style>
  </fieldset>
  <?php echo plxToken::getTokenPostMethod() ?>

  <h3><label for="fB"><span><?php echo $plxPlugin->getLang('L_HELP') ?></span></label></h3>
  <fieldset id="B">
    <legend><?php echo $plxPlugin->getLang('L_HELP') ?></legend>
    <!--<input type="submit" name="submitB" value="Enregistrer" />-->
    <p class="fullWidth"><b><?php echo $plxPlugin->getLang('L_PLUG_VERSION') ?></b></p>
    <div>
	<h2><?php echo $plxPlugin->getLang('L_DISPLAY_OPTIONS') ?></h2>
    <p><?php echo $plxPlugin->getLang('L_CHOICE_FRONT_DISPLAY') ?></p>
	</div>
	<div>
    <h2><?php echo $plxPlugin->getLang('L_ID_CARD') ?></h2>
    <p><?php echo $plxPlugin->getLang('L_ID_USE') ?></p>	
	</div>
	<div>
    <h2><?php echo $plxPlugin->getLang('L_CREDITS') ?></h2>
    <p><?php echo $plxPlugin->getLang('L_CREDITS_USE') ?></p>
	</div>
	<div>
    <h2><?php echo $plxPlugin->getLang('L_THEMES') ?></h2>
    <p><?php echo $plxPlugin->getLang('L_THEMES_USE') ?></p>
    <ol style="list-style-position:inside">
		<?php echo $plxPlugin->getLang('L_THEMES_DETAILS') ?>
    </ol>
	</div>
	<div class="fullWidth">
	<?php include(PLX_PLUGINS.$plugin.'/lang/fr-help.php'); ?>
	</div>
  </fieldset>
</form>
<!-- script kept here. vars are updated from plugin parameters -->
<script>(function () {
  // get references to select list and display text box
  let sel = document.getElementById("epubMode");  
  let sel2 = document.getElementById("triAuthors");  
  let calConfig = {magMY: <?php echo $var['magMY'] ?>, magTY: <?php echo $var['magTY'] ?>, magSY: <?php echo $var['magSY'] ?>, magAY:<?php echo $var['magAY'] ?>, magMM : <?php echo $var['magMM'] ?>, magTM: <?php echo $var['magTM'] ?>, magSM:<?php echo $var['magSM'] ?>, triAuthors: '<?php echo $var['triAuthors']  ?>'};


  function loopOption(sel) {
    var opt;
    var formAdd;
    for (var i = 0, len = sel.options.length; i < len; i++) {
      opt = sel.options[i];
      let idobj=sel.options[i].value ;
      let formAdd = document.getElementById(idobj);
      if (opt.selected === true) {
        formAdd.classList.remove("hide");
      } else {
       formAdd.classList.add("hide");
      }
    }
  }
  sel.onchange = function(){loopOption(sel); }
  sel2.onchange = function(){loopOption(sel2); }
  window.onload =  function(){
	  loopOption(sel2);
	  loopOption(sel);
  }

	for (let e of document.querySelectorAll('#magMY, #magMM,#magTY, #magTM,#magSY, #magSM,#magAY,#triAuthors')) {
		let setVl=  e.getAttribute('name');
		e.value = calConfig[setVl];  
	}

})();
</script>
