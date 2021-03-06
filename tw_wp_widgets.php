<?php
date_default_timezone_set ('America/New_York');

add_action('widgets_init', create_function('', 'return register_widget("tw_contact_info_widget");'));
class tw_contact_info_widget extends WP_Widget{

  function tw_contact_info_widget() {
    parent::__construct(false, $name = 'Wunder Contact Info Widget');
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['enable_address'] = strip_tags($new_instance['enable_address']);
		$instance['enable_phone']   = strip_tags($new_instance['enable_phone']);
		$instance['enable_tollfree']= strip_tags($new_instance['enable_tollfree']);
		$instance['enable_fax']     = strip_tags($new_instance['enable_fax']);
		$instance['enable_email']   = strip_tags($new_instance['enable_email']);
		$instance['enable_social']   = strip_tags($new_instance['enable_social']);
    return $instance;
  }

  function form($instance) {
    $title 		         = esc_attr($instance['title']);
    $enabled_address   = esc_attr($instance['enable_address']);
		$enabled_phone     = esc_attr($instance['enable_phone']);
		$enabled_tollfree  = esc_attr($instance['enable_tollfree']);
		$enabled_fax       = esc_attr($instance['enable_fax']);
		$enabled_email     = esc_attr($instance['enable_email']);
		$enabled_social    = esc_attr($instance['enable_social']);
		?>
		<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
		<p>
      <input class="checkbox" type="checkbox" <?php checked($enabled_address, 'on'); ?> id="<?php echo $this->get_field_id('enable_address'); ?>" name="<?php echo $this->get_field_name('enable_address'); ?>" />
      <label for="<?php echo $this->get_field_id('enable_address'); ?>"><?php _e('Show Address','tw'); ?></label>
		</p>
		<p>
      <input class="checkbox" type="checkbox" <?php checked($enabled_phone, 'on'); ?> id="<?php echo $this->get_field_id('enable_phone'); ?>" name="<?php echo $this->get_field_name('enable_phone'); ?>" />
      <label for="<?php echo $this->get_field_id('enable_phone'); ?>"><?php _e('Show Phone','tw'); ?></label>
		</p>
		<p>
      <input class="checkbox" type="checkbox" <?php checked($enabled_tollfree, 'on'); ?> id="<?php echo $this->get_field_id('enable_tollfree'); ?>" name="<?php echo $this->get_field_name('enable_tollfree'); ?>" />
      <label for="<?php echo $this->get_field_id('enable_tollfree'); ?>"><?php _e('Show Toll Free Number','tw'); ?></label>
		</p>
		<p>
      <input class="checkbox" type="checkbox" <?php checked($enabled_fax, 'on'); ?> id="<?php echo $this->get_field_id('enable_fax'); ?>" name="<?php echo $this->get_field_name('enable_fax'); ?>" />
      <label for="<?php echo $this->get_field_id('enable_fax'); ?>"><?php _e('Show Fax','tw'); ?></label>
		</p>
		<p>
      <input class="checkbox" type="checkbox" <?php checked($enabled_email, 'on'); ?> id="<?php echo $this->get_field_id('enable_email'); ?>" name="<?php echo $this->get_field_name('enable_email'); ?>" />
      <label for="<?php echo $this->get_field_id('enable_email'); ?>"><?php _e('Show Email','tw'); ?></label>
		</p>
		<p>
      <input class="checkbox" type="checkbox" <?php checked($enabled_social, 'on'); ?> id="<?php echo $this->get_field_id('enable_social'); ?>" name="<?php echo $this->get_field_name('enable_social'); ?>" />
      <label for="<?php echo $this->get_field_id('enable_social'); ?>"><?php _e('Show Social','tw'); ?></label>
		</p>
		<?php
  }

  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
    tw_show_contact_info_widget($args, $instance);
  }
}


