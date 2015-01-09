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
      $social_info   = tw_get_theme_social_options();

      echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title; ?>
				<div>
          <?php if($message!==''): ?><p><?php echo $message; ?></p><?php endif; ?>

          <?php if($social_info): $count = count($social_info); ?>
            <div class="contact-social">
              <ul>
                <?php foreach($social_info as $network=>$details): ?>
                  <li class="width-<?php echo $count; ?>">
                    <a class="contact-<?php echo $network; ?>" href="<?php echo $details['url']; ?>" target="_blank" title="<?php echo ucfirst($network); ?>"><i class="fa <?php echo $details['icon']; ?>"></i></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

				</div>
        <?php echo $after_widget;
    }

    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {
  		$instance = $old_instance;
  		$instance['title'] = strip_tags($new_instance['title']);
  		$instance['message'] = strip_tags($new_instance['message']);
      return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
      $title 		= esc_attr($instance['title']);
      $message	= esc_attr($instance['message']);
      ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Sub-heading'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
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
              <div class="fb-like-box" data-href="<?php echo $fb_page_url; ?>" data-width="100%" data-height="<?php echo $height;?>" data-colorscheme="<?php echo $color;?>" data-show-faces="<?php echo $show_faces; ?>" data-header="<?php echo $show_header; ?>" data-stream="<?php echo $show_posts;?>" data-show-border="<?php echo $show_border; ?>"></div>
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
        <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />
      </p>

      <p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Color Scheme','tw'); ?> </label>
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
?>