<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


class UniteGalleryGalleryType extends UniteElementsBaseUG{	
	
	const FILENAME_SCRIPTS = "scripts.php";
	const FILENAME_OUTPUT = "output.php";
	const FILENAME_INCLUDES = "includes.php";	
	
	private $pathGallery;
	private $folderName;
	private $filepathXmlFile;
	private $urlBase;
	
	private $name;
	private $isPublished;
	private $typeTitle;
	private $itemsType = "all";		//all , video , images
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * validate that the gallery is inited
	 */
	private function validataInited(){
		if(empty($this->name))
			UniteFunctionsUG::throwError("The gallery {$this->$folderName} is not inited!");
	}
	
	
	
	/**
	 * 
	 * get gallery data from xml file
	 */
	private function initDataFromXml(){
		
		$errorPrefix =  $this->filepathXmlFile." parsing error:";
		
		if(!file_exists($this->filepathXmlFile))
			UniteFunctionsUG::throwError("File: '$filepath' not exists!!!");
			
		$obj = simplexml_load_file($this->filepathXmlFile);
		
		if(empty($obj))
			UniteFunctionsUG::throwError("Wrong xml file format: {$this->filepathXmlFile}");
		
		$objGeneral = $obj->general;
		if(empty($objGeneral))
			UniteFunctionsUG::throwError($errorPrefix."General section not found.");
		
		//get general data
		$arrData = (array)$objGeneral;
		$this->name = UniteFunctionsUG::getVal($arrData, "name");
		$this->typeTitle = UniteFunctionsUG::getVal($arrData, "title");
		$this->isPublished = UniteFunctionsUG::getVal($arrData, "published");
		$this->isPublished = UniteFunctionsUG::strToBool($this->isPublished);
		$this->itemsType = UniteFunctionsUG::getVal($arrData, "items_type", "all");
		
		//validatopm
		UniteFunctionsUG::validateNotEmpty($this->name,$errorPrefix);
		UniteFunctionsUG::validateNotEmpty($this->typeTitle,$errorPrefix);
	}
	
	
	/**
	 * 
	 * init gallery by it's folder
	 */
	public function initByFolder($folderName){
		
		$pathGallery = GlobalsUG::$pathGalleries.$folderName."/";
		if(is_dir($pathGallery) == false)
			UniteFunctionsUG::throwError("Gallery $folderName not exists in gallery folder");
			 
		$this->pathGallery = $pathGallery;
					
		$filepathConfig = $pathGallery."config.xml";
		if(file_exists($filepathConfig) == false)
			UniteFunctionsUG::throwError("$folderName gallery config file not exists. ");
		
		$this->folderName = $folderName;	
		$this->filepathXmlFile = $filepathConfig;
		
		$this->initDataFromXml();		
	}
	
	/**
	 * 
	 * get gallery name
	 */
	public function getName(){
		$this->validataInited();
		return($this->name);
	}
	
	/**
	 * 
	 * get gallery folder
	 */
	public function getFolder(){
		$this->validataInited();
		return($this->folderName);
	}
	
	
	/**
	 * 
	 * get gallery title
	 */
	public function getTypeTitle(){
		$this->validataInited();
		return($this->typeTitle);
	}
	
	
	/**
	 * 
	 * get if the gallery is published
	 */
	public function isPublished(){
		$this->validataInited();
		return($this->isPublished);
	}
	
	/**
	 *
	 * get if the gallery is published
	 */
	public function getItemsType(){
		$this->validataInited();
		return($this->itemsType);
	}
	
	
	/**
	 * 
	 * get gallery path
	 */
	public function getPathGallery(){
		$this->validataInited();
		return($this->pathGallery);
	}
	
	/**
	 * 
	 * get path scripts include
	 */
	public function getPathScriptsIncludes(){
		$this->validataInited();
		$pathScripts = $this->pathGallery.self::FILENAME_SCRIPTS;
		return($pathScripts);
	}
	 
	
	/**
	 * 
	 * get path script includes
	 */
	public function getPathIncludes(){
		$this->validataInited();
		$pathIncludes = $this->pathGallery.self::FILENAME_INCLUDES;
		return($pathIncludes);
	}
	
	/**
	 * 
	 * get url gallery base
	 */
	public function getUrlGalleryBase(){
		$urlGalleryBase = GlobalsUG::$urlGalleries.$this->folderName."/";
		return($urlGalleryBase);
	}
	

	
	
}

?>
