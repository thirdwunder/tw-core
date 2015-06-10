<?php
/**
 * Wordpress General helper functions
 *
 */

/**
 * Returns color hex from rgb array values
 * @param  array  rgb
 * @return string hex
 */
if(!function_exists('tw_rgb2hex')){
  function tw_rgb2hex($rgb) {
     $hex = "#";
     $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
     $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
     $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

     return $hex;
  }
}

/**
 * Returns color rgba array hex string value
 * @param  string hex
 * @return array rgb
 */
if(!function_exists('tw_hex2rgb')){
  function tw_hex2rgb($hex) {
     $hex = str_replace("#", "", $hex);

     if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
     } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
     }
     $rgb = array($r, $g, $b);
     return $rgb;
  }
}

/**
 * Returns a base domain from a full url
 * @param  string $url
 * @return string $domain
 */
if(!function_exists('tw_url2domain')){
  function tw_url2domain($url){
    $domain = preg_replace('#^https?://#', '', $url);
    $domain = preg_replace('#^www.#','',$url);
    return $domain;
  }
}

/**
 * Returns a clean phone number value with parenthesis, dashes or dots
 * @param  string $phone
 * @return string $phone
 */
if(!function_exists('tw_clean_phone_number')){
  function tw_clean_phone_number($phone){
    return preg_replace('~[\W\s]~', "", $phone);
  }
}

/**
 * Returns a clean string with no hyphens or special characters
 * @param  string $string
 * @return string $string
 */
if(!function_exists('tw_clean_string')){
  function tw_clean_string($string) {
     $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
  }
}

/**
 * Returns an abbereviated name without prefixes or postfixes
 * @param  string $name
 * @return string $abbreviated
 */
if(!function_exists('tw_abbreviate_name')){
  function tw_abbreviate_name($name){
    $prefixes = array('Ms','Miss','Mrs','Mr','Master','Rev','Dr','Prof','Atty', 'Att','Hon','Pres','Gov','.');
    $abbreviated = str_replace($prefixes, "", $name);
    $abbreviated =  preg_replace('~\b(\w)|.~', '$1', $abbreviated);
    return $abbreviated;
  }
}


/**
 * Returns Font Awesome file icon class from file url
 * @param  string $file_url
 * @return string $file_icon
 */
if(!function_exists('tw_file_icon_from_url')){
  function tw_file_icon_from_url($file_url){
    $f = pathinfo($file_url);
    return tw_fa_file_icon($f['extension']);
  }
}

/**
 * Returns Font Awesome file icon class from extension
 * @param  string $extension
 * @return string $file_icon
 */
if(!function_exists('tw_fa_file_icon')){
  function tw_fa_file_icon($extension){
    $file_icon = 'fa-file';
    $file_ext = array(
                    'txt'  => 'text',
                    'aiff' => 'audio',
                    'mp4'  => 'video',
                    'avi'  => 'video',
                    'pdf'  => 'pdf',
                    'doc'  => 'word',
                    'docx' => 'word',
                    'xls'  => 'excel',
                    'xlsx' => 'excel',
                    'ppt'  => 'powerpoint',
                    'pptx' => 'powerpoint',
                    'jpg'  => 'image',
                    'jpeg' => 'image',
                    'png'  => 'image',
                    'gif'  => 'image',
                    'bmp'  => 'image',
                    'mp3'  => 'audio',
                    'aiff' => 'audio',
                    'mp4'  => 'video',
                    'avi'  => 'video',
                    'zip'  => 'zip',
                    'rar'  => 'archive',
                    'php'  => 'code',
                    'html' => 'code',
                    'css'  => 'code',
                    'js'   => 'code',
                  );
   if(isset($file_ext[$extension])){
     $file_icon = $file_icon.'-'.$file_ext[$extension].'-o';
   }else{
     $file_icon = $file_icon.'-o';
   }
   return $file_icon;
  }
}

/**
 * Returns Youtube or Vimeo embed iframe from url
 * @param  string $url
 * @param  string $autoplay
 * @return string $iframe
 */
