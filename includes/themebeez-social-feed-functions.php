<?php


if( ! function_exists( 'themebeez_social_feed_is_access_token_valid' ) ) {

	function themebeez_social_feed_is_access_token_valid() {

		$instagram_settings = get_option( 'themebeez_social_feed_api_setting' );

		if( empty( $instagram_settings ) ) {

			return false;
		}

		$instagram_access_token = isset( $instagram_settings['instagram_access_token'] ) ? $instagram_settings['instagram_access_token'] : '';

		if( empty( $instagram_access_token ) ) {

			return false;
		}

		$url_args = add_query_arg( array(
            'fields'       => 'id,username',
            'access_token' => $instagram_access_token,

        ), 'https://graph.instagram.com/me' );

        $response = wp_remote_get( $url_args );

        if ( is_wp_error( $response ) ) {

            return false;
        }

        if ( 200 != wp_remote_retrieve_response_code( $response ) ) {

            return false;
        }

        return true;
	}
}

if( ! function_exists( 'themebeez_social_feed_admin_notice' ) ) {

	function themebeez_social_feed_admin_notice() {

		if( themebeez_social_feed_is_access_token_valid() == false ) {
			?>
			<div class="error notice is-dismissible">
				<p>
					<?php echo __( 'Invalid or expired Instagram Access Token. Please try reconnecting your Instagram account.', 'themebeez-social-feed' ); ?>
				</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'themebeez_social_feed_admin_notice' );