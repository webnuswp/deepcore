<?php
class WebnusLoginWidget extends WP_Widget {
	
	function __construct(){
		$params = array(
			'description'	=> 'Webnus Login Widget',
			'name'			=> 'Webnus-Login'
		);
		parent::__construct('WebnusLoginWidget', '', $params);
	}

	/**
	 * Widget Form.
	 *
	 * @author Webnus
	 * @since 1.0.0
	 */
	public function form( $instance ) {
		extract( $instance ); ?>
		<p><label for="<?php echo esc_attr( $this->get_field_id('title') ) ?>"><?php esc_html_e('Title:','deep') ?></label><input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ) ?>" name="<?php echo esc_attr( $this->get_field_name('title') ) ?>" value="<?php if( isset($title) )  echo esc_attr($title); ?>" /></p>
		<?php 
	}

	/**
	 * Widget Output.
	 *
	 * @author Webnus
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		echo wp_kses( $before_widget, wp_kses_allowed_html( 'post' ) );
		if( ! empty( $title ) ) {
			echo wp_kses( $before_title, wp_kses_allowed_html( 'post' ) );
			echo wp_kses( $title, wp_kses_allowed_html( 'post' ) );
			echo wp_kses( $after_title, wp_kses_allowed_html( 'post' ) );
		}
		?>
		<div class="webnus-login">
			<?php deep_login(); ?>
			<div class="clear"></div>
		</div>	 
		<?php
		echo wp_kses( $after_widget, wp_kses_allowed_html( 'post' ) );
	} 
}

/**
 * Register Widget.
 *
 * @author Webnus
 * @since 1.0.0
 */
function register_deep_login_widget() {
	register_widget( 'WebnusLoginWidget' );
}
add_action( 'widgets_init','register_deep_login_widget' ); 