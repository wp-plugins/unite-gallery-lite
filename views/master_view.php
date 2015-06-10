<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

	global $ugMaxItems;
	$galleryTypeName = "";
	$galleryID = "";
	
	if(!empty(UniteGalleryAdmin::$currentGalleryType)){
		$galleryTypeName = GlobalsUGGallery::$galleryTypeName;
		
		$ugMaxItems = 12;
		switch($galleryTypeName){
			case "ug-carousel":
			case "ug-tilescolumns":
			case "ug-tilesjustified":
			case "ug-tilesgrid":
				$ugMaxItems = 20;
				break;
		}
		
		$galleryID = GlobalsUGGallery::$galleryID;
	}
	
	global $uniteGalleryVersion;
	
?>

<a id="fancybox_trigger" style="display:none" href="index.php?option=com_media&view=images&tmpl=component&author=&fieldid=field_image_dialog_choose">Fancybox Trigger</a>


<?php 
	$script = "
		var g_galleryType = \"{$galleryTypeName}\";
		var g_view = \"".self::$view."\";
		var g_galleryID = \"".$galleryID."\";
		var g_pluginName = \"".GlobalsUG::PLUGIN_NAME."\";
		var g_urlAjaxActions = \"".GlobalsUG::$url_ajax."\";
		var g_urlViewBase = \"".GlobalsUG::$url_component_admin."\";
		if(typeof(g_settingsObj) == 'undefined')
			var g_settingsObj = {};
		var g_ugAdmin;
	";
	
	//get nonce
	if(method_exists("UniteProviderFunctionsUG", "getNonce"))
		$script .= "\n		var g_ugNonce='".UniteProviderFunctionsUG::getNonce()."';";
	
	UniteProviderFunctionsUG::printCustomScript($script);
?>
	
						
<div id="div_debug"></div>

<div id="debug_line" style="display:none"></div>
<div id="debug_side" style="display:none"></div>

<div class='unite_error_message' id="error_message" style="display:none;"></div>

<div class='unite_success_message' id="success_message" style="display:none;"></div>

<div id="viewWrapper" class="unite-view-wrapper unite-admin">

<?php
	self::requireView($view);
	
	$jsArrayText = UniteFunctionsUG::phpArrayToJsArrayText(GlobalsUG::$arrClientSideText);
	
?>

</div>

<div class="unite-clear"></div>
<div class="unite-plugin-version-line unite-admin">
	<?php UniteProviderFunctionsUG::putFooterTextLine() ?>
	Plugin verson <?php echo $uniteGalleryVersion?>
</div>


<div id="divColorPicker" style="display:none;"></div>

<?php 
	$script = "
		var g_text = {
				".$jsArrayText."
			};	
	";
	
	UniteProviderFunctionsUG::printCustomScript($script);
	
?>
	
		