<?php
/**
 * Plugin E-Book
 **/
class EBook extends plxPlugin {

	private $url = ''; # parametre de l'url pour accèder à la page de telechargement des epubs
	public $lang = '';
	public $epubRepertory = PLX_ROOT.'EPUBS';
	

	/**
	 * Constructeur de la classe
	 *
	 * @param	default_lang	langue par défaut
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

		# appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		$this->url = $this->getParam('url')=='' ? 'EBook' : $this->getParam('url');
		

		# droits pour accèder à la page config.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);
		
		# limite l'accès à l'écran d'administration du plugin
        $this->setAdminProfil(PROFIL_ADMIN);        
		$this->setAdminMenu( ' '. $this->getLang("L_MENU_CATEGORIES").''  , 20,  ''.$this->getLang("L_MENU_CATEGORIES").'');

		# déclaration des hooks
		$this->addHook('AdminTopBottom', 'AdminTopBottom');

		# Si le fichier de langue existe on peut mettre en place la partie visiteur
		//if(file_exists(PLX_PLUGINS.$this->plug['name'].'/lang/'.$default_lang.'.php')) {			 
				$this->addHook('plxMotorPreChauffageBegin', 'plxMotorPreChauffageBegin');
				$this->addHook('plxShowConstruct', 'plxShowConstruct');
				$this->addHook('plxShowStaticListEnd', 'plxShowStaticListEnd');
				$this->addHook('plxShowPageTitle', 'plxShowPageTitle');			 
		//}
	}
	#code à exécuter à l’activation du plugin
	/* repertoire par defaut de stockage des epubs */	
        public function OnActivate() { 
			if (!file_exists(PLX_ROOT.'EPUBS')) {
				mkdir(PLX_ROOT.'EPUBS', 0777, true);
			}
		}	
		


	/**
	 * Méthode qui affiche un message si laa langue n'est pas disponible
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function AdminTopBottom() {
		echo '<?php
		$file = PLX_PLUGINS."EBook/lang/".$plxAdmin->aConf["default_lang"].".php";
		if(!file_exists($file)) {
			echo "<p class=\"warning\">Plugin EBook<br />".sprintf("'.$this->getLang('L_LANG_UNAVAILABLE').'", $file)."</p>";
			plxMsg::Display();
		}
		?>';
	}

	/**
	 * Méthode de traitement du hook plxShowConstruct
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function plxShowConstruct() {

		# infos sur la page statique
		$string  = "if(\$this->plxMotor->mode=='".$this->url."') {";
		$string .= "	\$array = array();";
		$string .= "	\$array[\$this->plxMotor->cible] = array(
			'name'		=> '".addslashes($this->getParam('mnuName'))."',
			'menu'		=> '',
			'url'		=> 'ebook',
			'readable'	=> 1,
			'active'	=> 1,
			'group'		=> ''
		);";
		$string .= "	\$this->plxMotor->aStats = array_merge(\$this->plxMotor->aStats, \$array);";
		$string .= "}";
		echo "<?php ".$string." ?>";
	}

	/**
	 * Méthode de traitement du hook plxMotorPreChauffageBegin
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function plxMotorPreChauffageBegin() {


		$template = $this->getParam('template')==''?'static.php':$this->getParam('template');

		$string = "
		if(\$this->get && preg_match('/^".$this->url."\/?/',\$this->get)) {
			\$this->mode = '".$this->url."';
			\$prefix = str_repeat('../', substr_count(trim(PLX_ROOT.\$this->aConf['racine_statiques'], '/'), '/'));
			\$this->cible = \$prefix.'plugins/EBook/epub';
			\$this->template = '".$template."';
			return true;
		}
		";

		echo "<?php ".$string." ?>";
	}

	/**
	 * Méthode de traitement du hook plxShowStaticListEnd
	 *
	 * @return	stdio
	 * @author	Bazooka07  https://forum.pluxml.org/discussion/7134/integration-plugin-comme-page-statique-beneficiant-de-la-variable-format
	 **/
public function plxShowStaticListEnd() {

    # ajout au menu pour accèder à la page ebook
    if($this->getParam('mnuDisplay')) {
        # $this correspond au plugin
        $url = $this->lang . $this->url;; 
        $pos = intval($this->getParam('mnuPos')) - 1;
        $name = $this->getParam('mnuName');
        $description = $this->getParam('description');
        # valeur par défaut de $format dans plxShow::staticList() :
        # <li class="#static_class #static_status" id="#static_id"><a href="#static_url" title="#static_name">#static_name</a></li>
        echo '<?php' . PHP_EOL;
?>
		# Injection de code par le plugin  '<?= __CLASS__  ?>'
		$stat = strtr($format, array(
			'#static_class'     	=> 'static menu',
			'#static_status'    	=> ($this->plxMotor->mode==  '<?= $url ?>' ) ? 'active' : 'noactive', 
			'#static_id'        	=> 'static-<?= __CLASS__ ?>'  , 
			'#static_url'       	=> $this->plxMotor->urlRewrite('?<?= $url ?>'), 
			'#static_name'      	=>  '<?= $name ?>' ,
			'#static_description'   =>  '<?= $description ?>' ,
		));
		array_splice($menus,  '<?= $pos ?>' , 0, array($stat));
<?php
        echo PHP_EOL . '?>';
    }
}



	/**
	 * Méthode qui rensigne le titre de la page dans la balise html <title>
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function plxShowPageTitle() {
		echo '<?php
			if($this->plxMotor->mode == "'.$this->url.'") {
				$this->plxMotor->plxPlugins->aPlugins["EBook"]->lang("L_PAGE_TITLE");
				return true;
			}
		?>';
	}

	/**
	* Fonctions internes au plugin
	*
	* nettoyage de chaines
	* generation des pages de l'e-book
	* generation des fichiers
	* @author Cyrille G. 
	*/
	
	
	/**
	* retire les accents
	* source : // source = https://github.com/WordPress/WordPress/blob/a2693fd8602e3263b5925b9d799ddd577202167d/wp-includes/formatting.php#L152
	*/
	public function remove_accents($string) { // source = https://github.com/WordPress/WordPress/blob/a2693fd8602e3263b5925b9d799ddd577202167d/wp-includes/formatting.php#L1528
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}

