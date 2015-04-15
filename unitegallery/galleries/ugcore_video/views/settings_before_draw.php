
<style type="text/css">
	#dialog_edit_css .CodeMirror-scroll{
    	height:400px !important;
	}
</style>

<div id="dialog_edit_css" title="<?php _e("Edit Skin Css", UNITEGALLERY_TEXTDOMAIN)?>" style="display:none;position:relative;">
	
	<div class="vert_sap20"></div>

	<div id="des_error_message" class="unite_error_message" style="display:none"></div>
	
	<div id="des_loader_operation" class="loader_text" style="position:absolute;top:39px;left:789px;display:none;"></div>
	
	<div id="des_loader" class="loader_text">Loading content...</div>
	
	<div id="des_editing_file_text"><?php _e("Editing File", UNITEGALLERY_TEXTDOMAIN)?>: <i> <span id="des_filepath"> </span></i> </div>
	
	<div class="vert_sap20"></div>
	
	<textarea id="des_area_editor" style="width:100%;height:400px;"></textarea>
	
</div>