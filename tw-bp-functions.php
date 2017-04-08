<?php
/******************************************************
***************** Buddypress Support ******************
******************************************************/
if ( ! function_exists( 'tw_bp_theme_setup' ) ){
  function tw_bp_theme_setup() {
     include_once 'tw-bp-ajax.php';

    // This theme styles the visual editor with editor-style.css to match the theme style.
//   	add_editor_style();

  	// This theme comes with all the BuddyPress goodies
//   	add_theme_support( 'buddypress' );

  	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
  		// Register buttons for the relevant component templates
  		// Friends button
  		if ( bp_is_active( 'friends' ) ){
    		add_action( 'bp_member_header_actions',    'bp_add_friend_button',           5 );
  		}

  		// Activity button
  		if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ){
    		add_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );
  		}

  		// Messages button
  		if ( bp_is_active( 'messages' ) ){
    		add_action( 'bp_member_header_actions',    'bp_send_private_message_button', 20 );
  		}

  		// Group buttons
  		if ( bp_is_active( 'groups' ) ) {
  			add_action( 'bp_group_header_actions',     'bp_group_join_button',           5 );
  			add_action( 'bp_group_header_actions',     'bp_group_new_topic_button',      20 );
  			add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
  		}

  		// Blog button
  		if ( bp_is_active( 'blogs' ) ){
    		add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );
  		}

  	}
  }
  add_action( 'after_setup_theme', 'tw_bp_theme_setup' );
}

if ( !function_exists( 'tw_bp_theme_enqueue_scripts' ) ){
  function tw_bp_theme_enqueue_scripts() {
    // Enqueue various scripts
  	wp_enqueue_script( 'bp-jquery-query' );
  	wp_enqueue_script( 'bp-jquery-cookie' );

  	// A similar check is done in BP_Core_Members_Widget, but due to a load order
	// issue, we do it again here
  	if ( is_active_widget( false, false, 'bp_core_members_widget' ) && ! is_admin() && ! is_network_admin() ) {
  		wp_enqueue_script( 'bp-widget-members' );
  	}

  	// Enqueue the global JS - Ajax will not work without it
  	wp_enqueue_script( 'tw-bp-theme-ajax-js', get_template_directory_uri() . '/includes/tw-wp-core/js/min/tw-bp-global-min.js', array( 'jquery' ), bp_get_version() );

  	// Add words that we need to use in JS to the end of the page so they can be translated and still used.
  	$params = array(
  		'my_favs'           => __( 'My Favorites', 'tw' ),
  		'accepted'          => __( 'Accepted', 'tw' ),
  		'rejected'          => __( 'Rejected', 'tw' ),
  		'show_all_comments' => __( 'Show all comments for this thread', 'tw' ),
  		'show_x_comments'   => __( 'Show all %d comments', 'tw' ),
  		'show_all'          => __( 'Show all', 'tw' ),
  		'comments'          => __( 'comments', 'tw' ),
  		'close'             => __( 'Close', 'tw' ),
  		'view'              => __( 'View', 'tw' ),
  		'mark_as_fav'	      => __( 'Favorite', 'tw' ),
  		'remove_fav'	      => __( 'Remove Favorite', 'tw' ),
  		'unsaved_changes'   => __( 'Your profile has unsaved changes. If you leave the page, the changes will be lost.', 'tw' ),
  	);
  	wp_localize_script( 'tw-bp-theme-ajax-js', 'TW_BP_Theme', $params );


  }
  add_action( 'wp_enqueue_scripts', 'tw_bp_theme_enqueue_scripts' );
}

if ( !function_exists( 'tw_bp_theme_enqueue_styles' ) ){
  function tw_bp_theme_enqueue_styles() {
    // Enqueue the main stylesheet
    wp_enqueue_style( 'tw-bp-style', get_template_directory_uri().'/includes/tw-wp-core/css/tw-bp-style.css', array(), null, true );
  }
  add_action( 'wp_enqueue_scripts', 'tw_bp_theme_enqueue_styles' );
}