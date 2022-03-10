<?php  if(!defined('PLX_ROOT')) exit; 
$plugName= basename(dirname(__FILE__));
//recuperation nom epub à effacer
$FileToDelete='';
if($_SESSION['profil']=='0') {
	if ( parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) !="") { 
		$FileToDelete = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY));
	}
 }
# récupération d'une instance de plxShow
$plxShow = plxShow::getInstance();
$plxPlugin = $plxShow->plxMotor->plxPlugins->getInstance($plugName);

# Si le fichier de langue n'existe pas
$lang = $plxShow->plxMotor->aConf['default_lang'];
if(!file_exists(PLX_PLUGINS.$plugName.'/lang/'.$lang.'.php')) {
	echo '<p>'.sprintf($plxPlugin->getLang('L_LANG_UNAVAILABLE'), PLX_PLUGINS.$plugName.'/lang/'.$lang.'.php').'</p>';
	return;
}
	$values['mother']="0";	// compatibilite plugin  plx-gc-categories	valeur par défaut 
echo '<link rel="stylesheet" type="text/css" href="'.PLX_PLUGINS.$plugName.'/css/site.css?t='.time().'" media="screen">';

?><section>
	<div id ="eboooks">
		<h2><?php $plxPlugin->lang('L_DL_EPUBS') ?></h2>
		<div><?php echo plxUtils::cdataCheck($plxPlugin->getParam('custom-start'));?></div>		
	<?php 
	$file = '*.epub';
	$dir = PLX_ROOT. trim(str_replace('../../', '',$plxPlugin->getParam('epubRepertory')));
	$sorted_array = listdir_by_date($dir.'/'.$file);
	function listdir_by_date($pathtosearch) {
		$i="0";
		foreach (glob($pathtosearch) as $filename){
			$i++;
			$file_array[$filename]=filectime($filename).'.'.$i; // or just $filename
		}
		natcasesort($file_array);
		return $file_array;
	}
	echo '<ol id="epubs">'.PHP_EOL;
		$i="0";
		foreach($sorted_array as $book => $val) {
			 if (isset($_SESSION['profil']) && $_SESSION['profil']=='0' && $FileToDelete == basename($book))  {
				unlink($book);
				echo '<li><s>'.$FileToDelete .'</s></li>';
				continue;
			 }
			
			$i++;
			echo '	<li><div><a href="'.$book.'">'.basename($book).'</a>';
			 if (isset($_SESSION['profil']) && $_SESSION['profil']=='0')  {
				 echo ' <a href="'.$_SERVER['REQUEST_URI'].'?del='.$book.'" class="delete" title="DELETE">X</a>';
				 } 
			$za = new ZipArchive();
			$za->open($book);
			for ($i = 0; $i < $za->numFiles; $i++)    {
			$stat = $za->statIndex($i);
			if ($stat['size'])
				$ext = pathinfo($stat['name'], PATHINFO_EXTENSION);
				if($ext=='opf'){        
					//echo '<b style="color:red">Trouvé! '. $stat['name'].'</b>';
					$opfFile=$stat['name'];
					$rootOpf= trim(dirname($opfFile));		
					$opfzip = zip_open($book);
					if ($opfzip) {
						while ($zip_entry = zip_read($opfzip)) {        
							if (zip_entry_name($zip_entry) == $opfFile) {
								if (zip_entry_open($opfzip, $zip_entry, "r")) { 
									$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));											
									zip_entry_close($zip_entry);                            
								}
							}
						}
					$package = simplexml_load_string("$buf");
					if(count($package->metadata->children('dc', true)->creator) > 1) {
						echo '<p><small><small>';echo $plxPlugin->lang('L_AUTHORS'); echo ':</small></small> - ';
						foreach($package->metadata->children('dc', true)->creator as $auts) {
							echo  $auts.' - ';
							} 
							echo '</p>';
							} else {
							echo '<p ><small>';echo $plxPlugin->lang('L_AUTHORS'); echo ':</small> '. $package->metadata->children('dc', true)->creator.'</p>';                
							}
					echo '<p><small>';echo $plxPlugin->lang('L_TITLE'); echo ': </small> '. $package->metadata->children('dc', true)->title.'</p>';
					echo '<p><small>';echo $plxPlugin->lang('L_DESC' ); echo ': </small> '.$package->metadata->children('dc', true)->description.'</p>';
					echo '<p><small>';echo $plxPlugin->lang('L_DATE' ); echo ': </small> '.date("d-M-Y", strtotime($package->metadata->children('dc', true)->date )).'</p></div>';

					zip_close($opfzip);
					}  
					$ii=0;
					foreach($package->manifest->item as $item){
						$ii++;
						foreach($item->attributes() as $a => $b) {
							$itemattributes[$ii][$a]= $b;
						}
						if(
							isset($itemattributes[$ii]['properties'])
							&& $itemattributes[$ii]['properties']=="cover-image"
						  )
						{	
						$pathimg= $rootOpf.'/'.$item->attributes()->href;
						if(substr($pathimg, 0 , 2 ) =='./') { $pathimg= substr($pathimg, 2 , 0 ); }
							showImg($book, $pathimg);
						}
						elseif(
						   isset($itemattributes[$ii]['id'])
						   && $itemattributes[$ii]['id']=="cover"
						   && isset($itemattributes[$ii]['media-type'])
						   && substr($itemattributes[$ii]['media-type'] , 0, 5)=="image"
						   && isset($itemattributes[$ii]['href'])
						  ) 
						{ 
						$pathimg= $rootOpf.'/'.$item->attributes()->href;
						if(substr($pathimg, 0 , 2 ) =='./') { $pathimg= substr($pathimg, 2 , 255 ); }
							showImg($book, $pathimg);
						} 
					} 
				}  
			} 
			echo '</li>'.PHP_EOL;			
		}
	echo '<li><div>'. plxUtils::cdataCheck($plxPlugin->getParam('custom-end')) .'</div></li>'.PHP_EOL ;  
	echo '</ol>'.PHP_EOL;	
	
   function showImg($epub,$img) {
	    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
		$allow = array('gif', 'png', 'jpg','jpeg','webp','bmp','tif','tiff');

		$zip = zip_open($epub);
			if ($zip) {
				while ($zip_entry = zip_read($zip)) {        
					if (zip_entry_name($zip_entry) == $img) {
						if (zip_entry_open($zip, $zip_entry, "r")) { 
							$img = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							// verif si c'est bien une image 
							if (in_array($ext, $allow)) {
							// encode data for img src.
							$img = base64_encode($img);
							echo '<img src="data:image/jpeg;base64,'.$img.'" class="imgcover" >';
							}
							zip_entry_close($zip_entry);
						}
					}
				}
			zip_close($zip);  
		}
	}
?>		
	</div>
</section>