function tw_show_contact_info_widget($args, $instance){
  if(function_exists('tw_contact_info_widget_custom_action')){
    add_action('tw_contact_info_widget_custom_hook', 'tw_contact_info_widget_custom_action', 10, 2 );
    echo $args['before_widget'];
    do_action( 'tw_contact_info_widget_custom_hook', $args, $instance);
    echo $args['after_widget'];
  }else{
    $enabled_address   = $instance['enable_address']  =='on' ? true : false;
		$enabled_phone     = $instance['enable_phone']    =='on' ? true : false;
		$enabled_tollfree  = $instance['enable_tollfree'] =='on' ? true : false;
		$enabled_fax       = $instance['enable_fax']      =='on' ? true : false;
		$enabled_email     = $instance['enable_email']    =='on' ? true : false;
		$enabled_social    = $instance['enable_social']    =='on' ? true : false;
    $contact_info      = tw_get_contact_info();
    $social_info       = tw_get_social_networks();

    echo $args['before_widget'];
    $title 		= apply_filters('widget_title', $instance['title']);

    if($contact_info){

    echo $args['before_title'] . $title . $args['after_title'];
      ?>
      <div class="contact-info-widget-container">

        <div itemscope itemtype="http://schema.org/LocalBusiness">
            <span class="sr-only" itemprop="name"><?php echo bloginfo('name');?></span>

            <?php if($enabled_address):?>
            <div class="contact-address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
              <?php if(isset($contact_info['address_1']) && $contact_info['address_1']!=='' ):?>
                <span class="streetAddress" itemprop="streetAddress">
                  <?php echo $contact_info['address_1'];?>
                  <?php if(isset($contact_info['address_2']) && $contact_info['address_2']!=='' ):?>,<br/><?php echo $contact_info['address_2']; ?> <?php endif;?>
                </span><br/>
              <?php endif;?>
              <?php if(isset($contact_info['city']) && $contact_info['city']!=='' ):?><span class="city" itemprop="addressLocality"><?php echo $contact_info['city'];?></span><?php endif;?>
              
              <?php if(isset($contact_info['state'])  && $contact_info['state']!=='' ):?><span class="state" itemprop="addressRegion"><?php echo $contact_info['state'];?></span><?php endif;?>
 	      <?php if(isset($contact_info['postcode'])  && $contact_info['postcode']!=='' ):?><span class="postcode" itemprop="postalCode"><?php echo $contact_info['postcode'];?></span><?php endif;?>
              <?php if(isset($contact_info['country'])  && $contact_info['country']!=='' ):?><br/><span class="country" itemprop="addressCountry"><?php echo $contact_info['country'];?></span><?php endif;?>
            </div><!-- contact-address-->
            <?php endif;?>

            <?php if($enabled_phone || $enabled_tollfree || $enabled_fax || $enabled_email):?>
            <div class="contact-phone">
              <ul class="fa fa-ul">
                <?php if($enabled_phone && isset($contact_info['phone']) && $contact_info['phone']!=='' ):?><li><i class="fa fa-fw fa-phone"></i> <span itemprop="telephone">
                  <a href="tel:<?php echo tw_clean_phone_number($contact_info['phone']);?>" title="<?php echo __('Call','tw'); ?> <?php echo bloginfo('name');?>"><?php echo $contact_info['phone']; ?></a>
                </span></li><?php endif;?>

                <?php if($enabled_tollfree && isset($contact_info['toll_free']) && $contact_info['toll_free']!=='' ):?><li><i class="fa fa-fw fa-phone"></i> <span itemprop="telephone">
                  <a href="tel:<?php echo tw_clean_phone_number($contact_info['toll_free']);?>" title="<?php echo __('Call','tw'); ?> <?php echo bloginfo('toll_free');?>"><?php echo $contact_info['toll_free']; ?></a>
                </span></li><?php endif;?>

                <?php if($enabled_fax && isset($contact_info['fax']) && $contact_info['fax']!=='' ):?><li><i class="fa fa-fw fa-fax"></i> <span itemprop="faxNumber"><?php echo $contact_info['fax']; ?></span></li><?php endif;?>

                <?php if($enabled_email && isset($contact_info['Email']) && $contact_info['Email']!=='' ):?><li><i class="fa fa-fw fa-envelope"></i> <span itemprop="email">
                  <a href="mailto:<?php echo $contact_info['Email']; ?>" target="_blank" title="<?php echo __('Email','tw'); ?> <?php echo bloginfo('toll_free');?>"><?php echo $contact_info['Email']; ?></a>
                </span></li><?php endif;?>
              <ul>
            </div><!-- contact-phone -->
            <?php endif;?>

            <?php if($enabled_social && is_array($social_info) ): ?>
              <div class="contact-social">
                <ul>
                  <?php foreach($social_info as $network=>$details): ?>
                    <li class="width-<?php echo $count; ?>">
                      <a class="contact-<?php echo $network; ?>" href="<?php echo $details['url']; ?>" target="_blank" title="<?php echo ucfirst($network); ?>">
                          <i class="fa <?php echo $details['icon']; ?>"></i>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif;?>

        </div>

      </div><!-- contact-info-widget-container -->
      <?php
    }
    echo $args['after_widget'];
  }
}

