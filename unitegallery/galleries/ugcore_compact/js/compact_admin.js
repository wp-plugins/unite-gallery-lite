
function UGCompactThemeAdmin(){
	
	var g_galleryAdmin = new UGAdmin();
	if(!g_ugAdmin)
		g_ugAdmin = new UniteAdminUG();	

	
	/**
	 * on select position select change. change the button text
	 */
	function onSelectPositionChange(event){
		var objSelect = jQuery(this);
		var objButton = jQuery("#theme_button_set_defaults");
		
		var pos = objSelect.val();
		var template = g_text["changedefauts_template"];
			
		if(!g_ugAdmin)
			g_ugAdmin = new UniteAdminUG();
			
		pos = g_ugAdmin.capitalizeFirstLetter(pos);
		
		var newText = template.replace("[pos]",pos);
		objButton.val(newText);		
	}

	
	/**
	 * call ajax to set defaut settings, then refresh the page
	 */
	function onChangeDefaults(){
	
		var confirmText = g_text["changedefauts_confirm"];
		
		if(confirm(confirmText) == false)
			return(false);
		
		var thumbpos = jQuery("#theme_panel_position").val();
		
		//if new gallery view - just refresh the page with the thumbpos url argument
		if(g_galleryID == ""){
			var urlView = g_galleryAdmin.getUrlCurrentView("thumbpos=" + thumbpos);
			urlView += "#confirmchange";
			window.location = urlView;	
			return(false);
		}
		else{
			var data = {"position":thumbpos};
			jQuery("#theme_button_set_defaults").hide();
			jQuery("#theme_button_set_defaults_loader").show();
			g_galleryAdmin.ajaxRequestGallery("update_thumbpanel_defaults", data);
		}
				
	};
	
	
	/**
	 * check the hash command and show the success message accordingly
	 */
	function handleHashCommands(){
		
		if(location.hash == "#confirmchange"){

			var successText = g_text["changedefauts_success"];
			
			g_ugAdmin.showSuccessMessage(successText);
			
			location.hash = "";
		}
		
	}
	
	
	/**
	 * init function
	 */
	this.init = function(){
		jQuery("#theme_panel_position").change(onSelectPositionChange);
		jQuery("#theme_button_set_defaults").click(onChangeDefaults);
		
		handleHashCommands();
		
	};
	
}

jQuery(document).ready(function(){
	
	compactAdmin = new UGCompactThemeAdmin();
	compactAdmin.init();
	
	
});	

