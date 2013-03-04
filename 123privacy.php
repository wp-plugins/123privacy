<?php
/*
Plugin Name: 123privacy
Plugin URI: https://www.123privacy.nl/wordpress.aspx
Description: Plugin om de kant-en-klare cookiewet oplossing van 123privacy.nl aan WordPress toe te voegen.
Version: 1.1
Author: Ezra Verheijen
Author URI: http://www.qualityinternetsolutions.nl/
License: GPL v3 or later
*/

/*  Copyright © 2012-2013  Ezra Verheijen @ QIS (info@qualityinternetsolutions.nl)

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


// Define a constant to include files in the plugin, with the correct path (including trailing slash)
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Include required files
require PLUGIN_PATH . 'classes/Privacy_Short_Codes.php';
require PLUGIN_PATH . 'classes/Twitter_Button.php';
require PLUGIN_PATH . 'classes/Privacy_To_Wordpress.php';

if ( ! is_admin() ) {
	
	// Add 123privacy script and stylesheet to the front-end
	add_action( 'wp_enqueue_scripts', 'add_privacy_enqueues' );
	
}

function add_privacy_enqueues() {
	
	/* JavaScript */

		// Start with jQuery
		wp_enqueue_script( 'jquery' );
	
		// Register script location, dependencies and version
		wp_register_script( 'cookieScript', 'https://www.123privacy.nl/cookiemessage.js', array( 'jquery' ), '1.0', false );
		// Enqueue the script
		wp_enqueue_script( 'cookieScript' );
	
	
	/* Stylesheet */
	
  		// Register CSS file location, dependencies, version and target media
		wp_register_style( 'cookie', 'https://www.123privacy.nl/Styles/cookie_min.css', array(), '1.1', 'all' );
		// Enqueue the CSS file
		wp_enqueue_style( 'cookie' );

}

add_action( 'admin_head', 'privacy_register_head' );
/**
 * Add stylesheet to the backend
 */
function privacy_register_head() {

	// Get the url of the stylesheet
    $stylesheet_url = plugins_url( 'css/style.css', __FILE__ );
	// Echo the stylesheet link to the admin <head>
    echo "<link rel='stylesheet' href='" . $stylesheet_url . "' type='text/css' />\n";
	
}

// Instantiate class Privacy_Short_Codes == Create shortcodes to replace content inline
new Privacy_Short_Codes();

// Instantiate class Twitter_Button == Add Twitter Button widget
new Twitter_Button();

// Register Twitter_Button widget with WordPress
add_action( 'widgets_init', create_function( '', 'register_widget( "twitter_button" );' ) );

// Instantiate class Privacy_To_Wordpress == Fire up the main plugin functionality
new Privacy_To_Wordpress();