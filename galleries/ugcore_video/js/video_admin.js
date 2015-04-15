(function( $ ){

	var g_galleryAdmin = new UGAdmin();
	var g_codemirrorCss = null;

	
	/**
	 * set the editor to the text area
	 */
	function setCodeMirrorEditor(){
        
		g_codemirrorCss = CodeMirror.fromTextArea(document.getElementById("des_area_editor"), {
            mode: {name: "css"},
            lineNumbers: true
        });
		
	}

	
/**
 * open edit css dialog
 */
function ugOpenEditCssDialog(){
	
	var buttonOpts = {};

	//update button
	buttonOpts[g_text.update] = function(){
		
		var data = {};
		var skin = jQuery("#dialog_edit_css").data("skin");
		data.skin = skin;
		
		if (g_codemirrorCss != null)
            data.css = g_codemirrorCss.getValue();
        else
            data.css = jQuery("#des_area_editor").val();
		
		jQuery("#des_loader_operation").show().html(g_text.updating);
		
		g_ugAdmin.setErrorMessageID("des_error_message");
		g_galleryAdmin.ajaxRequestGallery("update_skin_css", data, function(response){
			jQuery("#des_loader_operation").hide();
			jQuery("#dialog_edit_css").dialog("close");
	
		});
				
	};

	//restore button
	buttonOpts[g_text.restore] = function(){
		
		var data = {};
		var skin = jQuery("#dialog_edit_css").data("skin");
		data.skin = skin;

		jQuery("#des_loader_operation").show().html(g_text.restoring);
		
		g_ugAdmin.setErrorMessageID("des_error_message");
		g_galleryAdmin.ajaxRequestGallery("get_original_skin_css", data, function(response){
			
			jQuery("#des_loader_operation").hide();
			
			if(!response.content)
				alert(response.message);
			else
				g_codemirrorCss.setValue(response.content);
			
		});
		
		
	};
	
	
	buttonOpts[g_text.cancel] = function(){
		jQuery("#dialog_edit_css").dialog("close");
	};
	
	
	jQuery("#dialog_edit_css").dialog({
		buttons:buttonOpts,
		minWidth:900,
		modal:true,
		open:function(){			
			var skin = jQuery("#theme_skin").val();
			
			jQuery("#dialog_edit_css").data("skin",skin);
			
			var data = {
					skin: skin
			};
			
			if (g_codemirrorCss != null)
                g_codemirrorCss.setValue("");
			else
				jQuery("#des_area_editor").val("").hide();
			
			
			jQuery("#des_editing_file_text").hide();
			jQuery("#des_loader").show();
			
			g_ugAdmin.setErrorMessageID("des_error_message");
			g_galleryAdmin.ajaxRequestGallery("get_skin_css", data, function(response){
				
				jQuery("#des_loader").hide();
								
				var content = response.content;
				var filepath = response.filepath;
								
				jQuery("#des_editing_file_text").show();
				jQuery("#des_filepath").html(filepath);
				
				 if (g_codemirrorCss != null)
	                    g_codemirrorCss.setValue(content);
				 else{
					
					jQuery("#des_area_editor").show().val(content);
	                setTimeout(setCodeMirrorEditor, 500);					 
				 }
                
			});

		}
	});
	
}
	
jQuery(document).ready(function(){
	
	jQuery("#button_edit_css").click(ugOpenEditCssDialog);
	
});	

})( jQuery );