	/**
	* nettoie les chaines avant insertion dans les pages epub
	*
	* traite le fichier comme une chaine et la modifie en fonction des routine préetablies. ajouter les votres au cas par cas.
	*
	* @author Cyrille G.
	*/
    public function cleanUp( $trA) { // traite le fichier comme une chaine et la modifie en fonction des routines préetablies. ajouter les votres au cas par cas.

// add here other thingy bobs being a drag inside an ebook ... youtube insert and else or any outside scripts or ressource from all kinds off plugins/apis	
	$trA = str_replace('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css" >','',$trA); //one of the fontawesome CDN  
	
// nettoyage et mise conformité xhtml autant que possible, liste de routines non exhaustive.				
	$trA = str_replace('<![CDATA[', '', $trA);
	$trA = str_replace(']]>','',$trA);									
//	$trA = str_replace(' string_to_remove_or_modify ',' empty_oo_modified ',$trA);	// copy and update with your the value you want to modify or be removed
//	voir si ajout possible dans une page 'configuration avançées'
	
// about path inside ebooks not alike PluXml							
	$trA = str_replace('src="/data','src="data',$trA);					// update path							
	$trA = str_replace('src="/images','src="images',$trA);				// update path							
//	$trA = str_replace('src="/','src="',$trA);							// update path ... 
	
// about cleaning up styles,search and remove or update comments and self closing tags
	$trA = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $trA); 		// remove <style>
	$trA = preg_replace('#<script(.*?)/>#is','<script $1 ></script>', $trA);// format tag <script>
//	$trA = preg_replace('#<script(.*?)>(.*?)</script>#is','', $trA);		// remove <script> if removed, don not generate inside epub pages the attribute/value :  properties="scripted"
	$trA = preg_replace('#<!--(.*?)>(.*?)-->#is', '', $trA);				// remove comment
	$trA = preg_replace('#style="(.*?)"#is', '', $trA);						// remove inline style
	$trA = preg_replace('#<br(.*?)>#is', '<br/>', $trA);					// xhtml requirement
	$trA = preg_replace('#<area(.*?)>#is', '<area $1/>', $trA);				// xhtml requirement
	$trA = preg_replace('#<base(.*?)>#is', '<base $1/>', $trA);				// xhtml requirement
	$trA = preg_replace('#<col(.*?)>#is', '<col $1/>', $trA);				// xhtml requirement
	$trA = preg_replace('#<hr(.*?)>#is', '<hr $1/>', $trA);					// xhtml requirement
	$trA = preg_replace('#<embed(.*?)>#is', '<embed $1/>', $trA);			// xhtml requirement
	$trA = preg_replace('#<source(.*?)>#is', '<source $1/>', $trA);			// xhtml requirement
	$trA = preg_replace('#<track(.*?)>#is', '<track $1/>', $trA);			// xhtml requirement
	$trA = preg_replace('#<wbr(.*?)>#is', '<wbr $1/>', $trA);				// xhtml requirement
	$trA = preg_replace('#<link(.*?)>#is', '<link $1/>', $trA);				// xhtml requirement
	$trA = preg_replace('#<meta(.*?)>#is', '<meta $1/>', $trA);				// xhtml requirement
	$trA = preg_replace('#<param(.*?)>#is', '<param $1/>', $trA);			// xhtml requirement
	$trA = preg_replace('#<img(.*?)>#is', '<img $1/>', $trA);				// xhtml requirement
	$trA = preg_replace('#<input(.*?)>#is', '<input $1/>', $trA);			// xhtml requirement

// about alternative // needs feedback and tests
	$trA = preg_replace('#<video(.*?)>(.*?)<video>#is', '<video $1/> $2 <p>Votre liseuse ne peut pas afficher cette vidéo.</p></video> ', $trA);		// xhtml requirement
	$trA = preg_replace('#<audio(.*?)>(.*?)<audio>#is', '<audio $1/> $2 <p>Votre liseuse ne peut pas lire ce fichier audio.</p></audio> ', $trA);		// xhtml requirement
	
// On reste en utf-8 accent et caractéres spéciaux en htmlentities retranscrits, indépendant de votre police.
	$trA = str_replace('&quot;', '"', $trA);            // ((double) quotation mark)
//	$trA = str_replace('&amp;', '&', $trA);             // (ampersand) This one should be kept
	$trA = str_replace('&apos;', '\'', $trA);           // (apostrophe  = apostrophe-quote)
	$trA = str_replace('&lt;', '&lsaquo;',$trA);        // (less-than sign TURNED into single left angle quotation for epub compatibility )
	$trA = str_replace('&gt;', '>',$trA);               // (greater-than sign)
	$trA = str_replace('&nbsp;', ' ',$trA);             // (non-breaking space)
	$trA = str_replace('&iexcl;', '¡',$trA);            // (inverted exclamation mark)
	$trA = str_replace('&cent;', '¢',$trA);             // (cent)
	$trA = str_replace('&pound;', '£',$trA);            //  (pound)
	$trA = str_replace('&curren;', '¤',$trA);           //  (currency)
	$trA = str_replace('&yen;', '¥',$trA);              //  (yen)
	$trA = str_replace('&brvbar;', '¦',$trA);           //  (broken vertical bar)
	$trA = str_replace('&sect;', '§',$trA);             //  (section)
	$trA = str_replace('&uml;', '¨',$trA);              //  (spacing diaeresis)
	$trA = str_replace('&copy;', '©',$trA);             //  (copyright)
	$trA = str_replace('&ordf;', 'ª',$trA);             //  (feminine ordinal indicator)
	$trA = str_replace('&laquo;', '«',$trA);            //  (angle quotation mark (left))
	$trA = str_replace('&not;', '¬',$trA);              //  (negation)
	$trA = str_replace('&shy;', ' ',$trA);              // (soft hyphen)
	$trA = str_replace('&reg;', '®',$trA);              //  (registered trademark)
	$trA = str_replace('&macr;', '¯',$trA);             //  (spacing macron)
	$trA = str_replace('&deg;', '°',$trA);              // (degree)
	$trA = str_replace('&plusmn;', '±',$trA);           //  (plus-or-minus)
	$trA = str_replace('&sup2;', '²',$trA);             // (superscript 2)
	$trA = str_replace('&sup3;', '³',$trA);             //  (superscript 3)
	$trA = str_replace('&acute;', '´',$trA);            //  (spacing acute)
	$trA = str_replace('&micro;', 'µ',$trA);            //  (micro)
	$trA = str_replace('&para;', '¶',$trA);             //  (paragraph)
	$trA = str_replace('&middot;', '·',$trA);           //  (middle dot)
	$trA = str_replace('&cedil;', '¸',$trA);            //  (spacing cedilla)
	$trA = str_replace('&sup1;', '¹',$trA);             //  (superscript 1)
	$trA = str_replace('&ordm;', 'º',$trA);             //  (masculine ordinal indicator)
	$trA = str_replace('&raquo;', '»',$trA);            //  (angle quotation mark (right))
	$trA = str_replace('&frac14;', '¼',$trA);           //  (fraction 1/4)
	$trA = str_replace('&frac12;', '½',$trA);           //  (fraction 1/2)
	$trA = str_replace('&frac34;', '¾',$trA);           //  (fraction 3/4)
	$trA = str_replace('&iquest;', '¿',$trA);           //  (inverted question mark)
	$trA = str_replace('&Agrave;', 'À',$trA);           //  (capital a, grave accent)
	$trA = str_replace('&Aacute;', 'Á',$trA);           //  (capital a, acute accent)
	$trA = str_replace('&Acirc;', 'Â',$trA);            //  (capital a, circumflex accent)
	$trA = str_replace('&Atilde;', 'Ã',$trA);           //  (capital a, tilde)
	$trA = str_replace('&Auml;', 'Ä',$trA);             //  (capital a, umlaut mark)
	$trA = str_replace('&Aring;', 'Å',$trA);            //  (capital a, ring)
	$trA = str_replace('&AElig;', 'Æ',$trA);            //  (capital ae)
	$trA = str_replace('&Ccedil;', 'Ç',$trA);           //  (capital c, cedilla)
	$trA = str_replace('&Egrave;', 'È',$trA);           //  (capital e, grave accent)
	$trA = str_replace('&Eacute;', 'É',$trA);           //  (capital e, acute accent)
	$trA = str_replace('&Ecirc;', 'Ê',$trA);            //  (capital e, circumflex accent)
	$trA = str_replace('&Euml;', 'Ë',$trA);             //  (capital e, umlaut mark)
	$trA = str_replace('&Igrave;', 'Ì',$trA);           //  (capital i, grave accent)
	$trA = str_replace('&Iacute;', 'Í',$trA);           //  (capital i, acute accent)
	$trA = str_replace('&Icirc;', 'Î',$trA);            //  (capital i, circumflex accent)
	$trA = str_replace('&Iuml;', 'Ï',$trA);             //  (capital i, umlaut mark)
	$trA = str_replace('&ETH;', 'Ð',$trA);              // (capital eth, Icelandic)
	$trA = str_replace('&Ntilde;', 'Ñ',$trA);           //  (capital n, tilde)
	$trA = str_replace('&Ograve;', 'Ò',$trA);           //  (capital o, grave accent)
	$trA = str_replace('&Oacute;', 'Ó',$trA);           //  (capital o, acute accent)
	$trA = str_replace('&Ocirc;', 'Ô',$trA);            //  (capital o, circumflex accent)
	$trA = str_replace('&Otilde;', 'Õ',$trA);           //  (capital o, tilde)
	$trA = str_replace('&Ouml;', 'Ö',$trA);             //  (capital o, umlaut mark)
	$trA = str_replace('&times;', '×',$trA);            //  (multiplication)
	$trA = str_replace('&Oslash;', 'Ø',$trA);           //  (capital o, slash)
	$trA = str_replace('&Ugrave;', 'Ù',$trA);           //  (capital u, grave accent)
	$trA = str_replace('&Uacute;', 'Ú',$trA);           //  (capital u, acute accent)
	$trA = str_replace('&Ucirc;', 'Û',$trA);            //  (capital u, circumflex accent)
	$trA = str_replace('&Uuml;', 'Ü',$trA);             //  (capital u, umlaut mark)
	$trA = str_replace('&Yacute;', 'Ý',$trA);           //  (capital y, acute accent)
	$trA = str_replace('&THORN;', 'Þ',$trA);            //  (capital THORN, Icelandic)
	$trA = str_replace('&szlig;', 'ß',$trA);            //  (small sharp s, German)
	$trA = str_replace('&agrave;', 'à',$trA);           //  (small a, grave accent)
	$trA = str_replace('&aacute;', 'á',$trA);           //  (small a, acute accent)
	$trA = str_replace('&acirc;', 'â',$trA);            //  (small a, circumflex accent)
	$trA = str_replace('&atilde;', 'ã',$trA);           //  (small a, tilde)
	$trA = str_replace('&auml;', 'ä',$trA);             //  (small a, umlaut mark)
	$trA = str_replace('&aring;', 'å',$trA);            //  (small a, ring)
	$trA = str_replace('&aelig;', 'æ',$trA);            //  (small ae)
	$trA = str_replace('&ccedil;', 'ç',$trA);           //  (small c, cedilla)
	$trA = str_replace('&egrave;', 'è',$trA);           //  (small e, grave accent)
	$trA = str_replace('&eacute;', 'é',$trA);           //  (small e, acute accent)
	$trA = str_replace('&ecirc;', 'ê',$trA);            //  (small e, circumflex accent)
	$trA = str_replace('&euml;', 'ë',$trA);             //  (small e, umlaut mark)
	$trA = str_replace('&igrave;', 'ì',$trA);           //  (small i, grave accent)
	$trA = str_replace('&iacute;', 'í',$trA);           //  (small i, acute accent)
	$trA = str_replace('&icirc;', 'î',$trA);            //  (small i, circumflex accent)
	$trA = str_replace('&iuml;', 'ï',$trA);             //  (small i, umlaut mark)
	$trA = str_replace('&eth;', 'ð',$trA);              //  (small eth, Icelandic)
	$trA = str_replace('&ntilde;', 'ñ',$trA);           //  (small n, tilde)
	$trA = str_replace('&ograve;', 'ò',$trA);           //  (small o, grave accent)
	$trA = str_replace('&oacute;', 'ó',$trA);           //  (small o, acute accent)
	$trA = str_replace('&ocirc;', 'ô',$trA);            //  (small o, circumflex accent)
	$trA = str_replace('&otilde;', 'õ',$trA);           //  (small o, tilde)
	$trA = str_replace('&ouml;', 'ö',$trA);             //  (small o, umlaut mark)
	$trA = str_replace('&divide;', '÷',$trA);           //  (division)
	$trA = str_replace('&oslash;', 'ø',$trA);           //  (small o, slash)
	$trA = str_replace('&ugrave;', 'ù',$trA);           //  (small u, grave accent)
	$trA = str_replace('&uacute;', 'ú',$trA);           //  (small u, acute accent)
	$trA = str_replace('&ucirc;', 'û',$trA);            //  (small u, circumflex accent)
	$trA = str_replace('&uuml;', 'ü',$trA);             // (small u, umlaut mark)
	$trA = str_replace('&yacute;', 'ý',$trA);           // (small y, acute accent)
	$trA = str_replace('&thorn;', 'þ',$trA);            // (small thorn, Icelandic)
	$trA = str_replace('&yuml;', 'ÿ',$trA);             // (small y, umlaut mark)
	$trA = str_replace('&OElig;', 'Œ',$trA);            //  (capital ligature OE)
	$trA = str_replace('&oelig;', 'œ',$trA);            // (small ligature oe)
	$trA = str_replace('&Scaron;', 'Š',$trA);           //(capital S with caron)
	$trA = str_replace('&scaron;', 'š',$trA);           //(small S with caron)
	$trA = str_replace('&Yuml;', 'Ÿ',$trA);             //(capital Y with diaeres)
	$trA = str_replace('&fnof;', 'ƒ',$trA);             //(f with hook)
	$trA = str_replace('&circ;', 'ˆ',$trA);             // (modifier letter circumflex accent)
	$trA = str_replace('&tilde;', '˜',$trA);             //(small tilde)
	$trA = str_replace('&Alpha;', 'Α',$trA);            // (Alpha)
	$trA = str_replace('&Beta;', 'Β',$trA);             // (Beta)
	$trA = str_replace('&Gamma;', 'Γ',$trA);            //(Gamma)
	$trA = str_replace('&Delta;', 'Δ',$trA);            // (Delta)
	$trA = str_replace('&Epsilon;', 'Ε',$trA);          //(Epsilon)
	$trA = str_replace('&Zeta;', 'Ζ',$trA);             // (Zeta)
	$trA = str_replace('&Eta;', 'Η',$trA);              // (Eta)
	$trA = str_replace('&Theta;', 'Θ',$trA);            // (Theta)
	$trA = str_replace('&Iota;', 'Ι',$trA);             // (Iota)
	$trA = str_replace('&Kappa;', 'Κ',$trA);            // (Kappa)
	$trA = str_replace('&Lambda;', 'Λ',$trA);           // (Lambda)
	$trA = str_replace('&Mu;', 'Μ',$trA);               // (Mu)
	$trA = str_replace('&Nu;', 'Ν',$trA);               // (Nu)
	$trA = str_replace('&Xi;', 'Ξ',$trA);               // (Xi)
	$trA = str_replace('&Omicron;', 'Ο',$trA);          // (Omicron)
	$trA = str_replace('&Pi;', 'Π',$trA);               // (Pi)
	$trA = str_replace('&Rho;', 'Ρ',$trA);              // (Rho)
	$trA = str_replace('&Sigma;', 'Σ',$trA);            // (Sigma)
	$trA = str_replace('&Tau;', 'Τ',$trA);              // (Tau)
	$trA = str_replace('&Upsilon;', 'Υ',$trA);          // (Upsilon)
	$trA = str_replace('&Phi;', 'Φ',$trA);              // (Phi)
	$trA = str_replace('&Chi;', 'Χ',$trA);              // (Chi)
	$trA = str_replace('&Psi;', 'Ψ',$trA);              // (Psi)
	$trA = str_replace('&Omega;', 'Ω',$trA);            // (Omega)
	$trA = str_replace('&alpha;', 'α',$trA);            // (alpha)
	$trA = str_replace('&beta;', 'β',$trA);             // (beta)
	$trA = str_replace('&gamma;', 'γ',$trA);            // (gamma)
	$trA = str_replace('&delta;', 'δ',$trA);            // (delta)
	$trA = str_replace('&epsilon;', 'ε',$trA);          // (epsilon)
	$trA = str_replace('&zeta;', 'ζ',$trA);             // (zeta)
	$trA = str_replace('&eta;', 'η',$trA);              // (eta)
	$trA = str_replace('&theta;', 'θ',$trA);            // (theta)
	$trA = str_replace('&iota;', 'ι',$trA);             // (iota)
	$trA = str_replace('&kappa;', 'κ',$trA);            // (kappa)
	$trA = str_replace('&lambda;', 'λ',$trA);           // (lambda)
	$trA = str_replace('&mu;', 'μ',$trA);               // (mu)
	$trA = str_replace('&nu;', 'ν',$trA);               // (nu)
	$trA = str_replace('&xi;', 'ξ',$trA);               // (xi)
	$trA = str_replace('&omicron;', 'ο',$trA);          // (omicron)
	$trA = str_replace('&pi;', 'π',$trA);               // (pi)
	$trA = str_replace('&rho;', 'ρ',$trA);              // (rho)
	$trA = str_replace('&sigmaf;', 'ς',$trA);           // (sigmaf)
	$trA = str_replace('&sigma;', 'σ',$trA);            // (sigma)
	$trA = str_replace('&tau;', 'τ',$trA);              // (tau)
	$trA = str_replace('&upsilon;', 'υ',$trA);          // (upsilon)
	$trA = str_replace('&phi;', 'φ',$trA);              // (phi)
	$trA = str_replace('&chi;', 'χ',$trA);              // (chi)
	$trA = str_replace('&psi;', 'ψ',$trA);              // (psi)
	$trA = str_replace('&omega;', 'ω',$trA);            // (omega)
	$trA = str_replace('&thetasym;', 'ϑ',$trA);         // (theta symbol)
	$trA = str_replace('&upsih;', 'ϒ',$trA);            // (upsilon symbol)
	$trA = str_replace('&piv;', 'ϖ',$trA);              // (pi symbol)
	$trA = str_replace('&ensp;', ' ',$trA);             // (en space)
	$trA = str_replace('&emsp;', ' ',$trA);             // (em space)
	$trA = str_replace('&thinsp;', ' ',$trA);           // (thin space)
	$trA = str_replace('&zwnj;', '',$trA);              // (zero width non-joiner)
	$trA = str_replace('&zwj;', '',$trA);               // (zero width joiner)
	$trA = str_replace('&lrm;', '',$trA);               // (left-to-right mark)
	$trA = str_replace('&rlm;', '',$trA);               // (right-to-left mark)
	$trA = str_replace('&ndash;', '–',$trA);            // (en dash)
	$trA = str_replace('&mdash;', '—',$trA);            // (em dash)
	$trA = str_replace('&lsquo;', '‘',$trA);            // (left single quotation mark)
	$trA = str_replace('&rsquo;', '’',$trA);            // (right single quotation mark)
	$trA = str_replace('&sbquo;', '‚',$trA);            // (single low-9 quotation mark)
	$trA = str_replace('&ldquo;', '“',$trA);            // (left double quotation mark)
	$trA = str_replace('&rdquo;', '”',$trA);            // (right double quotation mark)
	$trA = str_replace('&bdquo;', '„',$trA);            // (double low-9 quotation mark)
	$trA = str_replace('&dagger;', '†',$trA);           // (dagger)
	$trA = str_replace('&Dagger;', '‡',$trA);           // (double dagger)
	$trA = str_replace('&bull;', '•',$trA);             // (bullet)
	$trA = str_replace('&hellip;', '…',$trA);           // (horizontal ellipsis)
	$trA = str_replace('&permil;', '‰',$trA);           // (per mille)
	$trA = str_replace('&prime;', '′',$trA);            // (minutes or prime)
	$trA = str_replace('&Prime;', '″',$trA);            // (seconds or Double Prime)
	$trA = str_replace('&lsaquo;', '‹',$trA);           // (single left angle quotation)
	$trA = str_replace('&rsaquo;', '›',$trA);           // (single right angle quotation)
	$trA = str_replace('&oline;', '‾',$trA);            // (overline)
	$trA = str_replace('&frasl;', '⁄',$trA);            // (fraction slash)
	$trA = str_replace('&euro;', '€',$trA);             // (euro)
	$trA = str_replace('&image;', 'ℑ',$trA);            // (blackletter capital I)
	$trA = str_replace('&weierp;', '℘',$trA);           // (script capital P)
	$trA = str_replace('&real;', 'ℜ',$trA);             // (blackletter capital R)
	$trA = str_replace('&trade;', '™',$trA);            // (trademark)
	$trA = str_replace('&alefsym;', 'ℵ',$trA);          // (alef)
	$trA = str_replace('&larr;', '←',$trA);             // (left arrow)
	$trA = str_replace('&uarr;', '↑',$trA);             // (up arrow)
	$trA = str_replace('&rarr;', '→',$trA);             // (right arrow)
	$trA = str_replace('&darr;', '↓',$trA);             // (down arrow)
	$trA = str_replace('&harr;', '↔',$trA);             // (left right arrow)
	$trA = str_replace('&crarr;', '↵',$trA);            // (carriage return arrow)
	$trA = str_replace('&lArr;', '⇐',$trA);             // (left double arrow)
	$trA = str_replace('&uArr;', '⇑',$trA);             // (up double arrow)
	$trA = str_replace('&rArr;', '⇒',$trA);             // (right double arrow)
	$trA = str_replace('&dArr;', '⇓',$trA);             // (down double arrow)
	$trA = str_replace('&hArr;', '⇔',$trA);             // (left right double arrow)
	$trA = str_replace('&forall;', '∀',$trA);           // (for all)
	$trA = str_replace('&part;', '∂',$trA);             // (partial differential)
	$trA = str_replace('&exist;', '∃',$trA);            // (there exists)
	$trA = str_replace('&empty;', '∅',$trA);            // (empty set)
	$trA = str_replace('&nabla;', '∇',$trA);            // (backward difference)
	$trA = str_replace('&isin;', '∈',$trA);             // (element of)
	$trA = str_replace('&notin;', '∉',$trA);            // (not an element of)
	$trA = str_replace('&ni;', '∋',$trA);               // (ni = contains as member)
	$trA = str_replace('&prod;', '∏',$trA);             // (n-ary product)
	$trA = str_replace('&sum;', '∑',$trA);              // (n-ary sumation)
	$trA = str_replace('&minus;', '−',$trA);            // (minus)
	$trA = str_replace('&lowast;', '∗',$trA);           // (asterisk operator)
	$trA = str_replace('&radic;', '√',$trA);            // (square root)
	$trA = str_replace('&prop;', '∝',$trA);             // (proportional to)
	$trA = str_replace('&infin;', '∞',$trA);            // (infinity)
	$trA = str_replace('&ang;', '∠',$trA);              // (angle)
	$trA = str_replace('&and;', '∧',$trA);              // (logical and)
	$trA = str_replace('&or;', '∨',$trA);               // (logical or)
	$trA = str_replace('&cap;', '∩',$trA);              // (intersection)
	$trA = str_replace('&cup;', '∪',$trA);              // (union)
	$trA = str_replace('&int;', '∫',$trA);              // (integral)
	$trA = str_replace('&there4;', '∴',$trA);           // (therefore)
	$trA = str_replace('&sim;', '∼',$trA);              // (similar to)
	$trA = str_replace('&cong;', '≅',$trA);             // (congruent to)
	$trA = str_replace('&asymp;', '≈',$trA);            // (approximately equal)
	$trA = str_replace('&ne;', '≠',$trA);               // (not equal)
	$trA = str_replace('&equiv;', '≡',$trA);            // (equivalent)
	$trA = str_replace('&le;', '≤',$trA);               // (less or equal)
	$trA = str_replace('&ge;', '≥',$trA);               // (greater or equal)
	$trA = str_replace('&sub;', '⊂',$trA);              // (subset of)
	$trA = str_replace('&sup;', '⊃',$trA);              // (superset of)
	$trA = str_replace('&nsub;', '⊄',$trA);             // (not subset of)
	$trA = str_replace('&sube;', '⊆',$trA);             // (subset or equal)
	$trA = str_replace('&supe;', '⊇',$trA);             // (superset or equal)
	$trA = str_replace('&oplus;', '⊕',$trA);            // (circled plus)
	$trA = str_replace('&otimes;', '⊗',$trA);           // (circled times)
	$trA = str_replace('&perp;', '⊥',$trA);             // (perpendicular)
	$trA = str_replace('&sdot;', '⋅',$trA);             // (dot operator)
	$trA = str_replace('&lceil;', '⌈',$trA);            // (left ceiling)
	$trA = str_replace('&rceil;', '⌉',$trA);            // (right ceiling)
	$trA = str_replace('&lfloor;', '⌊',$trA);           // (left floor)
	$trA = str_replace('&rfloor;', '⌋',$trA);           // (right floor)
	$trA = str_replace('&lang;', '⟨',$trA);             // (left angle bracket = bra)
	$trA = str_replace('&rang;', '⟩',$trA);             // (right angle bracket = ket)
	$trA = str_replace('&loz;', '◊',$trA);              // (lozenge)
	$trA = str_replace('&spades;', '♠',$trA);           // (spade)
	$trA = str_replace('&clubs;', '♣',$trA);            // (club)
	$trA = str_replace('&hearts;', '♥',$trA);           // (heart)
	$trA = str_replace('&diams;', '♦',$trA);            // (diamond)
	
