
function UniteSettingsUG(){
	
	var arrControls = {};
	var colorPicker;
	
	var t=this;
	
	
	/**
	 * get settings object
	 */
	this.getSettingsObject = function(formID){		
		
		var obj = new Object();
		var form = document.getElementById(formID);
		if(!form){
			trace("form with id: " + formID + " not found!");
			return(false);
		}
			
		var name,value,type,flagUpdate;
		
		//enabling all form items connected to mx
		for(var i=0; i<form.elements.length; i++){
			name = form.elements[i].name;		
			value = form.elements[i].value;
			type = form.elements[i].type;
			
			flagUpdate = true;
			switch(type){
				case "checkbox":
					value = form.elements[i].checked;
				break;
				case "radio":
					if(form.elements[i].checked == false) 
						flagUpdate = false;				
				break;
				case "button":
					flagUpdate = false;
				break;
			}
			
			if(flagUpdate == true && name != undefined) 
				obj[name] = value;
		}
		
		
		return(obj);
	};
	
	
	/**
	 * compare control values
	 */
	function iscValEQ(controlValue, value){
		
		if(typeof value != "string"){
			
			return jQuery.inArray( controlValue, value) != -1;
		}else{
			return (value.toLowerCase() == controlValue);
		}

	}
	
	/**
	 * on selects change - impiment the hide/show, enabled/disables functionality
	 */
	function onSettingChange(){

		var controlValue = this.value.toLowerCase();
		var controlID = this.name;
		
		if(!arrControls[controlID]) 
			return(false);
		
		var arrChildControls = arrControls[controlID];
				
		jQuery(arrChildControls).each(function(){
			var childInputID = this.name;
			
			var objChildInput = jQuery("#" + childInputID);
			
			var objChildRow = jQuery("#" + childInputID + "_row");
			
			if(objChildRow.length == 0)
				return(true);
			
			var value = this.value;
			
			
			var inputTagName = "";
			if(objChildInput.length)
				inputTagName = objChildInput.get(0).tagName;
			
			var isChildRadio = (inputTagName == "SPAN" && objChildInput.length && objChildInput.hasClass("radio_wrapper"));
			
			switch(this.type){
				case "enable":
				case "disable":
					
					if(objChildInput.length > 0){
						
						//disable
						if(this.type == "enable" && iscValEQ(controlValue,value) == false || this.type == "disable" && iscValEQ(controlValue,value) == true){
							objChildRow.addClass("setting-disabled");
							
							if(objChildInput.length)
								objChildInput.prop("disabled","disabled").css("color","");
							
							if(isChildRadio)
								objChildInput.children("input").prop("disabled","disabled").addClass("disabled");
						}//enable						
						else{	
							
							objChildRow.removeClass("setting-disabled");
							
							if(objChildInput.length)
								objChildInput.prop("disabled","");
							
							if(isChildRadio)
								objChildInput.children("input").prop("disabled","").removeClass("disabled");
							
							//color the input again
							if(objChildInput.length && objChildInput.hasClass("inputColorPicker")) 
								colorPicker.linkTo(objChildInput);							
		 				}
						
					}
				break;
				case "show":
					if(iscValEQ(controlValue,value) == true) 
						objChildRow.show();									
					else 
						objChildRow.hide();					
				break;
				case "hide":
					if(iscValEQ(controlValue,value) == true) 
						objChildRow.hide();									
					else 
						objChildRow.show();
				break;
			}
			
		});
	}
	
	
	/**
	 * combine controls to one object, and init control events.
	 */
	function initControls(){
		
		//combine controls
		for(key in g_settingsObj){
			var obj = g_settingsObj[key];
			
			for(controlKey in obj.controls){
				arrControls[controlKey] = obj.controls[controlKey];				
			}
		}
		
		//init events
		jQuery(".settings_wrapper select").change(onSettingChange);
		jQuery(".settings_wrapper input[type='radio']").change(onSettingChange);
		
	}
	
	
	//init color picker
	function initColorPicker(){
		var colorPickerWrapper = jQuery('#divColorPicker');
		
		colorPicker = jQuery.farbtastic('#divColorPicker');
		jQuery(".inputColorPicker").focus(function(){
			colorPicker.linkTo(this);
			
			var bodyWidth = jQuery("body").width();
			
			colorPickerWrapper.show();
			var input = jQuery(this);
			var offset = input.offset();
			
			var offsetView = jQuery("#viewWrapper").offset();
			var wrapperWidth = colorPickerWrapper.width();
			var inputWidth = input.width();
			var inputHeight = input.height();
			
			var posLeft = offset.left - offsetView.left - wrapperWidth / 2 + inputWidth/2;
			
			var posRight = posLeft + wrapperWidth;
			if(posRight > bodyWidth)
				posLeft = bodyWidth - wrapperWidth;
			
			var posTop = offset.top - offsetView.top - colorPickerWrapper.height() / 2 - inputHeight - 20;	// + 100-offsetView.top;
			
			colorPickerWrapper.css({
				"left":posLeft,
				"top":posTop
			});

			
		}).click(function(){			
			return(false);	//prevent body click
		});
		
		colorPickerWrapper.click(function(){
			return(false);	//prevent body click
		});
		
		jQuery("body").click(function(){
			colorPickerWrapper.hide();
		});
	}
	
	/**
	 * close all accordion items
	 */
	function closeAllAccordionItems(formID){
		jQuery("#"+formID+" .unite-postbox .inside").slideUp("fast");
		jQuery("#"+formID+" .unite-postbox .unite-postbox-title").addClass("box_closed");
	}
	
	/**
	 * init side settings accordion - started from php
	 */
	this.initAccordion = function(formID){
		var classClosed = "box_closed";
		jQuery("#"+formID+" .unite-postbox .unite-postbox-title").click(function(){
			var handle = jQuery(this);
			
			//open
			if(handle.hasClass(classClosed)){
				closeAllAccordionItems(formID);
				handle.removeClass(classClosed).siblings(".inside").slideDown("fast");
			}else{	//close
				handle.addClass(classClosed).siblings(".inside").slideUp("fast");
			}
			
		});
	};
	
	/**
	 * image search
	 */
	function initImageSearch(){
		
		jQuery(".button-image-select").click(function(){
			var settingID = this.id.replace("_button","");
			g_ugAdmin.openAddImageDialog("Choose Image",function(urlImage){
				
				//update input:
				jQuery("#"+settingID).val(urlImage);
				
				//update preview image:
				var urlShowImage = g_ugAdmin.getUrlShowImage(urlImage,100,70,true);
				jQuery("#" + settingID + "_button_preview").html("<img width='100' height='70' src='"+urlShowImage+"'></img>");
				
			});
		})
		
	}
	
	/**
	 * init tipsy
	 */
	function initTipsy(gravity, parentSelector){
		if(!gravity)
			var gravity = "e";
		
		if(!parentSelector)
			var parentSelector = ".settings_wrapper";
		
		//init tipsy
		jQuery(parentSelector+" .setting_text").tipsy({
			html:true,
			gravity:gravity,
	        delayIn: 70
		});
		
	}
	
	/**
	 * init the settings function, set the tootips on sidebars.
	 */
	this.init = function(){
		
		initTipsy();
		
		initControls();
		
		initColorPicker();
		
		initImageSearch();
	};
	
	
	/**
	 * update events (in case of ajax set)
	 */
	this.updateEvents = function(parentSelector){
		
		if(!parentSelector)
			var parentSelector = ".settings_wrapper";
		
		initTipsy("s",parentSelector);
		initControls();
	};
	

} // UniteSettings class end


