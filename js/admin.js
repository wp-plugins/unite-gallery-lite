
(function($){ 
    $.mlp = {x:0,y:0}; // Mouse Last Position
    function documentHandler(){
        var $current = this === document ? $(this) : $(this).contents();
        $current.mousemove(function(e){jQuery.mlp = {x:e.pageX,y:e.pageY}});
        $current.find("iframe").load(documentHandler);
    }
    $(documentHandler);
    $.fn.ismouseover = function(overThis) {  
        var result = false;
        this.eq(0).each(function() {  
                var $current = $(this).is("iframe") ? $(this).contents().find("body") : $(this);
                var offset = $current.offset();             
                result =    offset.left<=$.mlp.x && offset.left + $current.outerWidth() > $.mlp.x &&
                            offset.top<=$.mlp.y && offset.top + $current.outerHeight() > $.mlp.y;
        });  
        return result;
    };  
})(jQuery);

if(typeof window.addEvent == "undefined")
	window.addEvent = function(){};


/**
 * Drop Down selector plugin
 * ------------------------------------------------------------
 */	
	jQuery.fn.dropDownSelectorPluginUG = function( config ) {

        var cfg = {
            wrapperCls      : 'unite-drop-down-wrapper',
            filterPanelCls  : "unite-selected-items",
            selected        : [],
            hiddenInputName : "",
            label           : "",
            textEmpty		: "No items chosen",
            makeMultiple	: false,			//make the select box multiple
            panelLabel		: "Selected Items",
            isSortable 		: true,
            selectFirstID	: "",			//first item select, if exists, update it together with tags add
            firstItemText	: "[First Item From List]"
        };
        jQuery.extend(cfg, config);

        //validation:
        if(cfg.hiddenInputName == "")
        	throw new Error("The dropdown selector plugin muyst have hidden input name");
        
        var dropDownElement = jQuery(this),
            wrapper,
            htmlSelect,
            wrapperPanel,
            filterPanel,
            hiddenInput,
            selectFirstItem,
            filters = [];

        wrapper         = jQuery('<div></div>');
        wrapper.addClass( cfg.wrapperCls );
        dropDownElement.before( wrapper );
        htmlSelect      = dropDownElement.clone();
        dropDownElement.remove();
        wrapperPanel    = jQuery('<div><div class="label">' + cfg.panelLabel + ':</div><ul></ul></div>');
        filterPanel     = wrapperPanel.find("ul");
        filterPanel.append(cfg.textEmpty);
        
        wrapperPanel.addClass(cfg.filterPanelCls);
        hiddenInput     = jQuery("input[name=" + cfg.hiddenInputName + "]");
        if ( hiddenInput.length == 0) {

            hiddenInput = jQuery('<input name="' + cfg.hiddenInputName + '" />');
            wrapperPanel.append( hiddenInput );
        }
        wrapper.append( jQuery('<div></div>').append( htmlSelect ) );
        wrapper.append( wrapperPanel );
        dropDownElement = htmlSelect;

        if(cfg.makeMultiple == true)
        	dropDownElement.attr("multiple", "multiple");
        
        //And finally Initialize filters with values
        var hiddenInputValue = hiddenInput.attr("value");
        
        //init first select
        if(cfg.selectFirstID != ""){
        	selectFirstItem = jQuery("#" + cfg.selectFirstID);
        	selectFirstItem.append(jQuery('<option>', { value : "first" , selected: "selected"}).text(cfg.firstItemText));
        }
        
        if ( hiddenInputValue ) {

            var values = hiddenInputValue.split(",");
            
            hiddenInput.val("");
            for ( var i=0; i < values.length; i++) {
                addFilter( values[i] );
            }

        }
        
        dropDownElement.val("");
        
        if(cfg.isSortable == true)
        	filterPanel.sortable({
        		create: function(){
        			filterPanel.data("is_sortable", true);
        		},
        		update:updateHiddenInput
        	});
        
        /**
         * on select choose 
         */
        function selected ( event ) {
            var val;
            if ( (val = dropDownElement.val() ) != "" ) {
                addFilter(val);
            }
        }
        
        /**
         * add filter
         */
        function addFilter( valueId ){
        	
            if(!valueId) {
                return false;
            }
            
            if ( filters.length == 0 ) {
                filterPanel.html("");
            }
            
            var option = dropDownElement.find('option[value='+valueId+']');
            if(option.length == 0)
            	return(false);
            
            var optionText = option.text();
            
            var object  = {
                    id      : valueId,
                    text    : optionText
                };
            
            filters.push( object );
            
            if(selectFirstItem){
            	selectFirstItem.append(jQuery('<option>', { value : valueId }).text(optionText));
            }
            
            option.remove();
            filterPanel.append(filterItemFactory(object));
            
            updateHiddenInput();
        }

        
        /**
         * remove filter
         */
        function removeFilter( config ){

            var li = filterPanel.find("li[_id="+config.id+"]"),
                option = jQuery('<option></option>');
            li.remove();
            
            if(selectFirstItem){
                var option = selectFirstItem.find('option[value='+config.id+']');
            	if(option.length)
            		option.remove();
            }
            
            option.html( config.text );
            option.attr("value", config.id);
            dropDownElement.append(option);
            
            updateHiddenInput();
        }
        
        
        /**
         * update hidden input
         */
        function updateHiddenInput(){
            
        	var arrValues = [];
        	
        	var isSortable = filterPanel.data("is_sortable");
        	if(isSortable === true){
            
        		arrValues = filterPanel.sortable( "toArray" ,{attribute: "_id"});
        	
        	}else{	//get the array from the dome

        		var objLI = filterPanel.find("li");
            	jQuery.each(objLI, function(index, item){
            		var id = jQuery(item).attr("_id");
            		
            		if(jQuery(item).hasClass("ui-sortable-placeholder") == false)
            			arrValues.push(id);
            	});
        		
        	}
        	
        	//make string from the array
        	var strValues = "";
        	
        	if(arrValues.length != 0)
        		strValues = arrValues.join(",");
        	
        	hiddenInput.val(strValues);
        }

        
        /**
         * init plugin
         */
        function filterItemFactory( config )
        {
            var element         = jQuery('<li></li>'),
                innerElement    = jQuery('<div></div>'),
                removeAction    = jQuery('<a class="remove"></a>');
            element.append(innerElement);
            element.attr("_id", config.id);
            innerElement.html( config.text );
            innerElement.append(removeAction);
            removeAction.click(removeFilter.bind(this, config));
            return element;
        }
        dropDownElement.change(selected.bind(this));

    };
    
