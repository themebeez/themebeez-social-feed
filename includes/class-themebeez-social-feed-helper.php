<?php

if( ! class_exists( 'Themebeez_Social_Feed_Helper' ) ) {

	class Themebeez_Social_Feed_Helper {

		public $instagram_media_url = 'https://graph.instagram.com/me/media';

		public $instagram_access_token;

		public $instagram_settings;

		public function __construct() {

			if( get_option( 'themebeez_social_feed_api_setting' ) ) {

				$this->instagram_settings = get_option( 'themebeez_social_feed_api_setting' );

				$this->instagram_access_token = isset( $this->instagram_settings['instagram_access_token'] ) ? $this->instagram_settings['instagram_access_token'] : '';
			}
		}

		public function request_feed() {

			$is_access_token_valid = themebeez_social_feed_is_access_token_valid();

			if( $is_access_token_valid && isset( $this->instagram_access_token ) ) {

				$request_query_args = add_query_arg( array(
		            'fields'       => 'caption,id,media_type,media_url,permalink,thumbnail_url,timestamp,username',
		            'access_token' => $this->instagram_access_token,
		        ), $this->instagram_media_url );

		        // Get the new images if the images are not fetched.
		        $query_response = wp_remote_get( $request_query_args );

		        $response_data = wp_remote_retrieve_body( $query_response );

		        return $response_data;
		        
			} else {

				return;
			}
		}

		public function display_feed( $settings ) {

			$output = '';

			$instagarm_username = $this->instagram_settings['instagram_username'];

			if( ! isset( $instagarm_username ) ) {

				$output .= __( 'You Instagram account is not connected. Please connect your Instagram account.', 'themebeez-social-feed' );

				return $output;
			}

			$instagram_data = get_transient( 'themebeez_social_feed_instagram_data_json' );

			if( $instagram_data == false ) {

				$instagram_data = $this->request_feed();

				$instagram_data = json_decode( $instagram_data, true );

				set_transient( 'themebeez_social_feed_instagram_data_json', $instagram_data, 2 * HOUR_IN_SECONDS );
			}

			if( ! $instagram_data ) {

				$output .= esc_html__( 'No Data', 'themebeez-social-feed' );
				return $output;
			} else if( isset( $instagram_data['meta']['error_message'] ) ) {

				if ( isset( $instagram_data['meta']['error_type'] ) ) {

					$output .= esc_html__( 'Provide API access token / Username', 'themebeez-social-feed' );
					return $output;
				} else {

					$output .= esc_html( $instagram_data['meta']['error_message'] );
					return $output;
				}
			} else if( isset( $instagram_data['error']['message'] ) && isset( $instagram_data['error']['type'] ) ) { // Invalid access token

				$output .= esc_html( $instagram_data['error']['message'] );
				return $output;
			} else {

				$instagram_items = $instagram_data['data'];

				if ( isset( $instagram_items ) && is_array( $instagram_items ) && 0 < count( $instagram_items ) ) {

					$data_count = count( $instagram_items );

					$output .= '<div class="tsf-wrapper">';
					$output .= '<div class="tsf-widget-content ' . esc_attr( $settings['custom_css_class_name'] )  . '">';
					$output .= '<ul class="tsf-row tsf-col-' . esc_attr( $settings['no_of_cols_per_row'] ) . '">';

					$min_loop = 0;

					if( $settings['no_of_images'] > $data_count ) {

						$min_loop = $data_count;
					} else {

						$min_loop = absint( $settings['no_of_images'] );
					}

					foreach( $instagram_items as $item_index =>  $src ) {

						if( $item_index >= $min_loop ) {

							break;
						}

						$output .= '<li class="tsf-col tsf-item">';

						$caption = isset( $src['caption'] ) ? $src['caption'] : '';

						if ( 'IMAGE' === $src['media_type'] ) {

							$output .= '<a target="_blank" href="' . esc_url( $src['permalink'] ) . '" title="' . esc_html( $caption ) . '">';

							$output .= '<figure>';

							$output .= '<img class="tsf-instagram-image" src="' . esc_url( $src['media_url'] ) . '" />';

							if( $settings['display_caption'] && $caption ) {

								$output .= '<figcaption>' . esc_html( $caption ) . '</figcaption>';
							}

							$output .= '</figure>';

							$output .= '</a>';
						}  // End if().
					} // End foreach().

					$output .= '</ul>';

					if( $settings['link_profile'] == true && isset( $settings['profile_link_button_title'] ) ) {

						$user_instagram_link = 'https://instagram.com/' . $instagarm_username;

						$output .= '<div class="tsf-instagram-button-wrapper">';
						$output .= '<a class="button instagram-button" href="' . esc_url( $user_instagram_link ) . '" target="_blank"><span class="dashicons dashicons-instagram"></span>&nbsp;' . esc_html( $settings['profile_link_button_title'] ) . '</a>';
						$output .= '</div>';
					}
					
					$output .= '</div>';
					$output .= '</div>';

					
				} else {

					$output .= esc_html__( 'No data / Invalid Username', 'themebeez-social-feed' );
					return $output;
				}

				// Return the HTML block.
				return $output;
			} 
		}
	}
}