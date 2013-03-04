<?php

/* Create shortcodes to replace content inline */

class Privacy_Short_Codes {

    public function __construct() {
		
		// [123privacy_tekst]
        add_shortcode( '123privacy_tekst', array( $this, 'short_text_code' ) );
		
		// [123privacy_url]
		add_shortcode( '123privacy_url', array( $this, 'short_url_code' ) );
		
		// Add shortcode support for widgets  
		add_filter( 'widget_text', 'do_shortcode' );
		
    }  
	
    public function short_text_code() {
	
        return '<a href="http://www.123privacy.nl/" title="123privacy.nl" target="_blank">Dé oplossing voor de nieuwe cookiewet</a>';
		  
    }
	
	public function short_url_code() {
	 
        return '<a href="http://www.123privacy.nl/" title="Dé oplossing voor de nieuwe cookiewet" target="_blank">123privacy.nl</a>';
		  
    }
	
}