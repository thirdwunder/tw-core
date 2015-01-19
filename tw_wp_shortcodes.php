<?php
add_shortcode('lead', 'tw_lead');
function tw_lead($atts, $content = null) {
  error_log($content);
  return '<div class="lead">'.$content.'</div>';
}

add_shortcode('blockquote', 'tw_blockquote');
function tw_blockquote($atts, $content = null) {
  $atts = shortcode_atts(array(
          'position' => 'center',
        ), $atts);
  return '<blockquote class="'.$atts['position'].'">'.$content.'</blockquote>';
}