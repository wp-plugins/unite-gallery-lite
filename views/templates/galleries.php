<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

?>
<?php require HelperGalleryUG::getPathHelperTemplate("header"); ?>
			
			
		<?php if(empty($arrGalleries)): ?>
		<div>
			<?php _e("No Galleries Found",UNITEGALLERY_TEXTDOMAIN)?>
		</div>			
		<?php else:?>
	
	<table class='unite_table_items'>
		<thead>
			<tr>
				<th width='3%'><?php _e("ID",UNITEGALLERY_TEXTDOMAIN); ?></th>
				<th width=''><?php _e("Name",UNITEGALLERY_TEXTDOMAIN); ?></th>
				<th width='100'><?php _e("Type",UNITEGALLERY_TEXTDOMAIN); ?></th>
				<th width='470'><?php _e("Actions",UNITEGALLERY_TEXTDOMAIN); ?></th>
				<th width='60'><?php _e("Preview",UNITEGALLERY_TEXTDOMAIN); ?></th>						
			</tr>
		</thead>
		<tbody>
			<?php foreach($arrGalleries as $gallery):
				
				$id = $gallery->getID();
				$typeTitle = $gallery->getTypeTitle();				
				$isTypeExists = $gallery->isTypeExists();
				
				$showTitle = $gallery->getShowTitle();
								
				$title = $gallery->getTitle();
				
				$alias = $gallery->getAlias();
				$shortCode = $gallery->getShortcode();			
				
				$editLink = HelperUG::getGalleryView($id);
				$editItemsLink = HelperUG::getItemsView($id);
				
				$previewLink = HelperUG::getPreviewView($id);
				
				$showTitle = UniteFunctionsUG::getHtmlLink($editLink, $showTitle);
				
			?>
				<tr>
					<td><?php echo $id?><span id="slider_title_<?php echo $id?>" style="display:none"><?php echo $title?></span></td>								
					<td><?php echo $showTitle?></td>
					<?php if($isTypeExists):?>
					<td><b><?php echo $typeTitle?></b></td>
					<td>
						<a href='<?php echo $editItemsLink?>' class="unite-button-primary float_left mleft_15"><?php _e("Edit Items",UNITEGALLERY_TEXTDOMAIN); ?></a>
						<a href='<?php echo $editLink?>' class="unite-button-secondary float_left mleft_15"><?php _e("Edit Settings",UNITEGALLERY_TEXTDOMAIN); ?></a>
						
						<a href='javascript:void(0)' data-galleryid="<?php echo $id?>" class="button_delete unite-button-secondary float_left mleft_15"><?php _e("Delete",UNITEGALLERY_TEXTDOMAIN); ?></a>
						<a href='javascript:void(0)' data-galleryid="<?php echo $id?>" class="button_duplicate unite-button-secondary float_left mleft_15"><?php _e("Duplicate",UNITEGALLERY_TEXTDOMAIN); ?></a>
					</td>
					<td>
						<a href='<?php echo $previewLink?>' class="unite-button-secondary float_left"><?php _e("Preview",UNITEGALLERY_TEXTDOMAIN); ?></a>					
					</td>
					<?php else:?>
					<td class="unite-color-red"><?php echo $typeTitle?></td>
					<td colspan="2">
						<a href='javascript:void(0)' data-galleryid="<?php echo $id?>" class="button_delete unite-button-secondary float_left mleft_15"><?php _e("Delete",UNITEGALLERY_TEXTDOMAIN); ?></a>
					</td>
					<?php endif?>
				</tr>							
			<?php endforeach;?>
			
		</tbody>		 
	</table>
		
		<?php endif?>
		
		<div class="vert_sap40"></div>
		
		<a id="button_create" class='unite-button-primary' href='javascript:void(0)'><?php _e("Create New Gallery", UNITEGALLERY_TEXTDOMAIN)?></a>
	
	
	<?php 
		if(method_exists("UniteProviderFunctionsUG", "putGalleriesViewText"))
			UniteProviderFunctionsUG::putGalleriesViewText();

		if(method_exists("UniteProviderFunctionsUG", "putUpdatePluginHtml"))
			UniteProviderFunctionsUG::putUpdatePluginHtml();
			
	?>
	
	
	
	
	<div id="dialog_new" class="dialog_new_gallery" title="<?php _e("Choose a gallery",UNITEGALLERY_TEXTDOMAIN)?>" style="display:none">
		<div class="unite-admin unite-dialog-inside">
			<ul id="listGalleries" class="list_galleries">
				<?php foreach($arrGalleryTypes as $gallery):
					
					$galleryName = UniteFunctionsUG::getVal($gallery, "name");
					$galleryTitle = UniteFunctionsUG::getVal($gallery, "title");
					
					$link = HelperUG::getViewUrl(GlobalsUG::VIEW_GALLERY,"type={$galleryName}");
				?>
				<li><a class="unite-button-secondary" href="<?php echo $link?>" data-name="<?php echo $galleryName?>"><?php echo $galleryTitle?></a></li>
				<?php endforeach;?>
			</ul>
			<div class="unite-clear"></div>
		</div> 
	
<?php 
	
	$script = "
	
		jQuery(document).ready(function(){
			var galleryAdmin = new UGAdmin();
			galleryAdmin.initGalleriesView();
		});	
	
	";	
	
	UniteProviderFunctionsUG::printCustomScript($script);
	
?>
