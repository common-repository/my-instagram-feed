<?php
/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}


/*
* Returns the Instagram saved settings
*/
if(!function_exists('mif_get_setting')){

function mif_get_setting( $key = null ){
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

/*
* Returns the Instagram API type to get data
*/
if(!function_exists('mif_instagram_type')):

	function mif_instagram_type(){

		$mif_personal_connected_accounts = 'personal';

		$mif_settings = mif_get_setting();

        if(isset($mif_settings['selected_type'])){
        	$mif_personal_connected_accounts = $mif_settings['selected_type'];
        }else{
             
            if( isset($mif_settings['authenticated_accounts']['approved_pages']) && !empty($mif_settings['authenticated_accounts']['approved_pages']) ){
                $mif_has_business_insta = false;

                $approved_pages = $mif_settings['authenticated_accounts']['approved_pages'];

                if($approved_pages)
                foreach ($approved_pages as $key => $approved_page):
                    if( array_key_exists('instagram_connected_account', $approved_page) ) $mif_has_business_insta = true;
                endforeach;   

                 if($mif_has_business_insta){
                    $mif_personal_connected_accounts = 'business';
                 }   
            }

        }

        return $mif_personal_connected_accounts;
	}

endif;

/*
* Returns the personal Instagram accounts
*/
if(!function_exists('mif_personal_account')):

function mif_personal_account(){

		$mif_personal_connected_accounts = array();
        
        $authenticated_accounts = mif_get_setting('authenticated_accounts');

        if( isset($authenticated_accounts['instagram_connected_account']) && !empty($authenticated_accounts['instagram_connected_account']) ){
        	$mif_personal_connected_accounts = $authenticated_accounts['instagram_connected_account'];
        }

        return $mif_personal_connected_accounts;
	}
endif;	


/*
* Return Default account ID
*/
if(!function_exists('mif_default_id')){

function mif_default_id(){

       $mif_default_id = ''; 
       
       $mif_settings = mif_get_setting();

       $mif_instagram_type = mif_instagram_type();

       $mif_personal_account = mif_personal_account();

       if( isset($mif_instagram_type) && $mif_instagram_type == 'personal' && $mif_personal_account){

            foreach ($mif_personal_account as $personal_id => $mif_personal_connected_account){

                $mif_default_id = $personal_id;
            }    
       }else{

            $mif_business_accounts = array_reverse(mif_insta_business_accounts());
            if($mif_business_accounts){
                foreach ($mif_business_accounts as $mif_insta_single_account){

                    $mif_default_id = $mif_insta_single_account->id;

                } 
            }  
       }

        return $mif_default_id;
    }
} 

/*
* Return the business accounts
*/
if(!function_exists('mif_insta_business_accounts')){

function mif_insta_business_accounts(){

        $mif_insta_business_accounts = array();
        
        $authenticated_accounts = mif_get_setting('authenticated_accounts');

        if( isset($authenticated_accounts['approved_pages']) && !empty($authenticated_accounts['approved_pages']) ){
            $mif_business_accounts = $authenticated_accounts['approved_pages'];

            if($mif_business_accounts){
                foreach ($mif_business_accounts as $key => $mif_business_account) {

                    if( isset( $mif_business_account['instagram_connected_account'] ) && $mif_business_account['instagram_connected_account']->id ){

                        $mif_insta_business_accounts[$key] = $mif_business_account['instagram_connected_account'];

                    }
                  
                }
            }

            
        }

        return $mif_insta_business_accounts;
    }
}

/*
* Return the business Instagram accounts
*/
if(!function_exists('mif_business_accounts')){

function mif_business_accounts(){

        $mif_business_accounts = array();
        
        $authenticated_accounts = mif_get_setting('authenticated_accounts');

        if( isset($authenticated_accounts['approved_pages']) && !empty($authenticated_accounts['approved_pages']) ){
            $mif_business_accounts = $authenticated_accounts['approved_pages'];
        }

        return $mif_business_accounts;
    }
}

/*
* Return the default business Instagram accounts
*/
if(!function_exists('mif_default_business_id')){

function mif_default_business_id(){

        $mif_business_user_id = '';
        
        $authenticated_accounts = mif_get_setting('authenticated_accounts');

        if( isset($authenticated_accounts['approved_pages']) && !empty($authenticated_accounts['approved_pages']) ){

            $approved_pages = $authenticated_accounts['approved_pages'];

            if(isset($approved_pages[array_keys($approved_pages)['0']]['instagram_accounts']->connected_instagram_account->id)):

             $mif_business_user_id = $approved_pages[array_keys($approved_pages)['0']]['instagram_accounts']->connected_instagram_account->id;

            endif;
        }

        return $mif_business_user_id;
    }
}

/*
* Convert hashtag to Instgram hashtag link
*/
if(!function_exists('mif_convert_to_hashtag')){

function mif_convert_to_hashtag($hashtag) {

    $regex = "/#+([a-zA-Z0-9_]+)/";

    $hashtag = preg_replace($regex, '<a target="_blank" href="https://www.instagram.com/explore/tags/$1">$0</a>', $hashtag);

    return($hashtag);
}

}

if ( ! function_exists( 'mif_demo_page_id' ) ) {
	/**
	 * Get Customizer demo page ID
	 *
	 * @return mixed|string
	 * @since 1.1.0
	 */
	function mif_demo_page_id() {
		$demo_page_id = '';

		return mif_get_setting('mif_page_id');
	}
}

if ( ! function_exists( 'mif_readable_count' ) ) {

	function mif_readable_count( $number ) {

		$number = number_format( $number );

		$number_count = substr_count( $number, ',' );

		if ( $number_count != '0' ) {

			if ( $number_count == '1' ) {

				return substr( $number, 0, - 4 ) . __( 'K', 'easy-facebook-likebox' );

			} else if ( $number_count == '2' ) {

				return substr( $number, 0, - 8 ) . __( 'M', 'easy-facebook-likebox' );

			} else if ( $number_count == '3' ) {

				return substr( $number, 0, - 12 ) . __( 'B', 'easy-facebook-likebox' );

			} else {

				return;
			}

		} else {
			return $number;
		}
	}
}

if ( ! function_exists( 'mif_readable_time' ) ) {
	function mif_readable_time( $date, $granularity = 2 ) {

		$date_time_strings = [
			"second"  => __( 'second', 'easy-facebook-likebox' ),
			"seconds" => __( 'seconds', 'easy-facebook-likebox' ),
			"minute"  => __( 'minute', 'easy-facebook-likebox' ),
			"minutes" => __( 'minutes', 'easy-facebook-likebox' ),
			"hour"    => __( 'hour', 'easy-facebook-likebox' ),
			"hours"   => __( 'hours', 'easy-facebook-likebox' ),
			"day"     => __( 'day', 'easy-facebook-likebox' ),
			"days"    => __( 'days', 'easy-facebook-likebox' ),
			"week"    => __( 'week', 'easy-facebook-likebox' ),
			"weeks"   => __( 'weeks', 'easy-facebook-likebox' ),
			"month"   => __( 'month', 'easy-facebook-likebox' ),
			"months"  => __( 'months', 'easy-facebook-likebox' ),
			"year"    => __( 'year', 'easy-facebook-likebox' ),
			"years"   => __( 'years', 'easy-facebook-likebox' ),
			"decade"  => __( 'decade', 'easy-facebook-likebox' ),
		];

		$ago_text = __( 'ago', 'easy-facebook-likebox' );

		$date = strtotime( $date );

		$difference = time() - $date;

		$periods = [
			'decade' => 315360000,
			'year'   => 31536000,
			'month'  => 2628000,
			'week'   => 604800,
			'day'    => 86400,
			'hour'   => 3600,
			'minute' => 60,
			'second' => 1,
		];

		foreach ( $periods as $key => $value ) {

			if ( $difference >= $value ) {
				$time       = floor( $difference / $value );
				$difference %= $value;
				$retval     .= ( $retval ? ' ' : '' ) . $time . ' ';
				$retval     .= ( ( $time > 1 ) ? $date_time_strings[ $key . 's' ] : $date_time_strings[ $key ] );
				$granularity --;
			}
			if ( $granularity == '0' ) {
				break;
			}
		}

		return '' . $retval . ' ' . $ago_text;
	}
}

if ( ! function_exists( 'mif_makeClickableLinks' ) ) {
	function mif_makeClickableLinks(
		$value, $protocols = [
		'http',
		'mail',
		'https',
	], array $attributes = []
	) {
		// Link attributes
		$attr = '';
		foreach ( $attributes as $key => $val ) {
			$attr .= ' ' . $key . '="' . htmlentities( $val ) . '"';
		}

		$links = [];

		// Extract existing links and tags
		$value = preg_replace_callback( '~(<a .*?>.*?</a>|<.*?>)~i', function ( $match ) use ( &$links ) {
			return '<' . array_push( $links, $match[1] ) . '>';
		}, $value );

		// Extract text links for each protocol
		foreach ( (array) $protocols as $protocol ) {
			switch ( $protocol ) {
				case 'http':
				case 'https':
					$value = preg_replace_callback( '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ( $match ) use ( $protocol, &$links, $attr ) {
						if ( $match[1] ) {
							$protocol = $match[1];
						}
						$link = $match[2] ?: $match[3];

						return '<' . array_push( $links, "<a $attr href=\"$protocol://$link\">$link</a>" ) . '>';
					}, $value );
					break;
				case 'mail':
					$value = preg_replace_callback( '~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ( $match ) use ( &$links, $attr ) {
						return '<' . array_push( $links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>" ) . '>';
					}, $value );
					break;
				case 'twitter':
					$value = preg_replace_callback( '~(?<!\w)[@#](\w++)~', function ( $match ) use ( &$links, $attr ) {
						return '<' . array_push( $links, "<a $attr href=\"https://twitter.com/" . ( $match[0][0] == '@' ? '' : 'search/%23' ) . $match[1] . "\">{$match[0]}</a>" ) . '>';
					}, $value );
					break;
				default:
					$value = preg_replace_callback( '~' . preg_quote( $protocol, '~' ) . '://([^\s<]+?)(?<![\.,:])~i', function ( $match ) use ( $protocol, &$links, $attr ) {
						return '<' . array_push( $links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>" ) . '>';
					}, $value );
					break;
			}
		}

		// Insert all link
		return preg_replace_callback( '/<(\d+)>/', function ( $match ) use ( &$links ) {
			return $links[ $match[1] - 1 ];
		}, $value );
	}
}

if ( ! function_exists( 'mif_get_cache_seconds' ) ) {

	/**
	 * Convert cache time span into seconds
	 * @param $instance
	 *
	 * @since 6.1.0
	 *
	 * @return false|float|int
	 */
	function mif_get_cache_seconds( $instance ) {

		if( !isset( $instance ) && !is_array( $instance ) ) return false;

		if( !isset( $instance['cache_unit'] ) || $instance['cache_unit'] < 1) {
			$cache_unit = 1;
		}
		//Calculate the cache time in seconds
		if($instance['cache_duration'] == 'minutes') {
			$cache_duration = 60;
		}
		if($instance['cache_duration'] == 'hours') {
			$cache_duration = 60*60;
		}
		if($instance['cache_duration'] == 'days') {
			$cache_duration = 60*60*24;
		}

		$cache_seconds = $cache_duration * $instance['cache_unit'];

		return $cache_seconds;
	}
}