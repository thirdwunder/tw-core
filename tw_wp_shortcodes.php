<?php
add_shortcode('lead', 'tw_lead');
function tw_lead($atts, $content = null) {
  error_log($content);
  return '<div class="lead">'.$content.'</div>';
}