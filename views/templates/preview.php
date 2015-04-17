<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

?>			
			<?php require HelperUG::getPathTemplate("header")?>

			<?php 
				$selectedGalleryTab = "preview";
				require HelperGalleryUG::getPathHelperTemplate("gallery_edit_tabs")
			?>
			
			<div class="unite-preview-wrapper">
			<div class="vert_sap40"></div>
			
			<?php
				require HelperGalleryUG::getPathView("preview");
			?>
			
			<div class="vert_sap50"></div>
				</div>
				
			<a class='unite-button-secondary mleft_10' href='<?php echo HelperGalleryUG::getUrlViewGalleriesList() ?>' ><?php _e("Close",UNITEGALLERY_TEXTDOMAIN); ?></a>
			