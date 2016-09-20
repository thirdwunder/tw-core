<?php
/**
 * Wordpress Functions
 *
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
/******************************************************
************* Theme Support Functions ****************
******************************************************/

if (!(is_admin() )) {
  add_filter( 'clean_url', 'tw_defer_js', 11, 1 );
  function tw_defer_js( $url ){
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    if ( strpos( $url, 'gravityforms' ) ) return $url;
    if ( strpos( $url, 'owl' ) ) return $url;

    // return "$url' defer ";
    return "$url' defer onload='";
  }
}


add_action( 'after_setup_theme', 'tw_woocommerce_support' );
function tw_woocommerce_support() {
  add_theme_support( 'woocommerce' );
}

/**
 * Adds prefetch dns meta to head
 */
function tw_dns_prefetch() {
  $enable_google_fonts      = get_field('tw_theme_prefetch_google_fonts','option');
  $enable_google_analytics  = get_field('tw_theme_prefetch_google_analytics','option');
  $enable_google_gtm        = get_field('tw_theme_prefetch_google_tagmanager','option');
  $enable_google_maps       = get_field('tw_theme_prefetch_google_maps','option');
  $enable_facebook          = get_field('tw_theme_prefetch_facebook','option');

	$dns_array = array();

	if($enable_google_fonts){
    $dns_array[] = '//fonts.googleapis.com';
	}
	if($enable_google_analytics){
    $dns_array[] = '//www.google-analytics.com';
	}
	if($enable_google_gtm){
    $dns_array[] = '//www.googletagmanager.com';
	}
	if($enable_google_maps){
    $dns_array[] = '//maps.gstatic.com';
    $dns_array[] = '//maps.google.com';
    $dns_array[] = '//maps.googleapis.com';
    $dns_array[] = '//mt0.googleapis.com';
    $dns_array[] = '//mt1.googleapis.com';
	}
	if($enable_facebook){
    $dns_array[] = '//connect.facebook.net';
    $dns_array[] = '//static.ak.facebook.com';
    $dns_array[] = '//s-static.ak.facebook.com';
    $dns_array[] = '//fbstatic-a.akamaihd.net';
	}

	$dns_array = apply_filters('tw_dns_prefetch_filter', $dns_array);
	foreach($dns_array as $dns){
  	echo '<link rel="dns-prefetch" href="'.$dns.'">';
	}
}
add_action('wp_head', 'tw_dns_prefetch', 1);

/**
 * Removes version information from css and js
 * @param string  $src
 * @param string  $src
 */
