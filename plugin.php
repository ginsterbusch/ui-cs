<?php
/**
 * Plugin Name: UI Content Shortcodes
 * Description: Shortcodes plus a few helpers for safe editing content. Esp. helpful when redesign or restructuring a pre-existing website.
 * Version: 1.2
 * Plugin URI: http://f2w.de/ui-cs
 * Author: Fabian Wolf
 * Author URI: http://usability-idealist.de/
 * License: GNU GPL v2
 */
// set up definitions
if( !defined( '_UI_CS_PLUGIN_PATH' ) ) {
	define( '_UI_CS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if( !defined( '_UI_CS_PLUGIN_URL' ) ) {
	define('_UI_CS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// base class
require_once( _UI_CS_PLUGIN_PATH . 'includes/base.php');


// menu helpers
require_once( _UI_CS_PLUGIN_PATH . 'includes/menu.php' );

if( class_exists( '_ui_cs_Menu' ) ) {
	add_action('plugins_loaded', array( '_ui_cs_Menu', 'init' ) );
}

// set up shortcodes
require_once( _UI_CS_PLUGIN_PATH . 'includes/shortcodes.php');

if( class_exists( '_ui_cs_Shortcodes' ) ) {
	add_action( 'plugins_loaded', array('_ui_cs_Shortcodes', 'init' ) );
}


