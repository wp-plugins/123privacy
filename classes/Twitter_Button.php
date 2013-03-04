<?php

/* Add Twitter_Button widget */

class Twitter_Button extends WP_Widget {

	// register widget with WordPress
	public function __construct() {
		parent::__construct(
	 		'twitter_button', // Base ID
			'Twitter Button', // Name
			array( 'description' => 'Gebruik deze widget om een cookievrije Twitter volg knop aan je site toe te voegen.' ) // Args
		);
	}

	// front-end display of widget
	public function widget( $args, $instance ) {
		extract( $args );
		$twitter_id = apply_filters( 'widget_content', $instance['twitter_id'] );
		
		// echo the standard opening widget HTML
		echo $before_widget;
		
		// echo the Twitter Button html code including the users Twitter ID
		if ( ! empty( $twitter_id ) )
			echo "<a name='tin59xv' id='tin59xv' href='http://www.twitterbutton.nl/' title='twitterbutton.nl'><img src='http://www.twitterbutton.nl/buttons/twitterbird.gif' alt='Volg ons op Twitter!' style='border: none;' /></a><script type='text/javascript'>t=\"" . $twitter_id . "\";x=document.getElementsByName('tin59xv');y=new Array(104,116,116,112,58,47,47,116,119,105,116,116,101,114,46,99,111,109,47);z='';for(i in y){z+=String.fromCharCode(y[i]);}for(i in x){x[i].href=z+t;}</script>";
		
		// echo the standard closing widget HTML
		echo $after_widget;
		
	}

	// sanitize widget form values as they are saved
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['twitter_id'] = strip_tags( $new_instance['twitter_id'] );

		// return array updated safe values to be saved
		return $instance;
		
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance previously saved values from database
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'twitter_id' ] ) ) {
			$twitter_id = $instance[ 'twitter_id' ];
		} else {
			$twitter_id = '123privacy';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'twitter_id' ); ?>">Uw Twitter ID:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_id' ); ?>" name="<?php echo $this->get_field_name( 'twitter_id' ); ?>" type="text" value="<?php echo esc_attr( $twitter_id ); ?>" />
		</p>
		<?php
	}

}