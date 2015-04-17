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

	<?php 
		if($isGalleryPage == true){
			$selectedGalleryTab = "items";
			require HelperGalleryUG::getPathHelperTemplate("gallery_edit_tabs");			
		}
	?>

	<div class="content_wrapper">
			
		<div id="galleryw" class="gallery_wrapper unselectable">
			<table class="layout_table" width="100%" cellpadding="0" cellspacing="0">
				<tr id="ug_row_content" class="ug_row_content">
					<td class="cell_cats" width="220px" valign="top">
						<div id="categories_wrapper" class="categories_wrapper unselectable">
						 	<div class="gallery_title">
						 		<?php _e("Categories", UNITEGALLERY_TEXTDOMAIN)?> 
						 	</div>
						 	<div class="gallery_buttons">
						 		<a id="button_add_category" type="button" class="unite-button-secondary"><?php _e("Add",UNITEGALLERY_TEXTDOMAIN)?></a>
						 		<a id="button_remove_category" type="button" class="unite-button-secondary button-disabled"><?php _e("Delete",UNITEGALLERY_TEXTDOMAIN)?></a>
						 		<a id="button_edit_category" type="button" class="unite-button-secondary button-disabled"><?php _e("Edit",UNITEGALLERY_TEXTDOMAIN)?></a>
						 	</div>
						 	<hr>
						 	<div id="cats_section" class="cats_section">
							 	<div class="cat_list_wrapper">			 
									<ul id="list_cats" class="list_cats">
										<?php echo $htmlCatList?>
									</ul>					
							 	</div>
						 	</div>			 	
						</div>
					</td>
					
					<td class="cell_items" valign="top">
						<div class="items_wrapper unselectable">
						 	
						 	<div class="gallery_title">
						 		<?php _e("Items",UNITEGALLERY_TEXTDOMAIN)?>
						 	</div>			 	
						 	<div id="gallery_buttons" class="gallery_buttons">
						 		<?php if($itemsType != "video"):?>
						 			<a id="button_add_images" type="button" class="unite-button-secondary unite-button-blue button-disabled"><?php _e("Add Images",UNITEGALLERY_TEXTDOMAIN)?></a>
						 		<?php endif?>
						 		
						 		<?php if($itemsType != "images"):?>
						 			<a id="button_add_video" type="button" class="unite-button-secondary unite-button-blue button-disabled"><?php _e("Add Video",UNITEGALLERY_TEXTDOMAIN)?></a>
						 		<?php endif?>
						 		
						 		<a id="button_select_all_items" type="button" class="unite-button-secondary button-disabled" data-textselect="<?php _e("Select All",UNITEGALLERY_TEXTDOMAIN)?>" data-textunselect="<?php _e("Unselect All",UNITEGALLERY_TEXTDOMAIN)?>"><?php _e("Select All",UNITEGALLERY_TEXTDOMAIN)?></a>
						 		<a id="button_duplicate_item" type="button" class="unite-button-secondary button-disabled"><?php _e("Duplicate",UNITEGALLERY_TEXTDOMAIN)?></a>
						 		<a id="button_edit_item_title" type="button" class="unite-button-secondary button-disabled"><?php _e("Edit Title",UNITEGALLERY_TEXTDOMAIN)?></a>
						 		<a id="button_edit_item" type="button" class="unite-button-secondary button-disabled"><?php _e("Edit Item",UNITEGALLERY_TEXTDOMAIN)?> </a>
						 		<a id="button_remove_item" type="button" class="unite-button-secondary button-disabled"><?php _e("Delete",UNITEGALLERY_TEXTDOMAIN)?></a>
						 	</div>
						 	
						 	<hr>
						 	
						 	<div id="items_outer" class="items_outer">
						 	
								<div id="items_list_wrapper" class="items_list_wrapper unselectable">
									<div id="items_loader" class="items_loader" style="display:none;">
										<?php _e("Getting Items", UNITEGALLERY_TEXTDOMAIN)?>...
									</div>
									
									<div id="no_items_text" class="no_items_text" style="display:none;">
										<?php _e("Empty Category", UNITEGALLERY_TEXTDOMAIN)?>
									</div>
									
									<ul id="list_items" class="list_items unselectable"></ul>
									<div id="drag_indicator" class="drag_indicator" style="display:none;">maxim</div>
									<div id="shadow_bar" class="shadow_bar" style="display:none"></div>
									<div id="select_bar" class="select_bar" style="display:none"></div>
								</div>
							
							</div>								
						</div>
						
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="status_line">
							<div class="status_loader_wrapper">
								<div id="status_loader" class="status_loader" style="display:none;"></div>
							</div>
							<div class="status_text_wrapper">
								<span id="status_text" class="status_text" style="display:none;"></span>
							</div>
							<div class="status_operations">
								<div class="status_num_selected">
									<span id="num_items_selected">0</span> <?php _e("items selected",UNITEGALLERY_TEXTDOMAIN)?>
								</div>
								<div id="item_operations_wrapper" class="item_operations_wrapper">
								
									<select id="select_item_operations" disabled="disabled">
									 	<option value="copy"><?php _e("Copy To",UNITEGALLERY_TEXTDOMAIN)?></option>
									 	<option value="move"><?php _e("Move To",UNITEGALLERY_TEXTDOMAIN)?></option>
									</select>
													
									<select id="select_item_category" disabled="disabled">
										<?php echo $htmlCatSelect ?>
									</select>				
									 
									 <a id="button_items_operation" class="unite-button-secondary button-disabled" href="javascript:void(0)">GO</a>
								 </div>
							</div>
						</div>
					</td>
				</tr>
			</table>
			
		</div>	<!--  end galleryw -->
		
		
		<div id="gallery_shadow_overlay" class="gallery_shadow_overlay" style="display:none"></div>
			
			<ul id="menu_copymove" class="context_menu" style="display:none">
				<li>
					<a href="javascript:void(0)" data-operation="copymove_copy"><?php _e("Copy",UNITEGALLERY_TEXTDOMAIN)?></a>
				</li>
				<li>
					<a href="javascript:void(0)" data-operation="copymove_move"><?php _e("Move",UNITEGALLERY_TEXTDOMAIN)?></a>
				</li>
			</ul>
			
			<!-- Right menu single -->
			
			<ul id="rightmenu_item" class="context_menu" style="display:none">
				<?php foreach($arrMenuItem as $operation=>$text):?>
				<li>
					<a href="javascript:void(0)" data-operation="<?php echo $operation?>"><?php echo $text?></a>
				</li>
				<?php endforeach?>
			</ul>
			
			<!-- Right menu multiple -->
			
			<ul id="rightmenu_item_multiple" class="context_menu" style="display:none">
				<?php foreach($arrMenuItemMultiple as $operation=>$text):?>
				<li>
					<a href="javascript:void(0)" data-operation="<?php echo $operation?>"><?php echo $text?></a>
				</li>
				<?php endforeach?>
			</ul>
			
			<!-- Right menu field -->
			<ul id="rightmenu_field" class="context_menu" style="display:none">
				<?php foreach($arrMenuField as $operation=>$text):?>
				<li>
					<a href="javascript:void(0)" data-operation="<?php echo $operation?>"><?php echo $text?></a>
				</li>
				<?php endforeach?>			
			</ul>
			
			<!-- Right menu category -->
			<ul id="rightmenu_cat" class="context_menu" style="display:none">
				<?php foreach($arrMenuCat as $operation=>$text):?>
				<li>
					<a href="javascript:void(0)" data-operation="<?php echo $operation?>"><?php echo $text?></a>
				</li>
				<?php endforeach?>
			</ul>
			
			<!-- Right menu category field-->
			<ul id="rightmenu_catfield" class="context_menu" style="display:none">
				<?php foreach($arrMenuCatField as $operation=>$text):?>
				<li>
					<a href="javascript:void(0)" data-operation="<?php echo $operation?>"><?php echo $text?></a>
				</li>
				<?php endforeach?>
			</ul>
			
		</div>	<!-- end content wrapper -->
	


