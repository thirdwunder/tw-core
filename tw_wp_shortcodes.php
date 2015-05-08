<?php
add_shortcode('lead', 'tw_lead');
//if(!function_exists('tw_lead')){
  function tw_lead($atts, $content = null) {
    return '<div class="lead">'.$content.'</div>';
  }
//}

add_shortcode('blockquote', 'tw_blockquote');
//if(!function_exists('tw_lead')){
  function tw_blockquote($atts, $content = null) {
    $atts = shortcode_atts(array(
            'position' => 'center',
          ), $atts);
    return '<blockquote class="'.$atts['position'].'">'.$content.'</blockquote>';
  }
//}