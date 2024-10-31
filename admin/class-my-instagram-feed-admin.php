<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'My_Instagram_Feed_Admin' ) ) {
	class My_Instagram_Feed_Admin {

		function __construct() {

			add_action( 'admin_menu', [
				$this,
				'register_menu',
			] );

			add_action( 'admin_footer', [
				$this,
				'dashboard_footer',
			] );

			add_action( 'admin_enqueue_scripts', [
				$this,
				'register_styles_scripts',
			] );

			add_action( 'admin_notices', [
				$this,
				'admin_notice',
			] );

			add_action( 'wp_ajax_mif_supported', [
				$this,
				'reviewed_checked'
			] );

			add_action( 'wp_ajax_mif_save_access_token', [
				$this,
				'authenticate_access_token'
			] );

			add_action( 'wp_ajax_mif_save_business_access_token', [
				$this,
				'authenticate_business_access_token'
			] );

			add_action( 'wp_ajax_mif_remove_access_token', [
				$this,
				'remove_access_token'
			] );

			add_action( 'wp_ajax_mif_remove_business_access_token', [
				$this,
				'remove_business_access_token'
			] );

			add_action( 'wp_ajax_mif_create_skin', [
				$this,
				'mif_create_skin'
			] );

			add_action( 'wp_ajax_mif_create_skin_url', [
				$this,
				'mif_create_skin_url'
			] );
			/*
			 * mif_delete_skin hooks fires on Ajax call.
			 * mif_delete_skin method will be call when the delete skin button is clicked.
			 */
			add_action( 'wp_ajax_mif_delete_skin', [
				$this,
				'mif_delete_skin'
			] );
			/*
			 * mif_delete_transient hooks fires on Ajax call.
			 * mif_delete_transient method will be call when the delete transient button is clicked.
			 */
			add_action( 'wp_ajax_mif_delete_transient', [
				$this,
				'mif_delete_transient'
			] );
			/*
			 * mif_delete_user hooks fires on Ajax call.
			 * mif_delete_user method will be call when the delete user button is clicked.
			 */
			add_action( 'wp_ajax_mif_delete_user', [
				$this,
				'mif_delete_user'
			] );

		}

		/*
		 * mif_admin_style will enqueue style and js files.
		 * Returns hook name of the current page in admin.
		 * $hook will contain the hook name.
		 */
		/**
		 * Register styles and scripts for admin page
		 *
		 * @param $hook
		 *
		 * @since 1.0.0
		 */
		public function register_styles_scripts( $hook ) {
			/*
			 * Following files should load only on mif page in backend.
			 */
			if ( 'toplevel_page_my-instagram-feed' !== $hook ) {
				return;
			}

			wp_deregister_script( 'bootstrap.min' );

			wp_deregister_script( 'bootstrap' );

			wp_deregister_script( 'jquery-ui-tabs' );

			wp_enqueue_style( 'materialize.min', MIF_PLUGIN_URL . 'admin/assets/css/materialize.min.css' );

			wp_enqueue_style( 'my-instagram-feed-animations', MIF_PLUGIN_URL . 'admin/assets/css/my-instagram-feed-animations.css' );

			wp_enqueue_style( 'my-instagram-feed-admin-style', MIF_PLUGIN_URL . 'admin/assets/css/my-instagram-feed-admin-style.css' );

			wp_enqueue_script( 'materialize.min', MIF_PLUGIN_URL . 'admin/assets/js/materialize.min.js', [ 'jquery' ] );

			wp_enqueue_script( 'clipboard' );

			wp_enqueue_script( 'jquery-effects-slide' );

			wp_enqueue_script( 'my-instagram-feed-admin-script', MIF_PLUGIN_URL . 'admin/assets/js/my-instagram-feed-admin-script.js', [
				'materialize.min',
				'jquery',
				'clipboard'
			] );

			wp_localize_script( 'my-instagram-feed-admin-script', 'mif', [
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'fremius_plan' => json_encode( mif_fs()->can_use_premium_code() ),
				'nonce'        => wp_create_nonce( 'mif-ajax-nonce' )
			] );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_media();
		}

		/*
		 * mif_menu will add admin page.
		 * Returns nothing.
		 */
		public function register_menu() {
			/*
			 * URL of the plugin icon.
			 */
			$icon_url       = MIF_PLUGIN_URL . 'admin/assets/images/plugin_icon.png';
			$mif_page_title = __( 'My Instagram Feed', 'my-instagram-feed' );
			$mif_ver        = mif_fs()->is_premium();
			$mif_version    = ( $mif_ver ? 'pro' : 'free' );
			if ( $mif_version == 'pro' ) {
				$mif_page_title = __( 'My Instagram Feed Pro', 'my-instagram-feed' );
			}

			add_menu_page( $mif_page_title, $mif_page_title, 'administrator', 'my-instagram-feed', [
					$this,
					'mif_page_cb'
				], $icon_url );

		}

		/*
		 * mif_page_cb contains the html/markup of the page.
		 * Returns nothing.
		 */
		public function mif_page_cb() {

			/**
			 * Instagram page view.
			 */
			include_once MIF_PLUGIN_DIR . 'admin/views/html-admin-page-my-instagram-feed.php';
		}


		/**
		 * Display admin notice about review
		 * @throws \Exception
		 */
		function admin_notice() {

			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$install_date = mif_get_setting( 'mif_installDate' );

			$display_date = date( 'Y-m-d h:i:s' );
			$datetime1    = new DateTime( $install_date );
			$datetime2    = new DateTime( $display_date );
			$diff_intrval = round( ( $datetime2->format( 'U' ) - $datetime1->format( 'U' ) ) / ( 60 * 60 * 24 ) );

			if ( $diff_intrval >= 7 && get_site_option( 'mif_support' ) != "yes" ) {

				$html = sprintf( '<div class="update-nag mif_msg">
                        <p>%s<b>%s</b>%s</p>
                        <p>%s<b>%s</b>%s</p>
                        <p>%s</p>
                        <br>
                        <p>%s</p>
                       ~Malik Danish (@danish-ali)
                       <div class="mif_support_btns">
                    <a href="https://wordpress.org/support/plugin/my-instagram-feed/reviews/?filter=5#new-post" class=" button button-primary" target="_blank">
                        %s  
                    </a>
                    <a href="javascript:void(0);" class="mif_HideRating button">
                    %s  
                    </a>
                    <br>
                    <a href="javascript:void(0);" class="mif_HideRating">
                    %s  
                    </a>
                        </div>
                        </div>', __( 'Awesome, you have been using ', 'my-instagram-feed' ), __( 'My Instagram Feed ', 'my-instagram-feed' ), __( 'for more than 1 week.', 'my-instagram-feed' ), __( 'If you like the plugin Upgrade to pro and give a', 'my-instagram-feed' ), __( '5-star ', 'my-instagram-feed' ), __( 'rating on Wordpress? ', 'my-instagram-feed' ), __( 'This will help to spread its popularity and to make this plugin a better one.', 'my-instagram-feed' ), __( 'Your help is much appreciated. Thank you very much. ', 'my-instagram-feed' ), __( 'I Like My Instagram Feed - It increased engagement on my site', 'my-instagram-feed' ), __( 'I already rated it', 'my-instagram-feed' ), __( 'No, not good enough, i do not like to rate it', 'my-instagram-feed' ) );

				$script = ' <script>
                jQuery( document ).ready(function( $ ) {

                jQuery(\'.mif_HideRating\').click(function(){
                   var data={\'action\':\'mif_supported\'}
                         jQuery.ajax({
                    
                    url: "' . admin_url( 'admin-ajax.php' ) . '",
                    type: "post",
                    data: data,
                    dataType: "json",
                    async: !0,
                    success: function(e ) {
                        
                        if (e=="success") {
                            jQuery(\'.mif_msg\').slideUp(\'fast\');
                           
                        }
                    }
                     });
                    })
                
                });
        </script>';
				echo $html . $script;
			}

			$screen = get_current_screen();
			if ( $screen->id != 'toplevel_page_mif' ) {
				return;
			}

			$mif_users = $this->mif_get_setting( 'authenticated_accounts' );

			$mif_disable_app_notification = $this->mif_get_setting( 'mif_disable_app_notification' );
			if ( empty( $mif_users ) ) {
				$mif_users = [];
			}
			$mif_pro_auth_noti = null;
		}

		/**
		 * Save reviewed close notice
		 *
		 * @since 1.0.0
		 */
		function reviewed_checked() {
			/* Update the supported value into the db. */
			update_site_option( 'mif_support', 'yes' );
			echo json_encode( [ "success" ] ); exit;
		}

		/**
		 * Get the access token from URL authenticate and save if valid
		 *
		 * @since 1.0.0
		 */
		public function authenticate_access_token() {

			$access_token = $_POST['access_token'];

			$mif_accounts_html = '';

			$self_data = "https://graph.instagram.com/me?fields=id,username&access_token={$access_token}";

			$self_decoded_data = $this->mif_get_data( $self_data );

			if ( isset( $self_decoded_data->error ) && ! empty( $self_decoded_data->error ) ) {

				echo wp_send_json_error( $self_decoded_data->error->message );
				wp_die();

			} else {
				if ( isset( $self_decoded_data ) && ! empty( $self_decoded_data ) ) {

					$mif_settings = mif_get_setting();

					$mif_accounts_html .= '<ul class="collection with-header"> <li class="collection-header"><h5>' . __( 'Connected Instagram Account', 'my-instagram-feed' ) . '</h5> 
                <a href="#fta-remove-at" class="modal-trigger fta-remove-at-btn tooltipped" data-type="personal" data-position="left" data-delay="50" data-tooltip="' . __( 'Delete Access Token', 'my-instagram-feed' ) . '"><i class="material-icons">delete_forever</i></a></li>
                <li class="collection-item li-' . $self_decoded_data->id . '">
                     
                          <span class="title">' . $self_decoded_data->username . '</span>
                          <p>' . __( 'ID:', 'my-instagram-feed' ) . ' ' . $self_decoded_data->id . ' <i class="material-icons efbl_copy_id tooltipped" data-position="right" data-clipboard-text="' . $self_decoded_data->id . '" data-delay="100" data-tooltip="' . __( 'Copy', 'my-instagram-feed' ) . '">content_copy</i></p>
                </li>
            </ul>';


					$mif_settings['authenticated_accounts']['instagram_connected_account'][ $self_decoded_data->id ];

					$mif_settings['authenticated_accounts']['instagram_connected_account'][ $self_decoded_data->id ]['username'] = $self_decoded_data->username;

					$mif_settings['authenticated_accounts']['instagram_connected_account'][ $self_decoded_data->id ]['access_token'] = $access_token;

					$mif_settings['selected_type'] = 'personal';

					$mif_saved = update_option( 'mif_settings', $mif_settings );

					if ( isset( $mif_saved ) ) {

						echo wp_send_json_success( [
							__( 'Successfully Authenticated! Taking you to next step', 'my-instagram-feed' ),
							$mif_accounts_html
						] );
						wp_die();

					} else {

						echo wp_send_json_error( __( 'Something went wrong! Refresh the page and try Again', 'my-instagram-feed' ) );
						wp_die();

					}

				} else {

					echo wp_send_json_error( __( 'Something went wrong! Refresh the page and try Again', 'my-instagram-feed' ) );
					wp_die();
				}

			}

		}

		/**
		 * Get the business access token from URL authenticate and save if valid
		 *
		 * @since 1.0.0
		 */
		public function authenticate_business_access_token() {

			$access_token = $_POST['access_token'];

			$id = $_POST['id'];

			$fta_api_url = 'https://graph.facebook.com/me/accounts?fields=access_token,username,id,name,fan_count,category,about&access_token=' . $access_token;

			$args = [
				'timeout'   => 150,
				'sslverify' => false
			];

			$fta_pages = wp_remote_get( $fta_api_url, $args );

			$fb_pages = json_decode( $fta_pages['body'] );

			$approved_pages = [];

			if ( $fb_pages->data ) {

				$title = __( 'Connected Instagram Accounts', 'my-instagram-feed' );

				$efbl_all_pages_html = '<ul class="collection with-header"> <li class="collection-header"><h5>' . $title . '</h5> 
            <a href="#fta-remove-at" class="modal-trigger fta-remove-at-btn tooltipped" data-position="left" data-delay="50" data-tooltip="' . __( 'Delete Access Token', 'my-instagram-feed' ) . '"><i class="material-icons">delete_forever</i></a></li>';

				foreach ( $fb_pages->data as $efbl_page ) {

					$page_logo_trasneint_name = "esf_logo_" . $efbl_page->id;

					$auth_img_src = get_transient( $page_logo_trasneint_name );

					if ( ! $auth_img_src || '' == $auth_img_src ) {

						$auth_img_src = 'https://graph.facebook.com/' . $efbl_page->id . '/picture?type=large&redirect=0&access_token=' . $access_token;

						if ( $auth_img_src ) {

							$auth_img_src = $this->mif_get_data( $auth_img_src );
						}

						if ( $auth_img_src->data->url ) {

							$auth_img_src = $auth_img_src->data->url;
						}

						set_transient( $page_logo_trasneint_name, $auth_img_src, 30 * 60 * 60 * 24 );

					}

					if ( $auth_img_src->error ) {

						$auth_img_src = '';
					}


					$fta_insta_api_url = 'https://graph.facebook.com/v4.0/' . $efbl_page->id . '/?fields=connected_instagram_account,instagram_accounts{username,profile_pic}&access_token=' . $efbl_page->access_token;

					$fta_insta_accounts = wp_remote_get( $fta_insta_api_url, $args );

					$fta_insta_accounts = json_decode( $fta_insta_accounts['body'] );

					$fta_insta_connected_api_url = 'https://graph.facebook.com/v4.0/' . $fta_insta_accounts->connected_instagram_account->id . '/?fields=name,profile_picture_url,ig_id,username&access_token=' . $efbl_page->access_token;

					$fta_insta_connected_account = wp_remote_get( $fta_insta_connected_api_url, $args );

					$fta_insta_connected_account = json_decode( $fta_insta_connected_account['body'] );


					if ( 'insta' == $id ) {

						if ( $fta_insta_connected_account->ig_id ) {

							$efbl_all_pages_html .= sprintf( '<li class="collection-item avatar fta_insta_connected_account li-' . $fta_insta_connected_account->ig_id . '">
                     
                    <a href="https://www.instagram.com/' . $fta_insta_connected_account->username . '" target="_blank">
                              <img src="%2$s" alt="" class="circle">
                    </a>          
                              <span class="title">%1$s</span>
                             <p>%5$s <br> %6$s %3$s <i class="material-icons efbl_copy_id tooltipped" data-position="right" data-clipboard-text="%3$s" data-delay="100" data-tooltip="%7$s">content_copy</i></p>
                     </li>', $fta_insta_connected_account->name, $fta_insta_connected_account->profile_picture_url, $fta_insta_connected_account->id, __( 'Instagram account connected with ' . $efbl_page->name . '', 'my-instagram-feed' ), $fta_insta_connected_account->username, __( 'ID:', 'my-instagram-feed' ), __( 'Copy', 'my-instagram-feed' ) );
						}
					}

					$efbl_page = (array) $efbl_page;

					$approved_pages[ $efbl_page['id'] ] = $efbl_page;

					$approved_pages[ $efbl_page['id'] ]['instagram_accounts'] = $fta_insta_accounts;

					$approved_pages[ $efbl_page['id'] ]['instagram_connected_account'] = $fta_insta_connected_account;
				}

				$efbl_all_pages_html .= '</ul>';
			}

			$fta_self_url = 'https://graph.facebook.com/me?fields=id,name&access_token=' . $access_token;

			$fta_self_data = $this->mif_get_data( $fta_self_url );

			$mif_settings = mif_get_setting();

			$mif_settings['authenticated_accounts']['approved_pages'] = $approved_pages;

			$mif_settings['authenticated_accounts']['access_token'] = $access_token;

			$mif_settings['authenticated_accounts']['author'] = $fta_self_data;

			$mif_settings['selected_type'] = 'business';

			$efbl_saved = update_option( 'mif_settings', $mif_settings );

			if ( isset( $efbl_saved ) ) {

				echo wp_send_json_success( [
					__( 'Successfully Authenticated! Taking you to next step', 'my-instagram-feed' ),
					$efbl_all_pages_html
				] );
				wp_die();
			} else {

				echo wp_send_json_error( __( 'Something went wrong! Refresh the page and try Again', 'my-instagram-feed' ) );
				wp_die();
			}

		}


		/*
		 * mif_create_skin on ajax.
		 * Returns the customizer URL with skin ID.
		 * Create the skin for instagram feeds
		 */
		function mif_create_skin() {

			$form_data = $_POST['form_data'];

			parse_str( $form_data );

			$xo_new_skins = [
				'post_title'   => sanitize_text_field( $mif_skin_title ),
				'post_content' => sanitize_text_field( $mif_skin_description ),
				'post_type'    => 'my_insta_feed_skins',
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
			];

			if ( wp_verify_nonce( $_POST['mif_nonce'], 'mif-ajax-nonce' ) ) {
				if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {

					$skin_id = wp_insert_post( $xo_new_skins );

				}
			}

			if ( isset( $skin_id ) ) {

				update_post_meta( $skin_id, 'layout', $mif_selected_layout );
				$thumbnail_id = $this->mif_get_image_id( $mif_skin_feat_img );

				set_post_thumbnail( $skin_id, $thumbnail_id );

				$page_id = mif_get_setting( 'mif_page_id' );

				$page_permalink = get_permalink( $page_id );

				$customizer_url = 'customize.php';

				if ( isset( $page_permalink ) ) {

					$customizer_url = add_query_arg( [
						'url'              => urlencode( $page_permalink ),
						'autofocus[panel]' => 'mif_customizer_panel',
						'mif_skin_id'      => $skin_id,
						'mif_customize'    => 'yes',
						'mif_account_id'   => $mif_selected_account,
					], $customizer_url );

				}

				echo wp_send_json_success( admin_url( $customizer_url ) );
				wp_die();
			} else {
				echo wp_send_json_error( __( 'Something Went Wrong! Please try again.', 'my-instagram-feed' ) );
				wp_die();
			}

			exit;

		}

		/* mif_create_skin Method ends here. */
		/*
		 * mif_delete_skin on ajax.
		 * Returns the Success or Error Message.
		 * Delete the skin
		 */
		function mif_delete_skin() {
			/* Getting the skin ID. */
			$skin_id = intval( $_POST['skin_id'] );

			if ( wp_verify_nonce( $_POST['mif_nonce'], 'mif-ajax-nonce' ) ):
				if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ):
					$skin_deleted = wp_delete_post( $skin_id, true );
				endif;
			endif;

			/* If skin is deleted successfully. */

			if ( isset( $skin_deleted ) ) {
				$returned_arr = [
					__( 'Skin is successfully deleted.', 'my-instagram-feed' ),
					$skin_id
				];
				/* Sending back the values. */
				echo wp_send_json_success( $returned_arr );
				die;
			} else {
				echo wp_send_json_error( __( 'Something Went Wrong! Please try again.', 'my-instagram-feed' ) );
				die;
			}

			exit;
		}

		/* mif_create_skin Method ends here. */
		/*
		 * mif_delete_transient on ajax.
		 * Returns the Success or Error Message.
		 * Delete the transient
		 */
		function mif_delete_transient() {
			/* Getting the skin ID. */
			$transient_id      = sanitize_text_field( $_POST['transient_id'] );
			$replaced_value    = str_replace( '_transient_', '', $transient_id );
			$mif_deleted_trans = delete_transient( $replaced_value );
			/* If skin is deleted successfully. */

			if ( isset( $mif_deleted_trans ) ) {
				$returned_arr = [
					__( 'Cache is successfully deleted.', 'my-instagram-feed' ),
					$transient_id
				];
				/* Sending back the values. */
				echo wp_send_json_success( $returned_arr );
				die;
			} else {
				echo wp_send_json_error( __( 'Something Went Wrong! Please try again.', 'my-instagram-feed' ) );
				die;
			}

			exit;
		}

		/* mif_delete_transient Method ends here. */
		/*
		 * mif_delete_user on ajax.
		 * Returns the Success or Error Message.
		 * Delete the User
		 */
		function mif_delete_user() {
			$mif_ver     = mif_fs()->is_premium();
			$mif_version = ( $mif_ver ? 'pro' : 'free' );
			/* Getting the User ID. */
			$user_id = intval( $_POST['user_id'] );
			/*
			 * Getting the saved settings.
			 */
			$mif_users = mif_get_setting();
			/*
			 * Removing the user.
			 */
			unset( $mif_users['authenticated_accounts'][ $user_id ] );
			/*
			 * If all users are deleted delete the default access token.
			 */
			if ( empty( $mif_users['authenticated_accounts'] ) ) {
				unset( $mif_users['mif_access_token'] );
			}
			/*
			 * updating mif_settings againg.
			 */
			$updated_option = update_option( 'mif_settings', $mif_users );
			$mif_user_html  = '<div class="mif_all_users_holder mif_no_user"><ul class="collection"><h5>' . __( 'Nothing Found!', 'my-instagram-feed' ) . '</h5><p>Please Connect Your Instagram Account from Above Button.</p></ul> </div>';
			/* If user is deleted successfully. */

			if ( isset( $updated_option ) ) {
				$adminurl     = admin_url( 'admin.php?page=mif' );
				$adminurl     = 'https://maltathemes.com/mif/session.php?return_uri=' . $adminurl . '&mif_version=' . $mif_version . '';
				$returned_arr = [
					__( 'User is successfully deleted.', 'my-instagram-feed' ),
					$user_id,
					$mif_user_html,
					$adminurl
				];
				/* Sending back the values. */
				echo wp_send_json_success( $returned_arr );
				die;
			} else {
				echo wp_send_json_error( __( 'Something Went Wrong! Please try again.', 'my-instagram-feed' ) );
				die;
			}

		}

		/* mif_disable_app_notification__premium_only Method ends here. */
		/* retrieves the attachment ID from the file URL */
		function mif_get_image_id( $image_url ) {
			/* Getting the global wpdb */ global $wpdb;
			/* Getting attachment ID from custom query */
			$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s';", $image_url ) );

			/* Returning back the attachment ID */

			return $attachment[0];
		}

		/* mif_get_image_id Method ends here. */
		/*
		 * It will get the remote URL, Retreive it and return decoded data.
		 */
		public function mif_get_data( $url ) {
			/*
			 * Getting the data from remote URL.
			 */
			$json_data = wp_remote_retrieve_body( wp_remote_get( $url ) );
			/*
			 * Decoding the data.
			 */
			$decoded_data = json_decode( $json_data );

			/*
			 * Returning it to back.
			 */

			return $decoded_data;
		}


		/* mif_mif_get_data method ends here. */
		/*
		 * Will return All Transients collection.
		 */
		public function mif_transients() {
			$returner = null;
			/*
			 * Getting saved access token.
			 */
			$access_token = mif_get_setting( 'mif_access_token' );
			/*
			 * Initializing global wpdb varibale.
			 */ global $wpdb;
			/*
			 * Custom Query for getting all transients from wp
			 */
			$mif_trans_sql = "SELECT `option_name` AS `name`, `option_value` AS `value`\r\n\t\t            FROM  {$wpdb->options}\r\n\t\t            WHERE `option_name` LIKE '%transient_%'\r\n\t\t            ORDER BY `option_name`";
			/*
			 * Getting results of the cahche.
			 */
			$mif_trans_results = $wpdb->get_results( $mif_trans_sql );
			/*
			 * Initializing empty array for mif transients.
			 */
			$mif_trans_posts = [];
			/*
			 * Initializing empty array for mif bio transients.
			 */
			$mif_trans_bio = [];
			/*
			 * Initializing empty array for mif hashtag transients.
			 */
			$mif_trans_hashtag = [];
			/*
			 * Looping thorugh transients if got any results.
			 */
			if ( $mif_trans_results ) {
				foreach ( $mif_trans_results as $mif_trans_result ) {
					/*
					 * Checking Mif exists in transient slug then save that in mif transient array.
					 */
					if ( strpos( $mif_trans_result->name, 'mif' ) !== false && strpos( $mif_trans_result->name, 'posts' ) !== false && strpos( $mif_trans_result->name, 'timeout' ) == false ) {
						$mif_trans_posts[ $mif_trans_result->name ] = $mif_trans_result->value;
					}
					/*
					 * Checking Mif exists in transient slug then save that in mif transient array.
					 */
					if ( strpos( $mif_trans_result->name, 'mif' ) !== false && strpos( $mif_trans_result->name, 'bio' ) !== false && strpos( $mif_trans_result->name, 'timeout' ) == false ) {
						$mif_trans_bio[ $mif_trans_result->name ] = $mif_trans_result->value;
					}
					/*
					 * Checking Mif exists in transient slug then save that in mif transient array.
					 */
					if ( strpos( $mif_trans_result->name, 'mif' ) !== false && strpos( $mif_trans_result->name, 'hashtag' ) !== false && strpos( $mif_trans_result->name, 'timeout' ) == false ) {
						$mif_trans_hashtag[ $mif_trans_result->name ] = $mif_trans_result->value;
					}
				}
			}
			/*
			 * Bio Cached.
			 */

			if ( $mif_trans_bio ) {
				$returner .= '<ul class="collection with-header mif_bio_collection">
                        <li class="collection-header"><h5>' . __( 'Profile Data', 'my-instagram-feed' ) . '</h5></li>';
				foreach ( $mif_trans_bio as $key => $value ) {
					$pieces            = explode( '-', $key );
					$trans_name        = array_pop( $pieces );
					$mif_auth_accounts = mif_get_setting( 'authenticated_accounts' );
					$trans_name        = $mif_auth_accounts[ $trans_name ]['username'];
					$returner          .= '<li class="collection-item ' . $key . '">
                                        <div>' . $trans_name . '
                                        <a href="javascript:void(0);" data-mif_collection="mif_bio_collection" data-mif_trans="' . $key . '" class="secondary-content mif_del_trans"><i class="material-icons">delete</i></a>
                                        </div>
                                    </li>';
				}
				$returner .= '</ul>';
			}

			/*
			 * Posts Cached.
			 */

			if ( $mif_trans_posts ) {
				$returner .= '<ul class="collection with-header mif_users_collection">
                        <li class="collection-header"><h5>' . __( 'Profile Feeds', 'my-instagram-feed' ) . '</h5></li>';
				foreach ( $mif_trans_posts as $key => $value ) {
					$pieces     = explode( '-', $key );
					$trans_name = array_pop( $pieces );
					//echo "<pre>"; print_r($trans_name);exit();
					$mif_auth_accounts = mif_get_setting( 'authenticated_accounts' );
					$trans_name        = $mif_auth_accounts[ $trans_name ]['username'];
					$returner          .= '<li class="collection-item ' . $key . '">
                                        <div>' . $trans_name . '
                                        <a href="javascript:void(0);" data-mif_collection="mif_users_collection" data-mif_trans="' . $key . '" class="secondary-content mif_del_trans"><i class="material-icons">delete</i></a>
                                        </div>
                                    </li>';
				}
				$returner .= '</ul>';
			}

			/*
			 * hashtag Cached.
			 */

			if ( $mif_trans_hashtag ) {
				$returner .= '<ul class="collection with-header mif_hashtags_collection">
                        <li class="collection-header"><h5>' . __( 'Hashtags', 'my-instagram-feed' ) . '</h5></li>';
				foreach ( $mif_trans_hashtag as $key => $value ) {
					$pieces     = explode( '-', $key );
					$trans_name = array_pop( $pieces );
					$returner   .= '<li class="collection-item ' . $key . '">
                                        <div>' . $trans_name . '
                                        <a href="javascript:void(0);" data-mif_collection="mif_hashtags_collection" data-mif_trans="' . $key . '" class="secondary-content mif_del_trans"><i class="material-icons">delete</i></a>
                                        </div>
                                    </li>';
				}
				$returner .= '</ul>';
			}

			if ( empty( $mif_trans_hashtag ) && empty( $mif_trans_posts ) && empty( $mif_trans_bio ) ) {
				$returner = '<h4>' . __( 'Nothing Found!', 'mif' ) . '</h4> <p>' . __( 'Nothing cached at the moment.Feeds will be automatically after showing the feeds on frontend.', 'my-instagram-feed' ) . '</p>';
			}

			/*
			 * Returning it to back.
			 */

			return $returner;
		}


		/*
		 * Returns the Skin URL
		 */
		function mif_create_skin_url() {

			$skin_id = intval( $_POST['skin_id'] );

			$selectedVal = intval( $_POST['selectedVal'] );

			$page_id = intval( $_POST['page_id'] );

			$page_permalink = get_permalink( $page_id );

			if ( wp_verify_nonce( $_POST['mif_nonce'], 'mif-ajax-nonce' ) ) {

				$customizer_url = admin_url( 'customize.php' );

				if ( isset( $page_permalink ) ) {

					$customizer_url = add_query_arg( [
						'url'              => urlencode( $page_permalink ),
						'autofocus[panel]' => 'mif_customizer_panel',
						'mif_skin_id'      => $skin_id,
						'mif_customize'    => 'yes',
						'mif_account_id'   => $selectedVal,
					], $customizer_url );
				}

				echo wp_send_json_success( [
					__( 'Please wait! We are generating preview for you.', 'my-instagram-feed' ),
					$customizer_url
				] );
				wp_die();

			} else {

				echo wp_send_json_error( __( 'Something Went Wrong! Please try again.', 'my-instagram-feed' ) );
				wp_die();
			}

		}

		/**
		 * Delete access token and app permissions
		 *
		 * @since 1.0.0
		 */
		function remove_business_access_token() {

			$mif_settings = mif_get_setting();


			if ( wp_verify_nonce( $_POST['nonce'], 'mif-ajax-nonce' ) ):

				if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ):

					$access_token = $mif_settings['access_token'];

					unset( $mif_settings['authenticated_accounts']['approved_pages'] );

					unset( $mif_settings['authenticated_accounts']['author'] );

					unset( $mif_settings['authenticated_accounts']['access_token'] );

					$mif_settings['selected_type'] = 'personal';

					$delted_data = update_option( 'mif_settings', $mif_settings );

					$response = wp_remote_request( 'https://graph.facebook.com/v4.0/me/permissions?access_token=' . $access_token . '', [
							'method' => 'DELETE'
						] );

					$body = wp_remote_retrieve_body( $response );

				endif;

			endif;

			if ( $delted_data ) {

				echo wp_send_json_success( __( 'Deleted', $Feed_Them_All->fta_slug ) );
				wp_die();

			} else {

				echo wp_send_json_error( __( 'Something Went Wrong! Please try again.', $Feed_Them_All->fta_slug ) );
				wp_die();
			}
			exit;

		}

		/**
		 * Delete access token and app permissions
		 *
		 * @since 1.0.0
		 */
		function remove_access_token() {

			$mif_settings = mif_get_setting();

			if ( wp_verify_nonce( $_POST['mif_nonce'], 'mif-ajax-nonce' ) ) {

				if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {

					unset( $mif_settings['authenticated_accounts']['instagram_connected_account'] );

					$mif_settings['selected_type'] = 'business';

					$delted_data = update_option( 'mif_settings', $mif_settings );

				}
			}

			if ( isset( $delted_data ) ) {

				echo wp_send_json_success( __( 'Deleted', $Feed_Them_All->fta_slug ) );
				wp_die();

			} else {

				echo wp_send_json_error( __( 'Something Went Wrong! Please try again.', $Feed_Them_All->fta_slug ) );
				wp_die();
			}
		}

		function dashboard_footer() {
			echo '<style>.toplevel_page_mif.wp-has-submenu ul li:last-child, .plugins-php [data-slug="my-instagram-feed"] .upgrade{
    display: none;
}</style>';
			echo "<script>function MIFremoveURLParameter(url, parameter) {\r\n    //prefer to use l.search if you have a location/link object\r\n    var urlparts= url.split('?');   \r\n    if (urlparts.length>=2) {\r\n\r\n        var prefix= encodeURIComponent(parameter)+'=';\r\n        var pars= urlparts[1].split(/[&;]/g);\r\n\r\n        //reverse iteration as may be destructive\r\n        for (var i= pars.length; i-- > 0;) {    \r\n            //idiom for string.startsWith\r\n            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  \r\n                pars.splice(i, 1);\r\n            }\r\n        }\r\n\r\n        url= urlparts[0]+'?'+pars.join('&');\r\n        return url;\r\n    } else {\r\n        return url;\r\n    }\r\n} </script>";
		}

		/**
		 * Get upgrade banner info from main site
		 * @return mixed|string[]
		 */
		public function mif_upgrade_banner() {

			$banner_info = get_transient( 'mif_upgrade_banner' );

			if ( isset( $banner_info ) && ! empty( $banner_info ) ) {
				return $banner_info;
			}

			$rest_upgrade_url = 'https://maltathemes.com/wp-json/mif-upgrade-banner/info';

			$response = wp_remote_get( $rest_upgrade_url );

			$responseBody = wp_remote_retrieve_body( $response );

			$banner_info = json_decode( $responseBody );



			if ( ! is_wp_error( $banner_info ) ) {

				$banner_info = (array) $banner_info;

				set_transient( 'mif_upgrade_banner', $banner_info, 86400 );

			} else {

				$banner_info = array(
					'name' => 'Easy Social Photo Feed',
					'bold' => 'PRO',
					'description' => 'Unlock all premium features such as Advanced PopUp, Load More, Hashtag, gallery in the PopUp, More Fancy Layouts like Carousel, Masonry, Half Width, Full Width and above all top notch priority support.',
					'discount-text' => 'Upgrade today and get a 10% discount! On the checkout click on "Have a promotional code?" and enter',
					'coupon' => 'mif10',
					'button-text' => 'Upgrade Now'
				);

			}

			return $banner_info;
		}

	}

	 new My_Instagram_Feed_Admin();
}