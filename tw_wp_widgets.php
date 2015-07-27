<?php
/**
 * Social Icons Widget
 */
class tw_social_widget extends WP_Widget {


  /** constructor -- name this the same as the class above */
  function tw_social_widget() {
    parent::WP_Widget(false, $name = 'TW Social Widget');
  }

  /** @see WP_Widget::widget -- do not rename this */
  function widget($args, $instance) {
    extract( $args );
    $title 		= apply_filters('widget_title', $instance['title']);
    $message 	= $instance['message'];
    $square_social_icons = (isset($instance['square-icons']) && trim($instance['square-icons'])=='on') ? true : false;
    //$social_info   = tw_get_theme_social_options($square_social_icons);
    $social_info   = tw_get_social_networks($square_social_icons, true);

    echo $before_widget;
      if ( $title )
          echo $before_title . $title . $after_title; ?>
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
    <?php echo $after_widget;
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


} // end class example_widget
add_action('widgets_init', create_function('', 'return register_widget("tw_social_widget");'));


/**
 * Facebook Likebox Widget
 */
class tw_fb_like_box_widget extends WP_Widget {

  /** constructor -- name this the same as the class above */
  function tw_fb_like_box_widget() {
      parent::WP_Widget(false, $name = 'TW Facebook Like Box Widget');
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
      parent::WP_Widget(false, $name = 'TW Blog Widget');
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

      <div class="blog-container">
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
                  <img src="<?php echo tw_get_default_image(); ?>" width="" height="" alt="" class="img-responsive" itemscope="image" />
                </a>
              <?php endif; ?>


              <div class="caption">
                <h3 itemprop="headline"><a href="<?php the_permalink(); ?><?php //echo $tw_tracking; ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
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
            <a href="<?php echo $button_url;?>" class="btn btn-primary btn-lg
              " title="<?php echo $button_title; ?>"><?php echo $button_title; ?></a>
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

?>