	$trA = str_replace('&#13;', '',$trA);            // (pollution)
	
// supplement object & iframe
//	$dataObject = preg_replace("#<object(.*?)data='data:text/html,(.*?)'(.*?)> </object>#is"," $2", $trA);// tag object
//	$dataObject = base64_encode($dataObject);
	$trA = preg_replace("#<object(.*?)> </object>#is","<object $1 ><p>Votre liseuse ne permet pas de voir ce contenu.</p></object>", $trA);//  maj tag object

//	$dataIframe = preg_replace("#<iframe(.*?)src='data:text/html,(.*?)'(.*?)> </iframe>#is"," $2", $trA);// tag <iframe>
//	$dataIframe = base64_encode($dataIframe);
//	$trA = preg_replace("#<iframe(.*?)src='data:text/html,(.*?)'(.*?)> </iframe>#is","<iframe $1 data='data:text/plain;charset=utf-8;base64,".$dataIframe."'$3 ><p>Votre liseuse ne permet pas de voir ce contenu.</p></iframe>", $trA);// maj tag <iframe>

// vire et remplace xmp tags par htmlspecialchars()	
//	$xmpTag = preg_replace("#<xmp>(.*?)</xmp>#is"," $1", $trA);//contenus tag <xmp>
//	$xmpTag = htmlspecialchars($xmpTag);
//	$trA = preg_replace("#<xmp>(.*?)</xmp>#is", $xmpTag , $trA);//contenus tag <xmp>
	
	
// remove double  //  the script may have added on already well-formed self-closing tags.
	$trA = str_replace('//>','/>',$trA );				// xhtml requirement	
	
return $trA;  // renvoie la chaine traitée

}

    /*
	* ne lance une fonction qu'une seule fois ... 
	* pas utilisé / unused
	*/
	
	public function once($function){ // https://www.w3resource.com/php-exercises/php-basic-exercise-101.php
		return function (...$args) use ($function) {
			static $called = false;
			if ($called) {
				return;
			}
			$called = true;
			return $function(...$args);
		};
	}

	/*  fonctionne a partir de config
	/* aborted
	$initZip =  makeEmptyZip('mimetype', 'application/epub+zip', $ebook); 
	$once = once($initZip); 
	*/
	
	/* crée l'archive epub avec son fichier mimetype depuis le fichier config.php .
	* création fichier archive Epub de base
	*/
	public function makeEmptyZip($file,$content,$zipFile) {
		$zip = new ZipArchive;
		$res = $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		if ($res === TRUE) {
			$zip->addFromString($file, $content);
			$zip->close();
			echo  'creation archive '. $zipFile .'<br>';
		} else {
			echo 'échec création container epub avec '. $file .PHP_EOL ;
		}
	}
	
	/*
	* création repertoire racine dans l'archive epub
	*/
	public function addRepertory($repertory,$zipFile){
		$zip = new ZipArchive;
		if ($zip->open($zipFile) === TRUE) {
			if($zip->addEmptyDir($repertory)) {
			 //echo'okay<br>';
			} else {
				echo 'Impossible de créer un nouveau dossier'.$repertory.'<br>';
			}
		$zip->close();
		} else {
		echo 'Échec';
		}
	}
	
	/* 
	* ajout fichier dans l'archive à partir d'une chaine
	*/
	public function addFiletxt( $file,$content,$zipFile) {
	$zip = new ZipArchive;
	$res = $zip->open($zipFile, ZipArchive::CREATE);
	if ($res === TRUE) {
		$zip->addFromString($file, $content);
		$zip->close();
	} else {
		echo 'échec ajout ficher:'. $file .' dans '. $zipFile .'<br>';
	}
}
		
	/*
	* televerse un fichier dans l'archive
	*/
	public function addFiles( $path, $file, $zipFile) {
		$zip = new ZipArchive;
	if ($zip->open($zipFile) === TRUE) {
		$zip->addFile($path, $file);
		$zip->close();
		echo 'ok ajout ficher:'. $file .' depuis '.$path.' dans '. $zipFile .'<br>';
	} else {
		echo  'échec ajout ficher:'. $file .' depuis '.$path.'<br>';
	}
	
}

	/* verif si une valeur existe dans un tableau multidimensionnel*/
	public function checkMultiArray($array,$needle) {			
		$arrayToCheck = implode(" ",array_map(function($a) {return implode(" ",$a);},$array));
		return strpos($arrayToCheck,$needle);		
	}
	
	/*
	* telechargement récursif de repertoire dans l'archive
	* inscription des fichier dans le manifest du fichier opf
	*/
	public function addDirectories(    $path, $zipfile, $opf, $id,$manifest,$imgToFind) {
	// Get real path for our folder
	$rootPath = realpath($path);
	$count="0";// incrementation $id-XX pour le manifest
	$filesToCheck = implode(" ",$imgToFind);
	// Initialize archive object
	$zip = new ZipArchive();
	$zip->open($zipfile);

	// Create recursive directory iterator
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($rootPath),
		RecursiveIteratorIterator::LEAVES_ONLY
	);

	foreach ($files as $name => $file) {      // Skip directories 
    if (!$file->isDir())     {
			// Get real and relative path for current file
			$filePath = $file->getRealPath();

			// on regarde si l'on a besoin de cette image/fichier	
			if(strpos($filesToCheck, basename($filePath)) !== false) {	
			$relativePath = substr($filePath, strlen($rootPath) + 1);
			
			$path= trim(str_replace('../../', '',$path));
			
			//recupe path du fichier dans l'archive		
			$bookpath = str_replace('/.t','/t',$relativePath);	
			$bookpath = str_replace('\\','/',$bookpath);
			
			$count++;
			
			//on recupere le mimetype
			$mtype=  mime_content_type($filePath);
			
			// ajout du fichier au manifest 			
			$books =$opf->createElement('item');
			$book_attr_1= $opf->createAttribute('id'); 
			$book_attr_1->value=$id.'-'.$count;;	
			$books->appendChild($book_attr_1);
			
			$book_attr_2= $opf->createAttribute('href'); 
			$book_attr_2->value=$path.'/'.$bookpath;
			$books->appendChild($book_attr_2);
			
			$book_attr_3= $opf->createAttribute('media-type'); 
			$book_attr_3->value=$mtype;
			$books->appendChild($book_attr_3);
			
			$manifest->appendChild($books);		

			// Add current file to archive
			$zip->addFile($filePath, 'EPUB/'.$path.'/'.$bookpath);
			}
		}
	}
	$zip->close();
	}

	/*
	* Ajout des polices selectionnées au livre et inscriptions au manifest
	*/
	public function addFontDirectories($path, $zipfile, $opf, $id,$manifest,$fontCSS) {
	// Get real path for our folder
	$rootPath = realpath($path);
	$count="0";
	// Initialize archive object
	$zip = new ZipArchive();
	$zip->open($zipfile);

	// Create recursive directory iterator
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($rootPath),
		RecursiveIteratorIterator::LEAVES_ONLY
	);

	foreach ($files as $name => $file) 
	{      // Skip directories (they would be added automatically)
		if (!$file->isDir())
		{
			// Get real and relative path for current file
			$filePath = $file->getRealPath();
				// check if we need this file/image	
				if(strpos($fontCSS, basename($filePath)) !== false) {
			$relativePath = substr($filePath, strlen($rootPath) + 1);
			// ajout du fichier au manifest 
			
			//recupe path du fichier dans l'archive		
			$bookpath = str_replace('/.t','/t',$relativePath);		
			$bookpath = str_replace('\\','/',$bookpath);
			
			$count++;
			
			// on recupere le mimetype
			$mtype=  mime_content_type($filePath);
			
			$books =$opf->createElement('item');
			$book_attr_1= $opf->createAttribute('id'); 
			$book_attr_1->value=$id.'-'.$count;;	
			$books->appendChild($book_attr_1);
			
			$book_attr_2= $opf->createAttribute('href'); 
			$book_attr_2->value='/CSS/fonts/'.basename($file);
			$books->appendChild($book_attr_2);
			
			$book_attr_3= $opf->createAttribute('media-type'); 
			$book_attr_3->value=$mtype;
			$books->appendChild($book_attr_3);
			
			$manifest->appendChild($books);		
			//echo 'ajout '.$filePath.' dans '. $zipfile .'<br>';
			// Add current file to archive
			$zip->addFile($filePath, 'EPUB/CSS/fonts/'.basename($file));
				}
		}
	}
