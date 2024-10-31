<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'My_Instagram_Feed_Skins' ) ) {
    class My_Instagram_Feed_Skins
    {
        function __construct()
        {
            add_action( 'init', array( $this, 'mif_skins_register' ), 20 );
            $this->mif_skins();
            $this->mif_default_skins();
        }
        
        /*
         * Register skins Post type.
         */
        public function mif_skins_register()
        {
            $args = array(
                'public'              => false,
                'label'               => __( 'My Instagram Feed Skins', 'my-instagram-feed' ),
                'show_in_menu'        => false,
                'exclude_from_search' => true,
                'has_archive'         => false,
                'hierarchical'        => true,
                'menu_position'       => null,
            );
            register_post_type( 'my_insta_feed_skins', $args );
        }
        
        public function mif_skins()
        {
            $mif_skins = array(
                'posts_per_page' => 1000,
                'post_type'      => 'my_insta_feed_skins',
                'post_status'    => array( 'publish', 'draft', 'pending' ),
            );
            $mif_skins = new WP_Query( $mif_skins );
            
            if ( $mif_skins->have_posts() ) {
                $mif_skins_holder = array();
                while ( $mif_skins->have_posts() ) {
                    $mif_skins->the_post();
                    $id = get_the_ID();
                    $design_settings = null;
                    $design_settings = get_option( 'mif_skin_' . $id, false );
                    $layout = get_post_meta( $id, 'layout', true );
                    $title = get_the_title();
                    if ( empty($title) ) {
                        $title = __( 'Skin', 'my-instagram-feed' );
                    }
                    $mif_skins_holder[$id] = array(
                        'ID'          => $id,
                        'title'       => $title,
                        'description' => get_the_content(),
                        'layout'      => $layout,
                    );
                    $defaults = $this->mif_default_skin_settings();
                    $mif_skins_holder[$id]['design'] = wp_parse_args( $design_settings, $defaults );
                }
                wp_reset_postdata();
            } else {
                return __( 'No skins found.', 'my-instagram-feed' );
            }
            
            $GLOBALS['my_instagram_feed_skins'] = $mif_skins_holder;
        }
        
        public function mif_default_skins()
        {
            $mif_settings = mif_get_setting();
            $pro_default_skins_added = '';
            $pro_default_skin_added = '';
            
            if ( !isset( $mif_settings['mif_skin_default_id'] ) && empty($mif_settings['mif_skin_default_id']) ) {
                $mif_new_skins = [
                    'post_title'   => __( "Skin - Grid", 'my-instagram-feed' ),
                    'post_content' => __( "This is the demo skin created by Easy Social Feed plugin automatically with default values. You can edit it and change the look & feel of your Feeds.", 'my-instagram-feed' ),
                    'post_type'    => 'my_insta_feed_skins',
                    'post_status'  => 'publish',
                    'post_author'  => get_current_user_id(),
                ];
                $mif_new_skins = apply_filters( 'mif_default_skin', $mif_new_skins );
                $skin_id = wp_insert_post( $mif_new_skins );
                if ( isset( $skin_id ) ) {
                    update_post_meta( $skin_id, 'layout', 'grid' );
                }
                $mif_settings['mif_skin_default_id'] = $skin_id;
                update_option( 'mif_settings', $mif_settings );
            }
            
            
            if ( !isset( $mif_settings['mif_page_id'] ) && empty($mif_settings['mif_page_id']) ) {
                $skin_id = $mif_settings['mif_skin_default_id'];
                $user_id = null;
                $user_id = mif_default_id();
                $mif_default_page = [
                    'post_title'   => __( "Instagram Demo - Customizer", 'my-instagram-feed' ),
                    'post_content' => __( "[my-instagram-feed user_id='{$user_id}' skin_id='{$skin_id}'] <br> This is a mif demo page created by plugin automatically. Please don't delete to make the plugin work properly.", 'my-instagram-feed' ),
                    'post_type'    => 'page',
                    'post_status'  => 'private',
                ];
                $mif_default_page = apply_filters( 'mif_default_page', $mif_default_page );
                $page_id = wp_insert_post( $mif_default_page );
                $mif_settings['mif_page_id'] = $page_id;
                update_option( 'mif_settings', $mif_settings );
            }
        
        }
        
        public function mif_default_skin_settings()
        {
            return [
                'show_load_more_btn'           => true,
                'number_of_cols'               => 3,
                'show_header'                  => false,
                'show_dp'                      => true,
                'show_no_of_followers'         => true,
                'show_next_prev_icon'          => true,
                'show_nav'                     => true,
                'loop'                         => true,
                'autoplay'                     => true,
                'show_bio'                     => true,
                'feed_header'                  => true,
                'show_comments'                => true,
                'feed_header_logo'             => true,
                'header_shadow_color'          => 'rgba(0,0,0,0.15)',
                'feed_shadow_color'            => 'rgba(0,0,0,0.15)',
                'show_likes'                   => true,
                'show_feed_caption'            => true,
                'show_feed_open_popup_icon'    => true,
                'show_feed_view_on_instagram'  => true,
                'show_feed_share_button'       => true,
                'popup_show_header'            => true,
                'popup_show_header_logo'       => true,
                'popup_show_caption'           => true,
                'popup_show_meta'              => true,
                'popup_show_reactions_counter' => true,
                'popup_show_comments_counter'  => true,
                'popup_show_view_insta_link'   => true,
                'popup_show_comments'          => true,
            ];
        }
    
    }
    $My_Instagram_Feed_Skins = new My_Instagram_Feed_Skins();
}
