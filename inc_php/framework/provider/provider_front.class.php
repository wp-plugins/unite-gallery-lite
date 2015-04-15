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
	 *
	 * the constructor
	 */
	public function __construct(){
		self::$t = $this;
		
		self::addAction(self::ACTION_ADD_SCRIPTS, "onAddScripts");
	}
	
	
	/**
	 * on add scripts
	 */
	public static function onAddScripts(){
		//HelperUG::addScript("unitegallery_admin");
	}
		
}


?>