/**
 * Social Icons Widget
 */
class tw_social_widget extends WP_Widget {

  /** constructor -- name this the same as the class above */

  function tw_social_widget() {
    parent::__construct(false, $name = 'Wunder Social Widget');
  }

  /** @see WP_Widget::update -- do not rename this */
  function update($new_instance, $old_instance) {
  		$instance = $old_instance;
  		$instance['title'] = strip_tags($new_instance['title']);
  		$instance['message'] = strip_tags($new_instance['message']);
  		$instance['square-icons'] = strip_tags($new_instance['square-icons']);
      return $instance;
    }

  /** @see WP_Widget::form -- do not rename this */
  function form($instance) {
    $title 		= esc_attr($instance['title']);
    $message	= esc_attr($instance['message']);
    $square_icons	= esc_attr($instance['square-icons']);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>
  		<p>
        <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Sub-heading'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
      </p>
      <p>
      <input class="checkbox" type="checkbox" <?php checked($square_icons, 'on'); ?> id="<?php echo $this->get_field_id('square-icons'); ?>" name="<?php echo $this->get_field_name('square-icons'); ?>" />
      <label for="<?php echo $this->get_field_id('square-icons'); ?>"><?php _e('Show Square social icons','tw'); ?></label>
      <p class="description"><?php _e('Default icons are circles.','tw'); ?></p>
    </p>
    <?php
  }

  /** @see WP_Widget::widget -- do not rename this */
  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
    tw_show_social_widget($args, $instance);
  }

} // end class example_widget
add_action('widgets_init', create_function('', 'return register_widget("tw_social_widget");'));

function tw_show_social_widget($args, $instance){
  // check if we have an override function for a theme specific display
  if(function_exists('tw_social_widget_custom_action')){
    add_action( 'tw_social_widget_custom_hook', 'tw_social_widget_custom_action', 10, 2 );
    echo $args['before_widget'];
    do_action( 'tw_social_widget_custom_hook', $args, $instance);
    echo $args['after_widget'];
  }else{
    echo $args['before_widget'];
      $title 		= apply_filters('widget_title', $instance['title']);
      $message 	= $instance['message'];
      $square_social_icons = (isset($instance['square-icons']) && trim($instance['square-icons'])=='on') ? true : false;
      //$social_info   = tw_get_theme_social_options($square_social_icons);
      $social_info   = tw_get_social_networks($square_social_icons, true);
      if ( $title )
      echo $args['before_title'] . $title . $args['after_title']; ?>
			<div class="social-widget-container">
          <?php if($message!==''): ?><p><?php echo $message; ?></p><?php endif; ?>

          <?php if($social_info): $count = count($social_info); ?>
            <div class="contact-social">
              <ul>
                <?php foreach($social_info as $network=>$details):
                  $title= '';
                  switch($network){
                    case 'facebook':
                      $title = __('Like us on Facebook','tw');
                      break;
                    case 'twitter':
                      $title = __('Follow us on Twitter','tw');
                      break;
                    case 'instagram':
                      $title = __('Follow us on Instagram','tw');
                      break;
                    case 'pinterest':
                      $title = __('Follow us on Pinterest','tw');
                      break;
                    case 'tumblr':
                      $title = __('Follow us on Tumblr','tw');
                      break;
                    case 'linkedin':
                      $title = __('Follow us on Linkedin','tw');
                      break;
                    case 'slideshare':
                      $title = __('See our presentations on Slideshare','tw');
                      break;
                    case 'googleplus':
                      $title = __('Follow us on Google+','tw');
                      break;
                    case 'youtube':
                      $title = __('Subscribe to our Youtube channel','tw');
                      break;
                    case 'vimeo':
                      $title = __('Watch our videos on Vimeo','tw');
                      break;
                  }
                ?>
                  <li class="width-<?php echo $count; ?>">
                    <a class="contact-<?php echo $network; ?>" href="<?php echo $details['url']; ?>" target="_blank" title="<?php echo $title; ?>" data-toggle="tooltip" data-placement="top">
                      <?php if($square_social_icons):?>
                        <i class="fa <?php echo $details['icon']; ?>"></i>
                      <?php else: ?>
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa <?php echo $details['icon']; ?> fa-stack-1x fa-inverse"></i>
                        </span>
                      <?php endif; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

			</div><!-- social-widget-container -->
<?php echo $args['after_widget'];
  }
}


