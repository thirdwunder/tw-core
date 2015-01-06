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
function tw_rgb2hex($rgb) {
   $hex = "#";
   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

   return $hex;
}

/**
 * Returns color rgba array hex string value
 * @param  string hex
 * @return array rgb
 */
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

/**
 * Returns a base domain from a full url
 * @param  string $url
 * @return string $domain
 */
function tw_url2domain($url){
  $domain = preg_replace('#^https?://#', '', $url);
  $domain = preg_replace('#^www.#','',$url);
  return $domain;
}

/**
 * Returns a clean phone number value with parenthesis, dashes or dots
 * @param  string $phone
 * @return string $phone
 */
function tw_clean_phone_number($phone){
  return preg_replace('~[\W\s]~', "", $phone);
}

/**
 * Returns a clean string with no hyphens or special characters
 * @param  string $string
 * @return string $string
 */
function tw_clean_string($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

/**
 * Returns an abbereviated name without prefixes or postfixes
 * @param  string $name
 * @return string $abbreviated
 */
function tw_abbreviate_name($name){
  $prefixes = array('Ms','Miss','Mrs','Mr','Master','Rev','Dr','Prof','Atty', 'Att','Hon','Pres','Gov','.');
  $abbreviated = str_replace($prefixes, "", $name);
  $abbreviated =  preg_replace('~\b(\w)|.~', '$1', $abbreviated);
  return $abbreviated;
}


/**
 * Returns Font Awesome file icon class from file url
 * @param  string $file_url
 * @return string $file_icon
 */
function tw_file_icon_from_url($file_url){
  $f = pathinfo($file_url);
  return tw_fa_file_icon($f['extension']);
}

/**
 * Returns Font Awesome file icon class from extension
 * @param  string $extension
 * @return string $file_icon
 */
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

/**
 * Returns Youtube or Vimeo embed iframe from url
 * @param  string $url
 * @param  string $autoplay
 * @return string $iframe
 */
function tw_videoURL_to_embedCode($url, $autoplay=false){
  $iframe = null;
  if(preg_match('/youtube/',$url)){
    $iframe = tw_youtubeURL_to_embedCode($url, $autoplay);
  }elseif(preg_match('/vimeo/',$url)){
    $iframe = tw_vimeoURL_to_embedCode($url, $autoplay);
  }
  return $iframe;
}

/**
 * Returns Vimeo embed iframe from url
 * @param  string $url
 * @param  string $autoplay
 * @return string $iframe
 */
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

/**
 * Returns Youtube embed iframe from url
 * @param  string $url
 * @param  string $autoplay
 * @return string $iframe
 */
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
  $iframe = '&lt;iframe class="embed-responsive-item" width="' . $width . '" height="' . $height . '" src="http://www.youtube.com/embed/' . $id . '?autoplay='.$autoplay.'" frameborder="0" allowfullscreen&gt;&lt;/iframe>';
  return $iframe;
}

/**
 * Returns character limited truncated string
 * @param  string $text
 * @param  integer $limit
 * @return string $text
 */
function tw_text_limit($text, $limit) {
  if (str_word_count($text, 0) > $limit) {
      $words = str_word_count($text, 2);
      $pos = array_keys($words);
      $text = substr($text, 0, $pos[$limit]) . '...';
  }
  return $text;
}