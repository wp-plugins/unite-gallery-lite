<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
  <head>
	  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  
	  <title><?php _e("Unite Gallery Media Select", UNITEGALLERY_TEXTDOMAIN)?></title>
	
	  <link rel="stylesheet" href="<?php echo $urlBase."jquery/ui-themes/smoothness/jquery-ui-1.10.1.custom.css"?>" type="text/css" />
	  <link rel="stylesheet" href="<?php echo $urlBase."css/elfinder_full.css"?>" type="text/css" />
	  
	  <script src="<?php echo $urlBase."jquery/jquery-1.9.1.min.js"?>" type="text/javascript"></script>
	  <script src="<?php echo $urlBase."jquery/jquery-ui-1.10.1.custom.min.js"?>" type="text/javascript"></script>
	  <script src="<?php echo $urlBase."js/elFinder.js"?>" type="text/javascript"></script>
	  <script src="<?php echo $urlBase."jsmin/elfinder.min2.js"?>" type="text/javascript"></script>
	  <script src="<?php echo $urlBase."js/jquery.elfinder.js"?>" type="text/javascript"></script>
	  <script src="<?php echo $urlBase."jsmin/elfinder.min.js"?>" type="text/javascript"></script>
	  <script src="<?php echo $urlBase."js/i18n/elfinder.en.js"?>" type="text/javascript"></script>
	  <script src="<?php echo $urlBase."js/jquery.dialogelfinder.js"?>" type="text/javascript"></script>
	  <script src="<?php echo $urlBase."js/proxy/elFinderSupportVer1.js"?>" type="text/javascript"></script>
		
	  <style type="text/css">
	  	
	  	#button_select{
	  		padding:5px;
	  		font-weight:bold;
	  	}
	  	
	  	.button_select_wrapper{
	  		text-align:center;
	  		padding-top:10px;
	  	}
	  	
	  </style>
	  
 </head>
 
	<body>
	
<div id="finder"><?php _e("Loading Media Select...", UNITEGALLERY_TEXTDOMAIN)?></div>
	
	<div class="button_select_wrapper">
		<input id="button_select" type="button" disabled value="<?php _e("Select Images",UNITEGALLERY_TEXTDOMAIN)?>">
	</div>
	
	<script type="text/javascript">

		//return true if some dir selected
		function isDirSelected(arrFiles){
			for(var index in arrFiles){
				var file = arrFiles[index];
				if(file.mime == "directory")
					return(true);
			}
			
			return(false);
		}

		//get url's array from files 
		function getArrUrls(selectedFiles){
			var arrUrls = [];
			for(var index in selectedFiles){
				var file = selectedFiles[index];
				var hash = file["hash"];
				var url = g_finder.url(hash);
				var path = g_finder.path(hash);
				arrUrls.push({url: url, path: path});
			}
			
			return(arrUrls);
		}
		
		var g_finder = null;
		
		$(document).ready(function() {
			node = $('#finder').elfinder({
				url : "<?php echo UniteFunctionsUG::normalizeLink(GlobalsUG::$url_component_admin."&view=mediaselect&action=connector")?>",
				handlers : {
					init:function(event, elfinderInstance){
						g_finder = elfinderInstance;
					},			
		            select : function(event, elfinderInstance) {
		                var selected = g_finder.selectedFiles();
		                
						var objButton = jQuery("#button_select");
											
						if(selected.length && isDirSelected(selected) == false){
							objButton.removeAttr("disabled");
						}
						else
							objButton.attr("disabled","");
		            }
					
                },
				onlyMimes : ['image'],
				requestType : 'POST',
				rememberLastDir : true,
				commands : ['reload', 'up','getfile', 
				            'quicklook', 'download',"rm", 'duplicate', 'rename', 'mkdir', 'upload', 'copy', 
						    'cut', 'paste', 'search', 'info', 'view', 'resize', 'sort'],
                
                getfile : {
                    onlyURL  : false,
                    multiple : true,
                    folders  : false,
                    oncomplete : 'close'
                },
                
                uiOptions : {
                    // toolbar configuration
                    toolbar : [
                        ['help'],
                        ['view'],
                        ['mkdir', 'mkfile'],
                        ['open', 'download'],
                        ['info'],
                        ['quicklook'],
                        ['copy', 'cut', 'paste'],
                        ['duplicate', 'rename', 'edit', 'resize'],
                        ['rm'],
                        ['upload'],
                        ['search'],
                    ],
                },
                
                contextmenu : {

                    cwd    : ['upload', '|' , 'reload', 'back', '|', 'mkdir', 'mkfile', 'paste', '|', 'info'],

                 },
                
			});

			//button select
			jQuery("#button_select").click(function(){
 
                var selectedFiles = g_finder.selectedFiles();
				var arrUrls = getArrUrls(selectedFiles);
				window.parent.jInsertFieldValue(arrUrls);
			});
			   
		});
		
		
	</script>
	
		
	</body>
</html>
