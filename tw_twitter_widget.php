<?php
if(class_exists('TwitterAPIExchange')){
  $twitter_api_creds = tw_get_twitter_api();
  if(count($twitter_api_creds)!==4){
    $twitter_api_creds = false;
  }

  if(false!==$twitter_api_creds){

    class tw_twitter_widget extends WP_Widget{
      function __construct(){
        parent::__construct(false, $name = 'TW Twitter Widget');
      }
      function update($new_instance, $old_instance){
        $instance = $old_instance;
    		$instance['title']        = strip_tags($new_instance['title']);
    		$instance['limit']        = strip_tags($new_instance['limit']);
    		$instance['username']     = strip_tags($new_instance['username']);
    		$instance['button_title'] = strip_tags($new_instance['button_title']);
    		$instance['button_url']   = strip_tags($new_instance['button_url']);

        return $instance;
      }
      function form($instance){
        $title 		    = esc_attr($instance['title']);
        $limit        = esc_attr($instance['limit']);
        $username     = esc_attr($instance['username']);
        $button_title = esc_attr($instance['button_title']);
    		$button_url   = esc_attr($instance['button_url']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','tw'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of Tweets','tw'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $limit; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username','tw'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
        </p>
        <!--
        <p>
          <label for="<?php echo $this->get_field_id('hashtag'); ?>"><?php _e('Hashtag','tw'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('hashtag'); ?>" name="<?php echo $this->get_field_name('hashtag'); ?>" type="text" value="<?php echo $hashtag; ?>" />
          <small class="description"><?php _e('Comma seperated list','tw');?></small>
        </p>
        -->
        <p>
          <label for="<?php echo $this->get_field_id('button_title'); ?>"><?php _e('Button Title','tw'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('button_title'); ?>" name="<?php echo $this->get_field_name('button_title'); ?>" type="text" value="<?php echo $button_title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Button URL','tw'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="text" value="<?php echo $button_url; ?>" />
        </p>
        <?php
      }
      function widget($args, $instance){
        $username = get_theme_twitter_username();
        extract( $args );
        $args['title']        = empty($instance['title'])         ? ''                                : apply_filters('widget_title', $instance['title']);
        $args['limit']        = empty($instance['limit'])         ? 5                                 : $instance['limit'];
        $args['username']     = empty($instance['username'])      ? $username                         : $instance['username'];
        $args['button_title'] = empty($instance['button_title'])  ? __('Follow us on Twitter','tw')   : $instance['button_title'];
        $args['button_url']   = empty($instance['button_url'])    ? 'https://twitter.com/'.$username  : $instance['button_url'];

        tw_twitter_display_widget($args);
      }
    }
    add_action('widgets_init', create_function('', 'return register_widget("tw_twitter_widget");'));


    function tw_twitter_display_widget($args){
      $username = get_theme_twitter_username();
      $defaults = array(
    		'limit'        => 5,
    		'username' 		 => $username,
    		'button_title' => __('Follow is on Twitter','tw'),
    		'button_url'   => 'https://twitter.com/'.$username,
    	);
    	$args = wp_parse_args( $args, $defaults );
    	$args = apply_filters( 'tw_twitter_widget_default_args', $args );
    	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    	$getfield = '?screen_name='.$args['username'].'&count='.$args['limit'];
    	$requestMethod = 'GET';

    	$settings = tw_get_twitter_api();
      if(count($settings)==4){

        if ( false === ( $timeline = get_transient( 'tw_twitter_timeline' ) ) ) {
          $twitter = new TwitterAPIExchange($settings);
          $timeline = $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
        	set_transient( 'tw_twitter_timeline', $timeline, HOUR_IN_SECONDS/4 );
        }else{
          $twitter = new TwitterAPIExchange($settings);
          $timeline = $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
        	set_transient( 'tw_twitter_timeline', $timeline, HOUR_IN_SECONDS/4 );
        }

        $timeline = json_decode($timeline);

        if(!is_null($timeline) && !empty($timeline) && $timeline){
          echo $args['before_widget'];
        ?>
          <div class="twitter-container">
            <?php if(!empty($args['title'])){ echo $args['before_title'] . esc_html( $args['title'] ) . $args['after_title']; }?>
            <div id="twitter-timeline-<?php echo $args['widget_id'];?>" class="twitter-timeline">
              <?php if(count($timeline)>0): ?>
                <ul class="tweets">
                  <?php foreach($timeline as $tweet):
                    $tweet_text = $tweet->text;
                    foreach($tweet->entities->urls as $url){
                      if(strpos($tweet_text, $url->url)){
                        $tweet_text = str_replace($url->url, '<a href="'.$url->expanded_url.'" target="_blank">'.$url->url.'</a>', $tweet_text);
                      }
                    }
                    foreach($tweet->entities->user_mentions as $mention){
                      if(strpos($tweet_text, '@'.$mention->screen_name)){
                        $tweet_text = str_replace('@'.$mention->screen_name, '<a href="https://twitter.com/'.$mention->screen_name.'" target="_blank" title="'.$mention->name.' on Twitter">@'.$mention->screen_name.'</a>', $tweet_text);
                      }
                    }
                  ?>
                  <li id="tweet-<?php echo $tweet->id; ?>" class="tweet">
                    <div class="tweet-text"><?php echo $tweet_text; ?></div>
                    <div class="tweet-created_at"><?php echo date('D jS F Y', strtotime($tweet->created_at)) ; ?></div>
                  </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
              <div class="alert alert-warning">
                <p><?php _e('Ooops. No Tweets found.','tw');?></p>
              </div>
              <?php endif; ?>

            </div><!-- twitter-timeline -->
            <?php if( trim($args['button_url'])!=='' && trim($args['button_title'])!=='' ):?>
            <div class="more">
              <a href="<?php echo trim($args['button_url']) ;?>" title="<?php echo trim($args['button_title']);?>" class="btn btn-default" target="_blank"><?php echo trim($args['button_title']);?></a>
            </div>
            <?php endif; ?>
          </div><!-- twitter-container -->
        <?php echo $args['after_widget'];
        }
      }

    }

  }
}
