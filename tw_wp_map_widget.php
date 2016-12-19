<?php

add_action('widgets_init', create_function('', 'return register_widget("tw_map_widget");'));
class tw_map_widget extends WP_Widget{
   function tw_map_widget() {
      parent::__construct(false, $name = 'Wunder Map Widget');
   }

   function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['enable_address'] = strip_tags($new_instance['enable_address']);
      $instance['enable_phone']   = strip_tags($new_instance['enable_phone']);
      $instance['enable_tollfree']= strip_tags($new_instance['enable_tollfree']);
      $instance['enable_fax']     = strip_tags($new_instance['enable_fax']);
      $instance['enable_email']   = strip_tags($new_instance['enable_email']);
      // $instance['enable_social']   = strip_tags($new_instance['enable_social']);
      return $instance;
   }

   function form($instance) {
      $title 		       = esc_attr($instance['title']);
      $enabled_address   = esc_attr($instance['enable_address']);
      $enabled_phone     = esc_attr($instance['enable_phone']);
      $enabled_tollfree  = esc_attr($instance['enable_tollfree']);
      $enabled_fax       = esc_attr($instance['enable_fax']);
      $enabled_email     = esc_attr($instance['enable_email']);
      // $enabled_social    = esc_attr($instance['enable_social']);
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
		<?php
   }

   function widget($args, $instance) {
      extract($args, EXTR_SKIP);

      // $contact_info = tw_get_contact_info();
      // $enable_map = get_field('tw_enable_google_map','option');
      $args['title']   = esc_attr($instance['title']);
      $args['enabled_address']   = $instance['enable_address']  =='on' ? true : false;
      $args['enabled_phone']     = $instance['enable_phone']    =='on' ? true : false;
      $args['enabled_tollfree']  = $instance['enable_tollfree'] =='on' ? true : false;
      $args['enabled_fax']       = $instance['enable_fax']      =='on' ? true : false;
      $args['enabled_email']     = $instance['enable_email']    =='on' ? true : false;
      $args['enabled_social']    = $instance['enable_social']   =='on' ? true : false;

      $args['enable_map'] = get_field('tw_enable_google_map','option');
      $args['google_map_api_key'] = get_field('tw_google_map_api_key', 'option');
      $args['enable_contact_map'] = get_field('tw_contact_enable_map');



      if($args['enable_map'] && $args['enable_contact_map']){
        $args['map_zoom'] = get_field('tw_contact_map_zoom_level');
        $args['map_marker_colour'] = get_field('tw_contact_map_marker_colour');
      }



      $args['contact_info']      = tw_get_contact_info();
      $args['social_info']       = tw_get_social_networks();

      tw_google_map_widget($args);

   }
}

function tw_google_map_widget($args){
  do_action( 'tw_google_map_widget_hook', $args);
}
add_action( 'tw_google_map_widget_hook', 'tw_google_map_widget_action', 10, 2 );