/**
 * Facebook Likebox Widget
 */
class tw_fb_like_box_widget extends WP_Widget {

  /** constructor -- name this the same as the class above */
  function tw_fb_like_box_widget() {
      parent::__construct(false, $name = 'Wunder Facebook Like Box Widget');
  }

  /** @see WP_Widget::widget -- do not rename this */
  function widget($args, $instance) {
    extract( $args );
    $title 		= apply_filters('widget_title', $instance['title']);
    $height 	= $instance['height'];
    if(!strpos($height, 'px')){
      $height = $height.'px';
    }
    $color 	= (isset($instance['color']) && trim($instance['color'])!=='' ) ? trim($instance['color']) : 'light';
    $show_faces 	= (isset($instance['show_faces']) && $instance['show_faces']=='on' ) ? 'true' : 'false';
    $show_posts 	= (isset($instance['show_posts']) && $instance['show_posts']=='on' ) ? 'true' : 'false';
    $show_header 	= (isset($instance['show_header']) && $instance['show_header']=='on' ) ? 'true' : 'false';
    $show_border 	= (isset($instance['show_border']) && $instance['show_border']=='on' ) ? 'true' : 'false';

    $social_info   = get_option('tw_theme_social_options');
    $fb_app_id = (isset($social_info['fb_app_id']) && trim($social_info['fb_app_id'])!=='' ) ? trim($social_info['fb_app_id']) : '';
    $fb_page_url = (isset($social_info['fb_page']) && trim($social_info['fb_page'])!=='' ) ? trim($social_info['fb_page']) : '';
    if($fb_app_id!=='' && $fb_page_url!==''){
      echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title; ?>
            <div class="facebook-widget-container">
              <div class="fb-like-box" data-href="<?php echo $fb_page_url; ?>" data-width="100%" data-height="<?php echo $height;?>" data-colorscheme="<?php echo $color;?>" data-show-faces="<?php echo $show_faces; ?>" data-header="<?php echo $show_header; ?>" data-stream="<?php echo $show_posts;?>" data-show-border="<?php echo $show_border; ?>"></div>
            </div>
    <?php
      echo $after_widget;
    }
  }

