
function UGAdmin(){
	
	var t = this;
	var g_settings = new UniteSettingsUG();
	var g_providerAdmin, g_codemirrorJS = null, g_comdemirrorCSS = null;
	
	
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
	 * get gallery view url
	 */
	this.getUrlGalleryView = function(galleryID, view){
		
		if(!galleryID)
			var galleryID = g_galleryID;
		
		if(!view)
			var view = "gallery";
		
		var urlView = t.getUrlView(view,"","id=" + galleryID);
		
		return(urlView);
	}
	
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
	 * on enable category tabs input click. show / hide category tabs page
	 */
	function onInputEnableCatTabsClick(){

		var objTab = jQuery("#tab_categorytabs_settings");
		var radioID = jQuery(this).attr("id");
		if(radioID == "enable_category_tabs_1")
			objTab.show();
		else
			objTab.hide();
	}
	
	/**
	 * on show advanced radio button click. show / hide advanced tab
	 */
	function onInputShowAdvancedTabClick(){
		var objTab = jQuery("#tab_advanced_settings");
		var radioID = jQuery(this).attr("id");
		
		if(radioID == "show_advanced_tab_1")
			objTab.show();
		else
			objTab.hide();
		
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

		jQuery("#enable_category_tabs input").click(onInputEnableCatTabsClick);
	    jQuery("#show_advanced_tab input").click(onInputShowAdvancedTabClick);
	    
		initShortcode();
		
		initGenerateShortcodeDialog();
		
	};
	
	
	
	/**
	 * init category tabs view
	 */
    this.initCategoryTabsView = function (){
    	
    	//start drop down selector plugin
        jQuery("#available_cats").dropDownSelectorPluginUG({
        	hiddenInputName: "categorytabs_ids",
            textEmpty: "You have not selected any categories",
            makeMultiple: true,
            panelLabel: "Category Tabs",
            selectFirstID: "tabs_init_catid",
            firstItemText: "[First Tab From List]"
        });
    	
        g_providerAdmin = new UniteProviderAdminUG();
        
        jQuery("#button_save_gallery").click(function(){

            var data = {};

            data.params = g_settings.getSettingsObject("form_gallery_category_settings");
            
            data.updateParamsOnly = true;
            
            if(jQuery("#form_gallery_category_settings_params").length)
                jQuery.extend(data.params, g_settings.getSettingsObject("form_gallery_category_settings_params") );

            var enableTabs = data.params["enable_category_tabs"];
            
            //some ajax beautifyer
            g_ugAdmin.setAjaxLoaderID("loader_update");
            g_ugAdmin.setAjaxHideButtonID("button_save_gallery");
            g_ugAdmin.setSuccessMessageID("update_gallery_success");

            g_ugAdmin.setErrorMessageID("error_message_settings");
            g_ugAdmin.ajaxRequest("update_gallery" ,data, function(response){
            	g_ugAdmin.showSuccessMessage(response.message);
            	if(enableTabs == "false"){
            		var urlView = t.getUrlGalleryView();
            		location.href = urlView;
            	}
            });
            
        });
        
    };

    
    /**
     * init advaced tab import / export
     */
    function advanced_initExportImport(linkExport){
    	
    	//init export button - go to url
    	jQuery("#export_gallery_settings").click(function(){
    		
    		location.href=linkExport;
    		
    	});
    	
    	
    	//init import button - open dialog
    	jQuery("#import_gallery_settings").click(function(){

    		var buttonOpts = {};
			
			buttonOpts[g_text.cancel] = function(){
				jQuery("#dialog_import_gallery").dialog("close");
			};
			
			jQuery("#dialog_import_gallery").dialog({
				buttons:buttonOpts,
				minWidth:700,
				modal:true
			});
    		
    	});
    	    	
    }
    
    
    /**
     * advanced view
     */
    this.initAdvancedView = function(linkExport){
		
    	//set codemirror
        setTimeout(function(){
        	
        	g_codemirrorJS = CodeMirror.fromTextArea(document.getElementById("ug_additional_scripts"), {
                mode: {name: "javascript"},
                lineNumbers: true
            });

        	g_comdemirrorCSS = CodeMirror.fromTextArea(document.getElementById("ug_additional_styles"), {
                mode: {name: "css"},
                lineNumbers: true
            });
        	
        }, 500);					 
    	
        
        jQuery("#button_save_gallery").click(function(){

            data = {};
			data.params = {};
			
			if(g_codemirrorJS === null)
				throw new Error("The codemirror editor not enabled");
			
			//get params
			data.params.ug_additional_scripts = g_codemirrorJS.getValue();
	        data.params.ug_additional_styles = g_comdemirrorCSS.getValue();
	        
            data.updateParamsOnly = true;
        	
            //some ajax beautifyer
            g_ugAdmin.setAjaxLoaderID("loader_update");
            g_ugAdmin.setAjaxHideButtonID("button_save_gallery");
            g_ugAdmin.setSuccessMessageID("update_gallery_success");
            g_ugAdmin.setErrorMessageID("error_message_settings");
            g_ugAdmin.ajaxRequest("update_gallery" ,data);
        	
        });
        
        advanced_initExportImport(linkExport);
    	
    }
    
};