//---- End drop down selector plugin -----

/**
 * Unite Admin Class
 */
function UniteAdminUG(){
	
	var t = this;
	
	var g_errorMessageID = null, g_hideMessageCounter = 0;
	var g_ajaxLoaderID = null, g_ajaxHideButtonID = null, g_successMessageID = null;	
	var g_colorPickerCallback = null;
	var g_providerAdmin = new UniteProviderAdminUG();

	this.__________GENERAL_FUNCTIONS_____ = function(){};	

	
	/**
	 * debug html on the top of the page (from the master view)
	 */
	this.debug = function(html){
		jQuery("#div_debug").show().html(html);
	};
	
	/**
	 * output data to console
	 */
	this.trace = function(data,clear){
		if(clear && clear == true)
			console.clear();	
		console.log(data);
	};
	
	
	/**
	 * escape html, turn html to a string
	 */
	this.htmlspecialchars = function(string){
		  return string
		      .replace(/&/g, "&amp;")
		      .replace(/</g, "&lt;")
		      .replace(/>/g, "&gt;")
		      .replace(/"/g, "&quot;")
		      .replace(/'/g, "&#039;");
	};
	
	
	/**
	 * capitalize first letter
	 */
	this.capitalizeFirstLetter = function(str){
		
		str = str.substr(0, 1).toUpperCase() + str.substr(1).toLowerCase();
		return(str);
	};
	
	
	/**
	 * Find absolute position on the screen of some element
	 */	
	this.getAbsolutePos = function(obj){
	  var curleft = curtop = 0;
		if (obj.offsetParent) {
			curleft = obj.offsetLeft;
			curtop = obj.offsetTop;
			while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft;
				curtop += obj.offsetTop;
			}
		}			
		return[curleft,curtop];
	};
	
	
	/**
	 * strip slashes to some string
	 */
	this.stripslashes = function(str) {
		return (str + '').replace(/\\(.?)/g, function (s, n1) {
			switch (n1) {
				case '\\':
				return '\\';
				case '0':
				return '\u0000';
				case '':
				return '';
				default:
				return n1;
			}
		});
	};
	
	/**
	 * turn string value ("true", "false") to string 
	 */
	this.strToBool = function(str){
		
		if(str == undefined)
			return(false);
			
		if(typeof(str) != "string")
			return(false);
		
		str = str.toLowerCase();
		
		var bool = (str == "true")?true:false;
		return(bool);
	};
	
	/**
	 * set callback on color picker movement
	 */
	this.setColorPickerCallback = function(callbackFunc){
		g_colorPickerCallback = callbackFunc;
	};
	
	/**
	 * on color picker event. Pass the event further
	 */
	this.onColorPickerMoveEvent = function(event){
		
		if(typeof g_colorPickerCallback == "function")
			g_colorPickerCallback(event);
	};
	
	/**
	 * change rgb & rgba to hex
	 */
	this.rgb2hex = function(rgb) {
		if (rgb.search("rgb") == -1 || jQuery.trim(rgb) == '') return rgb; //ie6
		
		function hex(x) {
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
		
		if(rgb.indexOf('-moz') > -1){
			var temp = rgb.split(' ');
			delete temp[0];
			rgb = jQuery.trim(temp.join(' '));
		}
		
		if(rgb.split(')').length > 2){
			var hexReturn = '';
			var rgbArr = rgb.split(')');
			for(var i = 0; i < rgbArr.length - 1; i++){
				rgbArr[i] += ')';
				var temp = rgbArr[i].split(',');
				if(temp.length == 4){
					rgb = temp[0]+','+temp[1]+','+temp[2];
					rgb += ')';
				}else{
					rgb = rgbArr[i];
				}
				rgb = jQuery.trim(rgb);
				
				rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
				
				hexReturn += "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3])+" ";
			}
			
			return hexReturn;
		}else{
			var temp = rgb.split(',');
			if(temp.length == 4){
				rgb = temp[0]+','+temp[1]+','+temp[2];
				rgb += ')';
			}
			
			rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
			
			return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
		}
		
		
	};
	
	/**
	 * get rgb from hex values
	 */
	this.convertHexToRGB = function(hex) {
		var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
		return [hex >> 16,(hex & 0x00FF00) >> 8,(hex & 0x0000FF)];
	};
	
	
	
	/**
	 * get transparency value from 0 to 100
	 */
	this.getTransparencyFromRgba = function(rgba, inPercent){
		var temp = rgba.split(',');
		if(temp.length == 4){
			inPercent = (typeof inPercent !== 'undefined') ? inPercent : true;
			return (inPercent) ? temp[3].replace(/[^\d.]/g, "") : temp[3].replace(/[^\d.]/g, "") * 100;
		}
		
		return false;
	};
	
	
	/**
	 * strip html tags
	 */
	this.stripTags = function(input, allowed) {
	    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
	        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	    });
	};
	
	/**
	 * load css file on the fly
	 * replace current item if exists
	 */
	this.loadCssFile = function(urlCssFile,replaceID){
		
		var rand = Math.floor((Math.random()*100000)+1);
		
		if(urlCssFile.indexOf("?") == -1)
			urlCssFile += "?rand="+rand;
		else
			urlCssFile += "&rand="+rand;
		
		if(replaceID)
			jQuery("#"+replaceID).remove();
		
		jQuery("head").append("<link>");
		var css = jQuery("head").children(":last");
		css.attr({
		      rel:  "stylesheet",
		      type: "text/css",
		      href: urlCssFile
		});
		
		//replace current element
		if(replaceID)
			css.attr({id:replaceID});
	};	
	
	
	/**
	 * unselect some button / buttons
	 */
	this.enableButton = function(buttonID){
		jQuery(buttonID).removeClass("button-disabled");
	};
	
	
	/**
	 * unselect some button / buttons
	 */
	this.disableButton = function(buttonID){
		jQuery(buttonID).addClass("button-disabled");
	};
	
	/**
	 * return true / false if the button enabled
	 */
	this.isButtonEnabled = function(buttonID){
		if(jQuery(buttonID).hasClass("button-disabled"))
			return(false);
		
		return(true);
	};
	
	/**
	 * check if was pressed right mouse button
	 */
	this.isRightButtonPressed = function(event){
		
		if(event.buttons == 2 || event.button == 2)
			return(true);
		
		return(false);
	};
	
	this._____________DIALOGS__________ = function(){};
	
	/**
	 * open "add image" dialog
	 */
	this.openAddImageDialog = function(title, onInsert, isMultiple){
		
		g_providerAdmin.openAddImageDialog(title, onInsert, isMultiple);
		
	};
	
	
	/**
	 * open video dialog
	 */
	this.openVideoDialog = function(callbackFunction, itemData){
		
		g_ugMediaDialog.openVideoDialog(callbackFunction, itemData);
		
	};
	
	
	this.__________AJAX_REQUEST_____ = function(){};
	
	/**
	 * show error message or call once custom handler function
	 */
	this.showErrorMessage = function(htmlError){
		
		if(g_errorMessageID !== null){
			jQuery("#"+g_errorMessageID).show().html(htmlError);			
		}else
			jQuery("#error_message").show().html(htmlError);
		
		showAjaxButton();
	};

	/**
	 * hide error message
	 */
	var hideErrorMessage = function(){
		if(g_errorMessageID !== null){
			jQuery("#"+g_errorMessageID).hide();
			if(g_hideMessageCounter > 0){
				g_hideMessageCounter = 0;
				g_errorMessageID = null;
			}else
				g_hideMessageCounter++;
		}else
			jQuery("#error_message").hide();
	};
	
	
	/**
	 * set error message id
	 */
	this.setErrorMessageID = function(id){
		g_errorMessageID = id;
		g_hideMessageCounter = 0;
	};
	
	
	
	/**
	 * set success message id
	 */
	this.setSuccessMessageID = function(id){
		g_successMessageID = id;
	};
	
	/**
	 * show success message
	 */
	this.showSuccessMessage = function(htmlSuccess){
		var id = "#success_message";		
		var delay = 2000;
		if(g_successMessageID){
			id = "#"+g_successMessageID;
			delay = 500;
		}
		
		jQuery(id).show().html(htmlSuccess);
		setTimeout("g_ugAdmin.hideSuccessMessage()",delay);
	};
	
	
	/**
	 * hide success message
	 */
	this.hideSuccessMessage = function(){
		
		if(g_successMessageID){
			jQuery("#"+g_successMessageID).hide();
			g_successMessageID = null;	//can be used only once.
		}
		else
			jQuery("#success_message").slideUp("slow").fadeOut("slow");
		
		showAjaxButton();
	};
	
	
	/**
	 * set ajax loader id that will be shown, and hidden on ajax request
	 * this loader will be shown only once, and then need to be sent again.
	 */
	this.setAjaxLoaderID = function(id){
		g_ajaxLoaderID = id;
	};
	
	/**
	 * show loader on ajax actions
	 */
	var showAjaxLoader = function(){
		if(g_ajaxLoaderID)
			jQuery("#"+g_ajaxLoaderID).show();
	};
	
	/**
	 * hide and remove ajax loader. next time has to be set again before "ajaxRequest" function.
	 */
	var hideAjaxLoader = function(){
		if(g_ajaxLoaderID){
			jQuery("#"+g_ajaxLoaderID).hide();
			g_ajaxLoaderID = null;
		}
	};
	
	/**
	 * set button to hide / show on ajax operations.
	 */
	this.setAjaxHideButtonID = function(buttonID){
		g_ajaxHideButtonID = buttonID;
	};
	
	/**
	 * if exist ajax button to hide, hide it.
	 */
	var hideAjaxButton = function(){
		if(g_ajaxHideButtonID)
			jQuery("#"+g_ajaxHideButtonID).hide();
	};
	
	/**
	 * if exist ajax button, show it, and remove the button id.
	 */
	var showAjaxButton = function(){
		if(g_ajaxHideButtonID){
			jQuery("#"+g_ajaxHideButtonID).show();
			g_ajaxHideButtonID = null;
		}		
	};
	
	
	/**
	 * Ajax request function. call wp ajax, if error - print error message.
	 * if success, call "success function" 
	 */
	this.ajaxRequest = function(action,data,successFunction){
		
		if(typeof data == "undefined")
			var data = {};
		
		//add galleryID to data
		if(g_galleryID != ""){
			data.galleryID = g_galleryID;
		}
		
		var objData = {
			action:g_pluginName+"_ajax_action",
			client_action:action,
			gallery_type: g_galleryType,
			data:data
		};
		
		if(typeof g_ugNonce == "string")
			objData["nonce"] = g_ugNonce;
		
		hideErrorMessage();
		showAjaxLoader();
		hideAjaxButton();
		
		jQuery.ajax({
			type:"post",
			url:g_urlAjaxActions,
			dataType: 'json',
			data:objData,
			success:function(response){
				hideAjaxLoader();
				
				if(!response){
					t.showErrorMessage("Empty ajax response!");
					return(false);					
				}

				if(response == -1){
					t.showErrorMessage("ajax error!!!");
					return(false);
				}
				
				if(response == 0){
					t.showErrorMessage("ajax error, action: <b>"+action+"</b> not found");
					return(false);
				}
				
				if(response.success == undefined){
					t.showErrorMessage("The 'success' param is a must!");
					return(false);
				}
				
				if(response.success == false){
					t.showErrorMessage(response.message);
					return(false);
				}
				
				//success actions:

				//run a success event function
				if(typeof successFunction == "function")
					successFunction(response);
				else{
					if(response.message)
						t.showSuccessMessage(response.message);
				}
				
				if(response.is_redirect)
					location.href=response.redirect_url;
			
			},		 	
			error:function(jqXHR, textStatus, errorThrown){
				hideAjaxLoader();
				
				if(textStatus == "parsererror")
					t.debug(jqXHR.responseText);
				
				t.showErrorMessage("Ajax Error!!! " + textStatus);
			}
		});
		
	};//ajaxrequest
	
	/**
	 * ajax request for creating thumb from image and get thumb url
	 * instead of the url can get image id as well
	 */
	this.requestThumbUrl = function(urlImage, imageID, callbackFunction){
		
		var data = {
				urlImage: urlImage,
				imageID: imageID
		};
		
		t.ajaxRequest("get_thumb_url",data, function(response){
			callbackFunction(response.urlThumb);
		});
		
	};
	
	
	/**
	 * global init
	 */
	this.globalInit = function(){
		
		var settings = new UniteSettingsUG();
		settings.init();
		
		//init fancybox trigger
		jQuery("#fancybox_trigger").fancybox({
			'width'				: 800,
			'height'			: 500,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'				: 'iframe'
		});
				
		g_ugMediaDialog.init();
		
	};
	
	
}


//user functions:
function trace(data,clear){
	
	if(!g_ugAdmin)
		g_ugAdmin = new UniteAdminUG();
		
	g_ugAdmin.trace(data,clear);
}

function clearTrace(){
	
	console.clear();
}

function debug(data){
	
	if(!g_ugAdmin)
		g_ugAdmin = new UniteAdminUG();
	
	g_ugAdmin.debug(data);
}


//run the init function
jQuery(document).ready(function(){
	
	if(!g_ugAdmin)
		g_ugAdmin = new UniteAdminUG();
	
	g_ugAdmin.globalInit();
	
});