function tw_remove_cssjs_ver( $src ) {
  if( strpos( $src, '?ver=' ) )
    $src = remove_query_arg( 'ver', $src );
  return $src;
}
add_filter( 'style_loader_src', 'tw_remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'tw_remove_cssjs_ver', 10, 2 );

/**
 * Adds custom image sizes based on parameteres
 * @param string  $ratio
 * @param integer $size
 * @param boolean $hardcrop - default true
 * @param boolean $unlimited_height - default false
 * @param array   $postion
 */
if(!function_exists('tw_add_image_size')){
  function tw_add_image_size($ratio, $size, $hard_crop = true, $unlimited_height = false, $postion = array()){
    $img_widths = array(
                        'xxxlarge'=>2048,
                        'xxlarge'=>1600,
                        'xlarge'=>1200,
                        'large'=>1024,
                        'medium'=>800,
                        'small'=>400,
                        'xsmall'=>250,
                        'xthumb'=>50
                      );
    $img_ratios = array(
                        '16x6'     =>array('w'=>16, 'h'=>6),
                        '16x9'     =>array('w'=>16, 'h'=>9),
                        '9x16'     =>array('w'=>9,  'h'=>16),
                        '4x3'      =>array('w'=>4,  'h'=>3),
                        '3x4'      =>array('w'=>3,  'h'=>4),
                        '3x2'      =>array('w'=>3,  'h'=>2),
                        '2x3'      =>array('w'=>2,  'h'=>3),
                        'square'   =>array('w'=>1,  'h'=>1),
                      );

    $r_w = $img_ratios[$ratio]['w'];
    $r_h = $img_ratios[$ratio]['h'];

    $width = $img_widths[$size];

    if($unlimited_height){
      $height = 999;
    }else{
      $height = ($r_h*$width)/$r_w;
    }

    if($unlimited_height){
      $hard_crop  = false;
      add_image_size( $ratio.'-'.$size.'-auto', $width, $height, $hard_crop, array('center','center'));
    }elseif(count($postion)>0){
      add_image_size( $ratio.'-'.$size, $width, $height, false, $postion );
    }elseif($hard_crop){
      add_image_size( $ratio.'-'.$size, $width, $height, $hard_crop, array('center','center'));
    }else{
      add_image_size( $ratio.'-'.$size, $width, $height, array('center','center'));
    }
  }
}

/**
 * Registers Menu and Theme Support options
 */
if(!function_exists('tw_theme_support')){
  function tw_theme_support() {
  	add_theme_support('post-thumbnails');      // wp thumbnails (sizes handled in functions.php)
  	//set_post_thumbnail_size(125, 125, true);   // default thumb size

    /*
    * Make theme available for translation.
    * Translations can be filed in the /languages/ directory.
    * If you're building a theme based on twentyfifteen, use a find and replace
    * to change 'twentyfifteen' to the name of your theme in all the template files
    */
    load_theme_textdomain( 'tw', get_template_directory() . '/language' );

  	//$defaults = array(
    //	'default-color'          => '#fff',
    //	'default-image'          => '',
    //	'wp-head-callback'       => '_custom_background_cb',
    //	'admin-head-callback'    => '',
    //	'admin-preview-callback' => ''
    //);
    //add_theme_support( 'custom-background', $defaults ); // wp custom background

    //add_theme_support( 'custom-header' );

    // Add default posts and comments RSS feed links to head.
  	add_theme_support('automatic-feed-links'); // rss thingy

    /*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
    add_theme_support( "title-tag" );


    /*
    * Switch default core markup for search form, comment form, and comments
    * to output valid HTML5.
    */
    add_theme_support( 'html5', array(
      'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );

    $theme_general_options = get_option('tw_theme_general_options') ? get_option('tw_theme_general_options') : null;
    $theme_menus = array(
                    'mobile' => 'Mobile Menu',    // mobile main navigation
                		'primary' => 'Primary Menu',  // main nav in header
                  );

    $enable_footer = get_field('tw_enable_footer_menu','option');
    if($enable_footer){
      $theme_menus['footer'] = 'Footer Menu';
    }

  	add_theme_support( 'menus' );            // wp menus
  	register_nav_menus( $theme_menus );      // wp3+ menus

  }
  add_action('after_setup_theme','tw_theme_support');
}

/**
 * Registers Post Formats
 */
if(!function_exists('tw_post_formats')){
  function tw_post_formats(){
    $enabled_post_formats = array();
    $post_formats = array(
  			'aside',   // title less blurb
  			'gallery', // gallery of images
  			'link',    // quick link to other site
  			'image',   // an image
  			'quote',   // a quick quote
  			'status',  // a Facebook like status update
  			'video',   // video
  			'audio',   // audio
  			'chat'     // chat transcript
  		);
    foreach($post_formats as $pf){
      $enabled = get_field('tw_blog_'.$pf,'option');
      if($enabled){
        $enabled_post_formats[] = $pf;
      }
    }
    add_theme_support( 'post-formats',$enabled_post_formats);
  }
  add_action('after_setup_theme','tw_post_formats');
}

/******************************************************
********************** Widgets ************************
******************************************************/

if(!function_exists('tw_register_sidebars')){
  function tw_register_sidebars() {

    $enable_primary_sidebar   = get_field('tw_enable_primary_sidebar','option');
    $enable_homepage_sidebar  = get_field('tw_enable_homepage_widgets','option');
    $footer_widgets           = intval(get_field('tw_footer_widget_areas','option'));

    if($enable_primary_sidebar){
      register_sidebar(array(
      	'id' => 'primary',
      	'name' => __('Primary Sidebar', 'tw'),
      	'description' => __('Primary Widget Area','tw'),
      	'before_widget' => '<div id="%1$s" class="widget %2$s">',
      	'after_widget' => '</div>',
      	'before_title' => '<h4 class="widget-title">',
      	'after_title' => '</h4>',
      ));
    }
    if($enable_homepage_sidebar){
      register_sidebar(array(
      	'id' => 'homepage',
      	'name' => __('Homepage Widget Area','tw'),
      	'description' => __('Homepage Widget Area. Widgets here will show in the widgetized homepage template.','tw'),
      	'before_widget' => '<div id="%1$s" class="widget %2$s">',
      	'after_widget' => '</div>',
      	'before_title' => '<h2 class="widget-title">',
      	'after_title' => '</h2>',
      ));
    }
    if($footer_widgets>0){
     for($i=1; $i<=$footer_widgets; $i++){
      register_sidebar(array(
      	'id' => 'footer-'.$i,
      	'name' => sprintf(__('Footer Widget Area %s','tw'), $i) ,
      	'description' => __('Foter widget areas','tw'),
      	'before_widget' => '<div id="%1$s" class="widget %2$s">',
      	'after_widget' => '</div>',
      	'before_title' => '<h4 class="widget-title">',
      	'after_title' => '</h4>',
      ));
      }
    }
  }
  add_action( 'widgets_init', 'tw_register_sidebars' );
}



/******************************************************
******************* User Functions ********************
******************************************************/

/**
 * Sets extra contact info to Wordpress Users
 * @param  array $contactmethods
 * @return array $contactmethods
 */
if(!function_exists('tw_extra_contact_info')){
  function tw_extra_contact_info($contactmethods) {
      unset($contactmethods['aim']);
      unset($contactmethods['yim']);
      unset($contactmethods['jabber']);
      $contactmethods['facebook']   = 'Facebook';
      $contactmethods['twitter']    = 'Twitter';
      $contactmethods['googleplus'] = 'Google+';
      $contactmethods['linkedin']   = 'LinkedIn';
      $contactmethods['flickr']     = 'Flickr';
      $contactmethods['pinterest']  = 'Pinterest';
      $contactmethods['instagram']  = 'Instagram';
      $contactmethods['youtube']    = 'Youtube';
      $contactmethods['soundcloud'] = 'SoundCloud';

      $contactmethods = apply_filters('tw_extra_contact_info_filter', $contactmethods);

      return $contactmethods;
  }
  add_filter('user_contactmethods', 'tw_extra_contact_info');
}



/******************************************************
***************** Header Functions ********************
******************************************************/
/**
 * Sets favicon url in header
 */
if(!function_exists('tw_favicon')){
  function tw_favicon(){ ?>
    <link rel="icon" type="image/png" href="<?php echo tw_get_favicon(); ?>" />
  <?php
  }
  add_action( 'wp_head', 'tw_favicon', 10 );
}

/***** Add apple icons ****/
/**
 * Sets Apple iOS icons url in header
 */
if(!function_exists('tw_apple_icon')){
  function tw_apple_icon(){ ?>
  		<link rel="apple-touch-icon" href="<?php echo tw_get_apple_icon();?>" />
      <link rel="apple-touch-icon" sizes="72x72" href="<?php echo tw_get_apple_icon_72();?>" />
      <link rel="apple-touch-icon" sizes="114x114" href="<?php echo tw_get_apple_icon_114();?>" />
      <link rel="apple-touch-icon" sizes="144x144" href="<?php echo tw_get_apple_icon_144();?>" />
  	<?php
  };
  add_action( 'wp_head', 'tw_apple_icon', 10 );
}

/******************************************************
******************* Post Functions ********************
******************************************************/

/**
 * Sets excerpt length
 * @param  integer  $length
 * @return string hex
 */
if(!function_exists('tw_excerpt_length')){
  function tw_excerpt_length( $length ) {
  	return 30;
  }
  add_filter( 'excerpt_length', 'tw_excerpt_length', 999 );
}

/**
 * Sets excerpt more postfix to ...
 * @param  string  $more
 * @return string $more
 */
if(!function_exists('tw_excerpt_more')){
  function tw_excerpt_more( $more ) {
  	return '... ';
  }
  add_filter('excerpt_more', 'tw_excerpt_more');
}

/**
 * Echo's post navigation - previous post
 */
if(!function_exists('tw_post_nav_previous')){
  function tw_post_nav_previous($type='Previous Article'){
    global $post;
    $previous = get_adjacent_post( false, '', true );
    if ( ! $previous )
  		return;
    ?>
    <a href="<?php echo get_permalink($previous->ID);?>" title="<?php echo $previous->post_title;?>"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;&nbsp;<span class="nav-title"><?php echo sprintf( __('%s', 'tw'), $type ); ?></span></a>
    <?php
  }
}

/**
 * Echo's post navigation - next post
 */
if(!function_exists('tw_post_nav_next')){
  function tw_post_nav_next($type='Next Article'){
    global $post;
    $next     = get_adjacent_post( false, '', false );
    if ( ! $next )
  		return;
    ?>
    <a href="<?php echo get_permalink($next->ID);?>" title="<?php echo $next->post_title;?>"><span class="nav-title"><?php echo sprintf( __('%s', 'tw'), $type ) ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i></a>
    <?php
  }
}

/**
 * Echo's post navigation
 */
if(!function_exists('tw_post_nav')){
  function tw_post_nav($type='Article') {
  	global $post;

  	// Don't print empty markup if there's nowhere to navigate.
  	$previous = get_adjacent_post( false, '', true );
  	$next     = get_adjacent_post( false, '', false );

  	if ( ! $next && ! $previous )
  		return;
  	?>
  	<nav class="navigation post-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
  		<h3 class="sr-only"><?php _e( 'Post navigation', 'tw' ); ?></h3>
      <ul class="hidden-print pager nav-links">
        <?php
          if($previous){
            ?>
            <li class="previous" itemprop="url"><a href="<?php echo get_permalink($previous->ID);?>" title="<?php echo $previous->post_title;?>"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;&nbsp;<span class="nav-title"><?php echo sprintf( __('Previous %s', 'tw'), $type ); ?></span></a></li>
            <?php
          }

          if($next){
            ?>
            <li class="next" itemprop="url"><a href="<?php echo get_permalink($next->ID);?>" title="<?php echo $next->post_title;?>"><span class="nav-title"><?php echo sprintf( __('Next %s', 'tw'), $type ) ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i></a></li>
            <?php
          }
			  ?>
      </ul>
  	</nav><!-- .navigation -->
  	<?php
  }
}


/******************************************************
***********************  Metaboxes ********************
******************************************************/
if( function_exists('acf_add_local_field_group') ):
$enable_map = get_field('tw_enable_google_map','option');
if($enable_map){
$map_zoom = array();
for($i=1; $i<=18; $i++){
  $map_zoom[$i] = $i;
}
acf_add_local_field_group(array (
	'key' => 'tw_theme_contact_page_map',
	'title' => 'Contact Page Map',
	'fields' => array (
		array (
			'key' => 'field_568482ac9411f',
			'label' => 'Google Map',
			'name' => 'tw_contact_enable_map',
			'type' => 'true_false',
			'instructions' => 'Enable the map on this page',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Enable Map',
			'default_value' => 0,
		),
		array (
			'key' => 'field_568482ea94120',
			'label' => 'Map Zoom Level',
			'name' => 'tw_contact_map_zoom_level',
			'type' => 'select',
			'instructions' => 'Choose the zoom level of the map. The marker will always centre on the address.',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_568482ac9411f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'choices' => $map_zoom,
			'default_value' => array (
				14 => 14,
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_568483346860e',
			'label' => 'Map Marker Colour',
			'name' => 'tw_contact_map_marker_colour',
			'type' => 'color_picker',
			'instructions' => 'Choose the map marker colour.',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_568482ac9411f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => '#FF0000',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
			array (
				'param' => 'page_template',
				'operator' => '==',
				'value' => 'template-contact.php',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));
}

$tw_cat_enable_color = get_field('tw_enable_category_colour','option');
$tw_cat_enable_img = get_field('tw_enable_category_image','option');
$tw_theme_cat_options = array();
if($tw_cat_enable_color){
$tw_theme_cat_options[] = array (
			'key' => 'field_56830b88a8568',
			'label' => 'Category Colour',
			'name' => 'tw_category_colour',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
		);
}
if($tw_cat_enable_img){
$tw_theme_cat_options[] = array (
			'key' => 'field_56830b6ea8567',
			'label' => 'Category Image',
			'name' => 'tw_category_img',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		);
}
acf_add_local_field_group(array (
	'key' => 'group_56830b3c87c27',
	'title' => 'Category Options',
	'fields' => $tw_theme_cat_options,
	'location' => array (
		array (
			array (
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'category',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));


acf_add_local_field_group(array (
	'key' => 'tw_theme_homepage_options',
	'title' => 'Homepage',
	'fields' => array (
		array (
			'key' => 'field_5681d8c721474',
			'label' => 'Page Content',
			'name' => 'tw_enable_hp_content',
			'type' => 'true_false',
			'instructions' => 'Choose to display or hide the page content',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Enable Page Content',
			'default_value' => 1,
		),
		array (
			'key' => 'field_5681d8f421475',
			'label' => 'Jumbotron',
			'name' => 'tw_enable_hp_jumbotron',
			'type' => 'true_false',
			'instructions' => 'Choose to display a Jumbotron on the homepage.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Enable Homepage Jumbotron',
			'default_value' => 0,
		),
		array (
			'key' => 'field_5681d995ce4d4',
			'label' => 'Background Images',
			'name' => 'tw_hp_jumbotron_bg_imgs',
			'type' => 'gallery',
			'instructions' => 'Add 1 image to only have a single background. Add more to have a background slider.',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'min' => '',
			'max' => '',
			'preview_size' => 'thumbnail',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array (
			'key' => 'field_568fe94d7c1ed',
			'label' => 'Background Slider Speed',
			'name' => 'tw_hp_jumbotron_bg_slider_speed',
			'type' => 'number',
			'instructions' => 'How many seconds should a background image be displayed before changing',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 4,
			'placeholder' => 3,
			'prepend' => '',
			'append' => '',
			'min' => 3,
			'max' => 8,
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5681da24ce4d5',
			'label' => 'Heading Title',
			'name' => 'tw_hp_jumbotron_title',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5681da42ce4d6',
			'label' => 'Heading Subtitle',
			'name' => 'tw_hp_jumbotron_subtitle',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5681da5cce4d7',
			'label' => 'Content',
			'name' => 'tw_hp_jumbotron_content',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 0,
		),
		array (
			'key' => 'field_5681e1fd4dcd2',
			'label' => 'Text Colour',
			'name' => 'tw_hp_jumbotron_text_colour',
			'type' => 'radio',
			'instructions' => 'Choose the content text colour.<br/>
Dark for a light or bright background.<br/>
Light for a dark or dim background.',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'dark' => 'Dark',
				'light' => 'Light',
			),
			'other_choice' => 0,
			'save_other_choice' => 0,
			'default_value' => 'dark',
			'layout' => 'horizontal',
		),
		array (
			'key' => 'field_5681de31cba93',
			'label' => 'Call to Action',
			'name' => 'tw_hp_jumbotron_cta',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Enable Call to Action button',
			'default_value' => 0,
		),
		array (
			'key' => 'field_5681dae3864b4',
			'label' => 'CTA Title',
			'name' => 'tw_hp_jumbotron_cta_title',
			'type' => 'text',
			'instructions' => 'Jumbotron Call to Action button title',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
					array (
						'field' => 'field_5681de31cba93',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => 30,
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'Learn More',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5681db1c864b5',
			'label' => 'CTA URL',
			'name' => 'tw_hp_jumbotron_cta_url',
			'type' => 'url',
			'instructions' => 'Jumbotron Call to Action button URL',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
					array (
						'field' => 'field_5681de31cba93',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => 30,
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'http://www.example.com',
		),
		array (
			'key' => 'field_5681db53864b6',
			'label' => 'CTA Style',
			'name' => 'tw_hp_jumbotron_cta_style',
			'type' => 'select',
			'instructions' => 'Choose a button style',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
					array (
						'field' => 'field_5681de31cba93',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => 30,
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'default' => 'Default',
				'primary' => 'Primary',
				'success' => 'Success',
				'info' => 'Info',
				'warning' => 'Warning',
				'danger' => 'Danger',
			),
			'default_value' => array (
				'default' => 'default',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_5681dbf770a24',
			'label' => 'Video',
			'name' => 'tw_hp_jumbotron_enable_video',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Enable Jumbotron Video',
			'default_value' => 0,
		),
		array (
			'key' => 'field_5681dc76cf51b',
			'label' => 'Video URL',
			'name' => 'tw_hp_jumbotron_video_url',
			'type' => 'url',
			'instructions' => 'Youtube or Vimeo video URL',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
					array (
						'field' => 'field_5681dbf770a24',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => 30,
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_5681de86cba97',
			'label' => 'Video Button Title',
			'name' => 'tw_hp_jumbotron_video_button_title',
			'type' => 'text',
			'instructions' => 'Video Call to Action button title',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
					array (
						'field' => 'field_5681dbf770a24',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => 30,
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'Watch Video',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5681dcf5cf51d',
			'label' => 'Video Button Style',
			'name' => 'tw_hp_jumbotron_video_button_style',
			'type' => 'select',
			'instructions' => 'Choose a button style',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_5681d8f421475',
						'operator' => '==',
						'value' => '1',
					),
					array (
						'field' => 'field_5681dbf770a24',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array (
				'width' => 30,
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'default' => 'Default',
				'primary' => 'Primary',
				'success' => 'Success',
				'info' => 'Info',
				'warning' => 'Warning',
				'danger' => 'Danger',
			),
			'default_value' => array (
				'primary' => 'primary',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
			array (
				'param' => 'page_template',
				'operator' => '==',
				'value' => 'template-homepage.php',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'field',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));


acf_add_local_field_group(array (
	'key' => 'tw_theme_blog_postformat_link',
	'title' => 'Link Post Format',
	'fields' => array (
		array (
			'key' => 'field_5681d7ed524c9',
			'label' => 'Link URL',
			'name' => 'tw_blog_link_url',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'http://www.example.com',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
			array (
				'param' => 'post_format',
				'operator' => '==',
				'value' => 'link',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));


acf_add_local_field_group(array (
	'key' => 'tw_theme_blog_postformat_audio',
	'title' => 'Audio Post Format',
	'fields' => array (
  	array (
			'key' => 'field_56821ecc39e2f',
			'label' => 'Audio Title',
			'name' => 'tw_blog_audio_title',
			'type' => 'text',
			'instructions' => 'Add a title to the audio file',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56809acb83293',
			'label' => 'Audio Option',
			'name' => 'tw_blog_audio_option',
			'type' => 'radio',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'internal' => 'Upload a file',
				'external' => 'Link to a file',
			),
			'other_choice' => 0,
			'save_other_choice' => 0,
			'default_value' => '',
			'layout' => 'horizontal',
		),
		array (
			'key' => 'field_56809a5250def',
			'label' => 'Audio URL',
			'name' => 'tw_blog_audio_url',
			'type' => 'url',
			'instructions' => 'Enter the url of an external audio stream',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_56809acb83293',
						'operator' => '==',
						'value' => 'external',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_56809b1783294',
			'label' => 'Audio Upload',
			'name' => 'tw_blog_audio_upload',
			'type' => 'file',
			'instructions' => 'Upload an MP3 audio file',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_56809acb83293',
						'operator' => '==',
						'value' => 'internal',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_size' => '',
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
			array (
				'param' => 'post_format',
				'operator' => '==',
				'value' => 'audio',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'field',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));


acf_add_local_field_group(array (
	'key' => 'tw_theme_blog_postformat_video',
	'title' => 'Video Post Format',
	'fields' => array (
		array (
			'key' => 'field_568099c620988',
			'label' => 'Video URL',
			'name' => 'tw_blog_video_url',
			'type' => 'url',
			'instructions' => 'Enter a Youtube or Vimeo URL',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_5682bea3718a2',
			'label' => 'Video Poster Image',
			'name' => 'tw_blog_video_img',
			'type' => 'image',
			'instructions' => 'Choose a poster image for the video. If non is chosen, then the default image will be used instead.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
			array (
				'param' => 'post_format',
				'operator' => '==',
				'value' => 'video',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'field',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));


acf_add_local_field_group(array (
	'key' => 'tw_theme_blog_postformat_gallery',
	'title' => 'Gallery Post Format',
	'fields' => array (
		array (
			'key' => 'field_56809bbc62320',
			'label' => 'Gallery',
			'name' => 'tw_blog_gallery',
			'type' => 'gallery',
			'instructions' => 'Upload and rearrange images for your gallery',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'min' => '',
			'max' => '',
			'preview_size' => 'thumbnail',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
			array (
				'param' => 'post_format',
				'operator' => '==',
				'value' => 'gallery',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'field',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));


acf_add_local_field_group(array (
	'key' => 'tw_theme_blog_postformat_quote',
	'title' => 'Quote Post Format',
	'fields' => array (
		array (
			'key' => 'field_56809c1c41482',
			'label' => 'Quote',
			'name' => 'tw_blog_quote',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 4,
			'new_lines' => 'wpautop',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56809c3d41483',
			'label' => 'Author',
			'name' => 'tw_blog_quote_author',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'Author Name',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5681d59eea916',
			'label' => 'Source Name',
			'name' => 'tw_blog_quote_source_name',
			'type' => 'text',
			'instructions' => 'Quote source name',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56809c5541484',
			'label' => 'Source URL',
			'name' => 'tw_blog_quote_source_url',
			'type' => 'url',
			'instructions' => 'URL to the source of the quote for attribution',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'http://example.com',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
			array (
				'param' => 'post_format',
				'operator' => '==',
				'value' => 'quote',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'field',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;


/******************************************************
********************* Comments ************************
******************************************************/
/**
 * Returns user avatar class with Bootstrap cirle classes
 * @param string $class
 * @return string $class
 */
if(!function_exists('tw_round_avatar_css')){
  function tw_round_avatar_css($class) {
    $class = str_replace("class='avatar", "itemprop='image' class='avatar img-circle media-object", $class) ;
    return $class;
  }
  add_filter('get_avatar','tw_round_avatar_css');
}

/**
 * Returns comment reply link with Bootstrap button classes
 * @param string $class
 * @return string $class
 */
if(!function_exists('tw_reply_link_class')){
  function tw_reply_link_class($class){
      $class = str_replace("class='comment-reply-link", "class='comment-reply-link btn btn-primary btn-xs", $class);
      return $class;
  }
  add_filter('comment_reply_link', 'tw_reply_link_class');
}


/**
 * Echos comment layout
 * @param $comments
 * @param $args
 * @param $depth
 */
if(!function_exists('tw_comments')){
  function tw_comments($comment, $args, $depth) {
     $GLOBALS['comment'] = $comment; ?>
     <li id="comment-<?php comment_ID(); ?>" class="clearfix media" itemscope itemtype="http://schema.org/UserComments">
        <a href="<?php echo get_comment_author_url();?>" title="<?php echo get_comment_author() ;?>" target="_blank" class="pull-left">
          <?php echo get_avatar( $comment, $size='75' ); ?>
        </a>
        <div class="media-body">
          <h4 class="media-heading comment-author vcard">
          <?php printf('<span itemprop="creator">%s</span>', get_comment_author_link()) ?>
          </h4>

          <?php if ($comment->comment_approved == '0') : ?>
      				<div class="alert alert-success">
          				<p><?php _e('Your comment is awaiting moderation.','tw') ?></p>
      				</div>
          <?php endif; ?>
  				<div class="comment-content" itemprop="commentText"><?php comment_text() ?></div>

  				<time itemprop="commentTime" datetime="<?php echo comment_time('Y-m-j'); ?>T<?php echo comment_time('H:i:s'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time('g:ia F jS, Y'); ?> </a></time>

  				<div class="row comment-options">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 comment-reply">
                <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 comment-edit">
              <?php edit_comment_link(__('Edit','tw'),'<span class="edit-comment btn btn-xs btn-default"><i class="fa fa-edit"></i> ','</span>') ?></div>
  				</div>
        </div>
      <!-- </li> is added by wordpress automatically -->
  <?php
  }
}

/**
 * Echos comment placeholders
 * @param $fields
 */
if(!function_exists('tw_comment_placeholders')){
  add_filter( 'comment_form_default_fields', 'tw_comment_placeholders' );
  function tw_comment_placeholders( $fields ){
      $fields['author'] = str_replace( '<input', '<div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span><input placeholder="'
              . _x( 'First and last name or a nick name *', 'comment form placeholder', 'tw' ) . '"', $fields['author'] );
      $fields['author'] = str_replace( '<p class="comment-form-author">', '<p class="comment-form-author form-group">', $fields['author'] );
      $fields['author'] = str_replace( '</p>', '</div></p>', $fields['author'] );
      $fields['author'] = str_replace( '<label ', '<label class="sr-only"', $fields['author'] );


      $fields['email'] = str_replace( '<input', '<div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span><input placeholder="'
              . _x( 'contact@example.com *', 'comment form placeholder', 'tw' ) . '"', $fields['email'] );
      $fields['email'] = str_replace( '<p class="comment-form-email">', '<p class="comment-form-author form-group">', $fields['email'] );
      $fields['email'] = str_replace( '</p>', '</div></p>', $fields['email'] );
      $fields['email'] = str_replace( '<label ', '<label class="sr-only"', $fields['email'] );

      $fields['url'] = str_replace( '<input', '<div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span><input placeholder="'
              . _x( 'http://example.com', 'comment form placeholder', 'tw' ) . '"', $fields['url'] );
      $fields['url'] = str_replace( '<p class="comment-form-email">', '<p class="comment-form-author form-group">', $fields['url'] );
      $fields['url'] = str_replace( '</p>', '</div></p>', $fields['url'] );
      $fields['url'] = str_replace( '<label ', '<label class="sr-only"', $fields['url'] );

      return $fields;
  }
}


/******************************************************
***************** Pagination ************************
******************************************************/
if(!function_exists('tw_pagination')){
  function tw_pagination($pages = '', $range = 4, $paged = null){
     $showitems = ($range * 2)+1;

     if(is_null($paged)){
       global $paged;
       if(empty($paged)) $paged = 1;
     }

     if($pages == ''){
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages){ $pages = 1; }
     }

     if(1 != $pages){
         echo "<ul class=\"hidden-print pagination\">";
         //echo "<span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>&laquo; First</a></li>";
         if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a></li>";

         for ($i=1; $i <= $pages; $i++){
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
                 echo ($paged == $i)? "<li class=\"active\"><a href='".get_pagenum_link($i)."'>".$i."</a></li>":
                                      "<li><a href='".get_pagenum_link($i)."' >".$i."</a></li>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<li><a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a></li>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>Last &raquo;</a></li>";
         echo "</ul>\n";
     }
  }
}


/******************************************************
*********************** Footer ************************
******************************************************/
if(!function_exists('tw_credit')){
  function tw_credit(){
    $credit =  __('Designed & Developed by <a href="http://www.thirdwunder.com/" title="Third Wunder">Third Wunder</a>','tw');
    $new_credit = trim(get_field('tw_footer_credit','option'));
    if(!empty($new_credit)){
      $credit = $new_credit;
    }
    echo $credit;
  }
}

if(!function_exists('tw_copyright')){
	function tw_copyright(){
	  $copyright =  '&copy; '.date('Y').' '.get_bloginfo('name').' '.__('All Rights Reserved','tw');
	  $new_copyright = trim(get_field('tw_footer_copyright','option'));
	  if(!empty($new_copyright)){
  	  $copyright =  $new_copyright;
	  }
	  echo $copyright;
	}
}

/******************************************************
***************** Image Functions *******************
******************************************************/

if(!function_exists( 'tw_get_post_images' ) ) {
	function tw_get_post_images( $image_sizes = array(), $offset = 1 ) {
    global $post;

    $sizes = array(
                    '4x3-small',
                    '4x3-medium',
                    '4x3-large',
                  );
    for($i=0;$i<count($image_sizes); $i++){
      $sizes[$i] = $image_sizes[$i];
    }
    $img_size = $sizes[1]; // The WP "size" to use for the large image
    if(class_exists('Mobile_Detect')){
      $detect = new Mobile_Detect;
      $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
      switch($deviceType){
        case 'phone';
          $img_size = $sizes[0];
          break;
        case 'tablet';
          $img_size = $sizes[1];
          break;
        case 'computer';
          $img_size = $sizes[2];
          break;
      }
    }


		// Arguments
		$repeat = 100; 				// Number of maximum attachments to get
		$output = array();

		$attachments = get_children( array(
  		'post_parent' => get_the_id(),
  		'numberposts' => $repeat,
  		'post_type' => 'attachment',
  		'post_mime_type' => 'image',
  		'order' => 'ASC',
  		'orderby' => 'menu_order date' )
		);
		if ( !empty($attachments) ) :
			$output = array();
			$count = 0;
			foreach ( $attachments as $att_id => $attachment ) {
				$count++;
				if ($count <= $offset) continue;
				$url = wp_get_attachment_image_src($att_id, $img_size, true);
				$alt = trim(strip_tags( get_post_meta($att_id, '_wp_attachment_image_alt', true) ));

				$output[] = array(
				    'id' => $att_id,
  				  'alt'=>$alt ,
				    'url' => $url[0],
  				  'caption' => $attachment->post_excerpt,
  				  'description'=>$attachment->post_content,

				  );
			}
		endif;
		return $output;
	} // End woo_get_post_images()
}

/**
 * Returns Image src based on given array of sizes and device type
 * @param: int image_id
 * @param: array image_sizes
 * @return: image source
 */
if(!function_exists('tw_get_image_src')){
  function tw_get_image_src($image_id, $image_sizes = array()){

    $sizes = array(
                    '4x3-small',
                    '4x3-medium',
                    '4x3-large',
                  );
    for($i=0;$i<count($image_sizes); $i++){
      $sizes[$i] = $image_sizes[$i];
    }
    $img_size = $sizes[1];
    if(class_exists('Mobile_Detect')){
      $detect = new Mobile_Detect;
      $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
      switch($deviceType){
        case 'phone';
          $img_size = $sizes[0];
          break;
        case 'tablet';
          $img_size = $sizes[1];
          break;
        case 'computer';
          $img_size = $sizes[2];
          break;
      }
    }

    $img_src = wp_get_attachment_image_src($image_id, $img_size);
    $img_src = $img_src[0];

    return $img_src;
  }
}

if(!function_exists('tw_get_default_image')){
  function tw_get_default_image(){
   return get_template_directory_uri().'/assets/img/default.png';
  }
}


/**
 * Returns image html based on given array of sizes and device type
 * @param: array image_id
 * @param: array image_sizes
 * @param: array attributes
 * @return: image html output
 */
if(!function_exists('tw_get_the_post_thumbnail')){
  function tw_get_the_post_thumbnail($img_id, $image_sizes = array(), $attr = array() ){
    //global $post;

    $sizes = array(
                    '4x3-small',
                    '4x3-medium',
                    '4x3-large',
                  );
    for($i=0;$i<count($image_sizes); $i++){
      $sizes[$i] = $image_sizes[$i];
    }

    $src = array( tw_get_default_image(), '', '' );
    $img_size = $sizes[1];

    $src = wp_get_attachment_image_src($img_id, $img_size);

    $alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);

    $html = '<img ';
    if(class_exists('Mobile_Detect')){
      $detect = new Mobile_Detect;
      $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
      switch($deviceType){
        case 'phone';
          $img_size = $sizes[0];
          break;
        case 'tablet';
          $img_size = $sizes[1];
          break;
        case 'computer';
          $img_size = $sizes[2];
          break;
      }
    }

    $html .= 'src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" alt="'.$alt.'" ';
    $class = 'attachment-'.$img_size.' wp-post-image';
    foreach($attr as $k=>$v){
      if($k=='class'){
        $class .= ' '.$v;
      }else{
       $html .= $k.'="'.$v.'"';
      }
    }
    $html .= ' class="'.$class.'"';

    $html .= ' />';
    return $html;
  }
}

/**
 * Returns image html based on given array of sizes and device type
 * @param: array image_sizes
 * @param: array attributes
 * @return: image html output
 */
if(!function_exists('tw_the_post_thumbnail')){
  function tw_the_post_thumbnail($image_sizes = array(), $attr = array(), $post_id=null){
    if(is_null($post_id)){
      global $post;
      $post_id = $post->ID;
    }

    $sizes = array(
                    'phone'    =>'4x3-small',
                    'tablet'   =>'4x3-medium',
                    'computer' =>'4x3-large',
                  );
    for($i=0;$i<count($image_sizes); $i++){
      switch ($i){
        case 0:
          $sizes['phone'] = $image_sizes[$i];
          break;
        case 1:
          $sizes['tablet'] = $image_sizes[$i];
          break;
        case 2:
          $sizes['computer'] = $image_sizes[$i];
          break;
      }
    }

    $src = array( tw_get_default_image(), '', '' );
    $img_size = $sizes['tablet'];

    $html = '<img ';
    if(class_exists('Mobile_Detect')){
      $detect = new Mobile_Detect;
      $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

      switch($deviceType){
        case 'phone';
          $img_size = $sizes[$deviceType];
          break;
        case 'tablet';
          $img_size = $sizes[$deviceType];
          break;
        case 'computer';
          $img_size = $sizes[$deviceType];
          break;
      }
    }

    if(has_post_thumbnail($post_id) ){
      $img_id = get_post_thumbnail_id($post_id);
      $src = wp_get_attachment_image_src($img_id, $img_size);

      $img_info = pathinfo($src[0]);
      $ext =  $img_info['extension'];
      if($ext == 'gif'){
        $src = wp_get_attachment_image_src($img_id, 'full');
      }
    }
    $alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);

    $html .= 'src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" alt="'.$alt.'" ';
    $class = 'attachment-'.$img_size.' wp-post-image';
    foreach($attr as $k=>$v){
      if($k=='class'){
        $class .= ' '.$v;
      }else{
       $html .= $k.'="'.$v.'"';
      }
    }
    $html .= ' class="'.$class.'"';

    $html .= ' />';
    return $html;
  }
}

if(!function_exists('tw_get_image_src_from_acf_array')){
  function tw_get_image_src_from_acf_array($acf_img, $image_sizes){
    $sizes = array(
                    '4x3-small',
                    '4x3-medium',
                    '4x3-large',
                  );
    for($i=0;$i<count($image_sizes); $i++){
      $sizes[$i] = $image_sizes[$i];
    }
    $img_size = $sizes[1];
    if(class_exists('Mobile_Detect')){
      $detect = new Mobile_Detect;
      $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
      switch($deviceType){
        case 'phone';
          $img_size = $sizes[0];
          break;
        case 'tablet';
          $img_size = $sizes[1];
          break;
        case 'computer';
          $img_size = $sizes[2];
          break;
      }
    }
    $img_src = $acf_img['sizes'][$img_size];
    return $img_src;
  }
}

/******************************************************
***************** General Functions *******************
******************************************************/

/**
 * Echos schema.org tag
 */
if(!function_exists('tw_html_tag_schema')){
  function tw_html_tag_schema($type=null, $itemscope=''){
    $schema = 'http://schema.org/';
    if(is_null($type) || $type=='' ){
      if(is_single()){
        // Is single post
        $type = "Article";
        //$type = "BlogPosting";
      } elseif( is_author() ){
        // Is author page
        $type = 'ProfilePage';
      }elseif( is_search() ){
        // Is search results page
        $type = 'SearchResultsPage';
      }else{
        $type = 'WebPage';
      }

    }

    echo 'itemscope="'.$itemscope.'" itemtype="' . $schema . $type . '"';
  }
}


if(!function_exists('tw_get_social_networks')){
  function tw_get_social_networks(){
    $social = array();

    $facebook   = trim(get_field('tw_facebook_url','option'));
    if($facebook && !empty($facebook)){
      $social['facebook'] = array(
        'url' => $facebook,
        'icon' => 'fa-facebook',
      );
    }

    $twitter    = trim(get_field('tw_twitter_handle','option'));
    if($twitter && !empty($twitter)){
      $social['twitter'] = array(
        'username' => $twitter,
        'url' => 'https://twitter.com/'.$twitter,
        'icon' => 'fa-twitter',
      );
    }

    $instagram  = trim(get_field('tw_instagram_handle','option'));
    if($instagram && !empty($instagram)){
      $social['instagram'] = array(
        'username' => $instagram,
        'url' => 'https://www.instagram.com/'.$instagram,
        'icon' => 'fa-instagram',
      );
    }

    $pinterest  = trim(get_field('tw_pinterest_url','option'));
    if($pinterest && !empty($pinterest)){
      $social['pinterest'] = array(
        'url' => $pinterest,
        'icon' => 'fa-pinterest',
      );
    }

    $tumblr     = trim(get_field('tw_tumblr_url','option'));
    if($tumblr && !empty($tumblr)){
      $social['tumblr'] = array(
        'url' => $tumblr,
        'icon' => 'fa-tumblr',
      );
    }

    $linkedin   = trim(get_field('tw_linkedin_url','option'));
    if($linkedin && !empty($linkedin)){
      $social['linkedin'] = array(
        'url' => $linkedin,
        'icon' => 'fa-linkedin',
      );
    }

    $slideshare = trim(get_field('tw_slideshare_url','option'));
    if($slideshare && !empty($slideshare)){
      $social['slideshare'] = array(
        'url' => $slideshare,
        'icon' => 'fa-slideshare',
      );
    }

    $googleplus = trim(get_field('tw_googleplus_url','option'));
    if($googleplus && !empty($googleplus)){
      $social['googleplus'] = array(
        'url' => $googleplus,
        'icon' => 'fa-google-plus',
      );
    }

    $youtube    = trim(get_field('tw_youtube_url','option'));
    if($youtube && !empty($youtube)){
      $social['youtube'] = array(
        'url' => $youtube,
        'icon' => 'fa-youtube',
      );
    }

    $vimeo     = trim(get_field('tw_vimeo_url','option'));
    if($vimeo && !empty($vimeo)){
      $social['vimeo'] = array(
        'url' => $vimeo,
        'icon' => 'fa-vimeo-square',
      );
    }

    $enable_rss = get_field('enable_rss_feed','option');
    if($enable_rss && count($social)>1){
      $social['rss'] = array('url'=> get_bloginfo('rss2_url') ,'icon'=>'fa-rss');
    }

    return $social;
  }
}

/**
 * Returns array of social network information formatted for easily display from Theme Options
 * @return array social
 */
if(!function_exists('tw_get_theme_social_options')){
  function tw_get_theme_social_options(){
    $social_info   = tw_get_social_networks();
     return $social;
  }
}

if(!function_exists('tw_get_contact_info')){
  function tw_get_contact_info(){
    $contact_info = array();

    $phone      = trim(get_field('tw_phone','option'));
    if($phone && !empty($phone)) {
      $contact_info['phone'] = $phone;
    }

    $toll_free  = trim(get_field('tw_toll_free_phone','option'));
    if($toll_free && !empty($toll_free)) {
      $contact_info['toll_free'] = $toll_free;
    }

    $fax        = trim(get_field('tw_fax','option'));
    if($fax && !empty($fax)) {
      $contact_info['fax'] = $fax;
    }

    $email      = trim(get_field('tw_email','option'));
    if($email && !empty($email)) {
      $contact_info['email'] = $email;
    }

    $enable_phone_in_menu = get_field('tw_enable_phone_in_menu','option');
    if($enable_phone_in_menu){
      $phone_in_menu = get_field('tw_phone_in_menu','option');
      $contact_info['phone_in_menu'] = $phone_in_menu;
    }

    $addr_1   = trim(get_field('tw_address_1','option'));
    if($addr_1 && !empty($addr_1)) {
      $contact_info['address_1'] = $addr_1;
    }
    $addr_2   = trim(get_field('tw_address_2','option'));
    if($addr_2 && !empty($addr_2)) {
      $contact_info['address_2'] = $addr_2;
    }

    $city     = trim(get_field('tw_city','option'));
    if($city && !empty($city)) {
      $contact_info['city'] = $city;
    }

    $state    = trim(get_field('tw_state','option'));
    if($state && !empty($state)) {
      $contact_info['state'] = $state;
    }

    $postcode = trim(get_field('tw_postcode','option'));
    if($postcode && !empty($postcode)) {
      $contact_info['postcode'] = $postcode;
    }

    $country  = trim(get_field('tw_country','option'));
    if($country && !empty($country)) {
      $contact_info['country'] = $country;
    }

    $enable_map = get_field('tw_enable_google_map','option');
    if($enable_map){
      $map = get_field('tw_google_map','option');
      if(is_array($map)){
        $contact_info['map'] = $map;
      }
    }


    return $contact_info;
  }
}

/******************************************************
**************** Formatting Functions ****************
******************************************************/
if(!function_exists('tw_address_to_google_map_url')){
  function tw_address_to_google_map_url(){
    $url_base = 'http://maps.google.com/?q=';
    $contact_info = tw_get_contact_info();
    $address_1 = isset($contact_info['address_1']) ? $contact_info['address_1'] : '';
    $address_2 = isset($contact_info['address_2']) ? $contact_info['address_2'] : '';
    $city = isset($contact_info['city']) ? $contact_info['city'] : '';
    $postcode = isset($contact_info['postcode']) ? $contact_info['postcode'] : '';
    $state = isset($contact_info['state']) ? $contact_info['state'] : '';
    $country = isset($contact_info['country']) ? $contact_info['country'] : '';
    $query_string = urlencode(trim($address_1.' '.$address_2.' '.$city.' '.$postcode.' '.$state.' '.$country));
    $map_url = $url_base.$query_string;
    return $map_url;
  }
}

/******************************************************
****************** Option Functions *******************
******************************************************/
if(!function_exists('tw_get_social_options')){
  function tw_get_social_options(){
    $social_options = get_option('tw_theme_social_options') ? get_option('tw_theme_social_options') : false;
    return $social_options;
  }
}

if(!function_exists('tw_email_subscribe_gform')){
  function tw_email_subscribe_gform(){
    $gform_id = get_field('tw_gf_newsletter_form','option');
    return $gform_id;
  }
}

if(!function_exists('tw_get_newsletter_gform')){
  function tw_newsletter_gform(){
    $gform_id = get_field('tw_gf_newsletter_form','option');
    return $gform_id;
  }
}

if(!function_exists('tw_is_footer_newsletter_enabled')){
  function tw_is_footer_newsletter_enabled(){
    $is_enabled = get_field('tw_gf_newsletter_form_in_footer','option');
    return $is_enabled;
  }
}

if(!function_exists('tw_get_contact_gform')){
  function tw_get_contact_gform(){
    $general_options = tw_get_general_options();
    $contact_gform_id = isset($general_options['contact_gform']) ? $general_options['contact_gform'] : false;
    return $contact_gform_id;
  }
}

if(!function_exists('tw_get_logo')){
  function tw_get_logo(){
    $logo = get_stylesheet_directory_uri().'/assets/img/logo-icon.png';
    $new_logo = get_field('tw_site_logo','option');
    if(!empty($new_logo) ){
      $logo = trim($new_logo);
    }
    return $logo;
  }
}

if(!function_exists('tw_get_logo_alt')){
  function tw_get_logo_alt(){
    $logo = get_stylesheet_directory_uri().'/assets/img/logo-alt.png';
    $new_logo = get_field('tw_site_logo_alt','option');
    if(!empty($new_logo) ){
      $logo = trim($new_logo);
    }
    return $logo;
  }
}

if(!function_exists('tw_get_favicon')){
  function tw_get_favicon(){
    $favicon = get_stylesheet_directory_uri().'/assets/img/favicon.png';
    $new_favicon = get_field('tw_site_favicon','option');
    if(!empty($new_favicon)){
      $favicon = $new_favicon;
    }
    return $favicon;
  }
}

if(!function_exists('tw_get_apple_icon')){
  function tw_get_apple_icon(){
    $icon = get_stylesheet_directory_uri().'/assets/img/apple-touch-icon.png';
    $new_icon = get_field('tw_icon_iphone_3','option');
    if(!empty($new_icon)){
      $icon = $new_icon;
    }
    return $icon;
  }
}

if(!function_exists('tw_get_apple_icon_72')){
  function tw_get_apple_icon_72(){
    $icon = get_stylesheet_directory_uri().'/assets/img/apple-touch-icon-72.png';
    $new_icon = get_field('tw_icon_iphone_4','option');
    if(!empty($new_icon)){
      $icon = $new_icon;
    }
    return $icon;
  }
}

if(!function_exists('tw_get_apple_icon_114')){
  function tw_get_apple_icon_114(){
    $icon = get_stylesheet_directory_uri().'/assets/img/apple-touch-icon-114.png';
    $new_icon = get_field('tw_icon_iphone_retina','option');
    if(!empty($new_icon)){
      $icon = $new_icon;
    }
    return $icon;
  }
}

if(!function_exists('tw_get_apple_icon_144')){
  function tw_get_apple_icon_144(){
    $icon = get_stylesheet_directory_uri().'/assets/img/apple-touch-icon-144.png';
    $new_icon = get_field('tw_icon_ipad','option');
    if(!empty($new_icon)){
      $icon = $new_icon;
    }
    return $icon;
  }
}

if(!function_exists('tw_is_mobile_menu_search_enabled')){
  function tw_is_mobile_menu_search_enabled(){
    $mobile_menu_search = get_field('tw_enable_mobile_menu_search', 'option');
    return $mobile_menu_search;
  }
}

if(!function_exists('tw_is_footer_menu_enabled')){
  function tw_is_footer_menu_enabled(){
    $enable_footer_menu = get_field('tw_enable_footer_menu','option');
    return $enable_footer_menu;
  }
}

if(!function_exists('tw_is_sidebar_enabled')){
  function tw_is_sidebar_enabled(){
    $sidebar = get_field('tw_enable_primary_sidebar','option');
    return $sidebar;
  }
}

if(!function_exists('tw_get_slider_style')){
  function tw_get_slider_style(){
    $style = get_field('tw_slider_transition','option');
    return $style;
  }
}

if(!function_exists('get_theme_twitter_username')){
  function get_theme_twitter_username(){
    $twitter_username = trim(get_field('tw_twitter_handle','option'));
    return $twitter_username;
  }
}

if(!function_exists('get_facebook_app_id')){
  function get_facebook_app_id(){
    $app_id = get_field('tw_facebook_app_id','option');
    return $app_id;
  }
}

if(!function_exists('tw_is_fb_coments_enabled')){
  function tw_is_fb_coments_enabled(){
    $fb_comments = false;
    $enable_fb =  get_field('tw_enable_facebook_app','option');
    $fb_app_id =  trim(get_field('tw_facebook_app_id','option'));
    $comment_options = get_field('tw_commenting_options','option');

    if($enable_fb && !empty($fb_app_id) &&  ($comment_options=='fb_comments' || $comment_options=='wp_fb_comments' )){
      $fb_comments = true;
    }
    return $fb_comments;
  }
}

if(!function_exists('tw_coment_options')){
  function tw_coment_options(){
    $comment_options = get_field('tw_commenting_options','option');
    return $comment_options;
  }
}

if(!function_exists('tw_is_blog_sidebar_enabled')){
  function tw_is_blog_sidebar_enabled(){
    $sidebar = get_field('tw_enable_blog_sidebar','option');
    return $sidebar;
  }
}

if(!function_exists('tw_is_related_posts_enabled')){
  function tw_is_related_posts_enabled(){
    $related_posts = get_field('tw_blog_related_posts','option');
    return $related_posts;
  }
}

if(!function_exists('tw_get_twitter_api')){
  function tw_get_twitter_api(){
    $twitter_api = array();
    $enable_twitter = get_field('tw_enable_twitter_api','option');
    if($enable_twitter){
      $access_token = trim(get_field('tw_twitter_access_token','option'));
      $access_token_secret = trim(get_field('tw_twitter_access_token_secret','option'));
      $consumer_key = trim(get_field('tw_twitter_consumer_key','option'));
      $consumer_secret = trim(get_field('tw_twitter_consumer_secret','option'));

      if(!empty($access_token)){
        $twitter_api['oauth_access_token'] = $access_token;
      }
      if(!empty($access_token_secret)){
        $twitter_api['oauth_access_token_secret'] = $access_token_secret;
      }
      if(!empty($consumer_key)){
        $twitter_api['consumer_key'] = $consumer_key;
      }
      if(!empty($consumer_secret)){
        $twitter_api['consumer_secret'] = $consumer_secret;
      }
    }

    if(count($twitter_api)>0){
      return $twitter_api;
    }
    return false;
  }
}



/******************************************************
***************** Shared Count API *******************
******************************************************/
if(!function_exists('get_sharedcount_api_key')){
  function get_sharedcount_api_key(){
    $social_options = tw_get_social_options();
    $api_key = (is_array($social_options) && isset($social_options['sharedcount_id']) && trim($social_options['sharedcount_id'])!=='' ) ? trim($social_options['sharedcount_id']) : false;
    return $api_key;
  }
}

if(!function_exists('tw_get_sharedcount_json')){
  function tw_get_sharedcount_json($url){
    $api_key = get_sharedcount_api_key();
    if($api_key){
      $request = wp_remote_get("http://free.sharedcount.com/?url=" . rawurlencode($url) . "&apikey=" . $api_key);
      try{
        $response = wp_remote_retrieve_body( $request );
        $json = json_decode($response, true);
      }catch(Exception $e){
        $json = null;
      }

      return $json;
    }else{
      return null;
    }
  }
}

if(!function_exists('tw_get_sharedcount_total')){
  function tw_get_sharedcount_total($url){
    $total = 0;
    $counts = tw_get_sharedcount_json($url);
    if(!is_null($counts)){
      $twitter = isset($counts['Twitter']) ? intval($counts["Twitter"]) : 0;
      $facebook = (isset($counts['Facebook']) && isset($counts['Facebook']['like_count']) ) ? intval($counts['Facebook']['like_count']) : 0;
      $gplus = isset($counts["GooglePlusOne"])? intval($counts["GooglePlusOne"]) : 0;
      $pinterest = isset($counts["Pinterest"])? intval($counts["Pinterest"]) : 0;
      $total = intval($twitter) + intval($facebook) + intval($gplus) + intval($pinterest);
    }else{
      $total= null;
    }

    return $total;
  }
}

/******************************************************
******************* Theme Actions *********************
******************************************************/
/**
 * Adds FB Script to footer for FB Comments
 */
add_action( 'wp_footer', 'tw_fb_script' );
function tw_fb_script(){
  $fb_app_id = get_facebook_app_id();
  $fb_comments = tw_is_fb_coments_enabled();
  if($fb_app_id):
  if(is_singular('post') || is_home()):
?>
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=<?php echo $fb_app_id; ?>&version=v2.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
<?php endif; endif;
}