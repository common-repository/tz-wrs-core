<?php
 
/**
 * Adds Weekly Radio Schedule Team Widget.
 */
class WRSTeamWidget extends WP_Widget {
 
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'WRSTeam', // Base ID
			esc_html__( 'WRS Team Widget', 'weekly-radio-schedule' ), // Name
			array( 'description' => esc_html__( 'Displays Station Managers, DJs and Operators', 'weekly-radio-schedule' ), ) // Args
        );

    }
 
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
 
		$title = apply_filters( 'widget_title', $instance['title'] );
		$picsize = $instance['picsize'];
		  
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		  
		// This is where you run the code and display the output
		echo Tz_Weekly_Radio_Schedule_Public::tzwrs_get_the_team($picsize, intval(get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' )));
		echo $args['after_widget'];
    }
 
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

		$defaults = array( 'title' => __( 'Our Team', 'weekly-radio-schedule' ), 'picsize' => intval(get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size') ));
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Our Team', 'weekly-radio-schedule' );
		}
		if ( isset( $instance[ 'picsize' ] ) ) {
			$picsize = $instance[ 'picsize' ];
		}
		else {
			$picsize = intval(get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size'));
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'weekly-radio-schedule' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'picsize' ); ?>"><?php esc_html_e( 'Picture size in pixels:', 'weekly-radio-schedule' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'picsize' ); ?>" name="<?php echo $this->get_field_name( 'picsize' ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $picsize ); ?>" size="3" />
		</p>
		
		<?php 
	}
 
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['picsize'] = strip_tags( $new_instance[ 'picsize' ] );
		return $instance;
	}
 
} // class WRSTeamWidget

?>