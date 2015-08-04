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
	 * check unite gallery output
	 */
	public static function add_shortcode_function($content){
		
		add_shortcode( 'unitegallery', 'unitegallery_shortcode' );
		
		return($content);
	}
	
	/**
	 * check unite gallery output
	 */
	public static function remove_shortcode_function($content){
	
		remove_shortcode( 'unitegallery');
	
		return($content);
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
		add_shortcode( 'unitegallery', 'unitegallery_shortcode' );
		$content = do_shortcode($content);
		
		//return all other tags
		$shortcode_tags = $current_shortcodes;
		
		return($content);
	}	
	
	
	/**
	 * remove shortcode before adding the gallery
	 * add it again in 999 position
	 */
	public static function remove_shortcode($content){
		
		remove_shortcode( 'unitegallery');
		
		return($content);
	}
	
	
	/**
	 * on after theme setup - fix the wpautop after do_shortcode (if exists)
	 */
	public static function onAfterThemeSetup(){
		
		add_filter("the_content", array(self::$t, "remove_shortcode"), 1);
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