  /** @see WP_Widget::update -- do not rename this */
  function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title']       = strip_tags($new_instance['title']);
		$instance['height']      = strip_tags($new_instance['height']);
		$instance['color']       = strip_tags($new_instance['color']);
		$instance['show_faces']  = strip_tags($new_instance['show_faces']);
		$instance['show_posts']  = strip_tags($new_instance['show_posts']);
		$instance['show_header'] = strip_tags($new_instance['show_header']);
		$instance['show_border'] = strip_tags($new_instance['show_border']);
    return $instance;
  }

  /** @see WP_Widget::form -- do not rename this */
  function form($instance) {
    $title 		    = esc_attr($instance['title']);
    $height	      = esc_attr($instance['height']);
    $color	      = esc_attr($instance['color']);
    $show_faces	  = esc_attr($instance['show_faces']);
    $show_posts	  = esc_attr($instance['show_posts']);
    $show_header	= esc_attr($instance['show_header']);
    $show_border	= esc_attr($instance['show_border']);
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tw'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height (in px)','tw'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="number" value="<?php echo $height; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Color Scheme','tw'); ?> </label>
      <select id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>">
         <option value="light"  <?php selected( $color, 'light' ); ?>> <?php echo __('Light','tw'); ?> </option>
         <option value="dark"   <?php selected( $color, 'dark' ); ?>>  <?php echo __('Dark','tw'); ?>  </option>
      </select>
    </p>
    <p>
      <input class="checkbox" type="checkbox" <?php checked($show_faces, 'on'); ?> id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" />
      <label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php _e('Show Faces','tw'); ?></label>
    </p>
    <p>
      <input class="checkbox" type="checkbox" <?php checked($show_posts, 'on'); ?> id="<?php echo $this->get_field_id('show_posts'); ?>" name="<?php echo $this->get_field_name('show_posts'); ?>" />
      <label for="<?php echo $this->get_field_id('show_posts'); ?>"><?php _e('Show Posts','tw'); ?></label>
    </p>
    <p>
      <input class="checkbox" type="checkbox" <?php checked($show_border, 'on'); ?> id="<?php echo $this->get_field_id('show_border'); ?>" name="<?php echo $this->get_field_name('show_border'); ?>" />
      <label for="<?php echo $this->get_field_id('show_border'); ?>"><?php _e('Show Border','tw'); ?></label>
    </p>
    <p>
      <input class="checkbox" type="checkbox" <?php checked($show_header, 'on'); ?> id="<?php echo $this->get_field_id('show_header'); ?>" name="<?php echo $this->get_field_name('show_header'); ?>" />
      <label for="<?php echo $this->get_field_id('show_header'); ?>"><?php _e('Show Header','tw'); ?></label>
    </p>
    <?php
  }

} // end class example_widget
add_action('widgets_init', create_function('', 'return register_widget("tw_fb_like_box_widget");'));


/**
 * Blog Likebox Widget
 */
class tw_blog_widget extends WP_Widget {
  /** constructor -- name this the same as the class above */

