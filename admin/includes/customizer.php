<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================
// Customizer code Of My Instagram Feeds
//======================================================================
class MIF_customizer
{
    /*
     * __construct initialize all function of this class.
     * Returns nothing. 
     * Used action_hooks to get things sequentially.
     */
    function __construct()
    {
        /*
         * Getting the page ID saved in db while activation.
         */
        $page_id = $this->mif_get_settings( 'mif_page_id' );
       
        /*
         * customize_register hook will add custom files in customizer.
         */
        add_action( 'customize_register', array( $this, 'mif_customizer' ) );
        /*
         * customize_preview_init hook will add our js file in customizer.
         */
        add_action( 'customize_preview_init', array( $this, 'mif_live_preview' ) );
        /*
         * customize_preview_init hook will add our js file in customizer.
         */
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'mif_customizer_scripts' ) );
        // add_action( 'wp_head', array ($this ,'mif_customizer_scripts'));
        // }
    }
    
    /* __construct Method ends here. */
    /*
     * mif_customizer holds cutomizer files.
     */
    function mif_customizer_scripts()
    {
 
        /*
         * Enqueing customizer style file.
         */
        wp_enqueue_style( 'mif_customizer_style', MIF_PLUGIN_URL . 'assets/css/mif_customizer_style.css' );
     
        /*
         * Base script file for admin area.
         */
         wp_enqueue_script( 'mif-customizer-custom', MIF_PLUGIN_URL . 'assets/js/mif-customizer-custom.js', array( 'jquery') );
    }
    
    /* mif_customizer_scripts Method ends here. */
    /*
     * mif_customizer holds code for customizer area.
     */
    public function mif_customizer( $wp_customize )
    {
        /* Getting the skin id from URL and saving in option for confliction.*/
        
        if ( isset( $_GET['mif_skin_id'] ) ) {
            $skin_id = $_GET['mif_skin_id'];
            update_option( 'mif_skin_id', $skin_id );
        }
        
        /* Getting back the skin saved ID.*/
        $skin_id = get_option( 'mif_skin_id', false );
        //======================================================================
        // My Instagram Feeds Section
        //======================================================================
        /* Adding our MIF panel in customizer.*/
        $wp_customize->add_panel( 'my_insta_feed_skins_panel', array(
            'title' => __( 'My Instagram Feeds', 'my-instagram-feed' ),
        ) );
        //======================================================================
        // Layout section
        //======================================================================
        /* Adding layout section in customizer under MIF panel.*/
        $wp_customize->add_section( 'mif_layout', array(
            'title'       => __( 'Layout', 'my-instagram-feed' ),
            'description' => __( 'Select the Layout in real time.', 'my-instagram-feed' ),
            'priority'    => 35,
            'panel'       => 'my_insta_feed_skins_panel',
        ) );

        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[number_of_cols]';
        /* Adding Setting of number of columns if layout set to grid.*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '3',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of number of columns if layout set to grid.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Number of columns', 'my-instagram-feed' ),
            'section'     => 'mif_layout',
            'settings'    => $setting,
            'description' => __( "Select the number of columns for feeds i.e. works with Grid layout only.", 'my-instagram-feed' ),
            'type'        => 'select',
            'choices'     => array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[show_follow_btn]';
        /* Adding Setting of show or hide Follow Button*/
        $wp_customize->add_setting( $setting, array(
            'default'   => true,
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of show or hide Follow Button.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Show Follow Button', 'my-instagram-feed' ),
            'section'     => 'mif_layout',
            'settings'    => $setting,
            'description' => __( 'Show or Hide follow button', 'my-instagram-feed' ),
            'type'        => 'checkbox',
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[show_load_more_btn]';
        /* Adding Setting of show or hide Follow Button*/
        $wp_customize->add_setting( $setting, array(
            'default'   => true,
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of show or hide Follow Button.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Show Load More Button', 'my-instagram-feed' ),
            'section'     => 'mif_layout',
            'settings'    => $setting,
            'description' => __( 'Show or Hide load more button', 'my-instagram-feed' ),
            'type'        => 'checkbox',
        ) );
        //======================================================================
        // Header section
        //======================================================================
        /* Adding layout section in customizer under MIF panel.*/
        $wp_customize->add_section( 'mif_header', array(
            'title'       => __( 'Header', 'my-instagram-feed' ),
            'description' => __( 'Customize the Header In Real Time', 'my-instagram-feed' ),
            'priority'    => 35,
            'panel'       => 'my_insta_feed_skins_panel',
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[show_header]';
        /* Adding Setting of show or hide header.*/
        $wp_customize->add_setting( $setting, array(
            'default'   => true,
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of show or hide header.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Show Header', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Show or Hide header.', 'my-instagram-feed' ),
            'type'        => 'checkbox',
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_background_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Header Background Color', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the background color of header.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_text_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Header Text Color', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the content color which are displaying in header.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[title_size]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '16',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Title Size', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the text size of profile name.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[show_dp]';
        /* Adding Setting of show or hide header.*/
        $wp_customize->add_setting( $setting, array(
            'default'   => true,
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of show or hide header.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Show Display Picture', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Show or Hide display picture of your account which are displaying in header.', 'my-instagram-feed' ),
            'type'        => 'checkbox',
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_round_dp]';
        /* Adding Setting of show or hide Bio*/
        $wp_customize->add_setting( $setting, array(
            'default'   => true,
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of show or hide Bio.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Round Display Picture', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Show rounded or boxed display picture', 'my-instagram-feed' ),
            'type'        => 'checkbox',
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_dp_hover_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => 'rgba(0,0,0,0.5)',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new Customize_Alpha_Color_Control( $wp_customize, $setting, array(
            'label'        => __( 'Display Picture Hover Shadow Color', 'my-instagram-feed' ),
            'section'      => 'mif_header',
            'description'  => __( "Select the shadow color which shows on dispaly picture's hover.", 'my-instagram-feed' ),
            'settings'     => $setting,
            'show_opacity' => true,
            'palette'      => array(
            'rgb(0, 0, 0)',
            'rgb(255, 255, 255)',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_dp_hover_icon_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#fff',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Display Picture Hover Icon color', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the icon color which shows on display picture hover.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[show_no_of_feeds]';
        /* Adding Setting of show or hide total number of posts.*/
        $wp_customize->add_setting( $setting, array(
            'default'   => true,
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of show or hide total number of posts.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Show Total Number Of Feeds', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Show or Hide total number of feeds which are displaying in header.', 'my-instagram-feed' ),
            'type'        => 'checkbox',
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[show_no_of_followers]';
        /* Adding Setting of show or hide total number of followers.*/
        $wp_customize->add_setting( $setting, array(
            'default'   => true,
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of show or hide total number of followers.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Show Total Number Of Followers', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Show or Hide Total Number Of Followers Which are displaying in header.', 'my-instagram-feed' ),
            'type'        => 'checkbox',
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[metadata_size]';
        /* Adding Setting of metadata size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '16',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of metadata size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Size of Total Posts And Followers', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the text size of total posts and followers which are displaying in header.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[show_bio]';
        /* Adding Setting of show or hide Bio*/
        $wp_customize->add_setting( $setting, array(
            'default'   => true,
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of show or hide Bio.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Show Bio', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Show or Hide Bio', 'my-instagram-feed' ),
            'type'        => 'checkbox',
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[bio_size]';
        /* Adding Setting of Bio size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '14',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Text Size of Bio', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the text size of bio.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_border_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#ccc',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Header Border Color', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the border color of header.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_border_style]';
        /* Adding Setting of number of columns if layout set to grid.*/
        $wp_customize->add_setting( $setting, array(
            'default'   => 'none',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of number of columns if layout set to grid.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Border Style', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the border style to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'select',
            'choices'     => array(
            'solid'  => 'Solid',
            'dashed' => 'Dashed',
            'dotted' => 'Dotted',
            'double' => 'Double',
            'groove' => 'Groove',
            'ridge'  => 'Ridge',
            'inset'  => 'Inset',
            'outset' => 'Outset',
            'none'   => 'None',
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_border_top]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '0',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Border Top', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the border size for top side to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_border_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '1',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Border Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the border size for bottom side to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_border_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '0',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Border Left', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the border size for left side to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_border_right]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '0',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Border Right', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the border size for right side to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_padding_top]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Top', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the padding for top side make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_padding_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the padding for bottom side to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_padding_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Left', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the padding for left side to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_padding_right]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Right', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Select the padding for right side to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[header_align]';
        /* Adding Setting of number of columns if layout set to grid.*/
        $wp_customize->add_setting( $setting, array(
            'default'   => 'left',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of number of columns if layout set to grid.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Align', 'my-instagram-feed' ),
            'section'     => 'mif_header',
            'settings'    => $setting,
            'description' => __( 'Show the header content in center left or right to make header look more nicer.', 'my-instagram-feed' ),
            'type'        => 'radio',
            'choices'     => array(
            'left'  => 'Left',
            'none'  => 'Center',
            'right' => 'Right',
        ),
        ) );
        //======================================================================
        // Feed section
        //======================================================================
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_background_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => 'transparent',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Background Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the Background color of feed.', 'my-instagram-feed' ),
        ) ) );
        /* Adding Control of pro options*/
        // $wp_customize->add_control( new Customize_MIF_PRO_PopUp( $wp_customize, 'mif_feed_image_filter_popup', array(
        //     'label'       => __( 'Image Filter', 'my-instagram-feed' ),
        //     'settings'    => array(),
        //     'section'     => 'mif_feed',
        //     'description' => __( "We're sorry, Image Filter feature is coming soon. Please signup to stay updated.", 'my-instagram-feed' ),
        //     'icon'        => 'color_lens',
        //     'popup_id'    => 'mif_feed_image_filter_popup',
        // ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_padding_top_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '5',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Top And Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding top and bottom of feed.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_padding_right_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '5',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Left And Right', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( "Select the padding left and right for feed i.e. doesn't work with Masonary layout.", 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_margin_top_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '5',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Margin Top And Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( "Select the margin top and bottom of feed i.e. doesn't work with Masonary layout.", 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_margin_right_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '5',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Margin Left And Right', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the margin left and right for feed.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Adding layout section in customizer under MIF panel.*/
        $wp_customize->add_section( 'mif_feed', array(
            'title'       => __( 'Feed', 'my-instagram-feed' ),
            'description' => __( 'Customize the Single Feed Design In Real Time', 'my-instagram-feed' ),
            'priority'    => 35,
            'panel'       => 'my_insta_feed_skins_panel',
        ) );
        /* Adding Control of pro options*/
        // $wp_customize->add_control( new Customize_MIF_PRO_PopUp( $wp_customize, 'mif_show_likes_popup', array(
        //     'label'       => __( 'Show Hearts of feeds', 'my-instagram-feed' ),
        //     'settings'    => array(),
        //     'section'     => 'mif_feed',
        //     'description' => __( "We're sorry, Show or hide hearts of feeds is coming soon. Please signup to stay updated.", 'my-instagram-feed' ),
        //     'icon'        => 'favorite_border',
        //     'popup_id'    => 'mif_show_likes_popup',
        // ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_likes_bg_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Likes Background Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the background color of likes.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_likes_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#ff',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Likes Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the color of likes.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_likes_padding_top_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '5',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Top And Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding top and bottom for likes.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_likes_padding_right_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Left And Right', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding left and right for likes.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Adding Control of pro options*/
        // $wp_customize->add_control( new Customize_MIF_PRO_PopUp( $wp_customize, 'mif_show_comments_popup', array(
        //     'label'       => __( 'Show Comments of feeds', 'my-instagram-feed' ),
        //     'settings'    => array(),
        //     'section'     => 'mif_feed',
        //     'description' => __( "We're sorry, Show or hide comments of feeds is coming soon. Please signup to stay updated.", 'my-instagram-feed' ),
        //     'icon'        => 'mode_comment',
        //     'popup_id'    => 'mif_show_comments_popup',
        // ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_comments_bg_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Comments Background Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the background color of comments.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_comments_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#fff',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Comments Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the color of comments.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_comments_padding_top_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '5',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Top And Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding top and bottom for comments.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_comments_padding_right_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Left And Right', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding left and right for comments.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Adding Control of pro options*/
        // $wp_customize->add_control( new Customize_MIF_PRO_PopUp( $wp_customize, 'mif_feed_caption_popup', array(
        //     'label'       => __( 'Show Feed Caption', 'my-instagram-feed' ),
        //     'settings'    => array(),
        //     'section'     => 'mif_feed',
        //     'description' => __( "We're sorry, Show or hide caption of feeds is coming soon. Please signup to stay updated.", 'my-instagram-feed' ),
        //     'icon'        => 'description',
        //     'popup_id'    => 'mif_feed_caption_popup',
        // ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_caption_background_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#fff',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Caption Background Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the background color of feed caption.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[caption_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Caption Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the feed caption color.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_caption_padding_top_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Top And Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding top and bottom for captions.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_caption_padding_right_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Left And Right', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding left and right for caption.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Adding Control of pro options*/
        // $wp_customize->add_control( new Customize_MIF_PRO_PopUp( $wp_customize, 'mif_feed_external_link_popup', array(
        //     'label'       => __( 'Show External Link', 'my-instagram-feed' ),
        //     'settings'    => array(),
        //     'section'     => 'mif_feed',
        //     'description' => __( "We're sorry, Show or external links is coming soon. Please signup to stay updated.", 'my-instagram-feed' ),
        //     'icon'        => 'link',
        //     'popup_id'    => 'mif_feed_external_link_popup',
        // ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_external_background_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'External Link Background Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the background color Of External Link Icon.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_external_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#fff',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'External Link Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the color Of External Link Icon.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_external_padding_top_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '8',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Top And Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding top and bottom for external link icon.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_external_padding_right_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Left And Right', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding left and right for external link icon.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Adding Control of pro options*/
        // $wp_customize->add_control( new Customize_MIF_PRO_PopUp( $wp_customize, 'mif_feed_open_popup_icon_popup', array(
        //     'label'       => __( 'Show Open PopUp Icon', 'my-instagram-feed' ),
        //     'settings'    => array(),
        //     'section'     => 'mif_feed',
        //     'description' => __( "We're sorry, Show or hide open popup icon is coming soon. Please signup to stay updated.", 'my-instagram-feed' ),
        //     'icon'        => 'add',
        //     'popup_id'    => 'mif_feed_open_popup_icon_popup',
        // ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[popup_icon_bg_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Open PopUp Icon background color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the icon background color which shows on feed hover for opening popup.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[popup_icon_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#fff',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Open PopUp Icon color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the icon color which shows on feed hover for opening popup.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_popup_icon_padding_top_bottom]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '8',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Top And Bottom', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding top and bottom for open popup icon.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_popup_icon_padding_right_left]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '10',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Padding Left And Right', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the padding left and right for open popup icon.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Adding Control of pro options*/
        // $wp_customize->add_control( new Customize_MIF_PRO_PopUp( $wp_customize, 'mif_feed_cta_popup', array(
        //     'label'       => __( 'Show Feed Call To Action Buttons', 'my-instagram-feed' ),
        //     'settings'    => array(),
        //     'section'     => 'mif_feed',
        //     'description' => __( "We're sorry, Show or hide call to action buttons is coming soon. Please signup to stay updated.", 'my-instagram-feed' ),
        //     'icon'        => 'favorite_border',
        //     'popup_id'    => 'mif_feed_cta_popup',
        // ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_cta_text_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Call To Action color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the color of links like(Share and View on Instagram).', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_cta_text_hover_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Call To Action Hover color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the hover color of links like(Share and View on Instagram).', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_time_text_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#000',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Feed Time Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the color of feed created time.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_hover_bg_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => 'rgba(0,0,0,0.5)',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new Customize_Alpha_Color_Control( $wp_customize, $setting, array(
            'label'        => __( 'Feed Hover Shadow Color', 'my-instagram-feed' ),
            'section'      => 'mif_feed',
            'description'  => __( 'Select the shadow color which shows on feed hover.', 'my-instagram-feed' ),
            'settings'     => $setting,
            'show_opacity' => true,
            'palette'      => array(
            'rgb(0, 0, 0)',
            'rgb(255, 255, 255)',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_seprator_color]';
        /* Adding Setting of Header text color*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '#ccc',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding Control of Header text color*/
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
            'label'       => __( 'Feed Seprator Color', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the color of feed Seprator.', 'my-instagram-feed' ),
        ) ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_border_size]';
        /* Adding Setting of title size*/
        $wp_customize->add_setting( $setting, array(
            'default'   => '1',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of title size.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Border Size', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the border size for feeds.', 'my-instagram-feed' ),
            'type'        => 'range',
            'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
        ) );
        /* Making settings dynamic and saving data with array.*/
        $setting = 'mif_skin_' . $skin_id . '[feed_border_style]';
        /* Adding Setting of number of columns if layout set to grid.*/
        $wp_customize->add_setting( $setting, array(
            'default'   => 'solid',
            'transport' => 'postMessage',
            'type'      => 'option',
        ) );
        /* Adding control of number of columns if layout set to grid.*/
        $wp_customize->add_control( $setting, array(
            'label'       => __( 'Border Style', 'my-instagram-feed' ),
            'section'     => 'mif_feed',
            'settings'    => $setting,
            'description' => __( 'Select the border style for feeds.', 'my-instagram-feed' ),
            'type'        => 'select',
            'choices'     => array(
            'solid'  => 'Solid',
            'dashed' => 'Dashed',
            'dotted' => 'Dotted',
            'double' => 'Double',
            'groove' => 'Groove',
            'ridge'  => 'Ridge',
            'inset'  => 'Inset',
            'outset' => 'Outset',
            'none'   => 'None',
        ),
        ) );
    }
    
    /* mif_customizer Method ends here. */
    /**
     * Used by hook: 'customize_preview_init'
     * 
     * @see add_action('customize_preview_init',$func)
     */
    public function mif_live_preview()
    {
        /* Getting saved skin id. */
        $skin_id = get_option( 'mif_skin_id', false );
        /* Enqueing script for displaying live changes. */
        wp_enqueue_script(
            'mif_live_preview',
            MIF_PLUGIN_URL . 'assets/js/mif_live_preview.js',
            array( 'jquery', 'customize-preview' ),
            true
        );
        /* Localizing script for getting skin id in js. */
        wp_localize_script( 'mif_live_preview', 'mif_skin_id', $skin_id );
    }
    
    /* mif_live_preview Method ends here. */
    /*
     * It will get the saved settings.
     */
    public function mif_get_settings( $key = null )
    {
        /*
         * Getting the options from database.
         */
        $mif_settings = get_option( 'mif_settings', false );
        if ( isset( $key ) ) {
            $mif_settings = $mif_settings[$key];
        }
        /*
         * Returning back the specific key values.
         */
        return $mif_settings;
    }

}
/* MIF_customizer Class ends here. */
$customizer_holder = new MIF_customizer();