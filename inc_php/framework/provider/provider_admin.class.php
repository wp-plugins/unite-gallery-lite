<?php

   class UniteProviderAdminUG extends UniteGalleryAdmin{
   		
   		private static $arrMenuPages = array();
	   	private static $arrSubMenuPages = array();
	   	private static $capability = "manage_options";
	   	
   		private $mainFilepath;
	   	
	   	private static $t;
	   	
	   	const ACTION_ADMIN_MENU = "admin_menu";
	   	const ACTION_ADMIN_INIT = "admin_init";
	   	const ACTION_ADD_SCRIPTS = "admin_enqueue_scripts";
   		const ACTION_PRINT_SCRIPT = "admin_print_footer_scripts";

   		
		/**
		 *
		 * the constructor
		 */
		public function __construct($mainFilepath){
			self::$t = $this;
			
			$this->mainFilepath = $mainFilepath;

			if(GlobalsUG::PERMISSION == "editor")
				self::$capability = "edit_posts";
			
			parent::__construct();
			
			$this->init();
		}		
		
		
		/**
		 * process activate event - install the db (with delta).
		 */
		public static function onActivate(){
			
			self::createTables();
		}

		
		/**
		 *
		 * create the tables if not exists
		 */
		public static function createTables(){
			self::createTable(GlobalsUG::TABLE_GALLERIES_NAME);
			self::createTable(GlobalsUG::TABLE_ITEMS_NAME);
			self::createTable(GlobalsUG::TABLE_CATEGORIES_NAME);
		}
		
		
		/**
		 *
		 * craete tables
		 */
		public static function createTable($tableName){
		
			global $wpdb;
						
			//if table exists - don't create it.
			$tableRealName = $wpdb->prefix.$tableName;
			if(UniteFunctionsWPUG::isDBTableExists($tableRealName))
				return(false);
		
			$charset_collate = $wpdb->get_charset_collate();
		
			switch($tableName){
				case GlobalsUG::TABLE_CATEGORIES_NAME:
					$sql = "CREATE TABLE " .$tableRealName ." (
					id int(9) NOT NULL AUTO_INCREMENT,
					title varchar(255) NOT NULL,
					alias varchar(255),
					ordering int not NULL,
					params text NOT NULL,
					type tinytext,
					parent_id int(9),
					PRIMARY KEY (id)
					)$charset_collate;";
					break;
				case GlobalsUG::TABLE_ITEMS_NAME:
					$sql = "CREATE TABLE " .$tableRealName ." (
					id int(9) NOT NULL AUTO_INCREMENT,
					published int(2) NOT NULL,
					title tinytext NOT NULL,
					alias tinytext,
					type varchar(60),
					url_image tinytext,
					url_thumb tinytext,
					ordering int not NULL,
					catid int(9) NOT NULL,
					imageid int(9),
					params text,
					content text,
					contentid varchar(60),
					parent_id int(9),
					PRIMARY KEY (id)
					)$charset_collate;";
					break;
				case GlobalsUG::TABLE_GALLERIES_NAME:
					$sql = "CREATE TABLE " .$tableRealName ." (
					id int(9) NOT NULL AUTO_INCREMENT,
					type varchar(60) NOT NULL,
					title tinytext NOT NULL,
					alias tinytext,
					ordering int not NULL,
					params text,
					PRIMARY KEY (id)
					)$charset_collate;";
					break;
				default:
					UniteFunctionsMeg::throwError("table: $tableName not found");
				break;
			}
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		/**
		 *
		 * add ajax back end callback, on some action to some function.
		 */
		protected static function addActionAjax($ajaxAction, $eventFunction){
			self::addAction('wp_ajax_'.GlobalsUG::PLUGIN_NAME."_".$ajaxAction, $eventFunction);
			self::addAction('wp_ajax_nopriv_'.GlobalsUG::PLUGIN_NAME."_".$ajaxAction, $eventFunction);
		}
		
		
		/**
		 *
		 * register the "onActivate" event
		 */
		protected function addEvent_onActivate($eventFunc = "onActivate"){
			
			register_activation_hook( $this->mainFilepath, array(self::$t, $eventFunc) );
		}
		
		
		/**
		 *
		 * add menu page
		 */
		protected static function addMenuPage($title,$pageFunctionName){
			self::$arrMenuPages[] = array("title"=>$title,"pageFunction"=>$pageFunctionName);
		}
		
		/**
		 *
		 * add sub menu page
		 */
		protected static function addSubMenuPage($slug,$title,$pageFunctionName){
			self::$arrSubMenuPages[] = array("slug"=>$slug,"title"=>$title,"pageFunction"=>$pageFunctionName);
		}
		
		
		/**
		 * add admin menus from the list.
		 */
		public static function addAdminMenu(){
			
				
			//return(false);
			foreach(self::$arrMenuPages as $menu){
				$title = $menu["title"];
				$pageFunctionName = $menu["pageFunction"];
				
				add_menu_page( $title, $title, self::$capability, GlobalsUG::PLUGIN_NAME, array(self::$t, $pageFunctionName) );
			}
		
			foreach(self::$arrSubMenuPages as $key=>$submenu){
		
				$title = $submenu["title"];
				$pageFunctionName = $submenu["pageFunction"];
		
				$slug = GlobalsUG::PLUGIN_NAME."_".$submenu["slug"];
		
				if($key == 0)
					$slug = GlobalsUG::PLUGIN_NAME;
		
				add_submenu_page(GlobalsUG::PLUGIN_NAME, $title, $title, self::$capability, $slug, array(self::$t, $pageFunctionName) );
			}
		
		}
		
		
		/**
		 *
		 * tells if the the current plugin opened is this plugin or not
		 * in the admin side.
		 */
		private function isInsidePlugin(){
			$page = UniteFunctionsUG::getGetVar("page");
		
			if($page == GlobalsUG::PLUGIN_NAME || strpos($page, GlobalsUG::PLUGIN_NAME."_") !== false)
				return(true);
		
			return(false);
		}
		
				
		/**
		 *
		 * add some wordpress action
		 */
		protected static function addAction($action,$eventFunction){
		
			add_action( $action, array(self::$t, $eventFunction) );
		}
		
		
		/**
		 *
		 * validate admin permissions, if no pemissions - exit
		 */
		protected static function validateAdminPermissions(){
			
			if(UniteFunctionsWPUG::isAdminPermissions(self::$capability) == false){
				echo "access denied, no ".GlobalsUG::PERMISSION." permissions";
				return(false);
			}
			
		}
		
		
		/**
		 *
		 * admin main page function.
		 */
		public static function adminPages(){
			
			self::createTables();
						
			parent::adminPages();
			
		}
		
		
		
		/**
		 * print custom scripts
		 */
		public static function onPrintFooterScripts(){
			
			$arrScrips = UniteProviderFunctionsUG::getCustomScripts();
			echo "<script type='text/javascript'>\n";
			foreach ($arrScrips as $script){
				echo $script."\n";
			}
			echo "</script>";
			
			UniteProviderFunctionsUG::printInlineStyles();
			
		}
		
		
		/**
		 * on ajax actions
		 */
		public static function onAjaxAction(){
			
				$arrFrontActions = array("front_get_cat_items");
				
				$clientAction = UniteFunctionsUG::getPostVariable("client_action");
				
				$isFrontAction = in_array($clientAction, $arrFrontActions);
				
				if($isFrontAction == true){		//front end action
					
					$operations = new UGOperations();
					$operations->onClientAjaxActions();
					
				}else{		//backend actions
					
					//verify nonce
					$nonce = UniteFunctionsUG::getPostGetVariable("nonce");
					$verified = wp_verify_nonce($nonce, "unitegallery_actions");
					if($verified == false){
						_e("Unauthorised access!, if you updated the gallery recently, please do ctrl+f5 - refresh the page and try again.", UNITEGALLERY_TEXTDOMAIN);
						exit();
					}
					
					parent::onAjaxAction();
				}
				
			
		}
		
		
		/**
		 * 
		 * init function
		 */
		public function init(){
			
			parent::init();

			self::addMenuPage('Unite Gallery', "adminPages");
			self::addSubMenuPage("galleries", __('Galleries',UNITEGALLERY_TEXTDOMAIN), "adminPages");
			self::addSubMenuPage("items", __('Items', UNITEGALLERY_TEXTDOMAIN), "adminPages");

			//add internal hook for adding a menu in arrMenus
			self::addAction(self::ACTION_ADMIN_MENU, "addAdminMenu");
			
			//if not inside plugin don't continue
			if($this->isInsidePlugin() == true){
				self::addAction(self::ACTION_ADD_SCRIPTS, "onAddScripts");
				self::addAction(self::ACTION_PRINT_SCRIPT, "onPrintFooterScripts");
			}

			$this->addEvent_onActivate();
			
			self::addActionAjax("ajax_action", "onAjaxAction");
		}

		
		
	}

?>