  function tw_blog_widget() {
      parent::__construct(false, $name = 'Wunder Blog Widget');
  }
  /** @see WP_Widget::widget -- do not rename this */
  function widget($args, $instance) {
    extract( $args );


    $defaults = array(
  		'tw-tracking-source'=>'',
  	) ;
  	$args = wp_parse_args( $args, $defaults );

  	// Allow child themes/plugins to filter here.
  	$args = apply_filters( 'tw_blog_default_args', $args );

  	//$tw_tracking = trim($args['tw-tracking-source'])!=='' ? '?tw_source='.$args['tw-tracking-source'].'&tw_medium=blog_widget' : '';

    $title 		= apply_filters('widget_title', $instance['title']);
    $number = (isset($instance['number']) && is_integer(intval(trim($instance['number']))) ) ?intval(trim($instance['number'])) : 6;
    $tags = (isset($instance['tags']) && trim($instance['tags'])!=='') ? trim($instance['tags']) : false;
    $button_title = (isset($instance['button_title']) && trim($instance['button_title'])!=='') ? trim($instance['button_title']) : false;
    $button_url = (isset($instance['button_url']) && trim($instance['button_url'])!=='') ? trim($instance['button_url'])/* .$tw_tracking */ : false;

    if($tags){
      $tags  = explode(',', $tags);
      $blog_args['tag'] = $tags[0];
    }

    $blog_args['posts_per_page'] = $number;

    $blog_query = new WP_Query( $blog_args );

    if ( $blog_query->have_posts() ) {
      echo $before_widget;

    $widget_area = $args['id'];
    if($widget_area=='homepage'){
      $post_count = $blog_query->post_count;
      switch($post_count){
        case 1:
          $class = 'col-xs-12 col-sm-12 col-md-12';
          break;
        case 2:
          $class = 'col-xs-12 col-sm-6 col-md-6';
          break;
        case 3:
        case 5:
        case 6:
          $class = 'col-xs-12 col-sm-6 col-md-4';
          break;
        case 4:
        case 7:
        case 8:
          $class = 'col-xs-12 col-sm-6 col-md-3';
          break;
        default:
          $class = 'col-xs-12 col-sm-6 col-md-6';
          break;
      }
    }else{
      $class = 'col-xs-12 col-sm-12 col-md-12';
    }
    ?>

      <div class="blog-container plugin-container">
       <?php if ( $title )
          echo $before_title . $title . $after_title; ?>
          <div id="<?php echo $args['widget_id'];?>-articles" class="articles row" >
          <?php while ( $blog_query->have_posts() ): $blog_query->the_post(); ?>
          <div id="article-<?php the_id();?>" class="article <?php echo $class;?>" itemscope="itemscope" itemtype="http://schema.org/Article">
            <div class="thumbnail">
              <?php
              //if(has_post_thumbnail()):
                if(function_exists('tw_get_image_src')):
                  $image_sizes = array('4x3-small','16x9-medium','16x9-medium');
                  if($widget_area=='homepage'){
                    $image_sizes = array('4x3-small','4x3-small','4x3-small');
                  }
                ?>
                <a href="<?php the_permalink();?><?php //echo $tw_tracking; ?>" title="<?php the_title(); ?>">
                  <?php echo tw_the_post_thumbnail($image_sizes, $attr = array('itemscope'=>'image','class'=>'img-responsive') ); ?>
                </a>
              <?php elseif(has_post_thumbnail()): ?>
                <a href="<?php the_permalink();?><?php //echo $tw_tracking; ?>" title="<?php the_title(); ?>">
                <?php get_the_post_thumbnail(get_the_id(), 'medium', array('itemscope'=>'image','class'=>'img-responsive')); ?>
                </a>
              <?php else: ?>
                <a href="<?php the_permalink();?><?php //echo $tw_tracking; ?>" title="<?php the_title(); ?>">
                  <img src="<?php echo tw_get_default_image(); ?>" width="" height="" alt="<?php the_title(); ?>" class="img-responsive" itemscope="image" />
                </a>
              <?php endif; ?>


              <div class="caption">
                <h3 itemprop="headline"><a href="<?php the_permalink(); ?><?php //echo $tw_tracking; ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                <div class="entry-author vcard" itemscope="itemscope" itemtype="http://schema.org/Person" itemprop="author"><?php echo __('by','tw'); ?> <span class="fn" itemprop="name"><?php the_author_posts_link();?></span></div>
                <p class="time"><time class="updated" itemprop="datePublished" datetime="<?php echo get_the_time('Y-m-j'); ?>T<?php echo get_the_time('H:i:s'); ?>" pubdate><?php echo get_the_time('F j, Y'); ?></time></p>

                <?php //if(has_excerpt()): ?>
                <div class="description" itemprop="description"><?php the_excerpt(); ?></div>
                <?php //endif;?>
              </div>
            </div><!-- thumbnail -->
          </div>
          <?php endwhile;?>
          </div><!-- articles -->
          <div class="clearfix"></div>
          <?php if($button_url!=='' && $button_title!==''): ?>
          <div class="more">
            <a href="<?php echo $button_url;?>" class="btn btn-primary btn-lg" title="<?php echo $button_title; ?>"><?php echo $button_title; ?></a>
          </div>
          <?php endif; ?>

      </div>
    <?php
      echo $after_widget;
    }
    wp_reset_postdata();
  }

  /** @see WP_Widget::update -- do not rename this */
  function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title']        = strip_tags($new_instance['title']);
		$instance['number']       = strip_tags($new_instance['number']);
		$instance['tags']         = strip_tags($new_instance['tags']);
		$instance['button_title'] = strip_tags($new_instance['button_title']);
		$instance['button_url']   = strip_tags($new_instance['button_url']);

    return $instance;
  }

  /** @see WP_Widget::form -- do not rename this */
  function form($instance) {
    $title 		    = esc_attr($instance['title']);
    $number	      = esc_attr($instance['number']);
    $tags         = esc_attr($instance['tags']);
    $button_title = esc_attr($instance['button_title']);
    $button_url   = esc_attr($instance['button_url']);

    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tw'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number','tw'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags','tw'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text" value="<?php echo $tags; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('button_title'); ?>"><?php _e('See All Button Text','tw'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('button_title'); ?>" name="<?php echo $this->get_field_name('button_title'); ?>" type="text" value="<?php echo $button_title; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('See All Button URL','tw'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="text" value="<?php echo $button_url; ?>" />
    </p>

    <?php
  }
}
add_action('widgets_init', create_function('', 'return register_widget("tw_blog_widget");'));