<div id="dialog_edit_category"  title="<?php _e("Edit Category",UNITEGALLERY_TEXTDOMAIN)?>" style="display:none;">

	<div class="dialog_edit_category_inner unite-inputs">
		
		<?php _e("Category ID", UNITEGALLERY_TEXTDOMAIN)?>: <b><span id="span_catdialog_id"></span></b>
		
		<br><br>
		
		<?php _e("Edit Name", UNITEGALLERY_TEXTDOMAIN)?>:
		<input type="text" id="input_cat_title" class="input-regular">
	</div>
	
</div>

<div id="dialog_edit_item_title"  title="<?php _e("Edit Title",UNITEGALLERY_TEXTDOMAIN)?>" style="display:none;">

	<div class="dialog_edit_title_inner unite-inputs mtop_20 mbottom_20" >
		<?php _e("Edit Title", UNITEGALLERY_TEXTDOMAIN)?>:
		<input type="text" id="input_item_title" class="unite-input-wide">
	</div>
	
</div>

<div id="dialog_edit_item"  title="<?php _e("Edit Item",UNITEGALLERY_TEXTDOMAIN)?>" style="display:none;">

	<div id="dialog_edit_item_loader" class="loader_round">
		<?php _e("Loading Item Data...",UNITEGALLERY_TEXTDOMAIN)?>
	</div>
	
	<div id="dialog_edit_item_content" class="dialog_edit_item_content"></div>
	
	<div id="dialog_edit_error_message" class="unite_error_message" style="display:none"></div>
	
</div>

<?php require GlobalsUG::$pathViews."system/video_dialog.php"; ?>
	

<a class='unite-button-secondary mleft_10' href='<?php echo HelperGalleryUG::getUrlViewGalleriesList() ?>' ><?php _e("Close",UNITEGALLERY_TEXTDOMAIN); ?></a>

	
		<?php 
			$script = "
				jQuery(document).ready(function(){
					var selectedCatID = \"{$selectedCategory}\";
					var galleryAdmin = new UGAdminItems();
					galleryAdmin.initGalleryView(selectedCatID);
				});
			";
			
			UniteProviderFunctionsUG::printCustomScript($script);
			
		?>
