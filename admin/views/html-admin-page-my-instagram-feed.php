<?php
/**
 * Admin View: Page - Instagram
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$app_ID = [ '405460652816219', '222116127877068' ];

$rand_app_ID = array_rand( $app_ID, '1' );

$u_app_ID = $app_ID[ $rand_app_ID ];


$auth_url = esc_url( add_query_arg( [
	'client_id'    => $u_app_ID,
	'redirect_uri' => 'https://maltathemes.com/efbl/app-' . $u_app_ID . '/index.php',
	'scope'        => 'pages_read_engagement,pages_manage_metadata,pages_read_user_content,instagram_basic',
	'state'        => admin_url( 'admin.php?page=my-instagram-feed' ),
], 'https://www.facebook.com/dialog/oauth' ) );

$mif_personal_clients = [ '2478794912435887', '494132551291859' ];

$mif_personal_app_ID = $mif_personal_clients[ array_rand( $mif_personal_clients, '1' ) ];

$personal_auth_url = esc_url( add_query_arg( [
	'client_id'     => $mif_personal_app_ID,
	'redirect_uri'  => 'https://easysocialfeed.com/efbl/app-' . $mif_personal_app_ID . '/index.php',
	'scope'         => 'user_profile,user_media',
	'response_type' => 'code',
	'state'         => admin_url( 'admin.php?page=my-instagram-feed' ),
], 'https://api.instagram.com/oauth/authorize' ) );

?>

    <div class="esf_loader_wrap">
        <div class="esf_loader_inner">
            <div class="loader mif_loader"></div>
        </div>
    </div>
    <div class="fta_wrap_outer <?php if( mif_fs()->is_free_plan()  ){?>mif-outer-free<?php  } ?>" <?php  if( mif_fs()->is_free_plan()  ){?> style="width: 78%" <?php  } ?>>
        <div class="mif_wrap z-depth-1">
            <div class="mif_wrap_inner">

                <div class="mif_loader_wrap">
                    <i class=" fa fa-spinner fa-spin"></i>
                </div>
                <div class="mif_tabs_holder">
                    <div class="mif_tabs_header">
                        <ul id="mif_tabs" class="tabs">
                            <li class="tab col s3">
                                <a class="active mif-general" href="#mif-general">
                                    <span><?php esc_html_e( "1", 'my-instagram-feed' ); ?>. <?php esc_html_e( "Authenticate", 'my-instagram-feed' ); ?></span>
                                </a></li>

                            <li class="tab col s3"><a
                                        class=" mif_for_disable mif-shortcode"
                                        href="#mif-shortcode">
                                    <span><?php esc_html_e( "2", 'my-instagram-feed' ); ?>. <?php esc_html_e( "Use", 'my-instagram-feed' ); ?></span>
                                </a>
                            </li>

                            <li class="tab col s3"><a
                                        class="mif_for_disable mif-skins"
                                        href="#mif-skins">
                                    <span><?php esc_html_e( "3", 'my-instagram-feed' ); ?>. <?php esc_html_e( "Customize (skins)", 'my-instagram-feed' ); ?></span>
                                </a>
                            </li>

                            <li class="tab col s3"><a
                                        class=" mif_for_disable mif-cache"
                                        href="#mif-cache">
                                    <span><?php esc_html_e( "Clear Cache", 'my-instagram-feed' ); ?></span>
                                </a>
                            </li>

                        </ul>
						<?php

						if ( $fta_settings['plugins']['facebook']['status'] && 'activated' == $fta_settings['plugins']['facebook']['status'] ) { ?>

                            <div class="mif_tabs_right">
                                <a class=""
                                   href="<?php echo esc_url( admin_url( 'admin.php?page=easy-facebook-likebox' ) ); ?>"><?php esc_html_e( "Facebook", 'my-instagram-feed' ); ?></a>
                            </div>

						<?php } ?>
                    </div>
                    <div class="mif_tab_c_holder">
						<?php include_once MIF_PLUGIN_DIR . 'admin/views/html-autenticate-tab.php'; ?>
						<?php include_once MIF_PLUGIN_DIR . 'admin/views/html-how-to-use-tab.php'; ?>
						<?php include_once MIF_PLUGIN_DIR . 'admin/views/html-skins-tab.php'; ?>
						<?php include_once MIF_PLUGIN_DIR . 'admin/views/html-clear-cache-tab.php'; ?>
                    </div>
                </div>

            </div>

            <div id="fta-remove-at" class="modal">
                <div class="modal-content">
                <span class="mif-close-modal modal-close"><i
                            class="material-icons dp48">close</i></span>
                    <div class="mif-modal-content"><span class="mif-lock-icon"><i
                                    class="material-icons dp48">error_outline</i> </span>
                        <h5><?php esc_html_e( "Are you sure?", 'my-instagram-feed' ); ?></h5>
                        <p><?php esc_html_e( "Do you really want to delete the access token? It will delete all the pages data, access tokens and premissions given to the app.", 'my-instagram-feed' ); ?></p>
                        <a class="waves-effect waves-light btn modal-close"
                           href="javascript:void(0)"><?php esc_html_e( "Cancel", 'my-instagram-feed' ); ?></a>
                        <a class="waves-effect waves-light btn efbl_delete_at_confirmed modal-close"
                           href="javascript:void(0)"><?php esc_html_e( "Delete", 'my-instagram-feed' ); ?></a>
                    </div>
                </div>

            </div>

            <div id="mif-remove-at" class="modal">
                <div class="modal-content">
                <span class="mif-close-modal modal-close"><i
                            class="material-icons dp48">close</i></span>
                    <div class="mif-modal-content"><span class="mif-lock-icon"><i
                                    class="material-icons dp48">error_outline</i> </span>
                        <h5><?php esc_html_e( "Are you sure?", 'my-instagram-feed' ); ?></h5>
                        <p><?php esc_html_e( "Do you really want to delete the access token? It will delete the access token saved in your website databse.", 'my-instagram-feed' ); ?></p>
                        <a class="waves-effect waves-light btn modal-close"
                           href="javascript:void(0)"><?php esc_html_e( "Cancel", 'my-instagram-feed' ); ?></a>
                        <a class="waves-effect waves-light btn mif_delete_at_confirmed"
                           href="#"><?php esc_html_e( "Delete", 'my-instagram-feed' ); ?></a>
                        <div class="mif-revoke-access-steps">
                            <p><?php esc_html_e( "If you want to disconnect plugin app also follow the steps below:", 'my-instagram-feed' ); ?></p>
                            <ol>
                                <li><?php esc_html_e( "Go to ", 'my-instagram-feed' ); ?>
                                    <a target="_blank"
                                       href="<?php echo esc_url( 'https://www.instagram.com/' ) ?>">instagram.com</a> <?php esc_html_e( "Log in with your username and password", 'my-instagram-feed' ); ?>
                                </li>
                                <li><?php esc_html_e( "Click on the user icon located on the top right of your screen.", 'my-instagram-feed' ); ?></li>
                                <li><?php esc_html_e( "Go in your Instagram Settings and select “Authorized Apps”", 'my-instagram-feed' ); ?></li>
                                <li><?php esc_html_e( "You will see a list of the apps & websites that are linked to your Instagram account. Click “Revoke Access” and “Yes” on the button below which you authenticated", 'my-instagram-feed' ); ?></li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>


            <div id="fta-auth-error" class="modal">
                <div class="modal-content">
                <span class="mif-close-modal modal-close"><i
                            class="material-icons dp48">close</i></span>
                    <div class="mif-modal-content"><span class="mif-lock-icon"><i
                                    class="material-icons dp48">error_outline</i> </span>
                        <p><?php esc_html_e( "Sorry, Plugin is unable to get the accounts data. Please delete the access token and select accounts in the second step of authentication to give the permission.", 'my-instagram-feed' ); ?></p>

                        <a class="waves-effect waves-light efbl_authentication_btn btn"
                           href="<?php echo $auth_url; ?>"><i
                                    class="material-icons right">camera_enhance</i><?php esc_html_e( "Connect My Instagram Account", 'my-instagram-feed' ); ?>
                        </a>
                    </div>
                </div>

            </div>

            <div id="mif-free-masonry-upgrade" class="fta-upgrade-modal modal">
                <div class="modal-content">

                    <div class="mif-modal-content"><span class="mif-lock-icon"><i
                                    class="material-icons dp48">lock_outline</i> </span>
                        <h5><?php esc_html_e( "Premium Feature", 'my-instagram-feed' ); ?></h5>
                        <p><?php esc_html_e( "We're sorry, Masonry layout is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", 'my-instagram-feed' ); ?>
                            <a target="_blank"
                               href="<?php echo esc_url( 'https://maltathemes.com/my-instagram-feed-demo/masonary' ); ?>"><?php esc_html_e( "Check out the demo", 'my-instagram-feed' ); ?></a>
                        </p>
                        <p><?php esc_html_e( 'Upgrade today and get a 10% discount! On the checkout click on "Have a promotional code?" and enter ', 'my-instagram-feed' ); ?>
                            <code>mif10</code></p>
                        <hr/>
                        <a href="<?php echo esc_url( mif_fs()->get_upgrade_url() ); ?>"
                           class="waves-effect waves-light btn"><i
                                    class="material-icons right">lock_open</i><?php esc_html_e( "Upgrade to pro", 'my-instagram-feed' ); ?>
                        </a>

                    </div>
                </div>

            </div>

			<?php if ( mif_fs()->is_free_plan() ) { ?>

                <div id="mif-free-carousel-upgrade" class="fta-upgrade-modal modal">
                    <div class="modal-content">

                        <div class="mif-modal-content"><span
                                    class="mif-lock-icon"><i
                                        class="material-icons dp48">lock_outline</i> </span>
                            <h5><?php esc_html_e( "Premium Feature", 'my-instagram-feed' ); ?></h5>
                            <p><?php esc_html_e( "We're sorry, Carousel layout is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", 'my-instagram-feed' ); ?>
                                <a target="_blank"
                                   href="<?php echo esc_url( 'https://maltathemes.com/my-instagram-feed-demo/carousel' ); ?>"><?php esc_html_e( "Check out the demo", 'my-instagram-feed' ); ?></a>
                            </p>
                            <p><?php esc_html_e( 'Upgrade today and get a 10% discount! On the checkout click on "Have a promotional code?" and enter ', 'my-instagram-feed' ); ?>
                                <code>mif10</code></p>
                            <hr/>
                            <a href="<?php echo esc_url( mif_fs()->get_upgrade_url() ); ?>"
                               class="waves-effect waves-light btn"><i
                                        class="material-icons right">lock_open</i><?php esc_html_e( "Upgrade to pro", 'my-instagram-feed' ); ?>
                            </a>

                        </div>
                    </div>

                </div>


                <div id="mif-free-half_width-upgrade"
                     class="fta-upgrade-modal modal">
                    <div class="modal-content">

                        <div class="mif-modal-content"><span
                                    class="mif-lock-icon"><i
                                        class="material-icons dp48">lock_outline</i> </span>
                            <h5><?php esc_html_e( "Premium Feature", 'my-instagram-feed' ); ?></h5>
                            <p><?php esc_html_e( "We're sorry, Half Width layout is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", 'my-instagram-feed' ); ?>
                                <a target="_blank"
                                   href="<?php echo esc_url( 'https://maltathemes.com/my-instagram-feed-demo/blog-layout' ); ?>"><?php esc_html_e( "Check out the demo", 'my-instagram-feed' ); ?></a>
                            </p>
                            <p><?php esc_html_e( 'Upgrade today and get a 10% discount! On the checkout click on "Have a promotional code?" and enter ', 'my-instagram-feed' ); ?>
                                <code>mif10</code></p>
                            <hr/>
                            <a href="<?php echo esc_url( mif_fs()->get_upgrade_url() ); ?>"
                               class="waves-effect waves-light btn"><i
                                        class="material-icons right">lock_open</i><?php esc_html_e( "Upgrade to pro", 'my-instagram-feed' ); ?>
                            </a>

                        </div>
                    </div>

                </div>


                <div id="mif-free-full_width-upgrade"
                     class="fta-upgrade-modal modal">
                    <div class="modal-content">

                        <div class="mif-modal-content"><span
                                    class="mif-lock-icon"><i
                                        class="material-icons dp48">lock_outline</i> </span>
                            <h5><?php esc_html_e( "Premium Feature", 'my-instagram-feed' ); ?></h5>
                            <p><?php esc_html_e( "We're sorry, Full Width layout is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", 'my-instagram-feed' ); ?>
                                <a target="_blank"
                                   href="<?php echo esc_url( 'https://maltathemes.com/my-instagram-feed-demo/full-width' ); ?>"><?php esc_html_e( "Check out the demo", 'my-instagram-feed' ); ?></a>
                            </p>
                            <p><?php esc_html_e( 'Upgrade today and get a 10% discount! On the checkout click on "Have a promotional code?" and enter ', 'my-instagram-feed' ); ?>
                                <code>mif10</code></p>
                            <hr/>
                            <a href="<?php echo esc_url( mif_fs()->get_upgrade_url() ); ?>"
                               class="waves-effect waves-light btn"><i
                                        class="material-icons right">lock_open</i><?php esc_html_e( "Upgrade to pro", 'my-instagram-feed' ); ?>
                            </a>

                        </div>
                    </div>

                </div>

			<?php } ?>

        </div>

    </div>


<?php if ( mif_fs()->is_free_plan() ) { ?>
    <div class="fta-other-plugins-sidebar">

		<?php $banner_info = $this->mif_upgrade_banner(); ?>

        <div class="espf-upgrade z-depth-2">
            <h2><?php  esc_html_e( $banner_info['name'] ); ?>
                <b><?php  esc_html_e( $banner_info['bold'] ); ?></b></h2>
            <p><?php  esc_html_e( $banner_info['description'] ); ?></p>
            <p><?php  esc_html_e( $banner_info['discount-text'] ); ?> <code><?php  esc_html_e( $banner_info['coupon'] ); ?></code>
            </p>
            <a href="<?php echo esc_url( mif_fs()->get_upgrade_url() ) ?>"
               class="waves-effect waves-light btn"><i class="material-icons right">lock_open</i><?php esc_html_e( $banner_info['button-text'] ); ?>
            </a>
        </div>

    </div>

<?php } ?>