$zip->close();
}

	/* telecharge un fichier situé sur un serveur distant, si le serveur distant le permet  */
	public function downloadRessource($externalLink) {
	$file = basename($externalLink)	;
	if (!file_exists( PLX_ROOT."data/medias/".$file)) {
		//création du fichier en local 
		$fh = fopen( PLX_ROOT."data/medias/".$file , "w");
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $externalLink);
		//curl_setopt($ch, CURLOPT_HEADER, true );
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
		// remplissage du fichier. !! todo : tester taille et contenu puis validé
		curl_setopt($ch, CURLOPT_FILE, $fh);

		curl_exec($ch);
		curl_close($ch); 
		}
	}

	/* ajout d'une page statique dans le groupe ebookAnnexe non active ni visible au menu par défaut */
	public function createAnnexeStatique($annexe,$aTitle) {
	global $plxAdmin;
	$max = max(array_keys($plxAdmin->aStats));
	$max++;
	$max= str_pad($max, 3, "0", STR_PAD_LEFT);
	 $newStatName = PLX_ROOT.$plxAdmin->aConf['racine_statiques'].$max.'.'.$annexe.'.php';	
	echo $newStatName.'<br>';
		$plxAdmin->aStats[$max]['group'] = 'ebookAnnexe';
		$plxAdmin->aStats[$max]['name'] = $aTitle;
		$plxAdmin->aStats[$max]['url'] = $annexe;
		$plxAdmin->aStats[$max]['active'] = '0';
		$plxAdmin->aStats[$max]['menu'] = 'non';
		$plxAdmin->aStats[$max]['ordre'] = $max;
		$plxAdmin->aStats[$max]['template'] = 'static.php';
		$plxAdmin->aStats[$max]['title_htmltag'] = $aTitle;
		$plxAdmin->aStats[$max]['meta_description'] = $annexe;
		$plxAdmin->aStats[$max]['meta_keywords'] = $annexe;
		$plxAdmin->aStats[$max]['date_creation'] = date('YmdHi');
		$plxAdmin->aStats[$max]['date_update'] = date('YmdHi');
	
	// update fichier config
			$xmlDoc = new DOMDocument("1.0", "utf-8");
			$xmlDoc->preserveWhiteSpace = false; 
			$xmlDoc->formatOutput = true;  
			$xml = file_get_contents(PLX_ROOT.PLX_CONFIG_PATH.'statiques.xml', true);
			$xmlDoc->loadXML($xml);
			
			//$xmlDoc->encoding = "utf-8";

			$xpath = new DomXpath($xmlDoc);
			$results = $xpath->query("/document");
			$document=$results->item(0); 
				
			$newStat= $xmlDoc->createElement('statique');
			$number=$xmlDoc->createAttribute('number');
			$number->value=$max;
			$newStat->appendChild($number);
	
			$active=$xmlDoc->createAttribute('active');
			$active->value='0';
			$newStat->appendChild($active);
			
			$menu=$xmlDoc->createAttribute('menu');
			$menu->value='non';
			$newStat->appendChild($menu);
			
			$urlST=$xmlDoc->createAttribute('url');
			$urlST->value=$annexe;
			$newStat->appendChild($urlST);
			
			$tpl=$xmlDoc->createAttribute('template');
			$tpl->value='static.php';
			$newStat->appendChild($tpl);
			
			$group = $xmlDoc->createElement('group');
			$groupContent=$xmlDoc->createCDATASection('ebookAnnexe');
			$group->appendChild($groupContent);
			$newStat->appendChild($group);
			
			$name = $xmlDoc->createElement('name');
			$nameContent=$xmlDoc->createCDATASection($aTitle);
			$name->appendChild($nameContent);
			$newStat->appendChild($name);
			
			$meta_desc = $xmlDoc->createElement('meta_description');
			$meta_descContent=$xmlDoc->createCDATASection($aTitle);
			$meta_desc->appendChild($meta_descContent);
			$newStat->appendChild($meta_desc);
			
			$meta_key = $xmlDoc->createElement('meta_keywords');
			$meta_keyContent=$xmlDoc->createCDATASection('');
			$meta_key->appendChild($meta_keyContent);
			$newStat->appendChild($meta_key);
			
			$title_htmltag = $xmlDoc->createElement('title_htmltag');
			$title_htmltagContent=$xmlDoc->createCDATASection('');
			$title_htmltag->appendChild($title_htmltagContent);
			$newStat->appendChild($title_htmltag);
			
			$date_creation = $xmlDoc->createElement('date_creation');
			$date_creationContent=$xmlDoc->createCDATASection(date('YmdHi'));
			$date_creation->appendChild($date_creationContent);
			$newStat->appendChild($date_creation);
			
			$date_update = $xmlDoc->createElement('date_update');
			$date_updateContent=$xmlDoc->createCDATASection(date('YmdHi'));
			$date_update->appendChild($date_updateContent);
			$newStat->appendChild($date_update);
			
			$document->appendChild($newStat);
		
			$xmlDoc->preserveWhiteSpace = false; 
			$xmlDoc->formatOutput = true;  
			$doUpdate =$xmlDoc->saveXML();
						
			$xmlDoc->save(PLX_ROOT.PLX_CONFIG_PATH.'statiques.xml');
			
			$createStat = fopen ($newStatName, 'w') ;
			
			file_put_contents($newStatName, $aTitle);
			
			//recupe id
			$this->AnnexeId = $max;

	}

	/* gestion images couverture
	* ajout textes sur image
	* coupe le texte en deux si trop long
	* ajoute le logo pluxml	
	*/

	/* coupe la chaine */
    public function checkImgTextLength($string, $font,$size) {
     $width="1000";// pas trop large ni trop etriqué! (base:1200)
	 $words = explode(' ', $string); 
	 $phraseContent=$words[0];
	 
	 foreach($words as $word ) {
	 if($word==$words[0]) {continue;}
	  $testPhraseContent = $phraseContent.' '.$word;
	  $testText = imageftbbox($size, 0, $font, $testPhraseContent );
	   if ( $testText[4] > $width ) {
		$phraseContent = $phraseContent. PHP_EOL . $word  ; 
	   }
	   else {
		   $phraseContent = $phraseContent. ' '. $word;
	   }
	}	

    $string= $phraseContent;
    return $string;
}
 
	/* création image avec texte */
    public function makeThemeImg($th,$jpg,$tcolor,$subcolor,$AuthColor,$fontA,$fontB,$fontC,$titre,$desc,$Auth,$top,$middle,$bottom,$plugin,$part) {
	global $plxAdmin;
	$period='';
	$width='1200';
	$height = round($width * '1.5454') ;
	echo $height.'<br/>';
	$imgPath = PLX_ROOT.'plugins/'.$plugin.'/covers/';
	$logos= $imgPath.'logosPlux/LogoViolet.png';
	$sub = 'Cat: '.$part;
	$go="false";	
	if( $this->getParam('settitle') ==1) {// on recupere la valeur de settitle pour option titre couverture
		$titre=$plxAdmin->aConf['title'];
	}
	
	if (!file_exists($imgPath.$th)) {
		mkdir($imgPath.$th, 0777, true);
	}
	
	if($this->getParam('epubMode') == 'magM') { 
		$period =' - '.  date("M-Y", strtotime('01-'.str_pad( $this->getParam('magMM'), 2, "0", STR_PAD_LEFT).'-'.$this->getParam('magMY'))); 
	}  
	if($this->getParam('epubMode') == 'magT') {
		$last = $this->getParam('magTM') + 2 ;
		$period =' | Mag:'. date("M", strtotime('01-'.str_pad( $this->getParam('magTM'), 2, "0", STR_PAD_LEFT).'-'.$this->getParam('magTY')))
		.$this->getLang('L_TO') 
		. date("M Y", strtotime('01-'.str_pad( $last, 2, "0", STR_PAD_LEFT)  .'-'.$this->getParam('magTY')));
		}   
	if($this->getParam('epubMode') == 'magS') {
		$last = $this->getParam('magSM') + 6 ;
		$period =' | Mag:'.  date("M", strtotime('01-'.str_pad( $this->getParam('magSM'), 2, "0", STR_PAD_LEFT).'-'.$this->getParam('magSY')))
		.$this->getLang('L_TO') 
		. date("M Y", strtotime('01-'.str_pad( $last, 2, "0", STR_PAD_LEFT)  .'-'.$this->getParam('magSY')));
		}  

	$period = $this->checkMonthLangDate($period);	
	
	//recup image 
	$logo 	= imagecreatefrompng($logos);
	$im = imagecreatetruecolor($width,  $height);
	$img = getimagesize($imgPath.$jpg);;
	$cover 	= imagecreatefromjpeg($imgPath.$jpg); 
	$black			 =imagecolorallocate($im,'0'          ,'0'          ,'0'          );
	$white			 =imagecolorallocate($im,'255'        ,'255'        ,'255'        );
	$pink			 =imagecolorallocate($im,'255'        ,'105'        ,'180'        );
	imagefill($im, 0, 0, $white);	
	//Ajout cover 
	imagecopyresampled($im, $cover, 0, 0, 0, 0, $img[0], $img[1], $img[0] , $img[1] );	
	$im 	= imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $width, 'height' => $height]);//si de taille non conforme
	//Ajout logo pluxml 
	imagecopyresampled($im, $logo, 30, 1740, 0, 0, 104, 82, 104 , 82 );
	// recup font
	$titleFont    = $fontA;
	$subTitlefont = $fontB;
	$Authorfont   = $fontC;
	
	// recup couleur des textes

	$colorTitle 	 =imagecolorallocate($im,$tcolor[0]   ,$tcolor[1]   ,$tcolor[2]   );
	$colorsub		 =imagecolorallocate($im,$subcolor[0] ,$subcolor[1] ,$subcolor[2] );
	$colorAuth		 =imagecolorallocate($im,$AuthColor[0],$AuthColor[1],$AuthColor[2]);
	$colorPart	=imagecolorallocatealpha($im,'255'        ,'255'        ,'255'        ,'90');
	$colorPartB	=imagecolorallocatealpha($im,$tcolor[0]   ,$tcolor[1]   ,$tcolor[2]   ,'90');	
	
	// on verifie la longueur des texte et on coupe en deux si trop long.
	if($part !=='all') {$title = $this->checkImgTextLength($plxAdmin->aCats[$part]['name'], $fontA, '90');} else {	$title  = $this->checkImgTextLength( $titre , $fontA, '90' );}
	if($part !=='all') {$subtitle = $this->checkImgTextLength( $desc . PHP_EOL .'Part:_'.$part , $fontB, '50' );} else {$subtitle = $this->checkImgTextLength( $desc  , $fontB, '50' );}
	if( $this->getParam('settitle') ==1) {	//option titre cover  uniquement titre du ste ou selon selection categories
	$title  = $this->checkImgTextLength( $titre , $fontA, '90' );
	}
	if( $this->getParam('settitle') ==1 && $part !=='all' ) {
	$subtitle = $subtitle = $this->checkImgTextLength( $plxAdmin->aCats[$part]['name'] . PHP_EOL .'Part:_'.$part , $fontB, '50' );
	}	
	
	//recup auteur 
	$author = $Auth;
	// si sous partie, récup n° catégorie avec un effet d'ombre et opacité
	if($part !=='all' && $this->getParam('settitle') !=1) {
		$go=true;
		imagettftext($im, 40, 90, 102, 1722, $colorPartB, $fontA, $plxAdmin->aConf['title'].$period );// shadow
		imagettftext($im, 40, 90, 100, 1720, $colorPart , $fontA, $plxAdmin->aConf['title'].$period );
	}
	if($part !=='all' && $this->getParam('settitle') ==1) {
		$go=true;
		imagettftext($im, 40, 90, 102, 1722, $colorPartB, $fontA, $plxAdmin->aConf['description'].$period );// shadow
		imagettftext($im, 40, 90, 100, 1720, $colorPart , $fontA, $plxAdmin->aConf['description'].$period );
	}

	//création des textes
	$bbox  = imageftbbox(90, 0, $titleFont   , $title    );
	$bbox2 = imageftbbox(50, 0, $subTitlefont, $subtitle );
	$bbox3 = imageftbbox(30, 0, $Authorfont  ,  $Auth    );
	
	//positionement des textes
	// Nos coordonnées en X et en Y
	// title
	$x  = ceil($bbox[0] 	+ (imagesx($im) / 2			) - ($bbox[4] / 2) - 5)	;
	$y  = ceil($bbox[1] 	+ (imagesy($im) / floatval($top) 		) - ($bbox[5] )) 		;
	// description/ sous-titre
	$x2 = ceil($bbox2[0]	+ (imagesx($im) / 2			) - ($bbox2[4] / 2) - 5)	;
	$y2 = ceil($bbox2[1]	+ (imagesy($im) / floatval($middle)	) - ($bbox2[5]  ))		;

	// auteur
	$x3 = ceil($bbox3[0]	+ (imagesx($im) / 2			) - ($bbox3[4] / 2) - 5	);
	$y3 = ceil((imagesy($im) / floatval($bottom)	) - ($bbox3[5]  ))		;

	if ($jpg =='cover10.jpg') {imagefttext($im, 90, 0, $x + 4, $y + 4, $black, $fontA, $title);}// shadow text
	
	imagefttext($im, 90, 0, $x , $y , $colorTitle, $fontA, $title   );
	imagefttext($im, 50, 0, $x2, $y2, $colorsub,   $fontB, $subtitle);
	
	// ajout auteur
	imagefttext($im, 30, 0, $x3, $y3, $colorAuth, $fontC, $Auth);
	
	// efface l'image si celle-ci existe avant de la créer à nouveau
	if (file_exists($imgPath.$th.'/cover.jpg')){unlink($imgPath.$th.'/cover.jpg');}
	// sauvegarde de l'image
	if(imagejpeg($im, $imgPath.$th.'/cover.jpg')){echo '<b>Cover Image =></b> '.$imgPath.$th.'/cover.jpg'.' okay!<br>';}
	// libere la memoire
	imagedestroy($im);		
}

	/* passe valeurs couleurs hexadécimale en rgb() dans un tableau */
	public function hexTorgb($hex) {
		list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
		$rgb[]=$r;
		$rgb[]=$g;
		$rgb[]=$b;
		return $rgb;
	}

	/*
	* translate month names if language file is avalaible
	*/
	public function checkMonthLangDate($stringPeriod) {
		if($this->default_lang !='en' && file_exists(PLX_PLUGINS.'EBook/lang/'.$this->default_lang.'.php')) {
			$MonthToTranslate = array('Jan','Feb','Mar','Apr','May','Jun','July','Aug','Sept','Oct',' Nov','Dec') ;
			$index=0;
			foreach($this->getLang('L_DATE_LANG') as $month){
				$stringPeriod = str_replace(trim($MonthToTranslate[$index]), $this->getLang('L_DATE_LANG')[$index], $stringPeriod);  	
				$index++;				
			}
		return $stringPeriod;
		}
		
	}

	/* On récupere et interdit de réutiliser les url potentiellement valides
	*
	*avoids to set an URL that is already valid, used elseway.
	*/
	public function forbiddenUriList($directory) {
		$forbidden = glob($directory.'*');
		foreach( $forbidden as $dir){
			$item=pathinfo($dir);
			$forbiddenName[]= $item['filename'];
		}
		echo "'". strtolower(implode("' , '",$forbiddenName ))."'";
	}
	
}
?>
