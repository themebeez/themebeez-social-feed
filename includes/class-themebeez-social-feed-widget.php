<?php
/**
 * Instagram Widget
 *
 * @package Themebeez_Social_Feed
 * @since 1.0.0
 */

if( ! class_exists( 'Themebeez_Social_Feed_Instagram_Widget' ) ) {

	class Themebeez_Social_Feed_Instagram_Widget extends WP_Widget {

		function __construct() { 

            parent::__construct(

                'themebeez-social-feed-instagram-widget',
                esc_html__( 'TSF: Instagram Widget', 'themebeez-social-feed' ),
                array(
                    'classname'     => '',
                    'description'   => esc_html__( 'Displays instagram feed.', 'themebeez-social-feed' ), 
                )
            );     
        }

        public function widget( $args, $instance ) {

            $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

            $widget_settings = array();

            $widget_settings['no_of_images'] = isset( $instance['no_of_images'] ) ? $instance['no_of_images'] : 6;

            $widget_settings['no_of_cols_per_row'] = isset( $instance['no_of_cols_per_row'] ) ? $instance['no_of_cols_per_row'] : 6;

            $widget_settings['display_caption'] = isset( $instance['display_caption'] ) ? $instance['display_caption'] : false;

            $widget_settings['link_profile'] = isset( $instance['link_profile'] ) ? $instance['link_profile'] : false;

            $widget_settings['profile_link_button_title'] = isset( $instance['profile_link_button_title'] ) ? $instance['profile_link_button_title'] : '';
            
            $widget_settings['custom_css_class_name'] = isset( $instance['custom_css_class_name'] ) ? $instance['custom_css_class_name'] : '';

            if( $args['before_widget'] ) {

                echo $args['before_widget'];
            }

            if( !empty( $title ) ) {
                
                echo $args['before_title'];

                echo esc_html( $title );

                echo $args['after_title'];
            }

            $feed_helper = new Themebeez_Social_Feed_Helper();

            echo $feed_helper->display_feed( $widget_settings );

            if( $args['after_widget'] ) {

        	   echo $args['after_widget'];
            }
    	}

    	public function form( $instance ) {

            $defaults = array(
                'title'       => '',
                'no_of_images' => 6,
                'no_of_cols_per_row' => 6,
                'display_caption' => false,
                'link_profile' => true,
                'profile_link_button_title' => '',
                'custom_css_class_name' => '',
            );

            $instance = wp_parse_args( (array) $instance, $defaults );
    		?>
    		<p>
                <label for="<?php echo esc_attr( $this->get_field_name('title') ); ?>">
                    <strong><?php esc_html_e( 'Title', 'themebeez-social-feed' ); ?></strong>
                </label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />   
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_name('no_of_images') ); ?>">
                    <strong><?php esc_html_e( 'Number of Images', 'themebeez-social-feed' ); ?></strong>
                </label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('no_of_images') ); ?>" name="<?php echo esc_attr( $this->get_field_name('no_of_images') ); ?>" max="30" min="1" type="number" value="<?php echo esc_attr( $instance['no_of_images'] ); ?>" />   
                <small><?php echo __( 'Maximum number of images is 30.', 'themebeez-social-feed' ); ?></small>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_name('no_of_cols_per_row') ); ?>">
                    <strong><?php esc_html_e( 'Number of Images Per Row', 'themebeez-social-feed' ); ?></strong>
                </label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('no_of_cols_per_row') ); ?>" name="<?php echo esc_attr( $this->get_field_name('no_of_cols_per_row') ); ?>" max="6" min="1" type="number" value="<?php echo esc_attr( $instance['no_of_cols_per_row'] ); ?>" /> 
                <small><?php echo __( 'Maximum number of columns is 6.', 'themebeez-social-feed' ); ?></small>  
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_name('display_caption') ); ?>">
                    <input id="<?php echo esc_attr( $this->get_field_id('display_caption') ); ?>" name="<?php echo esc_attr( $this->get_field_name('display_caption') ); ?>" type="checkbox" <?php checked( $instance['display_caption'], true ); ?> /> 
                    <strong><?php esc_html_e( 'Display Image Caption', 'themebeez-social-feed' ); ?></strong>
                </label>                  
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_name('link_profile') ); ?>">
                    <input id="<?php echo esc_attr( $this->get_field_id('link_profile') ); ?>" name="<?php echo esc_attr( $this->get_field_name('link_profile') ); ?>" type="checkbox" <?php checked( $instance['link_profile'], true ); ?> /> 
                    <strong><?php esc_html_e( 'Display Profile Link Button', 'themebeez-social-feed' ); ?></strong>
                </label>                  
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_name('profile_link_button_title') ); ?>">
                    <strong><?php esc_html_e( 'Title of Profile Link Button', 'themebeez-social-feed' ); ?></strong>
                </label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('profile_link_button_title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('profile_link_button_title') ); ?>" type="text" value="<?php echo esc_attr( $instance['profile_link_button_title'] ); ?>" />   
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_name('custom_css_class_name') ); ?>">
                    <strong><?php esc_html_e( 'Custom CSS Class Name', 'themebeez-social-feed' ); ?></strong>
                </label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('custom_css_class_name') ); ?>" name="<?php echo esc_attr( $this->get_field_name('custom_css_class_name') ); ?>" type="text" value="<?php echo esc_attr( $instance['custom_css_class_name'] ); ?>" />   
            </p>
    		<?php
        }
     
        public function update( $new_instance, $old_instance ) {
     
            $instance = $old_instance;

            $instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

            $instance['no_of_images'] = ( isset( $new_instance['no_of_images'] ) && ( $new_instance['no_of_images'] <= 30 ) ) ? absint( $new_instance['no_of_images'] ) : 6;

            $instance['no_of_cols_per_row'] = ( isset( $new_instance['no_of_cols_per_row'] ) && ( $new_instance['no_of_cols_per_row'] <= 6 ) ) ? absint( $new_instance['no_of_cols_per_row'] ) : 6;

            $instance['display_caption'] = isset( $new_instance['display_caption'] ) ? wp_validate_boolean( $new_instance['display_caption'] ) : false;

            $instance['link_profile'] = isset( $new_instance['link_profile'] ) ? wp_validate_boolean( $new_instance['link_profile'] ) : false;

            $instance['profile_link_button_title'] = isset( $new_instance['profile_link_button_title'] ) ? sanitize_text_field( $new_instance['profile_link_button_title'] ) : '';

            $instance['custom_css_class_name'] = isset( $new_instance['custom_css_class_name'] ) ? sanitize_text_field( $new_instance['custom_css_class_name'] ) : '';

            return $instance;
        } 

	}
}

/**
 * Register Themebeez_Social_Feed_Instagram_Widget Class.
 *
 * @since 1.0.0
 */
function themebeez_social_feed_register_widget() {

    register_widget( 'Themebeez_Social_Feed_Instagram_Widget' );
}
add_action( 'widgets_init', 'themebeez_social_feed_register_widget' );