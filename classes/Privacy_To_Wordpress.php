<?php

/* Fire up the main plugin functionality */

class Privacy_To_Wordpress {

	public function __construct() {
		$this -> add_menu();
	}

	// Add all the functionalities of the plugin to the plugin admin menu
	public function add_menu() {
	
		// Adding 123privacy stuff to the admin menu
		add_action( 'admin_menu', array( $this, 'admin_add_menu' ) );
		
		// Adding 123privacy stuff to the frontend right before the </body> tag
		add_action( 'wp_footer', array( $this, 'body_add_data' ) );
		
		// Adding 123privacy stuff to the front end inside the <head>
		add_action( 'wp_head', array( $this, 'head_add_data' ) );
		
		// Adding AddThis stuff to the posts (only if selected by user in the form)
		if ( get_option( 'addthis_button_show' ) == 1 )
			add_action( 'the_content', array( $this, 'addthis_button_onpost' ) );
		
	}
	
	public function admin_add_menu() {
		// Add options page to the admin menu
		add_options_page( '123privacy', '123privacy', 'administrator', 'privacy_to_wordpress', array( $this, 'options' ) );	
	}
	
	/**
	 * Retrieve 123privacy information to be added to the <body> section
	 */
	public function data_save_body() {
	   	
		// First validate that the content of the form comes from the location on the current site and not somewhere else
		if ( wp_verify_nonce( $_POST['form_content_validator'], 'validate_form_content' ) ) {
			
			// Process content if valid
			if ( isset( $_POST['body_submitter'] ) && ( ! empty( $_POST['body_submitter'] ) ) ) {
				$option_name_body        = 'add_to_body';
				$options_body['data'][0] = $_POST['body_data'];
			
				if ( get_option( $option_name_body ) ) {
					// Update user information if user information is present
					update_option( $option_name_body, $options_body );
				} else {
					// Add user information if user information is empty
					add_option( $option_name_body, $options_body );
				}
			}
		}
	}
	
	public function body_add_data() {
	   /**
	    * Adding 123privacy information to the <body> section
	    */
		$options_body = get_option( 'add_to_body' );
		$output_body  = $options_body['data'][0];	
		// Filter out any inserted HTML-code for added security
		$output_body  = wp_filter_nohtml_kses( $output_body );
		
		// Code to output to the frontend
		$output_body  = "<!-- Deze website werkt met de kant-en-klare oplossing voor de cookiewet van 123privacy -->\n<div id='123privacy_footer'></div>\n<script type='text/javascript'>\n\tjQuery(document).ready(function(){\n\t\tjQuery(CookieControle(\"" . $output_body . "\"));\n\t});\n</script>\n<!-- www.123privacy.nl -->\n";
		// Echo the code
		echo stripslashes( $output_body );
		
	}
	
	/**
	 * Retrieve 123privacy information to be added to the <head> section
	 */
	public function data_save_head() {
	  
		// First validate that the content of the form comes from the location on the current site and not somewhere else
		if ( wp_verify_nonce( $_POST['form_content_validator'], 'validate_form_content' ) ) {
			
			// Process content if valid
			if ( isset( $_POST['head_submitter'] ) && ( ! empty( $_POST['head_submitter'] ) ) ) {
				$option_name_head        = 'add_to_head';
				$options_head['data'][0] = $_POST['head_data'];
			
				if ( get_option($option_name_head) ) {
					// Update user information if user information is present
					update_option( $option_name_head, $options_head );
				} else {
					// Add user information if user information is empty
					add_option( $option_name_head, $options_head );
				}
			}
		}
	}
	
