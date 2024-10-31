<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( "My_Instagram_Feed_Customizer" ) ) {
    class My_Instagram_Feed_Customizer
    {
        function __construct()
        {
            add_action( 'customize_register', [ $this, 'register_settings' ] );
            add_action( 'customize_preview_init', [ $this, 'live_preview' ] );
            add_action( 'customize_controls_enqueue_scripts', [ $this, 'register_styles' ] );
        }
        
        /**
         * Include styles for Instagram section
         *
         * @since 1.0.0
         */
        function register_styles()
        {
            wp_enqueue_style( 'my-instagram-feed-customizer', MIF_PLUGIN_URL . 'admin/assets/css/my-instagram-feed-customizer-style.css' );
        }
        
        /**
         * Register customizer settings for My Instagram Feed
         *
         * @param $wp_customize
         *
         * @since 1.0.0
         */
        public function register_settings( $wp_customize )
        {
            
            if ( isset( $_GET['mif_skin_id'] ) ) {
                $skin_id = $_GET['mif_skin_id'];
                update_option( 'mif_skin_id', $skin_id );
            }
            
            
            if ( isset( $_GET['mif_account_id'] ) ) {
                $mif_account_id = $_GET['mif_account_id'];
                update_option( 'mif_account_id', $mif_account_id );
            }
            
            $skin_id = get_option( 'mif_skin_id', false );
            $skin_values = get_option( 'mif_skin_' . $skin_id, false );
            $selected_layout = get_post_meta( $skin_id, 'layout', true );
            if ( !$selected_layout ) {
                $selected_layout = strtolower( $skin_values['layout_option'] );
            }
            $selected_layout = strtolower( $selected_layout );
            $wp_customize->add_panel( 'mif_customizer_panel', [
                'title' => __( 'My Instagram Feed', 'my-instagram-feed' ),
            ] );
            $wp_customize->add_section( 'mif_customizer_layout', [
                'title'       => __( 'Layout Settings', 'my-instagram-feed' ),
                'description' => __( 'Select the layout settings in real-time.', 'my-instagram-feed' ),
                'priority'    => 35,
                'panel'       => 'mif_customizer_panel',
            ] );
            
            if ( 'grid' == $selected_layout ) {
                $mif_cols_transport = 'postMessage';
            } else {
                $mif_cols_transport = 'refresh';
            }
            
            if ( mif_fs()->is_free_plan() ) {
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_layout_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Layout Settings', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_layout',
                    'description' => __( 'We are sorry, Layout settings are not included in your plan. Please upgrade to premium version to unlock following settings<ul>
                           <li>Number Of Columns</li>
                           <li>Show Or Hide Load More Button</li>
                           <li>Load More Background Color</li>
                           <li>Load More Color</li>
                           <li>Load More Hover Background Color</li>
                           <li>Load More Hover Color</li>
                           </ul>', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_customizer_layout_upgrade',
                ] ) );
            }
            //======================================================================
            // Header section
            //======================================================================
            $wp_customize->add_section( 'mif_customizer_header', [
                'title'       => __( 'Header', 'my-instagram-feed' ),
                'description' => __( 'Customize the Header In Real Time', 'my-instagram-feed' ),
                'priority'    => 35,
                'panel'       => 'mif_customizer_panel',
            ] );
            $setting = 'mif_skin_' . $skin_id . '[show_header]';
            $wp_customize->add_setting( $setting, [
                'default'   => false,
                'transport' => 'refresh',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( $setting, [
                'label'       => __( 'Show Or Hide Header', 'my-instagram-feed' ),
                'section'     => 'mif_customizer_header',
                'settings'    => $setting,
                'description' => __( 'Show or hide page header.', 'my-instagram-feed' ),
                'type'        => 'checkbox',
            ] );
            $setting = 'mif_skin_' . $skin_id . '[header_background_color]';
            $wp_customize->add_setting( $setting, [
                'default'   => '#fff',
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, [
                'label'       => __( 'Header Background Color', 'my-instagram-feed' ),
                'section'     => 'mif_customizer_header',
                'settings'    => $setting,
                'description' => __( 'Select the background color of header.', 'my-instagram-feed' ),
            ] ) );
            $setting = 'mif_skin_' . $skin_id . '[header_text_color]';
            $wp_customize->add_setting( $setting, [
                'default'   => '#000',
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, [
                'label'       => __( 'Header Text Color', 'my-instagram-feed' ),
                'section'     => 'mif_customizer_header',
                'settings'    => $setting,
                'description' => __( 'Select the content color in header.', 'my-instagram-feed' ),
            ] ) );
            $setting = 'mif_skin_' . $skin_id . '[title_size]';
            $wp_customize->add_setting( $setting, [
                'default'   => 16,
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( $setting, [
                'label'       => __( 'Title Size', 'my-instagram-feed' ),
                'section'     => 'mif_customizer_header',
                'settings'    => $setting,
                'description' => __( 'Select the text size of profile name.', 'my-instagram-feed' ),
                'type'        => 'number',
                'input_attrs' => [
                'min' => 0,
                'max' => 100,
            ],
            ] );
            $setting = 'mif_skin_' . $skin_id . '[header_shadow]';
            $wp_customize->add_setting( $setting, [
                'default'   => false,
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( $setting, [
                'label'       => __( 'Show Or Hide Box Shadow', 'my-instagram-feed' ),
                'section'     => 'mif_customizer_header',
                'settings'    => $setting,
                'description' => __( 'Show or Hide box shadow.', 'my-instagram-feed' ),
                'type'        => 'checkbox',
            ] );
            $setting = 'mif_skin_' . $skin_id . '[header_shadow_color]';
            $wp_customize->add_setting( $setting, [
                'default'   => 'rgba(0,0,0,0.15)',
                'type'      => 'option',
                'transport' => 'postMessage',
            ] );
            $wp_customize->add_control( new Customize_Alpha_Color_Control( $wp_customize, $setting, [
                'label'        => __( 'Shadow color', 'my-instagram-feed' ),
                'section'      => 'mif_customizer_header',
                'settings'     => $setting,
                'show_opacity' => true,
            ] ) );
            
            if ( mif_fs()->is_free_plan() ) {
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_dp_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Show Or Hide Display Picture', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Show Or Hide Display Picture” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_dp_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_round_dp_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Round Display Picture', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Round Display Picture” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_round_dp_upgrade',
                ] ) );
            }
            
            $setting = 'mif_skin_' . $skin_id . '[metadata_size]';
            $wp_customize->add_setting( $setting, [
                'default'   => 16,
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( $setting, [
                'label'       => __( 'Size of total followers', 'my-instagram-feed' ),
                'section'     => 'mif_customizer_header',
                'settings'    => $setting,
                'description' => __( 'Select the text size of total followers in the header.', 'my-instagram-feed' ),
                'type'        => 'number',
                'input_attrs' => [
                'min' => 0,
                'max' => 100,
            ],
            ] );
            
            if ( mif_fs()->is_free_plan() ) {
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_hide_bio_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Show Or Hide Bio', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Show Or Hide Bio” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_hide_bio_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_border_color_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Text Size of Bio', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Text Size of Bio” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_border_color_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_border_color_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Header Border Color', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Header Border Color” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_border_color_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_border_style_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Border Style', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Border Style” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_border_style_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_border_top_size_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Border Top', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Border Top” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_border_top_size_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_border_bottom_size_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Border Bottom', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Border Bottom” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_border_bottom_size_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_border_left_size_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Border Left', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Border Left” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_border_left_size_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_border_right_size_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Border Right', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Border Right” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_border_right_size_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_padding_top_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Padding Top', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Padding Top” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_padding_top_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_padding_bottom_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Padding Bottom', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Padding Bottom” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_padding_bottom_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_padding_left_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Padding Left', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Padding Left” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_padding_left_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_head_padding_right_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Padding Right', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_header',
                    'description' => __( 'We are sorry, “Padding Right” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_head_padding_right_upgrade',
                ] ) );
            }
            
            //======================================================================
            // Feed section
            //======================================================================
            
            if ( 'half_width' == $selected_layout || 'full_width' == $selected_layout || 'grid' == $selected_layout ) {
                $setting = 'mif_skin_' . $skin_id . '[feed_background_color]';
                $wp_customize->add_setting( $setting, [
                    'default'   => '#fff',
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, [
                    'label'       => __( 'Background Color', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'settings'    => $setting,
                    'description' => __( 'Select the Background color of feed.', 'my-instagram-feed' ),
                ] ) );
                $setting = 'mif_skin_' . $skin_id . '[feed_borders_color]';
                $wp_customize->add_setting( $setting, [
                    'default'   => '#dee2e6',
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, [
                    'label'       => __( 'Borders Color', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'settings'    => $setting,
                    'description' => __( 'Select the borders color in the feed', 'my-instagram-feed' ),
                ] ) );
            }
            
            
            if ( 'carousel' !== $selected_layout ) {
                $setting = 'mif_skin_' . $skin_id . '[feed_shadow]';
                $wp_customize->add_setting( $setting, [
                    'default'   => false,
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( $setting, [
                    'label'       => __( 'Show Or Hide Box Shadow', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'settings'    => $setting,
                    'description' => __( 'Show or Hide box shadow.', 'my-instagram-feed' ),
                    'type'        => 'checkbox',
                ] );
                $setting = 'mif_skin_' . $skin_id . '[feed_shadow_color]';
                $wp_customize->add_setting( $setting, [
                    'default'   => 'rgba(0,0,0,0.15)',
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ] );
                $wp_customize->add_control( new Customize_Alpha_Color_Control( $wp_customize, $setting, [
                    'label'        => __( 'Shadow color', 'my-instagram-feed' ),
                    'section'      => 'mif_customizer_feed',
                    'settings'     => $setting,
                    'show_opacity' => true,
                ] ) );
            }
            
            if ( 'half_width' == $selected_layout || 'full_width' == $selected_layout ) {
                
                if ( mif_fs()->is_free_plan() ) {
                    $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_header_feed_upgrade', [
                        'settings'    => [],
                        'label'       => __( 'Show Or Hide Feed Header', 'my-instagram-feed' ),
                        'section'     => 'mif_customizer_feed',
                        'description' => __( 'We are sorry, “Show Or Hide Feed Header” is a premium feature.', 'my-instagram-feed' ),
                        'popup_id'    => 'mif_customizer_header_feed_upgrade',
                    ] ) );
                    $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_header_feed_logo_upgrade', [
                        'settings'    => [],
                        'label'       => __( 'Show Or Hide Feed Header Logo', 'my-instagram-feed' ),
                        'section'     => 'mif_customizer_feed',
                        'description' => __( 'We are sorry, “Show Or Hide Feed Header Logo” is a premium feature.', 'my-instagram-feed' ),
                        'popup_id'    => 'mif_customizer_header_feed_logo_upgrade',
                    ] ) );
                }
            
            }
            
            if ( 'carousel' !== $selected_layout ) {
                if ( mif_fs()->is_free_plan() ) {
                    $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_text_color_feed_upgrade', [
                        'settings'    => [],
                        'label'       => __( 'Text Color', 'my-instagram-feed' ),
                        'section'     => 'mif_customizer_feed',
                        'description' => __( 'We are sorry, “Text Color” is a premium feature.', 'my-instagram-feed' ),
                        'popup_id'    => 'mif_text_color_feed_upgrade',
                    ] ) );
                }
                
                if ( $selected_layout == 'grid' ) {
                    $feed_default_padding = 3;
                } else {
                    $feed_default_padding = 15;
                }
                
                $setting = 'mif_skin_' . $skin_id . '[feed_padding_top]';
                $wp_customize->add_setting( $setting, [
                    'default'   => $feed_default_padding,
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( $setting, [
                    'label'       => __( 'Padding Top', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'settings'    => $setting,
                    'description' => __( 'Select the padding top', 'my-instagram-feed' ),
                    'type'        => 'number',
                    'input_attrs' => [
                    'min' => 0,
                    'max' => 100,
                ],
                ] );
                $setting = 'mif_skin_' . $skin_id . '[feed_padding_bottom]';
                $wp_customize->add_setting( $setting, [
                    'default'   => $feed_default_padding,
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( $setting, [
                    'label'       => __( 'Padding Bottom', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'settings'    => $setting,
                    'description' => __( 'Select the padding bottom of feed.', 'my-instagram-feed' ),
                    'type'        => 'number',
                    'input_attrs' => [
                    'min' => 0,
                    'max' => 100,
                ],
                ] );
                $setting = 'mif_skin_' . $skin_id . '[feed_padding_right]';
                $wp_customize->add_setting( $setting, [
                    'default'   => $feed_default_padding,
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( $setting, [
                    'label'       => __( 'Padding Right', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'settings'    => $setting,
                    'description' => __( 'Select the padding right for feed.', 'my-instagram-feed' ),
                    'type'        => 'number',
                    'input_attrs' => [
                    'min' => 0,
                    'max' => 100,
                ],
                ] );
                $setting = 'mif_skin_' . $skin_id . '[feed_padding_left]';
                $wp_customize->add_setting( $setting, [
                    'default'   => $feed_default_padding,
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( $setting, [
                    'label'       => __( 'Padding  Left', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'settings'    => $setting,
                    'description' => __( 'Select the padding left for feed.', 'my-instagram-feed' ),
                    'type'        => 'number',
                    'input_attrs' => [
                    'min' => 0,
                    'max' => 100,
                ],
                ] );
            }
            
            
            if ( $selected_layout == 'grid' ) {
                $feed_default_spacing = 30;
                $feed_transport = 'postMessage';
            } elseif ( $selected_layout == 'carousel' ) {
                $feed_default_spacing = 0;
                $feed_transport = 'refresh';
            } else {
                $feed_default_spacing = 20;
                $feed_transport = 'postMessage';
            }
            
            $setting = 'mif_skin_' . $skin_id . '[feed_spacing]';
            $wp_customize->add_setting( $setting, [
                'default'   => $feed_default_spacing,
                'transport' => $feed_transport,
                'type'      => 'option',
            ] );
            $wp_customize->add_control( $setting, [
                'label'       => __( 'Spacing', 'my-instagram-feed' ),
                'section'     => 'mif_customizer_feed',
                'settings'    => $setting,
                'description' => __( 'Select the spacing between feeds.', 'my-instagram-feed' ),
                'type'        => 'number',
                'input_attrs' => [
                'min' => 0,
                'max' => 100,
            ],
            ] );
            $wp_customize->add_section( 'mif_customizer_feed', [
                'title'       => __( 'Feed', 'my-instagram-feed' ),
                'description' => __( 'Customize the Single Feed Design In Real Time', 'my-instagram-feed' ),
                'priority'    => 35,
                'panel'       => 'mif_customizer_panel',
            ] );
            
            if ( 'half_width' == $selected_layout || 'full_width' == $selected_layout ) {
                if ( mif_fs()->is_free_plan() ) {
                    $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_meta_feed_upgrade', [
                        'settings'    => [],
                        'label'       => __( 'Feed Meta Color', 'my-instagram-feed' ),
                        'section'     => 'mif_customizer_feed',
                        'description' => __( 'We are sorry, “Feed Meta Color” is a premium feature.', 'my-instagram-feed' ),
                        'popup_id'    => 'mif_meta_feed_upgrade',
                    ] ) );
                }
                $setting = 'mif_skin_' . $skin_id . '[show_likes]';
                $wp_customize->add_setting( $setting, [
                    'default'   => true,
                    'transport' => 'refresh',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( $setting, [
                    'label'    => __( 'Show Or Hide Likes Counter', 'my-instagram-feed' ),
                    'section'  => 'mif_customizer_feed',
                    'settings' => $setting,
                    'type'     => 'checkbox',
                ] );
                $setting = 'mif_skin_' . $skin_id . '[show_comments]';
                $wp_customize->add_setting( $setting, [
                    'default'   => true,
                    'transport' => 'refresh',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( $setting, [
                    'label'    => __( 'Show Or Hide Comments Counter', 'my-instagram-feed' ),
                    'section'  => 'mif_customizer_feed',
                    'settings' => $setting,
                    'type'     => 'checkbox',
                ] );
                $setting = 'mif_skin_' . $skin_id . '[show_feed_caption]';
                $wp_customize->add_setting( $setting, [
                    'default'   => true,
                    'transport' => 'refresh',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( $setting, [
                    'label'    => __( 'Show Or Hide Feed Caption', 'my-instagram-feed' ),
                    'section'  => 'mif_customizer_feed',
                    'settings' => $setting,
                    'type'     => 'checkbox',
                ] );
            }
            
            
            if ( mif_fs()->is_free_plan() ) {
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_popup_icon_feed_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Show Or Hide Open PopUp Icon', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'description' => __( 'We are sorry, “Show Or Hide Open PopUp Icon” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_customizer_popup_icon_feed_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_popup_icon_color_feed_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Open PopUp Icon color', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'description' => __( 'We are sorry, “Open PopUp Icon color” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_customizer_popup_icon_color_feed_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_popup_icon_color_feedtype_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Feed Type Icon color', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'description' => __( 'We are sorry, “Feed Type Icon color” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_customizer_popup_icon_color_feedtype_upgrade',
                ] ) );
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_popup_cta_feed_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Show Or Hide Feed Call To Action Buttons', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'description' => __( 'We are sorry, “Show Or Hide Feed Call To Action Buttons” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_customizer_popup_cta_feed_upgrade',
                ] ) );
            }
            
            if ( mif_fs()->is_free_plan() ) {
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_popup_bg_hover_feed_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Feed Hover Shadow Color', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_feed',
                    'description' => __( 'We are sorry, “Feed Hover Shadow Color” is a premium feature.', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_customizer_popup_bg_hover_feed_upgrade',
                ] ) );
            }
            //======================================================================
            // PopUP section
            //======================================================================
            $wp_customize->add_section( 'mif_customizer_popup', [
                'title'       => __( 'Media lightbox', 'my-instagram-feed' ),
                'description' => __( 'Customize the PopUp In Real Time', 'my-instagram-feed' ),
                'priority'    => 35,
                'panel'       => 'mif_customizer_panel',
            ] );
            if ( mif_fs()->is_free_plan() ) {
                $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, 'mif_customizer_popup_popup_upgrade', [
                    'settings'    => [],
                    'label'       => __( 'Media Lightbox Settings', 'my-instagram-feed' ),
                    'section'     => 'mif_customizer_popup',
                    'description' => __( 'We are sorry, Media Lightbox Settings are not included in your plan. Please upgrade to premium version to unlock following settings<ul>
                           <li>Sidebar Background Color</li>
                           <li>Sidebar Content Color</li>
                           <li>Show Or Hide PopUp Header</li>
                           <li>Show Or Hide Header Logo</li>
                           <li>Header Title Color</li>
                           <li>Post Time Color</li>
                           <li>Show Or Hide Caption</li>
                           <li>Show Or Hide Meta Section</li>
                           <li>Meta Background Color</li>
                           <li>Meta Content Color</li>
                           <li>Show Or Hide Reactions Counter</li>
                           <li>Show Or Hide Comments Counter</li>
                           <li>Show Or Hide View On Facebook Link</li>
                           <li>Show Or Hide Comments</li>
                           <li>Comments Background Color</li>
                           <li>Comments Color</li>
                           </ul>', 'my-instagram-feed' ),
                    'popup_id'    => 'mif_customizer_popup_popup_upgrade',
                ] ) );
            }
        }
        
        /**
         * Includes scripts for live preview
         *
         * @since 1.0.0
         */
        public function live_preview()
        {
            $skin_id = get_option( 'mif_skin_id', false );
            wp_enqueue_script(
                'mif-instagram-feed-live-preview',
                MIF_PLUGIN_URL . 'admin/assets/js/mif-instagram-feed-live-preview.js',
                [ 'jquery', 'customize-preview' ],
                true
            );
            wp_localize_script( 'mif-instagram-feed-live-preview', 'mif_skin_id', $skin_id );
        }
    
    }
    new My_Instagram_Feed_Customizer();
}
