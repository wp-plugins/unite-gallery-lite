<?php

defined('_JEXEC') or die('Restricted access');

?>
<?php require HelperGalleryUG::getPathHelperTemplate("header"); ?>

		
		<div class="settings_panel">
		
			<div class="settings_panel_left">
				<div class="settings_panel_left_inner settings_panel_box">
					<?php $outputMain->draw("form_gallery_main",true)?>
										
					<?php require HelperGalleryUG::getPathHelperTemplate("gallery_new_buttons")?>
										
				</div>
				
			</div>
			
			<div class="settings_panel_right">
				<?php //$outputParams->draw("form_gallery_params",true); ?>
			</div>
			
			<div class="unite-clear"></div>				
		</div>

	<script type="text/javascript">
	
		jQuery(document).ready(function(){
			
			var objAdmin = new UGAdmin();
			objAdmin.initCommonAddGalleryView();
			
		});
		
	</script>
	