	public function head_add_data() {
	   /**
	    * Adding 123privacy information to the <head> section
	    */
		$options_head = get_option( 'add_to_head' );
		$output_head  = $options_head['data'][0];
		// Filter out any inserted HTML-code for added security
		$output_head  = wp_filter_nohtml_kses( $output_head );
				
		if ( ! isset( $output_head ) || empty( $output_head ) ) {
			// Do not add anything to the frontend if no information was entered by user
			return false;
		} else {
			// Code to output to the frontend
			$output_head = "\n<!-- Deze website werkt met de kant-en-klare oplossing voor de cookiewet van 123privacy -->\n<script type='text/plain' privacy-type='cookiesAllowed'>\n\n  var _gaq = _gaq || [];\n  _gaq.push(['_setAccount', '" . $output_head . "']);\n  _gaq.push(['_trackPageview']);\n\n  (function() {\n    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;\n    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';\n    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);\n  })();\n\n</script>\n<!-- www.123privacy.nl -->\n\n";
			// Echo the code
			echo stripslashes( $output_head );
		}
	}
	
	public function addthis_button_onpost( $post_content ) {
	   /**
	    * Adding 'AddThis' buttons to the posts
	    */
		if ( is_single() ) {
		
			/* Code to add to the posts */
			
				$output = "\n\n<!-- AddThis Button BEGIN -->\n<div class='addthis_toolbox addthis_default_style '>\n";
			
				// Facebook button
				if ( get_option( 'addthis_button_showfacebook' ) == '1' ) {
					$output .= "<a class='addthis_button_facebook_like' fb:like:layout='button_count' fb:like:locale='en_US'></a>\n";
				}
				// Tweet button
				if ( get_option( 'addthis_button_showtwitter' ) == '1' ) {
					$output .= "<a class='addthis_button_tweet'></a>\n";
				}
				// Hyves Respect button
				if ( get_option( 'addthis_button_showhyves' ) == '1' ) {
					$output .= "<a class='addthis_button_hyves_respect'></a>\n";
				}
				// Google +1 button
				if ( get_option( 'addthis_button_showgoogle' ) == '1' ) {
					$output .= "<a class='addthis_button_google_plusone' g:plusone:size='medium'></a>\n";
				}
		
				$output .= "</div>\n<script type='text/plain' privacy-type='cookiesAllowed' dis_src='http://s7.addthis.com/js/250/addthis_widget.js'></script>\n<!-- AddThis Button END -->\n\n";
				
				// Put buttons to the bottom of the posts - left or right depending on the users choice
				if ( get_option( 'addthis_button_vp' ) == 'bottom' ) {
					$output = $post_content . "\n<!-- Deze website werkt met de kant-en-klare oplossing voor de cookiewet van 123privacy -->\n<div privacy-type='cookiesAllowed'><div class='addthis-" . get_option( 'addthis_button_hp' ) . "'><br />" . $output . "</div><div class='addthis-clear-both'></div><br /></div>\n<!-- www.123privacy.nl -->\n\n";
					
				// Put buttons at the top of the posts - left or right depending on the users choice
				} elseif ( get_option( 'addthis_button_vp' ) == 'top' ) {
					$output = "\n<!-- Deze website werkt met de kant-en-klare oplossing voor de cookiewet van 123privacy -->\n<div privacy-type='cookiesAllowed'><div class='addthis-" . get_option( 'addthis_button_hp' ) . "'>" . $output . "</div><div class='addthis-clear-both'></div><br /></div>\n<!-- www.123privacy.nl -->\n\n" . $post_content;
				}
		}
		// Return values to be saved
		return $output;
	}

