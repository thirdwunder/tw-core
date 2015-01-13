<?php
/**
 * Wordpress Functions
 *
 */

/******************************************************
************* Theme Support Functions ****************
******************************************************/

/**
 * Adds custom image sizes based on parameteres
 * @param string  $ratio
 * @param integer $size
 * @param boolean $hardcrop - default true
 * @param boolean $unlimited_height - default false
 * @param array   $postion
 */
function tw_add_image_size($ratio, $size, $hard_crop = true, $unlimited_height = false, $postion = array()){
  $img_widths = array(
                      'xlarge'=>2048,
                      'large'=>1024,
                      'medium'=>800,
                      'small'=>400,
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
    if(!is_null($theme_general_options) && is_array($theme_general_options)){
      if(isset($theme_general_options['enable_top_menu']) && $theme_general_options['enable_top_menu']==true){
        $theme_menus['top'] = 'Top Menu';
      }
      if(isset($theme_general_options['enable_footer_menu']) && $theme_general_options['enable_footer_menu']==true){
        $theme_menus['footer'] = 'Footer Menu';
      }
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
    $tw_blog_options = tw_get_blog_options();
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
  if(is_array($tw_blog_options)){
    $enabled_post_formats = array();
    foreach($post_formats as $pf){
      if($tw_blog_options[$pf]){
        $enabled_post_formats[] = $pf;
      }
    }
    add_theme_support( 'post-formats',$enabled_post_formats);
  }


  }
  add_action('after_setup_theme','tw_post_formats');
}


/******************************************************
********************** Widgets ************************
******************************************************/

if(!function_exists('tw_register_sidebars')){
  function tw_register_sidebars() {
    $theme_general_options = get_option('tw_theme_general_options') ? get_option('tw_theme_general_options') : null;
    $primary_sidebar = $theme_general_options['enable_sidebar'];
    if($primary_sidebar){
      register_sidebar(array(
      	'id' => 'primary',
      	'name' => 'Primary Sidebar',
      	'description' => 'Primary Widget Area',
      	'before_widget' => '<div id="%1$s" class="widget %2$s">',
      	'after_widget' => '</div>',
      	'before_title' => '<h4 class="widget-title">',
      	'after_title' => '</h4>',
      ));
    }

    $homepage_sidebar = $theme_general_options['enable_homepage_sidebar'];
    if($homepage_sidebar){
      register_sidebar(array(
      	'id' => 'homepage',
      	'name' => 'Homepage Widget Area',
      	'description' => 'Homepage Widget Area. Widgets here will show in the widgetized homepage template.',
      	'before_widget' => '<div id="%1$s" class="widget %2$s">',
      	'after_widget' => '</div>',
      	'before_title' => '<h2 class="widget-title">',
      	'after_title' => '</h2>',
      ));
    }


    $footer_widgets = $theme_general_options['enable_footer_widgets'];

    if($footer_widgets>0){
     for($i=1; $i<=$footer_widgets; $i++){
      register_sidebar(array(
      	'id' => 'footer-'.$i,
      	'name' => 'Footer Widget Area '.$i,
      	'description' => '',
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
  function tw_favicon(){?>
    <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/favicon.png" />
  <?php
  }
  add_action( 'wp_head', 'tw_favicon', 10 );
}

/***** Add apple icons ****/
/**
 * Sets Apple iOS icons url in header
 */
if(!function_exists('tw_apple_icon')){
  function tw_apple_icon() { ?>
    		<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon.png" />
      <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-72x72.png" />
      <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-114x114.png" />
      <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-144x144.png" />
  	<?php
  }
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
 * Echo's post navigation
 */
if(!function_exists('tw_post_nav')){
  function tw_post_nav() {
  	global $post;

  	// Don't print empty markup if there's nowhere to navigate.
  	$previous = get_adjacent_post( false, '', true );
  	$next     = get_adjacent_post( false, '', false );

  	if ( ! $next && ! $previous )
  		return;
  	?>
  	<nav class="navigation post-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
  		<h3 class="sr-only"><?php _e( 'Post navigation', 'tw' ); ?></h3>
      <ul class="pager nav-links">
        <?php
          if($previous){
            ?>
            <li class="previous" itemprop="url"><a href="<?php echo get_permalink($previous->ID);?>" title="<?php echo $previous->post_title;?>"><i class="fa fa-long-arrow-left"></i> <?php _e('Previous Article','tw'); ?></a></li>
            <?php
            //previous_post_link( '<li class="previous" itemprop="url">%link</li>', _x( '<i class="fa fa-long-arrow-left"></i> Previous Article', 'Previous Article', 'tw' ) );
          }

          if($next){
            ?>
            <li class="next" itemprop="url"><a href="<?php echo get_permalink($next->ID);?>" title="<?php echo $next->post_title;?>"><?php _e('Next Article','tw'); ?> <i class="fa fa-long-arrow-right"></i></a></li>
            <?php
            //next_post_link( '<li class="next" itemprop="url">%link</li>', _x( '%title <i class="fa fa-long-arrow-right"></i>', 'Next Article', 'tw' ) );
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
if(class_exists('AT_Meta_Box')){
  $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
  $template_file = get_post_meta($post_id,'_wp_page_template', TRUE);

  $blog_options = get_option('tw_theme_blog_options') ? get_option('tw_theme_blog_options') : null;
  if(is_array($blog_options)){
    $prefix = 'tw_';

    $video_pf = isset($blog_options['video']) ? !! $blog_options['video'] : false;
    $quote_pf = isset($blog_options['quote']) ? !! $blog_options['quote'] : false;
    $audio_pf = isset($blog_options['audio']) ? !! $blog_options['audio'] : false;
    // Video Post Format Options
    if($video_pf){

      $video_config = array(
        'id'             => 'video_metabox',          // meta box id, unique per meta box
        'title'          => 'Video Post Format Options',          // meta box title
        'pages'          => array('post'),      // post types, accept custom post types as well, default is array('post'); optional
        'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
        'priority'       => 'high',            // order of meta box: high (default), low; optional
        'fields'         => array(),            // list of meta fields (can be added by field arrays)
        'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
        'use_with_theme' => get_stylesheet_directory_uri() . '/includes/My-Meta-Box/meta-box-class',
      );
      $video_post_meta =  new AT_Meta_Box($video_config);
      $video_post_meta->addText($prefix.'video_url',array('name'=> 'Video URL', 'desc'=>'Enter a Youtube or Vimeo URL. <br/>Video will be shown when the Video post format is selected.'));
      $video_post_meta->Finish();
    }

    if($quote_pf){
      $quote_config = array(
        'id'             => 'quote_metabox',          // meta box id, unique per meta box
        'title'          => 'Quote Post Format Options',          // meta box title
        'pages'          => array('post'),      // post types, accept custom post types as well, default is array('post'); optional
        'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
        'priority'       => 'high',            // order of meta box: high (default), low; optional
        'fields'         => array(),            // list of meta fields (can be added by field arrays)
        'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
        'use_with_theme' => get_stylesheet_directory_uri() . '/includes/My-Meta-Box/meta-box-class',
      );
      $quote_post_meta =  new AT_Meta_Box($quote_config);
      $quote_post_meta->addText($prefix.'quote_author',array('name'=> 'Author', 'desc'=>''));
      $quote_post_meta->addText($prefix.'quote_source',array('name'=> 'Source', 'desc'=>'Publication or origin source'));
      $quote_post_meta->addText($prefix.'quote_source_url',array('name'=> 'Source URL', 'desc'=>'URL to the quotes original source.'));
      $quote_post_meta->addTextarea($prefix.'quote',array('name'=> 'Quote', 'desc'=>''));
      $quote_post_meta->Finish();
    }

    if($audio_pf){
      $audio_config = array(
        'id'             => 'audio_metabox',          // meta box id, unique per meta box
        'title'          => 'Audio Post Format Options',          // meta box title
        'pages'          => array('post'),      // post types, accept custom post types as well, default is array('post'); optional
        'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
        'priority'       => 'high',            // order of meta box: high (default), low; optional
        'fields'         => array(),            // list of meta fields (can be added by field arrays)
        'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
        'use_with_theme' => get_stylesheet_directory_uri() . '/includes/My-Meta-Box/meta-box-class',
      );
      $audio_post_meta =  new AT_Meta_Box($audio_config);
      $audio_post_meta->addText($prefix.'audio_title',array('name'=> 'Audio File Title'));
      $audio_post_meta->addText($prefix.'audio_source',array('name'=> 'Audio File URL', 'desc'=>'Audio player will be shown when the Audio post format is selected.'));
      $audio_post_meta->Finish();
    }

  }

  if($template_file =='template-homepage.php'){
    $homepage_config = array(
        'id'             => 'homepage_metabox',          // meta box id, unique per meta box
        'title'          => 'Homepage Options',          // meta box title
        'pages'          => array('page'),      // post types, accept custom post types as well, default is array('post'); optional
        'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
        'priority'       => 'high',            // order of meta box: high (default), low; optional
        'fields'         => array(),            // list of meta fields (can be added by field arrays)
        'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
        'use_with_theme' => get_stylesheet_directory_uri() . '/includes/My-Meta-Box/meta-box-class',
      );
    $homepage_post_meta =  new AT_Meta_Box($homepage_config);

    $jumbotron_fields[] = $homepage_post_meta->addText($prefix.'jumbotron_title',array('name'=> 'Title'),true);
    $jumbotron_fields[] = $homepage_post_meta->addTextarea($prefix.'jumbotron_text',array('name'=> 'Text'),true);
    $jumbotron_fields[] = $homepage_post_meta->addText($prefix.'jumbotron_button_title',array('name'=> 'CTA Title'),true);
    $jumbotron_fields[] = $homepage_post_meta->addText($prefix.'jumbotron_button_url',array('name'=> 'CTA URL'),true);
    $jumbotron_fields[] = $homepage_post_meta->addImage($prefix.'jumbotron_bg_image',array('name'=> 'Background Image'), true);
    $jumbotron_fields[] = $homepage_post_meta->addRadio($prefix.'jumbotron_color',array('light'=>'Light','dark'=>'Dark'),array('name'=> 'Text Color','desc'=>'Choose the body text colour based on the background image.', 'std'=> array('dark')), true);
    $jumbotron_fields[] = $homepage_post_meta->addText($prefix.'jumbotron_video_url',array('name'=> 'Video URL','desc'=>'Add a youtube or vimeo video to your jumbotron. **optional'),true);
    $jumbotron_fields[] = $homepage_post_meta->addImage($prefix.'jumbotron_video_poster',array('name'=> 'Video Poster','desc'=>'Poster image to overlay over video. **optional'), true);

    $homepage_post_meta->addCheckbox($prefix.'homepage_show_content',array('name'=> 'Show content','desc'=>'Show the content of this page.'));

    $homepage_post_meta->addCondition($prefix.'homepage_jumbotron',
      array(
        'name'   => __('Jumbotron','tw'),
        'desc'   => __('<small>Enable a large sized jumobtron area at the header of this page.</small>','mmb'),
        'fields' => $jumbotron_fields,
        'std'    => false
      ));

    $homepage_post_meta->Finish();

  }

}

/******************************************************
********************* Comments ************************
******************************************************/
/**
 * Returns user avatar class with Bootstrap cirle classes
 * @param string $class
 * @return string $class
 */

add_filter('get_avatar','tw_round_avatar_css');
function tw_round_avatar_css($class) {
  $class = str_replace("class='avatar", "itemprop='image' class='avatar img-circle media-object", $class) ;
  return $class;
}

/**
 * Returns comment reply link with Bootstrap button classes
 * @param string $class
 * @return string $class
 */
add_filter('comment_reply_link', 'tw_reply_link_class');
function tw_reply_link_class($class){
    $class = str_replace("class='comment-reply-link", "class='comment-reply-link btn btn-primary btn-xs", $class);
    return $class;
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
  add_filter( 'comment_form_default_fields', 'tw_comment_placeholders' );
}


/******************************************************
***************** Pagination ************************
******************************************************/
if(!function_exists('tw_pagination')){
  function tw_pagination($pages = '', $range = 4){
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == ''){
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages){ $pages = 1; }
     }

     if(1 != $pages){
         echo "<ul class=\"pagination\">";
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
    echo 'Designed & Developed by <a href="http://www.thirdwunder.com/" title="Third Wunder">Third Wunder</a>';
  }
}

if(!function_exists('tw_copyright')){
	function tw_copyright(){
	  echo '&copy; '.date('Y').' '.get_bloginfo('name').' '.__('All Rights Reserved','tw');
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
    return $img_src[0];
  }
}

/**
 * Returns image html based on given array of sizes and device type
 * @param: array image_sizes
 * @param: array attributes
 * @return: image html output
 */
if(!function_exists('tw_the_post_thumbnail')){
  function tw_the_post_thumbnail($image_sizes = array(), $attr = array() ){
    global $post;

    $sizes = array(
                    '4x3-small',
                    '4x3-medium',
                    '4x3-large',
                  );
    for($i=0;$i<count($image_sizes); $i++){
      $sizes[$i] = $image_sizes[$i];
    }

    $img_id = get_post_thumbnail_id($post->ID);
    $img_size = $sizes[1];

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

    $src = wp_get_attachment_image_src($img_id, $img_size);

    $html .= 'src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" alt="'.$alt.'"';
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


/******************************************************
***************** General Functions *******************
******************************************************/

/**
 * Echos schema.org tag
 */
if(!function_exists('tw_html_tag_schema')){
  function tw_html_tag_schema($type=null){
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

    echo 'itemscope="itemscope" itemtype="' . $schema . $type . '"';
  }
}


/**
 * Returns array of social network information formatted for easily display from Theme Options
 * @return array social
 */
if(!function_exists('tw_get_theme_social_options')){
  function tw_get_theme_social_options(){
     $social_info   = get_option('tw_theme_social_options');
     $social = array();
     foreach($social_info as $network => $value){
        $username = '';
        if($network!=='fb_app_id' && $network!=='sharedcount_id' && $network!=='enable_fb_comments'){

          switch ($network) {
            case 'fb_page':
                $network = 'facebook';
                $icon = 'fa-facebook-square';
                break;
            case 'twitter':
                $username = $value;
                $url  = 'http://twitter.com/'.$value;
                $icon = 'fa-twitter-square';
                break;
            case 'instagram':
                $username = $value;
                $url  = 'http://instagram.com/'.$value;
                $icon = 'fa-instagram';
                break;
            case 'pinterest':
                $icon = 'fa-pinterest-square';
                break;
            case 'linkedin':
                $icon = 'fa-linkedin-square';
                break;
            case 'googleplus':
                $icon = 'fa-google-plus-square';
                break;
            case 'youtube':
                $icon = 'fa-youtube-square';
                break;
            case 'vimeo':
                $icon = 'fa-vimeo-square';
                break;
            case 'flickr':
                $icon = 'fa-flickr';
                break;
            case 'slideshare':
                $icon = 'fa-slideshare';
                break;
            case 'tumblr':
                $icon = 'fa-tumblr-square';
                break;
          }

          if(!empty($value)){
            $social[$network] = array(
                                  'url' =>$value,
                                  'icon'=>$icon
                                );
            if($username){
              $social[$network]['username'] = $username;
            }
          }


        }
     }
     return $social;
  }
}


/******************************************************
****************** Option Functions *******************
******************************************************/
function tw_get_general_options(){
  $general_options = get_option('tw_theme_general_options') ? get_option('tw_theme_general_options') : false;
  return $general_options;
}

function tw_get_blog_options(){
  $blog_options = get_option('tw_theme_blog_options') ? get_option('tw_theme_blog_options') : false;
  return $blog_options;
}

function tw_get_social_options(){
  $social_options = get_option('tw_theme_social_options') ? get_option('tw_theme_social_options') : false;
  return $social_options;
}

function tw_is_top_menu_enabled(){
  $general_options = tw_get_general_options();
  $top_menu = isset($general_options['enable_top_menu']) ? !!$general_options['enable_top_menu'] : false;
  return $top_menu;
}

function tw_is_footer_menu_enabled(){
  $general_options = tw_get_general_options();
  $footer_wigets = isset($general_options['enable_footer_menu']) ? !!$general_options['enable_footer_menu'] : false;
  return $footer_wigets;
}

function tw_is_sidebar_enabled(){
  $general_options = tw_get_general_options();
  $sidebar = isset($general_options['enable_sidebar']) ? !!$general_options['enable_sidebar'] : false;
  return $sidebar;
}


function tw_is_fb_coments_enabled(){
  $fb_comments = false;
  $social_options = tw_get_social_options();
  if(is_array($social_options) && isset($social_options['enable_fb_comments'])){
    $fb_comments = !!$social_options['enable_fb_comments'];
  }
  return $fb_comments;
}



function tw_is_related_posts_enabled(){
  $blog_options = get_option('tw_theme_blog_options');
  $related_posts = (is_array($blog_options)&& isset($blog_options['enable_related_posts']) ) ? !!$blog_options['enable_related_posts'] : false;
  return $related_posts;
}



/******************************************************
***************** Shared Count API *******************
******************************************************/
function get_get_sharedcount_api_key(){
  $social_options = tw_get_social_options();
  $api_key = (is_array($social_options) && isset($social_options['sharedcount_id']) && trim($social_options['sharedcount_id'])!=='' ) ? trim($social_options['sharedcount_id']) : false;
  return $api_key;
}

function tw_get_sharedcount_json($url){
  $api_key = get_get_sharedcount_api_key();
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