<?php


defined('_JEXEC') or die('Restricted access');

require HelperGalleryUG::getPathHelperTemplate("header");

$selectedGalleryTab = "advanced";
require HelperGalleryUG::getPathHelperTemplate("gallery_edit_tabs");
$operations = new UGOperations();

?>

<div class="settings_panel ug-settings-advanced">

    <div class="settings_panel_left">

        <div class="settings_panel_box settings_panel_single">
            
            <?php $outputMain->draw("form_gallery_advanced_main",true)?>

            <div class="vert_sap40"></div>
			
			<div id="update_button_wrapper" class="update_button_wrapper">
				<a class='unite-button-primary' href='javascript:void(0)' id="button_save_gallery" ><?php _e("Update Settings",UNITEGALLERY_TEXTDOMAIN); ?></a>
				<div id="loader_update" class="loader_round" style="display:none;"><?php _e("Updating",UNITEGALLERY_TEXTDOMAIN); ?>...</div>
				<div id="update_gallery_success" class="success_message" class="display:none;"></div>
			</div>
		
			<a id="button_close_gallery" class='unite-button-secondary float_left mleft_10' href='<?php echo HelperGalleryUG::getUrlViewGalleriesList() ?>' ><?php _e("Close",UNITEGALLERY_TEXTDOMAIN); ?></a>	
		
			<div class="vert_sap20"></div>
			
			<div id="error_message_settings" class="unite_error_message" style="display:none"></div>
            
        </div>
    </div>	<!-- settings panel left -->
    
    <div class="settings_panel_right">
        <?php $outputParams->draw("form_gallery_advanced_params",true); ?>
    </div>
    
    <div class="unite-clear"></div>
    
</div>

		<div id="dialog_import_gallery" title="<?php _e("Import Gallery Settings",UNITEGALLERY_TEXTDOMAIN)?>" style="display:none;">	
			<br>
			<div class="unite-dialog-desc">
				<?php _e("To import the settings please select the gallery settings export file.",UNITEGALLERY_TEXTDOMAIN) ?>		
			
			<br>
		
			<?php _e("Note, that the gallery global type (tiles / themes) should be the same.", UNITEGALLERY_TEXTDOMAIN)?>
			<br><br>
			<?php _e("Current settings will be replaced. All the settings from the left side (items category, gallery size) will remain unchanged.", UNITEGALLERY_TEXTDOMAIN)?>
		
			<br><br>
			 <?php _e("File example: unitegallery_gallery1.txt",UNITEGALLERY_TEXTDOMAIN)?>	</div>	
			
			<br>	
		
			<form action="<?php echo GlobalsUG::$url_ajax?>" enctype="multipart/form-data" method="post">
				
				<?php $operations->putAjaxFormFields("import_gallery_settings",$galleryID)?>
				
				<?php _e("Choose the export file:",UNITEGALLERY_TEXTDOMAIN)?>
				<br><br>
				
				<input type="file" name="export_file" class="ug-input-file">
				
				<br><br>
			
				<input type="submit" class='unite-button-primary' value="<?php _e("Import Settings",UNITEGALLERY_TEXTDOMAIN)?>">	
			</form>
			
			<br><br>
			
		</div>


<script type="text/javascript">

    jQuery(document).ready(function(){
        var linkExport = "<?php echo $linkExport?>";
        var objAdmin = new UGAdmin();
        objAdmin.initAdvancedView(linkExport);
    });

</script>
	
