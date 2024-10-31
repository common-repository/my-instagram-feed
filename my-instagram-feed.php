<?php

/*
Plugin Name: Easy Social Photos Gallery 
Plugin URI: https://wordpress.org/plugins/my-instagram-feed/
Description: Formerly "My Instagram Feed - Instagram Photos Gallery" display photos and videos from a non-private Instagram account in a responsive, mobile friendly and customizable layout on your website. My Instagram Feed and Photos Gallery is a modern plugin with a beginner's friendly workflow to list your stunning Instagram photos from Instagram on your site in a minute. Yes, one minute (or less)! 
Author: Sajid Javed
Version: 3.1.2
Author URI: https://sajidjaved.me/
*/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'mif_fs' ) ) {
    mif_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'mif_fs' ) ) {
        // Create a helper function for easy SDK access.
        function mif_fs()
        {
            global  $mif_fs ;
            
            if ( !isset( $mif_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_2312_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_2312_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $mif_fs = fs_dynamic_init( array(
                    'id'             => '2312',
                    'slug'           => 'my-instagram-feed',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_c9525f04b280132c30cb230807ce6',
                    'is_premium'     => false,
                    'premium_suffix' => 'Premium',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'    => 'my-instagram-feed',
                    'support' => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $mif_fs;
        }
        
        // Init Freemius.
        mif_fs();
        // Signal that SDK was initiated.
        do_action( 'mif_fs_loaded' );
    }
    
    
    if ( !class_exists( 'My_Instagram_Feed' ) ) {
        class My_Instagram_Feed
        {
            public  $version = '3.1.2' ;
            function __construct()
            {
                add_action( 'init', array( $this, 'constants' ) );
                add_action( 'init', array( $this, 'includes' ) );
                register_activation_hook( __FILE__, array( $this, 'mif_activated' ) );
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'mif_settings_link' ) );
            }
            
            /*
             * Defined required plugin constants.
             */
            public function constants()
            {
                if ( !defined( 'MIF_VERSION' ) ) {
                    define( 'MIF_VERSION', $this->version );
                }
                if ( !defined( 'MIF_PLUGIN_DIR' ) ) {
                    define( 'MIF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
                }
                if ( !defined( 'MIF_PLUGIN_URL' ) ) {
                    define( 'MIF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
                }
                if ( !defined( 'MIF_PLUGIN_FILE' ) ) {
                    define( 'MIF_PLUGIN_FILE', __FILE__ );
                }
            }
            
            /*
             * mif_activated will Add all the necessary data into the database on plugin install.
             */
            public function mif_activated()
            {
                include plugin_dir_path( __FILE__ ) . 'includes/mif-helper-functions.php';
                $install_date = mif_get_setting( 'mif_installDate' );
                $mif_settings['mif_installDate'] = date( 'Y-m-d h:i:s' );
                update_option( 'mif_settings', $mif_settings );
            }
            
            /*
             * include plugin files
             */
            public function includes()
            {
                include MIF_PLUGIN_DIR . 'includes/mif-helper-functions.php';
                if ( !class_exists( 'My_Instagram_Feed_Skins' ) ) {
                    include MIF_PLUGIN_DIR . 'admin/includes/class-my-instagram-feed-skins.php';
                }
                if ( !class_exists( 'My_Instagram_Feed_Admin' ) ) {
                    include MIF_PLUGIN_DIR . 'admin/class-my-instagram-feed-admin.php';
                }
                if ( !class_exists( 'My_Instagram_Feed_Customizer' ) ) {
                    include MIF_PLUGIN_DIR . 'admin/includes/class-my-instagram-feed-customizer.php';
                }
                include MIF_PLUGIN_DIR . 'admin/includes/class-my-instagram-feed-customizer-extend.php';
                if ( !class_exists( 'My_Instagram_Feed_Frontend' ) ) {
                    include MIF_PLUGIN_DIR . 'frontend/class-my-instagram-feed-frontend.php';
                }
            }
            
            /*
             * Add settins link in plugins tab
             */
            public function mif_settings_link( $links )
            {
                $mif_link = array( '<a href="' . admin_url( 'admin.php?page=my-instagram-feed' ) . '">' . __( 'Settings', 'my-instagram-feed' ) . '</a>' );
                return array_merge( $mif_link, $links );
            }
        
        }
        $My_Instagram_Feed = new My_Instagram_Feed();
    }

}
