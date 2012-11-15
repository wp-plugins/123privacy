<?php
/*
Plugin Name: 123privacy
Plugin URI: https://www.123privacy.nl/wordpress.aspx
Description: Plugin om de kant-en-klare cookiewet oplossing van 123privacy.nl aan WordPress toe te voegen.
Version: 1.0
Author: Ezra Verheijen
Author URI: http://www.qualityinternetsolutions.nl/
License: GPL v3 or later

/*  Copyright 2012  Ezra Verheijen  (info@qualityinternetsolutions.nl)

    This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 3, or 
	any later version, as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, visit <http://www.gnu.org/licenses/>.
*/

/*  Parts of this plugin are based on the 'Add to Header' plugin from Jens Törnell and the 'HP - Addthis Button for post' plugin from Hardik  */


// define a constant to include files in the plugin, with the correct path (including trailing slash)
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// include required classes
require PLUGIN_PATH . 'classes/Privacy_Short_Codes.php';
require PLUGIN_PATH . 'classes/Twitter_Button.php';
require PLUGIN_PATH . 'classes/Privacy_To_Wordpress.php';

if ( ! is_admin() ) {
	// add 123privacy script and stylesheet to the front-end
	add_action( 'wp_enqueue_scripts', 'add_privacy_enqueues' );
}

function add_privacy_enqueues() {
	
	/* JavaScript */

		// start with jQuery
		wp_enqueue_script( 'jquery' );
	
		// register script location, dependencies and version
		wp_register_script( 'CompriScript', 'https://www.123privacy.nl/function_compri.js', array( 'jquery' ), '1.0', false );
		// enqueue the script
		wp_enqueue_script( 'CompriScript' );
	
	
	/* Stylesheet */
	
  		// register CSS file location, dependencies, version and target media
		wp_register_style( 'cookie', 'https://www.123privacy.nl/Styles/cookie_min.css', array(), '1.0', 'all' );
		// enqueue the CSS file
		wp_enqueue_style( 'cookie' );

}

add_action( 'admin_head', 'privacy_register_head' );
/**
 * Add stylesheet to the backend
 */
function privacy_register_head() {

	// get the url of the stylesheet
    $stylesheet_url = plugins_url( 'css/style.css', __FILE__ );
	// echo the stylesheet link
    echo "<link rel='stylesheet' href='" . $stylesheet_url . "' type='text/css' />\n";
	
}

// instantiate class Privacy_Short_Codes == create shortcodes to replace content inline
new Privacy_Short_Codes();

// instantiate class Twitter_Button == add Twitter Button widget
new Twitter_Button();

// register Twitter_Button widget with WordPress
add_action( 'widgets_init', create_function( '', 'register_widget( "twitter_button" );' ) );

// instantiate class Privacy_To_Wordpress == fire up the main plugin functionality
new Privacy_To_Wordpress();