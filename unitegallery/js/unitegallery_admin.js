function UGAdmin(){
	
	var t = this;
	var g_settings = new UniteSettingsUG();
	var g_providerAdmin;
	
	if(typeof UniteProviderAdminUG == "function")
		g_providerAdmin = new UniteProviderAdminUG();
	
	
	/**
	 * get url of some view
	 */
	this.getUrlView= function(view,type,options){
		var url = g_urlViewBase+"&view="+view;
		if(type && type != "")
			url += "&type="+type;
		
		if(options && options != "")
			url += "&"+options;
		
		return(url);
	};
	
	
	/**
	 * get current view url
	 */
	this.getUrlCurrentView = function(options){
		var url = g_urlViewBase+"&view=" + g_view;
		
		if(g_galleryType != "")
			url += "&type=" + g_galleryType;
		
		if(g_galleryID != "")
			url += "&id="+g_galleryID;
		
		if(options)
			url += "&"+options;
		
		return(url);
	};
	
	
	/**
	 * call ajax to some certain gallery type
	 */
	this.ajaxRequestGallery = function(action, data, funcSuccess){
		if(!funcSuccess)
			var funcSuccess = null;
		
		var dataSend = {};
		dataSend.gallery_action = action;
		dataSend.gallery_data = data;		
		
		g_ugAdmin.ajaxRequest("gallery_actions", dataSend, funcSuccess);
	};
	
	
	/**
	 * init galleries view
	 */
	this.initGalleriesView = function(){
		
		if(!g_ugAdmin)
			g_ugAdmin = new UniteAdminUG();
		
		if(typeof g_providerAdmin.initGalleriesView == "function")
			g_providerAdmin.initGalleriesView();
		
		//button create click - open galleries dialog
		jQuery("#button_create").click(function(){
			
			var buttonOpts = {};
			
			buttonOpts[g_text.cancel] = function(){
				jQuery("#dialog_new").dialog("close");
			};
			
			jQuery("#dialog_new").dialog({
				buttons:buttonOpts,
				minWidth:700,
				modal:true
			});
			 
		});
		
		/**
		 * delete gallery
		 */
		jQuery(".button_delete").click(function(){
			if(confirm(g_text.confirm_remove_gallery) == false)
				return(false);
			
			var galleryID = jQuery(this).data("galleryid");
			var data = {galleryID:galleryID};
			
			g_ugAdmin.ajaxRequest("delete_gallery",data);
						
		});
		
		/**
		 * delete gallery
		 */
		jQuery(".button_duplicate").click(function(){
			
			var galleryID = jQuery(this).data("galleryid");
			var data = {galleryID:galleryID};
			
			g_ugAdmin.ajaxRequest("duplicate_gallery",data);
		});
		
	};
	
	
	
	/**
	 * init shortcode functionality in the folio new and folio edit views.
	 */
	function initShortcode(){
		
		updateShortcode();
		
		//select shortcode text when click on it.
		jQuery("#shortcode").focus(function(){				
			this.select();
		});
		jQuery("#shortcode").click(function(){				
			this.select();
		});
		
		//update shortcode
		jQuery("#alias").change(function(){
			updateShortcode();
		});

		jQuery("#alias").keyup(function(){
			updateShortcode();
		});
	};
	
	/**
	 * update shortcode from alias value.
	 */
	function updateShortcode(inputID, catid){
		var alias = jQuery("#alias").val();
		
		var shortcode = g_providerAdmin.getShortcode(alias, catid);
		
		if(!inputID)
			var inputID = "#shortcode";
		
		jQuery(inputID).val(shortcode);
		
	};
	
	/**
	 * init generate shortcode dialog
	 */
	function initGenerateShortcodeDialog(){
		
		jQuery("#button_generate_shortcode").click(function(){

			var buttonOpts = {};
			
			buttonOpts[g_text.cancel] = function(){
				jQuery("#dialog_shortcode").dialog("close");
			};
			
			jQuery("#dialog_shortcode").dialog({
				buttons:buttonOpts,
				minWidth:600,
				modal:true,
				open:function(){
					updateShortcode("#ds_shortcode");
				}
			});
			
			//on select category
			jQuery("#ds_select_cats").change(function(){
				var catID = jQuery(this).val();
				updateShortcode("#ds_shortcode", catID);
			});
		
			//select shortcode text when click on it.
			jQuery("#ds_shortcode").focus(function(){				
				this.select();
			});
			
			jQuery("#ds_shortcode").click(function(){				
				this.select();
			});			
			
		});
		
		
	}
	
	
	/**
	 * init "gallery" view functionality
	 */
	function initSaveGalleryButton(ajaxAction){
		
		jQuery("#button_save_gallery").click(function(){
				
				var data = {};
				
				data.main = g_settings.getSettingsObject("form_gallery_main");
				data.params = {};
				
				if(jQuery("#form_gallery_params").length)
					data.params = g_settings.getSettingsObject("form_gallery_params");
					
								
				//add gallery id to the data
				if(ajaxAction == "update_gallery"){
					
					//some ajax beautifyer
					g_ugAdmin.setAjaxLoaderID("loader_update");
					g_ugAdmin.setAjaxHideButtonID("button_save_gallery");
					g_ugAdmin.setSuccessMessageID("update_gallery_success");
				}
				
				g_ugAdmin.setErrorMessageID("error_message_settings");
				g_ugAdmin.ajaxRequest(ajaxAction ,data);
		});		
	}
	
	

	/**
	 * init gallery view with common settings
	 */
	this.initCommonAddGalleryView = function(){
		g_providerAdmin = new UniteProviderAdminUG();

		jQuery("#title").focus();
		initSaveGalleryButton("create_gallery");
	};
	
	/**
	 * init gallery view with common settings
	 */
	this.initCommonEditGalleryView = function(){
		g_providerAdmin = new UniteProviderAdminUG();
		
		initSaveGalleryButton("update_gallery");
		
		//delete gallery action
		jQuery("#button_delete_gallery").click(function(){
			
			if(confirm(g_text.confirm_remove_gallery) == false)
				return(false);
			
			g_ugAdmin.ajaxRequest("delete_gallery");
		});
		
		initShortcode();
		
		initGenerateShortcodeDialog();
		
	};
	

};