if(!function_exists('tw_videoURL_to_embedCode')){
  function tw_videoURL_to_embedCode($url, $autoplay=false){
    $iframe = null;
    if(preg_match('/youtube/',$url)){
      $iframe = tw_youtubeURL_to_embedCode($url, $autoplay);
    }elseif(preg_match('/vimeo/',$url)){
      $iframe = tw_vimeoURL_to_embedCode($url, $autoplay);
    }
    return $iframe;
  }
}

/**
 * Returns Vimeo embed iframe from url
 * @param  string $url
 * @param  string $autoplay
 * @return string $iframe
 */
if(!function_exists('tw_vimeoURL_to_embedCode')){
  function tw_vimeoURL_to_embedCode($url, $autoplay=false){
    $regex = '~
  		# Match Vimeo link and embed code
  		(?:<iframe [^>]*src=")?         # If iframe match up to first quote of src
  		(?:                             # Group vimeo url
  				https?:\/\/             # Either http or https
  				(?:[\w]+\.)*            # Optional subdomains
  				vimeo\.com              # Match vimeo.com
  				(?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
  				\/                      # Slash before Id
  				([0-9]+)                # $1: VIDEO_ID is numeric
  				[^\s]*                  # Not a space
  		)                               # End group
  		"?                              # Match end quote if part of src
  		(?:[^>]*></iframe>)?            # Match the end of the iframe
  		(?:<p>.*</p>)?                  # Match any title information stuff
  		~ix';

  	preg_match( $regex, $url, $matches );
    $vedio_id = $matches[1];

    $embedurl = "//player.vimeo.com/video/".$vedio_id;
    if($autoplay){
      $embedurl = $embedurl.'?autoplay=1';
    }

    $width = '640';
    $height = '385';
    $iframe = '&lt;iframe class="embed-responsive-item" width="'.$width.'" height="'.$height.'" src="'.$embedurl.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen &gt;&lt;/iframe>';

    return $iframe;
  }
}

/**
 * Returns Youtube embed iframe from url
 * @param  string $url
 * @param  string $autoplay
 * @return string $iframe
 */
if(!function_exists('tw_youtubeURL_to_embedCode')){
  function tw_youtubeURL_to_embedCode($url, $autoplay=false){
    preg_match(
            '/[\\?\\&]v=([^\\?\\&]+)/',
            $url,
            $matches
        );
    $id = $matches[1];
    $autoplay = intval($autoplay);

    $width = '640';
    $height = '385';
    $iframe = '&lt;iframe class="embed-responsive-item" width="' . $width . '" height="' . $height . '" src="http://www.youtube.com/embed/' . $id . '?autoplay='.$autoplay.'&amp;rel=0" frameborder="0" allowfullscreen&gt;&lt;/iframe>';
    return $iframe;
  }
}

/**
 * Returns character limited truncated string
 * @param  string $text
 * @param  integer $limit
 * @return string $text
 */
if(!function_exists('tw_text_limit')){
  function tw_text_limit($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
  }
}

/**
 * Returns stripped and slugged string
 * @param  string $text
 * @return string $text
 */
if(!function_exists('tw_slugify')){
  function tw_slugify($text){
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text); // replace non letter or digits by -
    $text = trim($text, '-'); // trim
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); // transliterate
    $text = strtolower($text); // lowercase
    $text = preg_replace('~[^-\w]+~', '', $text); // remove unwanted characters
    if (empty($text)){
      return 'n-a';
    }
    return $text;
  }
}

/**
 * Returns array of countries
 * @return array $countries
 */
