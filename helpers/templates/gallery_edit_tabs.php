<?php


defined('_JEXEC') or die('Restricted access');

				$classSettings = "";
				$classItems = "";
				$classPreview = "";
				
				if(isset($selectedGalleryTab)){
					switch($selectedGalleryTab){
						default:
						case "settings":
							$classSettings = "class='selected'";
						break;
						case "items":
							$classItems = "class='selected'";							
						break;
						case "preview":
							$classPreview = "class='selected'";
						break;
					}
				}
				
				global $ugMaxItems;
				
			?>
			
			<div class='settings_tabs'>
				<ul class="list-tabs-settings">
					<li <?php echo $classSettings?>>
						<a href="<?php echo HelperGalleryUG::getUrlViewCurrentGallery()?>"><?php _e("Settings", UNITEGALLERY_TEXTDOMAIN)?></a>
					</li>
					<li <?php echo $classItems?>>
						<a href="<?php echo HelperGalleryUG::getUrlViewItems()?>"><?php _e("Items", UNITEGALLERY_TEXTDOMAIN)?></a>
					</li>
					<li <?php echo $classPreview?>>
						<a href="<?php echo HelperGalleryUG::getUrlViewPreview()?>"><?php _e("Preview", UNITEGALLERY_TEXTDOMAIN)?></a>
					</li>
				</ul>
				
				<div class='settings_limit_message'>
					This gallery has limitations: <b> <?php echo $ugMaxItems?> items limit </b> in the preview and output.
					<br>
					For removing the limitations, upgrade to <b>"Unite Gallery Full Version"</b>.
					&nbsp; <a href="http://codecanyon.net/item/unite-gallery-wordpress-plugin/10458750?ref=valiano" target="_blank">Get It Now!</a>
				</div>
				
				<div class="unite-clear"></div>
			</div>	

			
			