<?php
/**
 * Menu helper
 * 
 * Mostly used to fix ugly behaviour of WP towards singular CPT items
 */

class _ui_cs_Menu extends _ui_cs_Base {
	public static function init() {
		new self();
	}
	
	function __construct() {
		if( defined( '_UI_CS_PLUGIN_PATH' ) ) {
			$this->pluginPath = trailingslashit( _UI_CS_PLUGIN_PATH );
		}
		
		if( defined( '_UI_CS_PLUGIN_URL' ) ) {
			$this->pluginURL = _UI_CS_PLUGIN_URL;
		}
		
		
		if( !is_admin() ) {
			add_filter('wp_nav_menu', array( $this, 'add_menu_item_slug' ) );
		}
	}
	
	/**
	 * Add slugs to custom menu items
	 * @link https://pastebin.com/RFAdNnUW
	 * @also @see https://wordpress.stackexchange.com/questions/81797/get-menu-item-slug#144625
	 */
	function add_menu_item_slug( $output ) {
		$return = $output;
		
		$ps = get_option('permalink_structure');
	  
		if( !empty( $ps ) ) {
			$strID = preg_match_all('/<li id="menu-item-(\d+)/', $return, $matches);

			if( !empty( $matches[1] ) ) {
				foreach( $matches[1] as $mid ) {
					$id = get_post_meta( $mid, '_menu_item_object_id', true);
					$slug = basename( get_permalink( $id ) );
					$return = preg_replace('/menu-item-'.$mid.'">/', 'menu-item-'.$mid.' menu-item-'.$slug.'">', $return, 1);
				}
			}
		}
		
		return $return;
	}

}