if(!function_exists('tw_get_countries_list')){
  function tw_get_countries_list(){
    $countries = array
      (
        'US' => __('United States','tw'),
        'CA' => __('Canada','tw'),
      	'AF' => __('Afghanistan','tw'),
      	'AX' => __('Aland Islands','tw'),
      	'AL' => __('Albania','tw'),
      	'DZ' => __('Algeria','tw'),
      	'AS' => __('American Samoa','tw'),
      	'AD' => __('Andorra','tw'),
      	'AO' => __('Angola','tw'),
      	'AI' => __('Anguilla','tw'),
      	'AQ' => __('Antarctica','tw'),
      	'AG' => __('Antigua And Barbuda','tw'),
      	'AR' => __('Argentina','tw'),
      	'AM' => __('Armenia','tw'),
      	'AW' => __('Aruba','tw'),
      	'AU' => __('Australia','tw'),
      	'AT' => __('Austria','tw'),
      	'AZ' => __('Azerbaijan','tw'),
      	'BS' => __('Bahamas','tw'),
      	'BH' => __('Bahrain','tw'),
      	'BD' => __('Bangladesh','tw'),
      	'BB' => __('Barbados','tw'),
      	'BY' => __('Belarus','tw'),
      	'BE' => __('Belgium','tw'),
      	'BZ' => __('Belize','tw'),
      	'BJ' => __('Benin','tw'),
      	'BM' => __('Bermuda','tw'),
      	'BT' => __('Bhutan','tw'),
      	'BO' => __('Bolivia','tw'),
      	'BA' => __('Bosnia And Herzegovina','tw'),
      	'BW' => __('Botswana','tw'),
      	'BV' => __('Bouvet Island','tw'),
      	'BR' => __('Brazil','tw'),
      	'IO' => __('British Indian Ocean Territory','tw'),
      	'BN' => __('Brunei Darussalam','tw'),
      	'BG' => __('Bulgaria','tw'),
      	'BF' => __('Burkina Faso','tw'),
      	'BI' => __('Burundi','tw'),
      	'KH' => __('Cambodia','tw'),
      	'CM' => __('Cameroon','tw'),
      	'CV' => __('Cape Verde','tw'),
      	'KY' => __('Cayman Islands','tw'),
      	'CF' => __('Central African Republic','tw'),
      	'TD' => __('Chad','tw'),
      	'CL' => __('Chile','tw'),
      	'CN' => __('China','tw'),
      	'CX' => __('Christmas Island','tw'),
      	'CC' => __('Cocos (Keeling) Islands','tw'),
      	'CO' => __('Colombia','tw'),
      	'KM' => __('Comoros','tw'),
      	'CG' => __('Congo','tw'),
      	'CD' => __('Congo, Democratic Republic','tw'),
      	'CK' => __('Cook Islands','tw'),
      	'CR' => __('Costa Rica','tw'),
      	'CI' => __('Cote D\'Ivoire','tw'),
      	'HR' => __('Croatia','tw'),
      	'CU' => __('Cuba','tw'),
      	'CY' => __('Cyprus','tw'),
      	'CZ' => __('Czech Republic','tw'),
      	'DK' => __('Denmark','tw'),
      	'DJ' => __('Djibouti','tw'),
      	'DM' => __('Dominica','tw'),
      	'DO' => __('Dominican Republic','tw'),
      	'EC' => __('Ecuador','tw'),
      	'EG' => __('Egypt','tw'),
      	'SV' => __('El Salvador','tw'),
      	'GQ' => __('Equatorial Guinea','tw'),
      	'ER' => __('Eritrea','tw'),
      	'EE' => __('Estonia','tw'),
      	'ET' => __('Ethiopia','tw'),
      	'FK' => __('Falkland Islands (Malvinas)','tw'),
      	'FO' => __('Faroe Islands','tw'),
      	'FJ' => __('Fiji','tw'),
      	'FI' => __('Finland','tw'),
      	'FR' => __('France','tw'),
      	'GF' => __('French Guiana','tw'),
      	'PF' => __('French Polynesia','tw'),
      	'TF' => __('French Southern Territories','tw'),
      	'GA' => __('Gabon','tw'),
      	'GM' => __('Gambia','tw'),
      	'GE' => __('Georgia','tw'),
      	'DE' => __('Germany','tw'),
      	'GH' => __('Ghana','tw'),
      	'GI' => __('Gibraltar','tw'),
      	'GR' => __('Greece','tw'),
      	'GL' => __('Greenland','tw'),
      	'GD' => __('Grenada','tw'),
      	'GP' => __('Guadeloupe','tw'),
      	'GU' => __('Guam','tw'),
      	'GT' => __('Guatemala','tw'),
      	'GG' => __('Guernsey','tw'),
      	'GN' => __('Guinea','tw'),
      	'GW' => __('Guinea-Bissau','tw'),
      	'GY' => __('Guyana','tw'),
      	'HT' => __('Haiti','tw'),
      	'HM' => __('Heard Island & Mcdonald Islands','tw'),
      	'VA' => __('Holy See (Vatican City State)','tw'),
      	'HN' => __('Honduras','tw'),
      	'HK' => __('Hong Kong','tw'),
      	'HU' => __('Hungary','tw'),
      	'IS' => __('Iceland','tw'),
      	'IN' => __('India','tw'),
      	'ID' => __('Indonesia','tw'),
      	'IR' => __('Iran, Islamic Republic Of','tw'),
      	'IQ' => __('Iraq','tw'),
      	'IE' => __('Ireland','tw'),
      	'IM' => __('Isle Of Man','tw'),
      	'IL' => __('Israel','tw'),
      	'IT' => __('Italy','tw'),
      	'JM' => __('Jamaica','tw'),
      	'JP' => __('Japan','tw'),
      	'JE' => __('Jersey','tw'),
      	'JO' => __('Jordan','tw'),
      	'KZ' => __('Kazakhstan','tw'),
      	'KE' => __('Kenya','tw'),
      	'KI' => __('Kiribati','tw'),
      	'KR' => __('Korea','tw'),
      	'KW' => __('Kuwait','tw'),
      	'KG' => __('Kyrgyzstan','tw'),
      	'LA' => __('Lao People\'s Democratic Republic','tw'),
      	'LV' => __('Latvia','tw'),
      	'LB' => __('Lebanon','tw'),
      	'LS' => __('Lesotho','tw'),
      	'LR' => __('Liberia','tw'),
      	'LY' => __('Libyan Arab Jamahiriya','tw'),
      	'LI' => __('Liechtenstein','tw'),
      	'LT' => __('Lithuania','tw'),
      	'LU' => __('Luxembourg','tw'),
      	'MO' => __('Macao','tw'),
      	'MK' => __('Macedonia','tw'),
      	'MG' => __('Madagascar','tw'),
      	'MW' => __('Malawi','tw'),
      	'MY' => __('Malaysia','tw'),
      	'MV' => __('Maldives','tw'),
      	'ML' => __('Mali','tw'),
      	'MT' => __('Malta','tw'),
      	'MH' => __('Marshall Islands','tw'),
      	'MQ' => __('Martinique','tw'),
      	'MR' => __('Mauritania','tw'),
      	'MU' => __('Mauritius','tw'),
      	'YT' => __('Mayotte','tw'),
      	'MX' => __('Mexico','tw'),
      	'FM' => __('Micronesia, Federated States Of','tw'),
      	'MD' => __('Moldova','tw'),
      	'MC' => __('Monaco','tw'),
      	'MN' => __('Mongolia','tw'),
      	'ME' => __('Montenegro','tw'),
      	'MS' => __('Montserrat','tw'),
      	'MA' => __('Morocco','tw'),
      	'MZ' => __('Mozambique','tw'),
      	'MM' => __('Myanmar','tw'),
      	'NA' => __('Namibia','tw'),
      	'NR' => __('Nauru','tw'),
      	'NP' => __('Nepal','tw'),
      	'NL' => __('Netherlands','tw'),
      	'AN' => __('Netherlands Antilles','tw'),
      	'NC' => __('New Caledonia','tw'),
      	'NZ' => __('New Zealand','tw'),
      	'NI' => __('Nicaragua','tw'),
      	'NE' => __('Niger','tw'),
      	'NG' => __('Nigeria','tw'),
      	'NU' => __('Niue','tw'),
      	'NF' => __('Norfolk Island','tw'),
      	'MP' => __('Northern Mariana Islands','tw'),
      	'NO' => __('Norway','tw'),
      	'OM' => __('Oman','tw'),
      	'PK' => __('Pakistan','tw'),
      	'PW' => __('Palau','tw'),
      	'PS' => __('Palestinian Territory, Occupied','tw'),
      	'PA' => __('Panama','tw'),
      	'PG' => __('Papua New Guinea','tw'),
      	'PY' => __('Paraguay','tw'),
      	'PE' => __('Peru','tw'),
      	'PH' => __('Philippines','tw'),
      	'PN' => __('Pitcairn','tw'),
      	'PL' => __('Poland','tw'),
      	'PT' => __('Portugal','tw'),
      	'PR' => __('Puerto Rico','tw'),
      	'QA' => __('Qatar','tw'),
      	'RE' => __('Reunion','tw'),
      	'RO' => __('Romania','tw'),
      	'RU' => __('Russian Federation','tw'),
      	'RW' => __('Rwanda','tw'),
      	'BL' => __('Saint Barthelemy','tw'),
      	'SH' => __('Saint Helena','tw'),
      	'KN' => __('Saint Kitts And Nevis','tw'),
      	'LC' => __('Saint Lucia','tw'),
      	'MF' => __('Saint Martin','tw'),
      	'PM' => __('Saint Pierre And Miquelon','tw'),
      	'VC' => __('Saint Vincent And Grenadines','tw'),
      	'WS' => __('Samoa','tw'),
      	'SM' => __('San Marino','tw'),
      	'ST' => __('Sao Tome And Principe','tw'),
      	'SA' => __('Saudi Arabia','tw'),
      	'SN' => __('Senegal','tw'),
      	'RS' => __('Serbia','tw'),
      	'SC' => __('Seychelles','tw'),
      	'SL' => __('Sierra Leone','tw'),
      	'SG' => __('Singapore','tw'),
      	'SK' => __('Slovakia','tw'),
      	'SI' => __('Slovenia','tw'),
      	'SB' => __('Solomon Islands','tw'),
      	'SO' => __('Somalia','tw'),
      	'ZA' => __('South Africa','tw'),
      	'GS' => __('South Georgia And Sandwich Isl.','tw'),
      	'ES' => __('Spain','tw'),
      	'LK' => __('Sri Lanka','tw'),
      	'SD' => __('Sudan','tw'),
      	'SR' => __('Suriname','tw'),
      	'SJ' => __('Svalbard And Jan Mayen','tw'),
      	'SZ' => __('Swaziland','tw'),
      	'SE' => __('Sweden','tw'),
      	'CH' => __('Switzerland','tw'),
      	'SY' => __('Syrian Arab Republic','tw'),
      	'TW' => __('Taiwan','tw'),
      	'TJ' => __('Tajikistan','tw'),
      	'TZ' => __('Tanzania','tw'),
      	'TH' => __('Thailand','tw'),
      	'TL' => __('Timor-Leste','tw'),
      	'TG' => __('Togo','tw'),
      	'TK' => __('Tokelau','tw'),
      	'TO' => __('Tonga','tw'),
      	'TT' => __('Trinidad And Tobago','tw'),
      	'TN' => __('Tunisia','tw'),
      	'TR' => __('Turkey','tw'),
      	'TM' => __('Turkmenistan','tw'),
      	'TC' => __('Turks And Caicos Islands','tw'),
      	'TV' => __('Tuvalu','tw'),
      	'UG' => __('Uganda','tw'),
      	'UA' => __('Ukraine','tw'),
      	'AE' => __('United Arab Emirates','tw'),
      	'GB' => __('United Kingdom','tw'),
      	'UM' => __('United States Outlying Islands','tw'),
      	'UY' => __('Uruguay','tw'),
      	'UZ' => __('Uzbekistan','tw'),
      	'VU' => __('Vanuatu','tw'),
      	'VE' => __('Venezuela','tw'),
      	'VN' => __('Viet Nam','tw'),
      	'VG' => __('Virgin Islands, British','tw'),
      	'VI' => __('Virgin Islands, U.S.','tw'),
      	'WF' => __('Wallis And Futuna','tw'),
      	'EH' => __('Western Sahara','tw'),
      	'YE' => __('Yemen','tw'),
      	'ZM' => __('Zambia','tw'),
      	'ZW' => __('Zimbabwe','tw'),
      );
    return $countries;
  }
}