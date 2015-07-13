<?php
add_shortcode('lead', 'tw_lead');
function tw_lead($atts, $content = null) {
  return '<div class="lead">'.$content.'</div>';
}

add_shortcode('blockquote', 'tw_blockquote');
function tw_blockquote($atts, $content = null) {
  $atts = shortcode_atts(array(
          'position' => 'center',
        ), $atts);
  return '<blockquote class="'.$atts['position'].'">'.$content.'</blockquote>';
}


// Collapse Content Shortcode
add_shortcode( 'collapse', 'tw_collapse_content' );
function tw_collapse_content( $atts , $content = null ) {
  extract( shortcode_atts(
		array(
			'title' => 'Read more',
		), $atts )
	);

  $html = '<a class="content-collapse" data-toggle="collapse" href="#contentCollapse" aria-expanded="false" aria-controls="contentCollapse">'.$title.' <i class="fa fa-caret-down"></i></a>';
  $html .= '<div class="collapse" id="contentCollapse">'.$content.'</div>';
  return $html;
}
