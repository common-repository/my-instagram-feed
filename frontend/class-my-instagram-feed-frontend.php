<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'My_Instagram_Feed_Frontend' ) ) {
    class My_Instagram_Feed_Frontend
    {
        public  $instagram_url = 'https://www.instagram.com' ;
        function __construct()
        {
            add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
            add_shortcode( 'my-instagram-feed', [ $this, 'register_shortcode' ] );
            add_action( 'wp_ajax_my-instagram-feed-customizer-style', [ $this, 'load_customizer_css' ] );
            add_action( 'wp_ajax_nopriv_my-instagram-feed-customizer-style', [ $this, 'load_customizer_css' ] );
        }
        
        public function register_scripts()
        {
            wp_enqueue_style( 'esf-custom-fonts', MIF_PLUGIN_URL . 'frontend/assets/css/esf-custom-fonts.css', [] );
            wp_enqueue_script( 'imagesloaded.pkgd.min', MIF_PLUGIN_URL . 'frontend/assets/js/imagesloaded.pkgd.min.js' );
            wp_enqueue_style( 'my-instagram-feed-frontend', MIF_PLUGIN_URL . 'frontend/assets/css/my-instagram-feed-frontend.css' );
            wp_enqueue_style( 'my-instagram-feed-customizer-style', admin_url( 'admin-ajax.php' ) . '?action=my-instagram-feed-customizer-style', 'my-instagram-feed-frontend' );
            $mif_ver = 'free';
            wp_enqueue_script(
                'my-instagram-feed-frontend',
                MIF_PLUGIN_URL . 'frontend/assets/js/my-instagram-feed-frontend.js',
                [ 'jquery' ],
                true
            );
            wp_localize_script( 'my-instagram-feed-frontend', 'mif', [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'version'  => $mif_ver,
                'nonce'    => wp_create_nonce( 'my-instagram-frontend-ajax-nonce' ),
            ] );
        }
        
        public function load_customizer_css()
        {
            header( "Content-type: text/css; charset: UTF-8" );
            require MIF_PLUGIN_DIR . 'frontend/assets/css/my-instagram-feed-customizer-style.css.php';
            exit;
        }
        
        /*
         * register_shortcode is the callback func of add_shortcode.
         * Will add the shortcode in wp.
         */
        public function register_shortcode( $atts )
        {
            $mif_skin_default_id = '';
            $mif_settings = mif_get_setting();
            if ( isset( $mif_settings['mif_skin_default_id'] ) ) {
                $mif_skin_default_id = $mif_settings['mif_skin_default_id'];
            }
            $atts = shortcode_atts( [
                'wrapper_class'  => null,
                'user_id'        => null,
                'hashtag'        => null,
                'skin_id'        => $mif_skin_default_id,
                'feeds_per_page' => 9,
                'caption_words'  => 25,
                'links_new_tab'  => 1,
                'load_more'      => 1,
                'cache_unit'     => 1,
                'cache_duration' => 'days',
            ], $atts, 'my-instagram-feed' );
            if ( isset( $atts ) ) {
                extract( $atts );
            }
            ob_start();
            include 'views/feed.php';
            $returner = ob_get_contents();
            ob_end_clean();
            return $returner;
        }
        
        /*
         * It will get the remote URL, Retreive it and return decoded data.
         */
        public function mif_get_data( $url )
        {
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
        
        /*
         * It will get current item number and feeds per page, Return the data accordingly.
         */
        public function mif_get_feeds(
            $feeds_per_page = 9,
            $current_item = 0,
            $cache_seconds = 3600,
            $user_id = null,
            $hashtag = null,
            $test_mode = false
        )
        {
            $fta_settings = mif_get_setting();
            $mif_authenticated_accounts = $fta_settings['authenticated_accounts'];
            $mif_instagram_type = mif_instagram_type();
            $approved_pages = [];
            $decoded_data_pag = null;
            $test_mode = apply_filters( 'mif_disable_cache', $test_mode );
            if ( isset( $mif_authenticated_accounts['approved_pages'] ) && !empty($mif_authenticated_accounts['approved_pages']) ) {
                $approved_pages = $mif_authenticated_accounts['approved_pages'];
            }
            if ( $approved_pages ) {
                foreach ( $approved_pages as $key => $approved_page ) {
                    if ( isset( $approved_page['instagram_connected_account']->id ) ) {
                        if ( $approved_page['instagram_connected_account']->id == $user_id ) {
                            $access_token = $approved_page['access_token'];
                        }
                    }
                }
            }
            $self_decoded_data = $this->mif_get_bio( $user_id );
            $mif_user_slug = "mif_user_posts-{$user_id}-{$feeds_per_page}-{$mif_instagram_type}";
            $decoded_data = get_transient( $mif_user_slug );
            $mif_all_feeds = null;
            if ( isset( $self_decoded_data->media_count ) && !empty($self_decoded_data->media_count) ) {
                $mif_all_feeds = $self_decoded_data->media_count;
            }
            
            if ( !$decoded_data || '' == $decoded_data ) {
                // if ( $mif_all_feeds > 0 ) {
                $mif_personal_connected_accounts = mif_personal_account();
                
                if ( mif_instagram_type() == 'personal' && isset( $mif_personal_connected_accounts ) && !empty($mif_personal_connected_accounts) && is_array( $mif_personal_connected_accounts ) ) {
                    $access_token = $mif_personal_connected_accounts[$user_id]['access_token'];
                    $remote_url = "https://graph.instagram.com/{$user_id}/media?fields=media_url,thumbnail_url,caption,id,media_type,timestamp,username,comments_count,like_count,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}&limit={$feeds_per_page}&access_token=" . $access_token;
                } else {
                    $remote_url = "https://graph.facebook.com/v4.0/{$user_id}/media?fields=thumbnail_url,children{permalink,thumbnail_url,media_url,media_type},media_type,caption,comments_count,id,ig_id,like_count,is_comment_enabled,media_url,owner,permalink,shortcode,timestamp,username,comments{id,hidden,like_count,media,text,timestamp,user,username,replies{hidden,id,like_count,media,text,timestamp,user,username}}&limit=" . $feeds_per_page . "&access_token=" . $access_token;
                }
                
                $decoded_data = $this->mif_get_data( $remote_url );
                
                if ( !isset( $decoded_data->error ) && !empty($decoded_data->data) ) {
                    $decoded_data = (object) [
                        'pagination' => $decoded_data->paging->next,
                        'data'       => $decoded_data->data,
                    ];
                    if ( !$test_mode ) {
                        set_transient( $mif_user_slug, $decoded_data, $cache_seconds );
                    }
                }
            
            }
            
            // }
            
            if ( !empty($current_item) or !empty($feeds_per_page) ) {
                if ( isset( $decoded_data->pagination ) && !empty($decoded_data->pagination) ) {
                    $decoded_data_pag = $decoded_data->pagination;
                }
                if ( isset( $decoded_data->data ) && !empty($decoded_data->data) ) {
                    $decoded_data = array_slice( $decoded_data->data, $current_item, $feeds_per_page );
                }
                $decoded_data = (object) [
                    'pagination' => $decoded_data_pag,
                    'data'       => $decoded_data,
                ];
            }
            
            return $decoded_data;
        }
        
        /*
         *  Return the bio of Instagram user.
         */
        public function mif_get_bio( $user_id = null )
        {
            $fta_settings = mif_get_setting();
            $mif_authenticated_accounts = $fta_settings['authenticated_accounts'];
            $approved_pages = [];
            if ( isset( $mif_authenticated_accounts['approved_pages'] ) && !empty($mif_authenticated_accounts['approved_pages']) ) {
                $approved_pages = $mif_authenticated_accounts['approved_pages'];
            }
            $mif_instagram_type = mif_instagram_type();
            if ( $approved_pages ) {
                foreach ( $approved_pages as $key => $approved_page ) {
                    if ( isset( $approved_page['instagram_connected_account']->id ) ) {
                        if ( $approved_page['instagram_connected_account']->id == $user_id ) {
                            $access_token = $approved_page['access_token'];
                        }
                    }
                }
            }
            $mif_bio_slug = "mif_user_bio_{$mif_instagram_type}-{$user_id}";
            $self_decoded_data = get_transient( $mif_bio_slug );
            
            if ( !$self_decoded_data || '' == $self_decoded_data ) {
                $mif_personal_connected_accounts = mif_personal_account();
                
                if ( mif_instagram_type() == 'personal' && isset( $mif_personal_connected_accounts ) && !empty($mif_personal_connected_accounts) && is_array( $mif_personal_connected_accounts ) ) {
                    $access_token = $mif_personal_connected_accounts[$user_id]['access_token'];
                    $mif_bio_url = "https://graph.instagram.com/me?fields=id,username,media_count,account_type&access_token=" . $access_token;
                } else {
                    $mif_bio_url = "https://graph.facebook.com/v4.0/{$user_id}/?fields=biography,followers_count,follows_count,id,ig_id,media_count,name,profile_picture_url,username,website&access_token=" . $access_token;
                }
                
                /*
                 * Getting the decoded data of authenticated user from instagram.
                 */
                $self_decoded_data = $this->mif_get_data( $mif_bio_url );
                if ( 400 !== $self_decoded_data->meta->code && !isset( $self_decoded_data->error ) ) {
                    set_transient( $mif_bio_slug, $self_decoded_data, $cache_seconds );
                }
            }
            
            return $self_decoded_data;
        }
    
    }
    new My_Instagram_Feed_Frontend();
}
