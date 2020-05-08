<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://themebeez.com
 * @since      1.0.0
 *
 * @package    Themebeez_Social_Feed
 * @subpackage Themebeez_Social_Feed/admin/partials
 */
?>
<div class="wrap">
	
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Themebeez Social Feed', 'themebeez-social-feed' ); ?></h1>

	<div class="plugin-page-section">
		<div class="plugin-page-section-header">
			<h2><?php esc_html_e( 'Connect An Instagram Account', 'catch-instagram-feed-gallery-widget' ); ?></h2>
		</div><!-- .header -->
		<div class="plugin-page-section-content">
			<?php
			if( themebeez_social_feed_is_access_token_valid() ) {
				?>
				<p><?php echo __( 'Themebeez Social Feed is connected to your Instagram account.', 'themebeez-social-feed' ); ?></p>
				<?php
			} else {
				?>
				<p><?php echo __( 'Themebeez Social Feed need to authorize to access your Instagram account. So, click the button below to get started.', 'themebeez-social-feed' ); ?></p>
				<a class="button button-primary" href="<?php echo esc_url( Themebeez_Social_Feed_Admin::authorize_url() ); ?>"><?php echo __( 'Connect An Instagram Account', 'themebeez-social-feed' ); ?></a>
				<?php
			}


			$themebeez_social_feed_settings = get_option( 'themebeez_social_feed_api_setting' );

			if ( isset( $_GET['access_token'] ) ) {

				$access_token = sanitize_text_field( wp_unslash( $_GET['access_token'] ) );

			} else if ( isset( $themebeez_social_feed_settings['instagram_access_token'] ) && ( '' !== $themebeez_social_feed_settings['instagram_access_token'] ) ) {

				$access_token = $themebeez_social_feed_settings['instagram_access_token'];
			} else {

				$access_token = '';
			}

			if( isset( $access_token ) && '' !== $access_token ) {

				Themebeez_Social_Feed_Admin::update_api_data( $access_token );
			}

			$themebeez_social_feed_settings = get_option( 'themebeez_social_feed_api_setting' );

			if( $themebeez_social_feed_settings ) {

				if( isset( $themebeez_social_feed_settings['instagram_access_token'] ) || isset( $themebeez_social_feed_settings['instagram_username'] ) || ( isset( $themebeez_social_feed_settings['instagram_userid'] ) && $themebeez_social_feed_settings['instagram_userid'] != 0 )  ) {
					?>
					<div class="settings-container">
						<form action='options.php' method='post'>
							<?php
							settings_fields('themebeez_social_feed_api_setting_group');
				            do_settings_sections('themebeez-social-feed');
				            // submit_button();
							?>
						</form>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>

	<div class="plugin-page-section">
		<div class="plugin-page-section-header">
			<h2><?php esc_html_e( 'Display your Instagram feed', 'themebeez-social-feed' ); ?></h2>
		</div>
		<div class="plugin-page-section-content">
			<p><?php echo __( 'Copy and paste the shortcode below in page or post.', 'themebeez-social-feed' ); ?></p>
			<input type="text" value="[themebeez-social-feed]" readonly="readonly">
			<table>
				<thead>
					<tr>
						<th><?php echo __( 'Shortcode Options', 'themebeez-social-feed' ); ?></th>
						<th><?php echo __( 'Description', 'themebeez-social-feed' ); ?></th>
						<th><?php echo __( 'Example', 'themebeez-social-feed' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo __( 'nums', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( 'The number of photos to display. Maximum 30.', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( '[themebeez-social-feed nums="6"]', 'themebeez-social-feed' ); ?></td>
					</tr>
					<tr>
						<td><?php echo __( 'cols', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( 'The number of columns in your feed. Maximum 6.', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( '[themebeez-social-feed cols="6"]', 'themebeez-social-feed' ); ?></td>
					</tr>
					<tr>
						<td><?php echo __( 'displaycaption', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( 'Whether to display photo&rsquo;s caption.', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( '[themebeez-social-feed displaycaption=true]', 'themebeez-social-feed' ); ?></td>
					</tr>
					<tr>
						<td><?php echo __( 'displayprofilelink', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( 'Whether to display link to Instagram profile.', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( '[themebeez-social-feed displayprofilelink=true]', 'themebeez-social-feed' ); ?></td>
					</tr>
					<tr>
						<td><?php echo __( 'buttontitle', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( 'Button title of Instagram profile&rsquo;s link. Note: displayprofilelink should be set true.', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( '[themebeez-social-feed buttontitle="Follow Me"]', 'themebeez-social-feed' ); ?></td>
					</tr>
					<tr>
						<td><?php echo __( 'cssclass', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( 'Add CSS class(es) to the feed container.', 'themebeez-social-feed' ); ?></td>
						<td><?php echo __( '[themebeez-social-feed cssclass="test-class custom-class"]', 'themebeez-social-feed' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>