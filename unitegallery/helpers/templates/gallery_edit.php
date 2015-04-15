<?php


defined('_JEXEC') or die('Restricted access');

			
		require HelperGalleryUG::getPathHelperTemplate("header");

		$selectedGalleryTab = "settings";
		require HelperGalleryUG::getPathHelperTemplate("gallery_edit_tabs")
	?>

			<div class="settings_panel">
			
				<div class="settings_panel_left">
					<div class="settings_panel_left_inner settings_panel_box">
					<?php $outputMain->draw("form_gallery_main",true)?>
					
					<?php require HelperGalleryUG::getPathHelperTemplate("gallery_edit_buttons")?>
					</div>
				</div>
				<div class="settings_panel_right">
					<?php $outputParams->draw("form_gallery_params",true); ?>
				</div>
				
				<div class="unite-clear"></div>				
			</div>
			
			<div id="dialog_shortcode" class="unite-inputs" title="<?php _e("Generate Shortcode",UNITEGALLERY_TEXTDOMAIN)?>" style="display:none;">	
				<br><br>
				
				<div class="mbottom_5">
					<?php _e("Generated shortcode for using with other categories",UNITEGALLERY_TEXTDOMAIN)?>:
				</div>
								
				<input id="ds_shortcode" type="text" class="input-regular input-readonly">
				
				<div class="vert_sap20"></div>

				<div class="mbottom_5">				
				<?php _e("Select category below", UNITEGALLERY_TEXTDOMAIN)?>:
				</div>
				
				<?php echo $htmlSelectCats?>
				<div class="vert_sap20"></div>
				
			</div>


	<script type="text/javascript">
	
		jQuery(document).ready(function(){
			var objAdmin = new UGAdmin();
			objAdmin.initCommonEditGalleryView();
		});
		
	</script>
	
