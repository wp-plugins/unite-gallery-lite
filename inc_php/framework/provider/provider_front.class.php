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
	 * on after theme setup - fix the wpautop after do_shortcode (if exists)
	 */
	public static function onAfterThemeSetup(){
		
		$priority = self::searchFilterFirstPriority("the_content", "do_shortcode");
		
		//fix br and p output - make do_shortcode to unitegallery shortcode work always after wpautop
		if($priority < 11){
			remove_shortcode("unitegallery");
			add_filter("the_content", array(self::$t, "remove_shortcode_function"), 1);
			add_filter("the_content", array(self::$t, "add_shortcode_function"), 11);
			add_filter("the_content", "do_shortcode", 12);
		}
		
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