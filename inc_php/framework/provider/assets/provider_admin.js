

/**
 * provider admin class
 */
function UniteProviderAdminUG(){
	
	
	/**
	 * open new add image dialog
	 */
	function openNewImageDialog(title,onInsert,isMultiple){
		
		if(isMultiple == undefined)
			isMultiple = false;
		
		// Media Library params
		var frame = wp.media({
			//frame:      'post',
            //state:      'insert',
			title : title,
			multiple : isMultiple,
			library : { type : 'image'},
			button : { text : 'Insert' }
		});

		// Runs on select
		frame.on('select',function(){
			var objSettings = frame.state().get('selection').first().toJSON();
			
			var selection = frame.state().get('selection');
			var arrImages = [];
			
			if(isMultiple == true){		//return image object when multiple
			    selection.map( function( attachment ) {
			    	var objImage = attachment.toJSON();
			    	var obj = {};
			    	obj.url = objImage.url;
			    	obj.id = objImage.id;
			    	arrImages.push(obj);
			    });
				onInsert(arrImages);
			}else{		//return image url and id - when single
				onInsert(objSettings.url, objSettings.id);
			}
			    
		});

		// Open ML
		frame.open();
	}
	
	
	/**
	 * open old add image dialog
	 */
	function openOldImageDialog(title,onInsert){
		var params = "type=image&post_id=0&TB_iframe=true";
		
		params = encodeURI(params);
		
		tb_show(title,'media-upload.php?'+params);
		
		window.send_to_editor = function(html) {
			 tb_remove();
			 var urlImage = jQuery(html).attr('src');
			 if(!urlImage || urlImage == undefined || urlImage == "")
				var urlImage = jQuery('img',html).attr('src');
			
			onInsert(urlImage,"");	//return empty id, it can be changed
		}
	}
	
	
	
	/**
	 * open "add image" dialog
	 */
	this.openAddImageDialog = function(title, onInsert, isMultiple){

		if(typeof wp != "undefined" && typeof wp.media != "undefined")
			openNewImageDialog(title,onInsert,isMultiple);
		else{
			openOldImageDialog(title,onInsert);
		}
				
		
	};


	/**
	 * get shortcode
	 */
	this.getShortcode = function(alias, catid){
		
		var addhtml = "";
		if(catid)
			addhtml = " catid="+catid;
		
		var shortcode = "[unitegallery "+alias + addhtml + "]";
		
		if(alias == "")
			shortcode = "";
		
		return(shortcode);
	};
	
	
	/**
	 * init galleries view provider related functionality
	 */
	this.initGalleriesView = function(){
		
		//init update plugin button
		jQuery("#ug_button_update_plugin").click(function(){

			jQuery("#dialog_update_plugin").dialog({
				minWidth:600,
				minHeight:400,
				modal:true,
			});
			
		});
		
	}
	
}

