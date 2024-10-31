<?php

/**
 * Admin View: Tab - How to Use
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global  $my_instagram_feed_skins ;
$mif_settings = mif_get_setting();
if ( $mif_settings['authenticated_accounts'] ) {
    $mif_users = $mif_settings['authenticated_accounts'];
}
if ( isset( $mif_settings['access_token'] ) ) {
    $fb_access_token = $mif_settings['access_token'];
}
?>
<div id="mif-shortcode" class="col s12 mif_tab_c slideLeft">
    <div class="mif-swipe-shortcode_wrap">

        <div class="mif_shortocode_genrator_wrap">

            <h5><?php 
esc_html_e( "How to use this plugin?", 'my-instagram-feed' );
?></h5>

	        <?php 

if ( isset( $mif_settings['authenticated_accounts']['approved_pages'] ) && !empty($mif_settings['authenticated_accounts']['approved_pages']) || mif_instagram_type() == 'personal' ) {
    ?>

                <p><?php 
    esc_html_e( "Copy and paste the following shortcode in any page, post or text widget to display the feed.", 'my-instagram-feed' );
    ?></p>
                <blockquote class="mif-shortcode-block">[my-instagram-feed
                    user_id="<?php 
    echo  mif_default_id() ;
    ?>"
                    skin_id="<?php 
    echo  $mif_settings['mif_skin_default_id'] ;
    ?>"
                    links_new_tab="1"
                    ]
                </blockquote>
                <a class="btn waves-effect mif_copy_shortcode waves-light tooltipped"
                   data-position="right" data-delay="50"
                   data-tooltip="<?php 
    esc_html_e( "Copy", 'my-instagram-feed' );
    ?>"
                   data-clipboard-text='[my-instagram-feed user_id="<?php 
    echo  mif_default_id() ;
    ?>" skin_id="<?php 
    echo  $mif_settings['mif_skin_default_id'] ;
    ?>" links_new_tab="1"]'
                   href="javascript:void(0);"><i class="material-icons right">content_copy</i>
                </a>

			<?php 
} else {
    ?>

                <blockquote
                        class="efbl-red-notice"><?php 
    esc_html_e( 'It looks like you have not connected your Instagram account with plugin yet. Please click on the Connect "My Instagram Account" button from authenticate tab to get the access token from Instagram', 'my-instagram-feed' );
    ?></blockquote>

			<?php 
}

?>

            <h5><?php 
esc_html_e( "Need More Options?", 'my-instagram-feed' );
?></h5>
            <p><?php 
esc_html_e( "Use the following shortcode generator to further customize the shortcode.", 'my-instagram-feed' );
?></p>
            <form class="mif_shortocode_genrator" name="mif_shortocode_genrator"
                  type="post">
                <div class="row">

                    <div class="input-field col s12 mif_fields">

                        <select id="mif_user_id" class="icons mif_skin_id"  <?php 
do_action( 'esf_insta_page_attr' );
?>>
							<?php 
$mif_personal_connected_accounts = mif_personal_account();

if ( mif_instagram_type() == 'personal' && !empty($mif_personal_connected_accounts) ) {
    $i = 0;
    foreach ( $mif_personal_connected_accounts as $personal_id => $mif_personal_connected_account ) {
        $i++;
        ?>
                                    <option value="<?php 
        echo  $personal_id ;
        ?>" <?php 
        if ( $i == 1 ) {
            ?> selected <?php 
        }
        ?> ><?php 
        echo  $mif_personal_connected_account['username'] ;
        ?></option>

								<?php 
    }
}

$mif_business_accounts = mif_insta_business_accounts();
if ( mif_instagram_type() != 'personal' && $mif_business_accounts ) {
    
    if ( $mif_business_accounts ) {
        $i = 0;
        foreach ( $mif_business_accounts as $mif_insta_single_account ) {
            $i++;
            ?>
                                        <option value="<?php 
            echo  $mif_insta_single_account->id ;
            ?>"
                                                data-icon="<?php 
            echo  $mif_insta_single_account->profile_picture_url ;
            ?>" <?php 
            if ( $i == 1 ) {
                ?> selected <?php 
            }
            ?>><?php 
            echo  $mif_insta_single_account->username ;
            ?></option>
									<?php 
        }
    } else {
        ?>

                                    <option value="" disabled
                                            selected><?php 
        esc_html_e( "No accounts found, Please connect your Instagram account with plugin first", 'my-instagram-feed' );
        ?></option>
								<?php 
    }

}
?>


                        </select>
                        <label><?php 
esc_html_e( "Account(s)", 'my-instagram-feed' );
?></label>
                    </div>

<!--                    <div class="input-field col s12 mif_fields esf-insta-addon-upgrade-link">-->
<!--		                --><?php 
//if( !class_exists('Esf_Multifeed_Instagram_Frontend') ){
?>
<!--                            <a href="--><?php 
//echo esc_url( admin_url('admin.php?slug=esf-multifeed&page=feed-them-all-addons') );
?><!--">--><?php 
//esc_html_e( "Multifeed Add-on: Display photos and videos from multiple accounts (even not owned by you - yes works with hashtag as well) in single feed (pro- addon)", 'my-instagram-feed' );
?><!--</a>-->
<!--		                --><?php 
//}
?>
<!--                    </div>-->

					<?php 

if ( mif_fs()->is_free_plan() ) {
    ?>
                        <span class="form_fields col s12">
                        <input name="" class="modal-trigger" href="#esf-insta-hashtag-upgrade"
                               type="checkbox" id="esf-insta-free-hashtag"/>
                        <label for="esf-insta-free-hashtag"><?php 
    esc_html_e( "Hashtag", 'my-instagram-feed' );
    ?></label>
                    </span>

                        <div id="esf-insta-hashtag-upgrade"
                             class="fta-upgrade-modal modal">
                            <div class="modal-content">

                                <div class="mif-modal-content"><span
                                            class="mif-lock-icon"><i
                                                class="material-icons dp48">lock_outline</i> </span>
                                    <h5><?php 
    esc_html_e( "Premium Feature", 'my-instagram-feed' );
    ?></h5>
                                    <p><?php 
    esc_html_e( "We're sorry, Hashtag feature is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", 'my-instagram-feed' );
    ?></p>
                                    <p><?php 
    esc_html_e( 'Upgrade today and get a 10% discount! On the checkout click on "Have a promotional code?" and enter', 'my-instagram-feed' );
    ?>
                                        <code>mif10</code></p>
                                    <hr/>
                                    <a href="<?php 
    echo  esc_url( mif_fs()->get_upgrade_url() ) ;
    ?>"
                                       class="waves-effect waves-light btn"><i
                                                class="material-icons right">lock_open</i><?php 
    esc_html_e( "Upgrade now", 'my-instagram-feed' );
    ?>
                                    </a>

                                </div>
                            </div>

                        </div>

					<?php 
}

?>

                    <div class="input-field col s12 mif_fields">
                        <select id="mif_skin_id" class="icons mif_skin_id">
							<?php 
if ( $my_instagram_feed_skins ) {
    foreach ( $my_instagram_feed_skins as $mif_skin ) {
        ?>

                                    <option value="<?php 
        echo  $mif_skin['ID'] ;
        ?>"
                                            data-icon="<?php 
        echo  get_the_post_thumbnail_url( $mif_skin['ID'], 'thumbnail' ) ;
        ?>"><?php 
        echo  $mif_skin['title'] ;
        ?></option>
								<?php 
    }
}

if ( mif_fs()->is_free_plan() ) {
    ?>

                                <option value="free-masonry"><?php 
    esc_html_e( "Skin - Masonry (pro)", 'my-instagram-feed' );
    ?></option>
                                <option value="free-carousel"><?php 
    esc_html_e( "Skin - Carousel (pro)", 'my-instagram-feed' );
    ?></option>
                                <option value="free-half_width"><?php 
    esc_html_e( "Skin - Half Width (pro)", 'my-instagram-feed' );
    ?></option>
                                <option value="free-full_width"><?php 
    esc_html_e( "Skin - Full Width (pro)", 'my-instagram-feed' );
    ?></option>
							<?php 
}

?>

                        </select>
                        <label><?php 
esc_html_e( "Select skin and layout", 'my-instagram-feed' );
?></label>
                    </div>

                    <div class="input-field col s6 mif_fields">
                        <input id="mif_feeds_per_page" type="number" value="9"
                               min="1">
                        <label for="mif_feeds_per_page"
                               class=""><?php 
esc_html_e( "Feeds Per Page", 'my-instagram-feed' );
?></label>
                    </div>

                    <div class="input-field col s6 mif_fields">
                        <input id="mif_caption_words" type="number" value="25"
                               min="1">
                        <label for="mif_caption_words"
                               class=""><?php 
esc_html_e( "Caption Words (Leave empty to show full text)", 'my-instagram-feed' );
?></label>
                    </div>


                    <div class="input-field col s6 mif_fields">
                        <input id="mif_cache_unit" type="number" value="1"
                               min="1">
                        <label for="mif_cache_unit"
                               class=""><?php 
esc_html_e( "Cache Unit", 'my-instagram-feed' );
?></label>
                    </div>

                    <div class="input-field col s6 mif_fields">
                        <select id="mif_cache_duration"
                                class="icons mif_cache_duration">
                            <option value="minutes"><?php 
esc_html_e( "Minutes", 'my-instagram-feed' );
?></option>
                            <option value="hours"><?php 
esc_html_e( "Hours", 'my-instagram-feed' );
?></option>
                            <option value="days" selected><?php 
esc_html_e( "Days", 'my-instagram-feed' );
?></option>
                        </select>
                        <label><?php 
esc_html_e( "Cache Duration", 'my-instagram-feed' );
?></label>
                    </div>

                    <div class="input-field col s12 mif_fields">
                        <input id="mif_wrap_class" type="text">
                        <label for="mif_wrap_class"
                               class=""><?php 
esc_html_e( "Wrapper Class", 'my-instagram-feed' );
?></label>
                    </div>
                    <?php 

if ( mif_fs()->is_free_plan() ) {
    ?>
                        <div class="col s6 mif_fields esf_insta_checkbox">
                            <input name="esf_insta_load_more_free"
                                   type="checkbox" class="filled-in modal-trigger" href="#esf-insta-load-more-upgrade"
                                   value="" id="esf_insta_load_more_free"/>
                            <label for="esf_insta_load_more_free"><?php 
    esc_html_e( "Load More", 'my-instagram-feed' );
    ?></label>
                        </div>

                    <div id="esf-insta-load-more-upgrade"
                         class="fta-upgrade-modal modal">
                        <div class="modal-content">

                            <div class="mif-modal-content"><span
                                        class="mif-lock-icon"><i
                                            class="material-icons dp48">lock_outline</i> </span>
                                <h5><?php 
    esc_html_e( "Premium Feature", 'my-instagram-feed' );
    ?></h5>
                                <p><?php 
    esc_html_e( "We're sorry, Load more is not included in your plan. Add load more button at the bottom of each feed to load more photos and videos.", 'my-instagram-feed' );
    ?></p>
                                <p><?php 
    esc_html_e( 'Upgrade today and get a 10% discount! On the checkout click on "Have a promotional code?" and enter', 'my-instagram-feed' );
    ?>
                                    <code>mif10</code></p>
                                <hr/>
                                <a href="<?php 
    echo  esc_url( mif_fs()->get_upgrade_url() ) ;
    ?>"
                                   class="waves-effect waves-light btn"><i
                                            class="material-icons right">lock_open</i><?php 
    esc_html_e( "Upgrade now", 'my-instagram-feed' );
    ?>
                                </a>

                            </div>
                        </div>

                    </div>

                    <?php 
}

?>

                    <div class="col s6 mif_fields esf_insta_checkbox">
                        <input name="esf_insta_link_new_tab"
                               type="checkbox" checked class="filled-in"
                               value="" id="esf_insta_link_new_tab"/>
                        <label for="esf_insta_link_new_tab"><?php 
esc_html_e( "Open links in new tab", 'my-instagram-feed' );
?></label>
                    </div>
                    <br>
                    <div class="clear"></div>
                    <input type="submit" class="btn  mif_shortcode_submit"
                           value="<?php 
esc_html_e( "Generate", 'my-instagram-feed' );
?>"/>
                </div>
            </form>
            <div class="mif_generated_shortcode">
                <blockquote class="mif-shortcode-block"></blockquote>
                <a class="btn waves-effect mif_copy_shortcode mif_shortcode_generated_final waves-light tooltipped"
                   data-position="right" data-delay="50"
                   data-tooltip="<?php 
esc_html_e( "Copy", 'my-instagram-feed' );
?>"
                   href="javascript:void(0);"><i class="material-icons right">content_copy</i>
                </a>
            </div>
        </div>

        <h5><?php 
esc_html_e( "Unable to understand shortcode parameters?", 'my-instagram-feed' );
?></h5>
        <p><?php 
esc_html_e( "No worries, Each shortcode parameter is explained below first read them and generate your shortocde.", 'my-instagram-feed' );
?></p>


        <ul class="collapsible" data-collapsible="accordion">
            <li>
                <div class="collapsible-header">
                    <span class="mif_detail_head"> <?php 
esc_html_e( "Wrapper Class", 'my-instagram-feed' );
?> </span>
                </div>
                <div class="collapsible-body">
                    <p><?php 
esc_html_e( "You can easily add the custom CSS class to the wraper of your Instagram Feeds.", 'my-instagram-feed' );
?></p>
                </div>
            </li>


            <li>
                <div class="collapsible-header">
                    <span class="mif_detail_head"> <?php 
esc_html_e( "Accounts", 'my-instagram-feed' );
?></span>
                </div>
                <div class="collapsible-body">
                    <p><?php 
esc_html_e( "You can display any of the connected account feeds. Select the account you wish to display the feeds from list.", 'my-instagram-feed' );
?></p>
                </div>
            </li>

            <li>
                <div class="collapsible-header">
                    <span class="mif_detail_head"> <?php 
esc_html_e( "Skin", 'my-instagram-feed' );
?></span>
                </div>
                <div class="collapsible-body">
                    <p><?php 
esc_html_e( "You can totally change the look and feel of your feeds section. Simply paste the Skin ID in skin_id parameter. You can find the skins in Dashboard -> My Instagram Feeds -> Skins section.", 'my-instagram-feed' );
?></p>
                </div>
            </li>

            <li>
                <div class="collapsible-header">
                    <span class="mif_detail_head"> <?php 
esc_html_e( "Feeds Per Page", 'my-instagram-feed' );
?></span>
                </div>
                <div class="collapsible-body">
                    <p><?php 
esc_html_e( "You can show only specific feeds. Simply paste the Feeds Per Page number in feeds_per_page parameter.", 'my-instagram-feed' );
?></p>
                </div>
            </li>

            <li>
                <div class="collapsible-header">
                    <span class="mif_detail_head"><?php 
esc_html_e( "Caption Words", 'my-instagram-feed' );
?></span>
                </div>
                <div class="collapsible-body">
                    <p><?php 
esc_html_e( "You can show limited caption words. Simply paste the Caption Words number in caption_words parameter.", 'my-instagram-feed' );
?></p>
                </div>
            </li>

            <li>
                <div class="collapsible-header">
                    <span class="mif_detail_head"> <?php 
esc_html_e( "Cache Unit", 'my-instagram-feed' );
?></span>
                </div>
                <div class="collapsible-body">
                    <p><?php 
esc_html_e( "Feeds Will be automatically refreshed after particular minutes/hours/days. Simply paste the number of days in cache_unit parameter.", 'my-instagram-feed' );
?></p>
                </div>
            </li>

            <li>
                <div class="collapsible-header">
                    <span class="mif_detail_head"><?php 
esc_html_e( "Cache Duration", 'my-instagram-feed' );
?></span>
                </div>
                <div class="collapsible-body">
                    <p><?php 
esc_html_e( "Define cache duration to refresh feeds automatically. Like after minutes/hours/days feeds would be refreshed. Simply paste the duration option in cache_duration parameter", 'my-instagram-feed' );
?></p>
                </div>
            </li>

            <li>
                <div class="collapsible-header">
                    <span class="mif_detail_head"><?php 
esc_html_e( "Load More", 'my-instagram-feed' );
?> <a href="<?php 
echo  esc_url( mif_fs()->get_upgrade_url() ) ;
?>">(<?php 
esc_html_e( "pro", 'my-instagram-feed' );
?>)</a> </span>
                </div>
                <div class="collapsible-body">
                    <p><?php 
esc_html_e( "Load More button at the bottom of each feed to infinitely load more posts, events, photos, videos, or albums.", 'my-instagram-feed' );
?></p>
                </div>
            </li>

        </ul>

    </div>
</div>