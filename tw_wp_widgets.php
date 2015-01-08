<?php
/**
 * Example Widget Class
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
?>