	public function options() {
	
		// Saving 123privacy data for the <body> section
		privacy_to_wordpress::data_save_body();
		$options_body = get_option( 'add_to_body' );
		
		// Saving 123privacy data for the <head> section
		privacy_to_wordpress::data_save_head();
		$options_head = get_option( 'add_to_head' );
		
		?>
		<div class="wrap">
		
			<div id="icon-options-general" class="icon32"><br /></div>
				
			<h2>123privacy &rarr; instellingen</h2>
				
			<div class="alignleft">
				
				<div class="metabox-holder">
				
					<form method="post" name="privacy_to_wordpress_form">
					
						<?php wp_nonce_field( 'validate_form_content', 'form_content_validator' ); ?>
			
						<div class="postbox">
					
							<h3>1. Voer hieronder uw eigen unieke code voor uw website in. Oftewel: uw serial/website_id</h3>
							
							<div class="inside">
							
								<p><em><strong>Voorbeeld</strong>: <span class="grey">a12b3456-1234-56ab-7c89-123456a78b12</span></em></p>
								<textarea id="body_data" name="body_data" class="large-text code" cols="50" rows="1"><?php echo stripslashes( $options_body['data'][0] ); ?></textarea>
								<input type="submit" name="body_submitter" value="<?php esc_attr_e( 'Save Changes' ); ?>" class="button-primary" />
								<input type="hidden" name="action_body" value="update" />
				
								<p><br /><strong>Opmerking</strong>: U hoeft hierboven enkel uw eigen unieke serial/website_id in te voeren. De overige code die in de &lt;head&gt; en &lt;body&gt; moet komen, wordt automatsch door deze plugin ingeladen.</p>
								<p>U kunt uw unieke serial/website_id vinden in <a title="Bekijk uw persoonlijke profiel op 123privacy.nl" href="https://123privacy.nl/profiel.aspx" target="_blank">uw persoonlijke profiel</a> op 123privacy.nl.</p>
				
							</div>
							
						</div>

						<div class="postbox">
					
							<h3>2. Voer hieronder uw eigen unieke Google Analytics accountnummer in</h3>
							
							<div class="inside">
							
								<p><em><strong>Voorbeeld</strong>: <span class="grey">UA-01234567-89</span></em></p>
						
								<textarea id="head_data" name="head_data" class="large-text code" cols="50" rows="1"><?php echo stripslashes( $options_head['data'][0] ); ?></textarea>
								<input type="submit" name="head_submitter" value="<?php esc_attr_e( 'Save Changes' ); ?>" class="button-primary" />
				
								<p><br /><strong>Belangrijk</strong>: Indien u gebruik maakt van een andere plugin om de Google Analytics code aan uw website toe te voegen, dient u deze eerst te <span class"underline">deactiveren</span> en/of te <span class"underline">verwijderen</span>!</p>
								<p><strong>Opmerking</strong>: Indien u geen gebruik maakt van Google Analytics of liever een andere plugin gebruikt om Google Analytics in uw website te integreren, hoeft u hier uiteraard niets in te vullen.</p>
						
							</div>
							
						</div>
					
						<div class="postbox">
					
							<h3>3. Help ons mee door een link naar 123privacy.nl op uw website te plaatsen</h3>
							
							<div class="inside">
							
								<table class="form-table">
									<tr>
										<td class="width-350"><em><strong>Voorbeeld</strong>: <span class="grey">&#91;123privacy_tekst&#93;</span></em></td>
										<td><em><strong>Voorbeeld</strong>: <span class="grey">&#91;123privacy_url&#93;</span></em></td>
									</tr>
									<tr>
										<td class="width-350"><em><strong>Resultaat</strong></em>: <a href="http://www.123privacy.nl/" title="123privacy.nl" rel="external">Dé oplossing voor de nieuwe cookiewet</a></td>
										<td><em><strong>Resultaat</strong></em>: <a href="http://www.123privacy.nl/" title="Dé oplossing voor de nieuwe cookiewet" rel="external">123privacy.nl</a></td>
									</tr>
								</table>
					
								<p><br /><strong>Werkwijze</strong>: Plaats bovenstaande code [inclusief haakjes] op de plek waar u wilt dat de link verschijnt. Dit kan ergens in één of meerdere pagina's en/of artikelen zijn.<br />
								Het is voor de werking niet nodig om eerst over te schakelen naar 'HTML-modus' in de teksteditor van WordPress.<br />
								<br />
								<strong>Opmerking</strong>: De code werkt ook wanneer deze in een (tekst)widget wordt geplaatst.</p>
								
							</div>
							
						</div>
				
					</form>
				
				</div>
			
			</div>
			
			<div class="alignright">
				
				<div class="metabox-holder">
			
					<div class="postbox">
				
						<h3>Extra optie: aangepaste AddThis buttons in alle berichten</h3>
					
						<form method="post" action="options.php">
						
							<?php wp_nonce_field( 'update-options' ); ?>
						
							<table class="form-table">
								<tr>
									<td class="width-150">Inschakelen voor berichten</td>
									<td class="top"><input type="checkbox" name="addthis_button_show" id="addthis_button_show" value="1"<?php if ( get_option( 'addthis_button_show' ) == 1 ) { echo ' checked="checked"'; } ?> /></td>
								</tr>
								<tr>
									<td class="width-150">Horizontale plaatsing</td>
									<td class="top">
										<select name="addthis_button_hp" id="addthis_button_hp" class="width-100">
											<option value="left"<?php if ( get_option( 'addthis_button_hp' ) == 'left' ) { echo ' selected="selected"'; } ?>>Links</option>
											<option value="right"<?php if ( get_option( 'addthis_button_hp' ) == 'right' ) { echo ' selected="selected"'; } ?>>Rechts</option>
										</select>
									</td>
								</tr>
								<tr>										
									<td class="width-150">Verticale plaatsing</td>
									<td class="top">
										<select name="addthis_button_vp" id="addthis_button_vp" class="width-100">
											<option value="top"<?php if ( get_option( 'addthis_button_vp' ) == 'top' ) { echo ' selected="selected"'; } ?>>Boven</option>
											<option value="bottom"<?php if ( get_option( 'addthis_button_vp' ) == 'bottom' ) { echo ' selected="selected"'; } ?>>Onder</option>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="bg-grey">Buttons (de)activeren</td>
								</tr>
								<tr>
									<td class="width-150">Facebook button</td>
									<td class="top"><input type="checkbox" name="addthis_button_showfacebook" id="addthis_button_showfacebook" value="1"<?php if ( get_option( 'addthis_button_showfacebook' ) == 1 ) { echo ' checked="checked"'; } ?> /></td>
								</tr>
								<tr>
									<td class="width-150">Tweet button</td>
									<td class="top"><input type="checkbox" name="addthis_button_showtwitter" id="addthis_button_showtwitter" value="1"<?php if ( get_option( 'addthis_button_showtwitter' ) == 1 ) { echo ' checked="checked"'; } ?> /></td>
								</tr>
								<tr>
									<td class="width-150">Hyves button</td>
									<td class="top"><input type="checkbox" name="addthis_button_showhyves" id="addthis_button_showhyves" value="1"<?php if ( get_option( 'addthis_button_showhyves' ) == 1 ) { echo ' checked="checked"'; } ?> /></td>
								</tr>
								<tr>
									<td class="width-150">Google +1 button</td>
									<td class="top"><input type="checkbox" name="addthis_button_showgoogle" id="addthis_button_showgoogle" value="1"<?php if ( get_option( 'addthis_button_showgoogle' ) == 1 ) { echo ' checked="checked"'; } ?> /></td>
								</tr>
								<tr>
									<td colspan="2"><br /><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" name="addthis_button_update_options" /></td>
								</tr>
							</table>
							<input type="hidden" name="action" value="update" />
							<input type="hidden" name="page_options" value="addthis_button_show, addthis_button_hp, addthis_button_vp, addthis_button_showfacebook, addthis_button_showtwitter, addthis_button_showhyves, addthis_button_showgoogle" />
								
						</form>
						
						<br />
						
					</div>
					
				</div>
				
			</div>
			
			<div class="alignright">
			
				<div class="metabox-holder">
			
					<div class="postbox">
				
						<h3>Tip</h3>
						
							<div class="inside">
					
								<p>Voor een cookievrije Twitter volg knop kunt u gebruik maken van de 'Twitter Button' widget. Deze widget is te vinden in het overzicht van beschikbare widgets in het hoofdmenu onder 'Weergave' of 'Appearance'.<br />
								<br />
								Voorbeeld van de Twitter volg knop:</p>
								
								<?php
								// Get the url and echo the example image
								echo '<img alt="Volg ons op Twitter!" src="' . plugins_url( 'img/twitterbird.gif' , dirname( __FILE__ ) ). '" />';
								?>
								
								<p>De Twitter Button widget maakt dankbaar gebruik van de gratis functionaliteit van <a href="http://www.twitterbutton.nl/" target="_blank">twitterbutton.nl</a>.</p>
							
							</div>
							
					</div>
					
				</div>
				
			</div>
			
		</div>	
			
	<?php
	}
}