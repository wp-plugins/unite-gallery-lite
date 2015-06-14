<?php

    defined('_JEXEC') or die('Restricted access');

    $classSettings          = "";
    $classItems             = "";
    $classCategoryTabs  	= "";
    $classAdvanced  		= "";
    $classPreview           = "";
    
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
            case "categorytabs":
                $classCategoryTabs = "selected";
            break;
            case "advanced":
            	$classAdvanced = "selected";
            break;
        }
    }
    
    //category tabs
    $enableTabs = GlobalsUGGallery::$gallery->getParam("enable_category_tabs");
    $enableTabs = UniteFunctionsUG::strToBool($enableTabs);
    
    if($enableTabs == false){
    	$classCategoryTabs .= " unite-tab-hidden";
    }
    
    if(!empty($classCategoryTabs))
    	$classCategoryTabs = "class='{$classCategoryTabs}'";
    //-------- advanced tab    
    $showAdvanced = GlobalsUGGallery::$gallery->getParam("show_advanced_tab");
    $showAdvanced = UniteFunctionsUG::strToBool($showAdvanced);
    
    
    
    if($showAdvanced == false){
    	$classAdvanced .= " unite-tab-hidden";
    }
    if(!empty($classAdvanced))
    	$classAdvanced = "class='{$classAdvanced}'";
    global $ugMaxItems;
?>

<div class='settings_tabs'>
    <ul class="list-tabs-settings">
        <li <?php echo $classSettings?>>
            <a href="<?php echo HelperGalleryUG::getUrlViewCurrentGallery()?>"><?php _e("Settings", UNITEGALLERY_TEXTDOMAIN)?></a>
        </li>
        <li id="tab_categorytabs_settings" <?php echo $classCategoryTabs?> >
            <a href="<?php echo HelperGalleryUG::getUrlViewCategoryTabs()?>"><?php _e("Category Tabs Settings", UNITEGALLERY_TEXTDOMAIN)?></a>
        </li>
        <li id="tab_advanced_settings" <?php echo $classAdvanced?> >
            <a href="<?php echo HelperGalleryUG::getUrlViewAdvanced()?>"><?php _e("Advanced", UNITEGALLERY_TEXTDOMAIN)?></a>
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