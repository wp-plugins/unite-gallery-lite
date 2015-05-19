<?php


defined('_JEXEC') or die('Restricted access');


	class UGMainOutput extends UniteOutputBaseUG{
		
		protected static $serial = 0;
		
		private $gallery;
		protected $urlPlugin;
		private $galleryHtmlID;
		private $galleryID;
		protected $theme;
		private $putNoConflictMode = false;
		protected $putJsToBody = false;
		protected $isTilesType = false;
		protected $arrJsParamsAssoc = array();
		
		const THEME_DEFAULT = "default";
		const THEME_COMPACT = "compact";
		const THEME_SLIDER = "slider";
		const THEME_GRID = "grid";
		const THEME_VIDEO = "video";
		const THEME_TILES = "tiles";
		const THEME_TILESGRID = "tilesgrid";
		const THEME_CAROUSEL = "carousel";
		
		
		/**
		 * 
		 * construct the output object
		 */
		public function __construct(){
						
			$this->init();
		}
		
		
		
		/**
		 * 
		 * init the gallery
		 */
		private function init(){
			
			$urlBase = GlobalsUGGallery::$urlBase;
			if(empty($urlBase))
				UniteFunctionsUG::throwError("The gallery globals object not inited!");
			 
			$this->urlPlugin = GlobalsUG::$url_media_ug;
			
		}
		
		/**
		 * get must fields that will be thrown from the settings anyway
		 */
		protected function getArrMustFields(){
			$arrMustKeys = array(
					"category",					
					"gallery_theme",
					"full_width",
					"gallery_width",
					"gallery_height",
					"position",
					"margin_top",
					"margin_bottom",
					"margin_left",
					"margin_right"
			);

			return($arrMustKeys);
		}
		
		
		/**
		 * 
		 * init gallery related variables
		 */
		protected function initGallery($galleryID){
			
			self::$serial++;
			
			$this->gallery = new UniteGalleryGallery();
			$this->gallery->initByID($galleryID);
			
			//get real gallery id: 
			$galleryID = $this->gallery->getID();
			
			$serial = self::$serial;
			$this->galleryID = $galleryID;
			$this->galleryHtmlID = "ugdefault_{$galleryID}_{$serial}";
			
			$origParams = $this->gallery->getParams();
			
			//set params for default settings get function
			$this->arrOriginalParams = $origParams;	
			
			$defaultValues = $this->getDefautSettingsValues();
						
			$origParams = UniteFunctionsUG::filterArrFields($origParams, $defaultValues, true);
			
			$this->arrOriginalParams = array_merge($defaultValues, $origParams);
						
			$arrMustKeys = $this->getArrMustFields();
						
			$this->arrParams = UniteFunctionsUG::getDiffArrItems($this->arrOriginalParams, $defaultValues, $arrMustKeys);
												
			$this->modifyOptions();
			
		}
		
				
		
		/**
		 * modify options
		 */
		protected function modifyOptions(){
			
			if($this->isTilesType == true){
				
				
				//handle compact lightbox type options
				$lightboxType = $this->getParam("lightbox_type");
				
				if($lightboxType == "compact"){
					
					$this->renameOption("lightbox_compact_overlay_opacity", "lightbox_overlay_opacity", true);
					$this->renameOption("lightbox_compact_overlay_color", "lightbox_overlay_color", true);
					$this->renameOption("lightbox_compact_show_numbers", "lightbox_show_numbers", true);
					$this->renameOption("lightbox_compact_numbers_size", "lightbox_numbers_size", true);
					$this->renameOption("lightbox_compact_numbers_color", "lightbox_numbers_color", true);
					$this->renameOption("lightbox_compact_numbers_padding_top", "lightbox_numbers_padding_top", true);
					$this->renameOption("lightbox_compact_numbers_padding_right", "lightbox_numbers_padding_right", true);
					$this->renameOption("lightbox_compact_show_textpanel", "lightbox_show_textpanel", true);
					$this->renameOption("lightbox_compact_textpanel_source", "lightbox_textpanel_source", true);
					$this->renameOption("lightbox_compact_textpanel_title_color", "lightbox_textpanel_title_color", true);
					$this->renameOption("lightbox_compact_textpanel_title_font_size", "lightbox_textpanel_title_font_size", true);
					$this->renameOption("lightbox_compact_textpanel_title_bold", "lightbox_textpanel_title_bold", true);
					$this->renameOption("lightbox_compact_textpanel_padding_left", "lightbox_textpanel_padding_left", true);
					$this->renameOption("lightbox_compact_textpanel_padding_right", "lightbox_textpanel_padding_right", true);
					$this->renameOption("lightbox_compact_textpanel_padding_top", "lightbox_textpanel_padding_top", true);
					$this->renameOption("lightbox_compact_slider_image_border", "lightbox_slider_image_border", true);
					$this->renameOption("lightbox_compact_slider_image_border_width", "lightbox_slider_image_border_width", true);
					$this->renameOption("lightbox_compact_slider_image_border_color", "lightbox_slider_image_border_color", true);
					$this->renameOption("lightbox_compact_slider_image_border_radius", "lightbox_slider_image_border_radius", true);
					$this->renameOption("lightbox_compact_slider_image_shadow", "lightbox_slider_image_shadow", true);
					
					$this->deleteOption("lightbox_textpanel_title_text_align");
				}else{
					
					//delete all compact related options if exists
					$arrOptionsToDelete = array(
						"lightbox_compact_overlay_opacity",
						"lightbox_compact_overlay_color",
						"lightbox_compact_show_numbers",
						"lightbox_compact_numbers_size",
						"lightbox_compact_numbers_color",
						"lightbox_compact_numbers_padding_top",
						"lightbox_compact_numbers_padding_right",
						"lightbox_compact_show_textpanel",
						"lightbox_compact_textpanel_source",
						"lightbox_compact_textpanel_title_color",
						"lightbox_compact_textpanel_title_font_size",
						"lightbox_compact_textpanel_title_bold",
						"lightbox_compact_textpanel_padding_top",
						"lightbox_compact_textpanel_padding_left",
						"lightbox_compact_textpanel_padding_right",
						"lightbox_compact_slider_image_border",
						"lightbox_compact_slider_image_border_width",
						"lightbox_compact_slider_image_border_color",
						"lightbox_compact_slider_image_border_radius",
						"lightbox_compact_slider_image_shadow"
					);
					
					$this->deleteOptions($arrOptionsToDelete);
				}
				
				//handle text panel source
				$lightboxSource = $this->getParam("lightbox_textpanel_source");
				if($lightboxSource == "desc"){
					$this->arrParams["lightbox_textpanel_enable_title"] = "false";
					$this->arrParams["lightbox_textpanel_enable_description"] = "true";
				
					$this->renameOption("lightbox_textpanel_title_color", "lightbox_textpanel_desc_color");
					$this->renameOption("lightbox_textpanel_title_text_align", "lightbox_textpanel_desc_text_align");
					$this->renameOption("lightbox_textpanel_title_font_size", "lightbox_textpanel_desc_font_size");
					$this->renameOption("lightbox_textpanel_title_bold", "lightbox_textpanel_desc_bold");
				
				}
				
			}else{
				if($this->isParamExists("strippanel_background_transparent")){
					$isTrans = $this->getParam("strippanel_background_transparent", self::FORCE_BOOLEAN);
					if($isTrans == true)
						$this->arrParams["strippanel_background_color"] = "transparent";
				}
			}
			
			
		}
		
		
		/**
		 * get array of skins that exists in the gallery
		 */
		protected function getArrActiveSkins($arrAddOptions = array()){
			
			$gallerySkin = $this->getParam("gallery_skin");
			
			if(empty($gallerySkin))
				$gallerySkin = "default";
			
			$arrSkins = array($gallerySkin=>true);
			
			$arrOptions = array(
					"strippanel_buttons_skin",
					"strippanel_handle_skin",
					"slider_bullets_skin",
					"slider_arrows_skin",
					"slider_play_button_skin",
					"slider_fullscreen_button_skin",
					"slider_zoompanel_skin"
			);
			
			$arrOptions = array_merge($arrOptions, $arrAddOptions);
			
			foreach($arrOptions as $option){
				$skin = $this->getParam($option);
				if(empty($skin))
					continue;
				
				$arrSkins[$skin] = true;
			}
			
			return($arrSkins);
		}
		
		
		/**
		 * 
		 * put gallery scripts
		 */
		protected function putScripts($putSkins = true){
			
			//put jquery
			$includeJQuery = $this->getParam("include_jquery", self::FORCE_BOOLEAN);
			if($includeJQuery == true){
				$urljQuery = GlobalsUG::$url_media_ug."js/jquery-11.0.min.js";
				UniteProviderFunctionsUG::addjQueryInclude("unitegallery", $urljQuery);
			}
			
			if($this->putJsToBody == false)
				HelperGalleryUG::addScriptAbsoluteUrl($this->urlPlugin."js/unitegallery.min.js", "unitegallery_main");
			
			HelperGalleryUG::addStyleAbsoluteUrl($this->urlPlugin."css/unite-gallery.css","unite-gallery-css");
			
			//include skins
			if($putSkins == true){
			
				$arrSkins = $this->getArrActiveSkins();
				
				foreach($arrSkins as $skin => $nothing){
					if(empty($skin) || $skin == "default")
						continue;
					
					HelperGalleryUG::addStyleAbsoluteUrl($this->urlPlugin."skins/{$skin}/{$skin}.css","ug-skin-{$skin}");
				}
			}
			
		}
		
		
		/**
		 * get default settings values
		 * get them only once
		 */
		protected function getDefautSettingsValues(){
			
			require HelperGalleryUG::getFilepathSettings("gallery_settings");
			
			return($valuesMerged);
		}
		
		
		
		
		/**
		 * get params array defenitions that shouls be put as is from the settings
		 */
		protected function getArrJsOptions(){
			
			$arr = array();
			$arr[] = $this->buildJsParam("gallery_theme");
			$arr[] = $this->buildJsParam("gallery_width", self::VALIDATE_SIZE, self::TYPE_SIZE);
			$arr[] = $this->buildJsParam("gallery_height", self::VALIDATE_SIZE, self::TYPE_SIZE);
			$arr[] = $this->buildJsParam("gallery_min_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gallery_min_height", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gallery_skin");
			$arr[] = $this->buildJsParam("gallery_images_preload_type");
			$arr[] = $this->buildJsParam("gallery_autoplay", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("gallery_play_interval", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gallery_pause_on_mouseover", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("gallery_mousewheel_role");
			$arr[] = $this->buildJsParam("gallery_control_keyboard", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("gallery_preserve_ratio", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("gallery_shuffle", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("gallery_debug_errors", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_background_color");
			$arr[] = $this->buildJsParam("slider_background_opacity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
					
			$arr[] = $this->buildJsParam("slider_scale_mode");
			$arr[] = $this->buildJsParam("slider_scale_mode_media");
			$arr[] = $this->buildJsParam("slider_scale_mode_fullscreen");
			
			$arr[] = $this->buildJsParam("slider_transition");
			$arr[] = $this->buildJsParam("slider_transition_speed", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_transition_easing");
			$arr[] = $this->buildJsParam("slider_control_swipe", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_control_zoom", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_zoom_max_ratio", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_enable_links", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_links_newpage", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_video_enable_closebutton", null, self::TYPE_BOOLEAN);
			
			$arr[] = $this->buildJsParam("slider_controls_always_on", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_controls_appear_ontap", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_controls_appear_duration", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			$arr[] = $this->buildJsParam("slider_loader_type", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_loader_color");
			
			$arr[] = $this->buildJsParam("slider_enable_bullets", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_bullets_skin");
			$arr[] = $this->buildJsParam("slider_bullets_space_between", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_bullets_align_hor");
			$arr[] = $this->buildJsParam("slider_bullets_align_vert");
			$arr[] = $this->buildJsParam("slider_bullets_offset_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_bullets_offset_vert", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);

			$arr[] = $this->buildJsParam("slider_enable_arrows", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_arrows_skin");
			$arr[] = $this->buildJsParam("slider_arrow_left_align_hor");
			$arr[] = $this->buildJsParam("slider_arrow_left_align_vert");
			$arr[] = $this->buildJsParam("slider_arrow_left_offset_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_arrow_left_offset_vert", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_arrow_right_align_hor");
			$arr[] = $this->buildJsParam("slider_arrow_right_align_vert");
			$arr[] = $this->buildJsParam("slider_arrow_right_offset_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_arrow_right_offset_vert", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);

			$arr[] = $this->buildJsParam("slider_enable_progress_indicator", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_progress_indicator_type");
			$arr[] = $this->buildJsParam("slider_progress_indicator_align_hor");
			$arr[] = $this->buildJsParam("slider_progress_indicator_align_vert");
			$arr[] = $this->buildJsParam("slider_progress_indicator_offset_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_progress_indicator_offset_vert", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			$arr[] = $this->buildJsParam("slider_progressbar_color");
			$arr[] = $this->buildJsParam("slider_progressbar_opacity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_progressbar_line_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			$arr[] = $this->buildJsParam("slider_progresspie_color1");
			$arr[] = $this->buildJsParam("slider_progresspie_color2");
			$arr[] = $this->buildJsParam("slider_progresspie_stroke_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_progresspie_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_progresspie_height", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			$arr[] = $this->buildJsParam("slider_enable_play_button", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_play_button_skin");
			$arr[] = $this->buildJsParam("slider_play_button_align_hor");
			$arr[] = $this->buildJsParam("slider_play_button_align_vert");
			$arr[] = $this->buildJsParam("slider_play_button_offset_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_play_button_offset_vert", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);

			$arr[] = $this->buildJsParam("slider_enable_fullscreen_button", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_fullscreen_button_skin");
			$arr[] = $this->buildJsParam("slider_fullscreen_button_align_hor");
			$arr[] = $this->buildJsParam("slider_fullscreen_button_align_vert");
			$arr[] = $this->buildJsParam("slider_fullscreen_button_offset_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_fullscreen_button_offset_vert", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);

			$arr[] = $this->buildJsParam("slider_enable_zoom_panel", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_zoompanel_skin");
			$arr[] = $this->buildJsParam("slider_zoompanel_align_hor");
			$arr[] = $this->buildJsParam("slider_zoompanel_align_vert");
			$arr[] = $this->buildJsParam("slider_zoompanel_offset_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_zoompanel_offset_vert", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			$arr[] = $this->buildJsParam("slider_enable_text_panel", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_textpanel_always_on", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_textpanel_align");
			$arr[] = $this->buildJsParam("slider_textpanel_margin", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_text_valign");
			$arr[] = $this->buildJsParam("slider_textpanel_padding_top", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_padding_bottom", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_height", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_padding_title_description", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_padding_right", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_padding_left", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_fade_duration", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_enable_title", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_textpanel_enable_description", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_textpanel_enable_bg", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("slider_textpanel_bg_color");
			$arr[] = $this->buildJsParam("slider_textpanel_bg_opacity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("slider_textpanel_bg_css", null, self::TYPE_OBJECT);
			$arr[] = $this->buildJsParam("slider_textpanel_css_title", null, self::TYPE_OBJECT);
			$arr[] = $this->buildJsParam("slider_textpanel_css_description", null, self::TYPE_OBJECT);
			
			$arr[] = $this->buildJsParam("thumb_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("thumb_height", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("thumb_border_effect", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("thumb_border_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("thumb_border_color");
			$arr[] = $this->buildJsParam("thumb_over_border_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("thumb_over_border_color");
			$arr[] = $this->buildJsParam("thumb_selected_border_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("thumb_selected_border_color");
			$arr[] = $this->buildJsParam("thumb_round_corners_radius", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("thumb_color_overlay_effect", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("thumb_overlay_color");
			$arr[] = $this->buildJsParam("thumb_overlay_opacity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("thumb_overlay_reverse", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("thumb_image_overlay_effect", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("thumb_image_overlay_type");
			$arr[] = $this->buildJsParam("thumb_transition_duration", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("thumb_transition_easing");
			$arr[] = $this->buildJsParam("thumb_show_loader", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("thumb_loader_type");
			
			$arr[] = $this->buildJsParam("strippanel_padding_top", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strippanel_padding_bottom", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strippanel_padding_left", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strippanel_padding_right", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strippanel_enable_buttons", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("strippanel_buttons_skin");
			$arr[] = $this->buildJsParam("strippanel_padding_buttons", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strippanel_buttons_role");
			$arr[] = $this->buildJsParam("strippanel_enable_handle", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("strippanel_handle_align");
			$arr[] = $this->buildJsParam("strippanel_handle_offset", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strippanel_handle_skin");
			$arr[] = $this->buildJsParam("strippanel_background_color");
			$arr[] = $this->buildJsParam("strip_thumbs_align");
			$arr[] = $this->buildJsParam("strip_space_between_thumbs", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strip_thumb_touch_sensetivity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strip_scroll_to_thumb_duration", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("strip_scroll_to_thumb_easing");
			$arr[] = $this->buildJsParam("strip_control_avia", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("strip_control_touch", null, self::TYPE_BOOLEAN);
			
			$arr[] = $this->buildJsParam("gridpanel_vertical_scroll", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("gridpanel_grid_align");
			$arr[] = $this->buildJsParam("gridpanel_padding_border_top", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gridpanel_padding_border_bottom", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gridpanel_padding_border_left", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gridpanel_padding_border_right", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gridpanel_arrows_skin");
			$arr[] = $this->buildJsParam("gridpanel_arrows_align_vert");
			$arr[] = $this->buildJsParam("gridpanel_arrows_padding_vert", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gridpanel_arrows_align_hor");
			$arr[] = $this->buildJsParam("gridpanel_arrows_padding_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gridpanel_space_between_arrows", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gridpanel_arrows_always_on", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("gridpanel_enable_handle", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("gridpanel_handle_align");
			$arr[] = $this->buildJsParam("gridpanel_handle_offset", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("gridpanel_handle_skin");
			$arr[] = $this->buildJsParam("gridpanel_background_color");
			
			$arr[] = $this->buildJsParam("grid_panes_direction");
			$arr[] = $this->buildJsParam("grid_num_cols", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("grid_space_between_cols", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("grid_space_between_rows", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("grid_transition_duration", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("grid_transition_easing");
			$arr[] = $this->buildJsParam("grid_carousel", null, self::TYPE_BOOLEAN);
			
			if($this->isTilesType == true){
				
				$arr[] = $this->buildJsParam("tile_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_height", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_enable_border", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_border_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_border_color");
				$arr[] = $this->buildJsParam("tile_border_radius", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_enable_outline", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_outline_color");
				$arr[] = $this->buildJsParam("tile_enable_shadow", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_shadow_h", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_shadow_v", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_shadow_blur", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_shadow_spread", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_shadow_color");
				$arr[] = $this->buildJsParam("tile_enable_action");
				$arr[] = $this->buildJsParam("tile_as_link", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_link_newpage", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_enable_overlay", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_overlay_opacity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_overlay_color");
				$arr[] = $this->buildJsParam("tile_enable_icons", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_show_link_icon", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_space_between_icons", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_enable_image_effect", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_image_effect_type");
				$arr[] = $this->buildJsParam("tile_image_effect_reverse", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_enable_textpanel", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_textpanel_source");
				
				$arr[] = $this->buildJsParam("tile_textpanel_always_on", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("tile_textpanel_appear_type");
				$arr[] = $this->buildJsParam("tile_textpanel_padding_top", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_textpanel_padding_bottom", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_textpanel_padding_left", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_textpanel_padding_right", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_textpanel_bg_color");
				$arr[] = $this->buildJsParam("tile_textpanel_bg_opacity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_textpanel_title_color");
				$arr[] = $this->buildJsParam("tile_textpanel_title_text_align");
				$arr[] = $this->buildJsParam("tile_textpanel_title_font_size", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("tile_textpanel_title_bold", null, self::TYPE_BOOLEAN);

				$arr[] = $this->buildJsParam("lightbox_hide_arrows_onvideoplay", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("lightbox_slider_control_zoom", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("lightbox_overlay_opacity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_overlay_color");
				
				$arr[] = $this->buildJsParam("lightbox_top_panel_opacity", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_show_numbers", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("lightbox_numbers_size", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_numbers_color");
				$arr[] = $this->buildJsParam("lightbox_show_textpanel", null, self::TYPE_BOOLEAN);
				
				$arr[] = $this->buildJsParam("lightbox_textpanel_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_textpanel_enable_title", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("lightbox_textpanel_enable_description", null, self::TYPE_BOOLEAN);
				
				$arr[] = $this->buildJsParam("lightbox_textpanel_title_color");
				$arr[] = $this->buildJsParam("lightbox_textpanel_title_text_align");
				$arr[] = $this->buildJsParam("lightbox_textpanel_title_font_size", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_textpanel_title_bold", null, self::TYPE_BOOLEAN);
				
				$arr[] = $this->buildJsParam("lightbox_textpanel_desc_color");
				$arr[] = $this->buildJsParam("lightbox_textpanel_desc_text_align");
				$arr[] = $this->buildJsParam("lightbox_textpanel_desc_font_size", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_textpanel_desc_bold", null, self::TYPE_BOOLEAN);
				
				//lightbox compact related styles
				$arr[] = $this->buildJsParam("lightbox_type");
				$arr[] = $this->buildJsParam("lightbox_arrows_position");
				$arr[] = $this->buildJsParam("lightbox_arrows_inside_alwayson", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("lightbox_numbers_padding_top", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_numbers_padding_right", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_textpanel_padding_left", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_textpanel_padding_right", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_textpanel_padding_top", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_slider_image_border", null, self::TYPE_BOOLEAN);
				$arr[] = $this->buildJsParam("lightbox_slider_image_border_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_slider_image_border_color");
				$arr[] = $this->buildJsParam("lightbox_slider_image_border_radius", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
				$arr[] = $this->buildJsParam("lightbox_slider_image_shadow", null, self::TYPE_BOOLEAN);
				
			}	//tiles type end
			
			
			return($arr);
		}
		
		
		/**
		 * put error message instead of the gallery
		 */
		private function putErrorMessage(Exception $e, $prefix){
			
			$message = $e->getMessage();
			$trace = "";
			if(GlobalsUG::SHOW_TRACE == true)
				$trace = $e->getTraceAsString();
			
			$message = $prefix . ": ".$message;
			
			HelperUG::$operations->putModuleErrorMessage($message, $trace);
			
			?>			
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery("<?php echo $this->galleryHtmlID?>").show();
				});
			</script>
			<?php
		}
		
		/**
		 * put javascript includes to the body before the gallery div
		 */
		protected function putJsIncludesToBody(){
			$src = $this->urlPlugin."js/unitegallery.min.js";
			$html = "\n <script type='text/javascript' src='{$src}'></script>";
			
			return($html);
		}
		
		
		/**
		 * get video add html
		 */
		protected function getVideoAddHtml($type, $objItem){
			
			$addHtml = "";
			switch($type){
				case UniteGalleryItem::TYPE_YOUTUBE:
				case UniteGalleryItem::TYPE_VIMEO:
				case UniteGalleryItem::TYPE_WISTIA:
					$videoID = $objItem->getParam("videoid");
					$addHtml .= "data-videoid=\"{$videoID}\" ";
					break;
				case UniteGalleryItem::TYPE_HTML5VIDEO:
					$urlMp4 = $objItem->getParam("video_mp4");
					$urlWebm = $objItem->getParam("video_webm");
					$urlOgv = $objItem->getParam("video_ogv");
			
					$addHtml .= "data-videomp4=\"{$urlMp4}\" ";
					$addHtml .= "data-videowebm=\"{$urlWebm}\" ";
					$addHtml .= "data-videoogv=\"{$urlOgv}\" ";
			
					break;
			}
			
			return($addHtml);
		}

		
		/**
		 * put gallery items
		 */
		protected function putItems($arrItems){
			
			$tab = "						";
			$nl = "\n".$tab;
			
			$thumbSize = "";
			if($this->isTilesType){
				$thumbSize = $this->getParam("tile_image_resolution");
			}
			
			$totalHTML = "";
			
			$counter = 0;
			
			// Dear friend. Yes, you have found a place where you can 
			// programmically remove the limitations. 
			// Though you should know that it's Illigal, and not moral! 
			// If you like the gallery and has respect to it's developers hard work, you should purchase a full version copy!.
			// Please buy it from here: http://codecanyon.net/item/unite-gallery-wordpress-plugin/10458750
			// You'll get lifetime support and updates, so why not, it's not so expensive!
			
			foreach($arrItems as $objItem):

				if($this->isTilesType && $counter >= 20)
					break;
				else 
				  if($this->isTilesType == false && $counter >= 12)
					break;
				
				$counter++;
			
				$urlImage = $objItem->getUrlImage();
				$urlThumb = $objItem->getUrlThumb($thumbSize);
				
				$title = $objItem->getTitle();
				$type = $objItem->getType();
				$alt = $objItem->getAlt();
				
				$description = $objItem->getParam("ug_item_description");
				
				$enableLink = $objItem->getParam("ug_item_enable_link");
				$enableLink = UniteFunctionsUG::strToBool($enableLink);
				
				//combine description
				if($enableLink == true){
					$link = $objItem->getParam("ug_item_link");
					
					/*
					if(!empty($link) && $this->isTilesType == false){
						$isBlank = ($objItem->getParam("ug_item_link_open_in") == "new");
						$htmlLink = UniteFunctionsUG::getHtmlLink($link, $link, "", "", $isBlank);
						$description .= " ".$htmlLink;
					}
					*/
				}
				
				$title = htmlspecialchars($title);
				$description = htmlspecialchars($description);
				$alt = htmlspecialchars($alt);
				
				
				$strType = "";
				if($type != UniteGalleryItem::TYPE_IMAGE){
					$strType = "data-type=\"{$type}\" ";
				}
				
				$addHtml = $this->getVideoAddHtml($type, $objItem);
				
				//set link (on tiles mode)
				$linkStart = "";
				$linkEnd = "";
				if($enableLink == true){
					$linkStart = "<a href=\"{$link}\">";
					$linkEnd = "</a>";
				}
				
				$html = "\n";
				
				if($linkStart)
					$html .= $nl.$linkStart;
				
				$html .= $nl."<img alt=\"{$alt}\"";
				$html .= $nl."    {$strType} src=\"{$urlThumb}\"";
				$html .= $nl."     data-image=\"{$urlImage}\"";
				$html .= $nl."     data-description=\"{$description}\"";
				$html .= $nl."     {$addHtml}style=\"display:none\">";
				
				if($linkEnd)
					$html .= $nl.$linkEnd;
				
				$totalHTML .= $html;
			
			 endforeach;	
			 
			 return($totalHTML);
		}
		
		
		/**
		 * set gallery output options like put js to body etc.
		 */
		protected function setOutputOptions(){
			
			$jsToBody = $this->getParam("js_to_body", self::FORCE_BOOLEAN);
			$this->putJsToBody = $jsToBody;
						
		}
		
		
		/**
		 * 
		 * put the gallery
		 */
		public function putGallery($galleryID, $arrOptions = array(), $initType = "id"){

			
			try{
				$objCategories = new UniteGalleryCategories();
				
				$this->initGallery($galleryID);
				
				$this->setOutputOptions();
				
				$this->putScripts();
				
				if(isset($arrOptions["scriptsonly"]))
					return(false);
				
				//custom items pass
				if(is_array($arrOptions) && array_key_exists("items", $arrOptions)){

					$arrItems = $arrOptions["items"];
					
				}else{
					
					//set gallery category
					$optCatID = UniteFunctionsUG::getVal($arrOptions, "categoryid");
					if(!empty($optCatID) && $objCategories->isCatExists($optCatID))
						$categoryID = $optCatID;
					else
						$categoryID = $this->getParam("category");
					
					if(empty($categoryID))
						UniteFunctionsUG::throwError(__("No items category selected", UNITEGALLERY_TEXTDOMAIN));
					
					$items = new UniteGalleryItems();
					$arrItems = $items->getCatItems($categoryID);
				}
				
				
				if(empty($arrItems))
					UniteFunctionsUG::throwError("No gallery items found", UNITEGALLERY_TEXTDOMAIN);
	
				//set wrapper style
				
				//size validation
				$this->getParam("gallery_width", self::FORCE_SIZE);
				if($this->isTilesType == false)
					$this->getParam("gallery_height", self::VALIDATE_NUMERIC);
				
				$fullWidth = $this->getParam("full_width", self::FORCE_BOOLEAN);
				
				if($fullWidth == true){
					$this->arrParams["gallery_width"] = "100%";
				}
				
				$wrapperStyle = $this->getPositionString();
								
				//set position			
				$jsOptions = $this->buildJsParams();
				
				
				global $uniteGalleryVersion;
				$output = "
					\n
					<!-- START Unite Gallery Lite {$uniteGalleryVersion} -->
					
				";
				
				if($this->putJsToBody == true)
					$output .= $this->putJsIncludesToBody();
				
				$linePrefix = "\n			";
				$linePrefix2 = "\n				";
				$linePrefix3 = "\n					";
				$linePrefix4 = "\n						";
				$br = "\n";
				
				$serial = self::$serial;
				$galleryHtmlID = $this->galleryHtmlID;
				
				$output .= $linePrefix."<div id='{$this->galleryHtmlID}' style='{$wrapperStyle}'>";
				$output .= $linePrefix2.$this->putItems($arrItems);
				$output .= $linePrefix."</div>";
				$output .= $br;
				$output .= $linePrefix."<script type='text/javascript'>";
				
				if($this->putNoConflictMode == true)
				$output .= $linePrefix2."jQuery.noConflict();";
								
				$output .= $linePrefix2."var ugapi{$serial};";
				$output .= $linePrefix2."jQuery(document).ready(function(){";
				$output .= $linePrefix3."var objUGParams = {";
				$output .= $linePrefix4.$jsOptions;
				$output .= $linePrefix3."};";
								
				$output .= $linePrefix3."if(ugCheckForErrors('#{$galleryHtmlID}', 'cms'))";
				$output .= $linePrefix4."ugapi{$serial} = jQuery('#{$galleryHtmlID}').unitegallery(objUGParams);";
				$output .= $linePrefix2."});";
				$output .= $linePrefix."</script>";
				$output .= $br;
				$output .= $linePrefix."<!-- END UNITEGALLERY -->";
				
				$compressOutput = $this->getParam("compress_output", self::FORCE_BOOLEAN);
				
				if($compressOutput == true){
					$output = str_replace("\r", "", $output);
					$output = str_replace("\n", "", $output);
					$output = trim($output);
				}
				return $output;
				?>
				
			<?php 
			
		     }catch(Exception $e){
		     	$prefix = __("Unite Gallery Lite Error",UNITEGALLERY_TEXTDOMAIN);
				$this->putErrorMessage($e, $prefix);
		     }
		
		  }
}

?>