/**
 * Instagram recent post Widget
 */

// function get_instagram_username(){
//   return get_field('tw_instagram_handle','option');
// }
//
// function get_instagram_page_link(){
//   return get_field('tw_instagram_page_url','option');
// }

function tw_get_instagram_access_token(){
  $instagram_access_token = false;
  $is_enabled = get_field('tw_enable_instagram_api', 'option');
  if($is_enabled){
   //  $instagram_api = array();
    $instagram_access_token      = get_field('tw_instagram_access_token', 'option');
   //  $instagram_api['oauth_access_token_secret'] = get_field('tw_instagram_access_token_secret', 'option');
   //  $instagram_api['consumer_key']              = get_field('tw_instagram_consumer_key', 'option');
   //  $instagram_api['consumer_secret']           = get_field('tw_instagram_consumer_secret', 'option');
  }
  return $instagram_access_token;
}

  $instagram_api_creds = tw_get_instagram_access_token();
  if(count($instagram_api_creds)!==1){
    $instagram_api_creds = false;
  }

if(false!==$instagram_api_creds){

   class tw_instagram_widget extends WP_Widget{

      function tw_instagram_widget(){
         parent::__construct(false, $name = 'Wunder Instagram Widget');
      }

      function update($new_instance, $old_instance){
         $instance = $old_instance;
         $instance['title']        = strip_tags($new_instance['title']);
         return $instance;
      }

      function form($instance){
         $title = esc_attr($instance['title']);
         ?>
         <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','tw'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
         </p>
         <?php
      }

      function widget($args, $instance){
         extract( $args );
         $args['title'] = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
         tw_instagram_display_widget($args);
      }

   }

   add_action('widgets_init', create_function('', 'return register_widget("tw_instagram_widget");'));

   function fetchApiData($url){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 20);
      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
   }
   function tw_instagram_display_widget($args){
      $social_info = tw_get_social_networks();
      $username = $social_info['instagram']['username'];
      $page_link = $social_info['instagram']['url'];

      $accessToken = tw_get_instagram_access_token();


      $tw_instagram_feed = get_option( 'tw_instagram_feed');

      if( (! $tw_instagram_feed) || (date('Y-m-d H:i:s',strtotime('-6 hour') > $tw_instagram_feed['date'] ))){
         $url = "https://api.instagram.com/v1/users/self/media/recent/?access_token=".$accessToken."&count=6";

         $result = fetchApiData($url);
         $result = json_decode($result);

         $tw_instagram_feed = array();
         $tw_instagram_feed['date'] = date('Y-m-d H:i:s');
         $tw_instagram_feed['feed'] = $result;

         update_option( 'tw_instagram_feed', $tw_instagram_feed, $autoload = null );

      }else{
         $result = $tw_instagram_feed['feed'];
      }



      echo $args['before_widget'];
      ?>
      <!-- Widget HTML shows for the frontend -->
      <div class="instagram-container container">
         <h2 class="instagram_widget_title">
            <span>
               <?php if(!empty($args['title'])){ echo $args['before_title'] . esc_html( $args['title'] ) . $args['after_title']; }?>
               <a class="instagram-username" href="<?php echo $page_link;?>" target="_blank">&nbsp;@<?php echo $username; ?></a>

            </span>

         </h2>

         <div id="instagram-feed-<?php echo $args['widget_id'];?>" class="instagram-feed">
            <?php if(count($result)>0): ?>
            <div class="feeds row">
               <?php foreach($result->data as $post):
                  $photo_url = $post->images->thumbnail->url;
                  $media_link = $post->link;
               ?>
               <div class="feed col-xs-6 col-sm-4 col-md-2">
                  <a href="<?php echo $media_link; ?>" target="_blank">
                     <img class="img-responsive" src="<?php echo $photo_url;?>" alt="<?php $post->user->username;?>">
                  </a>
               </div>
               <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="alert alert-warning">
               <p><?php _e('Ooops. No Images found.','tw');?></p>
            </div>
            <?php endif; ?>
         </div><!-- instagram-timeline -->


      </div><!-- instagram-container -->

      <?php echo $args['after_widget'];
   }

}


?>
