<?php

class UniteProviderFrontUG{
	
	private static $t;
	const ACTION_ADD_SCRIPTS = "wp_enqueue_scripts";

	
	/**
	 *
	 * add some wordpress action
	 */
	protected static function addAction($action,$eventFunction){
	
		add_action( $action, array(self::$t, $eventFunction) );
	}
	
	
	
	
	
	/**
	 * get first priority of the filter function
	 */
	public static function searchFilterFirstPriority($tag, $function){
		global $wp_filter;
		
		$arrPriority = (array) array_keys($wp_filter[$tag]);
		asort($arrPriority);
		
		foreach ($arrPriority as $priority ) {
			if ( isset($wp_filter[$tag][$priority][$function]) )
				return $priority;
		}
		
		return(false);
	}
	
	
	/**
	 * check unite gallery output
	 */
	public static function process_shortcode($content){
		
		//clear all other tags
		global $shortcode_tags;
		$current_shortcodes = $shortcode_tags;
		$shortcode_tags = array();
		
		//process unite gallery shortcode
		add_shortcode( 'unitegalleryprocess', 'unitegallery_shortcode' );
		$content = do_shortcode($content);
		
		//return all other tags
		$shortcode_tags = $current_shortcodes;
		
		return($content);
	}	
	
	
	/**
	 * rename shortcode to another shortcode, don't let filters in between to touch it.
	 * process it in 999 position. don't touch the unitegallery original shortcode
	 */
	public static function rename_shortcode($content){
		
		$content = str_replace("[unitegallery ", "[unitegalleryprocess ", $content);
		
		
		return($content);
	}
	
	
	/**
	 * on after theme setup - fix the wpautop after do_shortcode (if exists)
	 */
	public static function onAfterThemeSetup(){
		
		add_filter("the_content", array(self::$t, "rename_shortcode"), 1);
		add_filter("the_content", array(self::$t, "process_shortcode"), 9999);
		
	}
	
	
	/**
	 *
	 * the constructor
	 */
	public function __construct(){
		self::$t = $this;
		
		$this->addAction("after_setup_theme", "onAfterThemeSetup");
		
	}
	
}

?>