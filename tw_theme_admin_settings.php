<?php

/**
 * This function introduces the theme options into the 'Appearance' menu and into a top-level
 * 'Third Wunder Theme' menu.
 */
function tw_theme_menu() {

	add_theme_page(
		'TW Theme', 					// The title to be displayed in the browser window for this page.
		'TW Theme',					// The text to be displayed for this menu item
		'administrator',					// Which type of users can see this menu item
		'tw_theme_options',			// The unique ID - that is, the slug - for this menu item
		'tw_theme_display'				// The name of the function to call when rendering this menu's page
	);

	add_menu_page(
		'TW Theme',					// The value used to populate the browser's title bar when the menu page is active
		'TW Theme',					// The text of the menu in the administrator's sidebar
		'administrator',					// What roles are able to access the menu
		'tw_theme_menu',				// The ID used to bind submenu items to this menu
		'tw_theme_display'				// The callback function used to render this menu
	);

	add_submenu_page(
		'tw_theme_menu',				// The ID of the top-level menu page to which this submenu item belongs
		__( 'General Options', 'tw' ),			// The value used to populate the browser's title bar when the menu page is active
		__( 'General Options', 'tw' ),					// The label of this submenu item displayed in the menu
		'administrator',					// What roles are able to access this submenu item
		'tw_theme_general_options',	// The ID used to represent this submenu item
		'tw_theme_display'				// The callback function used to render the options for this submenu item
	);

  add_submenu_page(
		'tw_theme_menu',
		__( 'Logo & Icon Options', 'tw' ),
		__( 'Logo & Icon Options', 'tw' ),
		'administrator',
		'tw_theme_logo_options',
		create_function( null, 'tw_theme_display( "logo_options" );' )
	);

  add_submenu_page(
		'tw_theme_menu',
		__( 'Blog Options', 'tw' ),
		__( 'Blog Options', 'tw' ),
		'administrator',
		'tw_theme_blog_options',
		create_function( null, 'tw_theme_display( "blog_options" );' )
	);

  add_submenu_page(
		'tw_theme_menu',
		__( 'Contact Information', 'tw' ),
		__( 'Contact Information', 'tw' ),
		'administrator',
		'tw_theme_contact_options',
		create_function( null, 'tw_theme_display( "contact_options" );' )
	);

	add_submenu_page(
		'tw_theme_menu',
		__( 'Social Options', 'tw' ),
		__( 'Social Options', 'tw' ),
		'administrator',
		'tw_theme_social_options',
		create_function( null, 'tw_theme_display( "social_options" );' )
	);


} // end tw_theme_menu
add_action( 'admin_menu', 'tw_theme_menu' );

/**
 * Renders a simple page to display for the theme menu defined above.
 */
function tw_theme_display( $active_tab = '' ) {
?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">

		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Third Wunder Theme Options', 'tw' ); ?></h2>
		<?php settings_errors(); ?>

		<?php if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} else if( $active_tab == 'logo_options' ) {
			$active_tab = 'logo_options';
		} else if( $active_tab == 'contact_options' ) {
			$active_tab = 'contact_options';
		} else if( $active_tab == 'blog_options' ) {
			$active_tab = 'blog_options';
		} else if( $active_tab == 'social_options' ) {
			$active_tab = 'social_options';
		//} else if( $active_tab == 'input_examples' ) {
			//$active_tab = 'input_examples';
		} else {
			$active_tab = 'display_options';
		} // end if/else ?>

		<h2 class="nav-tab-wrapper">
			<a href="?page=tw_theme_options&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'tw' ); ?></a>
			<a href="?page=tw_theme_options&tab=logo_options" class="nav-tab <?php echo $active_tab == 'logo_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Logo & Icon Options', 'tw' ); ?></a>
			<a href="?page=tw_theme_options&tab=contact_options" class="nav-tab <?php echo $active_tab == 'contact_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Contact Options', 'tw' ); ?></a>
			<a href="?page=tw_theme_options&tab=blog_options" class="nav-tab <?php echo $active_tab == 'blog_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Blog Options', 'tw' ); ?></a>
			<a href="?page=tw_theme_options&tab=social_options" class="nav-tab <?php echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Social Options', 'tw' ); ?></a>
			<!-- <a href="?page=tw_theme_options&tab=input_examples" class="nav-tab <?php echo $active_tab == 'input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Input Examples', 'tw' ); ?></a> -->
		</h2>

		<form method="post" action="options.php">
			<?php

				if( $active_tab == 'display_options' ) {

					settings_fields( 'tw_theme_general_options' );
					do_settings_sections( 'tw_theme_general_options' );

				} elseif( $active_tab == 'logo_options' ) {

					settings_fields( 'tw_theme_logo_options' );
					do_settings_sections( 'tw_theme_logo_options' );

				}elseif( $active_tab == 'contact_options' ) {

					settings_fields( 'tw_theme_contact_options' );
					do_settings_sections( 'tw_theme_contact_options' );

				} elseif( $active_tab == 'blog_options' ) {

					settings_fields( 'tw_theme_blog_options' );
					do_settings_sections( 'tw_theme_blog_options' );

				} elseif( $active_tab == 'social_options' ) {

					settings_fields( 'tw_theme_social_options' );
					do_settings_sections( 'tw_theme_social_options' );

				}

				submit_button();

			?>
		</form>

	</div><!-- /.wrap -->
<?php
} // end tw_theme_display

/* ------------------------------------------------------------------------ *
 * JS & CSS
 * ------------------------------------------------------------------------ */
function tw_settings_enqueue_scripts() {
    wp_register_script( 'tw-upload', get_template_directory_uri() .'/includes/tw-wp-core/js/min/tw-settings-min.js', array('jquery','media-upload','thickbox') );
    if ( 'tw-theme_page_tw_theme_logo_options' == get_current_screen()->id ) {
        wp_enqueue_script('jquery');

        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');

        wp_enqueue_script('media-upload');
        wp_enqueue_script('tw-upload');

    }

}
add_action('admin_enqueue_scripts', 'tw_settings_enqueue_scripts');

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */


/**
 * Provides default values for the Input Options.
 */
function tw_theme_default_input_options() {

	$defaults = array(
		'input_example'		=>	'',
		'textarea_example'	=>	'',
		'checkbox_example'	=>	'',
		'radio_example'		=>	'',
		'time_options'		=>	'default'
	);

	return apply_filters( 'tw_theme_default_input_options', $defaults );

} // end tw_theme_default_input_options


/**
 * Initializes the theme's input example by registering the Sections,
 * Fields, and Settings. This particular group of options is used to demonstration
 * validation and sanitization.
 *
 * This function is registered with the 'admin_init' hook.
 */
function tw_theme_initialize_input_examples() {

	if( false == get_option( 'tw_theme_input_examples' ) ) {
		add_option( 'tw_theme_input_examples', apply_filters( 'tw_theme_default_input_options', tw_theme_default_input_options() ) );
	} // end if

	add_settings_section(
		'input_examples_section',
		__( 'Input Examples', 'tw' ),
		'tw_input_examples_callback',
		'tw_theme_input_examples'
	);

	add_settings_field(
		'Input Element',
		__( 'Input Element', 'tw' ),
		'tw_input_element_callback',
		'tw_theme_input_examples',
		'input_examples_section'
	);

	add_settings_field(
		'Textarea Element',
		__( 'Textarea Element', 'tw' ),
		'tw_textarea_element_callback',
		'tw_theme_input_examples',
		'input_examples_section'
	);

	add_settings_field(
		'Checkbox Element',
		__( 'Checkbox Element', 'tw' ),
		'tw_checkbox_element_callback',
		'tw_theme_input_examples',
		'input_examples_section'
	);

	add_settings_field(
		'Radio Button Elements',
		__( 'Radio Button Elements', 'tw' ),
		'tw_radio_element_callback',
		'tw_theme_input_examples',
		'input_examples_section'
	);

	add_settings_field(
		'Select Element',
		__( 'Select Element', 'tw' ),
		'tw_select_element_callback',
		'tw_theme_input_examples',
		'input_examples_section'
	);

	register_setting(
		'tw_theme_input_examples',
		'tw_theme_input_examples',
		'tw_theme_validate_input_examples'
	);

} // end tw_theme_initialize_input_examples
add_action( 'admin_init', 'tw_theme_initialize_input_examples' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function provides a simple description for the Input Examples page.
 *
 * It's called from the 'tw_theme_initialize_input_examples_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function tw_input_examples_callback() {
	echo '<p>' . __( 'Provides examples of the five basic element types.', 'tw' ) . '</p>';
} // end tw_general_options_callback

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 *
 * It accepts an array or arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function tw_toggle_header_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_general_options');

	// Next, we update the name attribute to access this element's ID in the context of the display options array
	// We also access the show_header element of the options collection in the call to the checked() helper function
	$html = '<input type="checkbox" id="show_header" name="tw_theme_general_options[show_header]" value="1" ' . checked( 1, isset( $options['show_header'] ) ? $options['show_header'] : 0, false ) . '/>';

	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="show_header">&nbsp;'  . $args[0] . '</label>';

	echo $html;

} // end tw_toggle_header_callback

function tw_toggle_content_callback($args) {

	$options = get_option('tw_theme_general_options');

	$html = '<input type="checkbox" id="show_content" name="tw_theme_general_options[show_content]" value="1" ' . checked( 1, isset( $options['show_content'] ) ? $options['show_content'] : 0, false ) . '/>';
	$html .= '<label for="show_content">&nbsp;'  . $args[0] . '</label>';

	echo $html;

} // end tw_toggle_content_callback

function tw_toggle_footer_callback($args) {

	$options = get_option('tw_theme_general_options');

	$html = '<input type="checkbox" id="show_footer" name="tw_theme_general_options[show_footer]" value="1" ' . checked( 1, isset( $options['show_footer'] ) ? $options['show_footer'] : 0, false ) . '/>';
	$html .= '<label for="show_footer">&nbsp;'  . $args[0] . '</label>';

	echo $html;

} // end tw_toggle_footer_callback

function tw_input_element_callback() {

	$options = get_option( 'tw_theme_input_examples' );

	// Render the output
	echo '<input type="text" id="input_example" name="tw_theme_input_examples[input_example]" value="' . $options['input_example'] . '" />';

} // end tw_input_element_callback

function tw_textarea_element_callback() {

	$options = get_option( 'tw_theme_input_examples' );

	// Render the output
	echo '<textarea id="textarea_example" name="tw_theme_input_examples[textarea_example]" rows="5" cols="50">' . $options['textarea_example'] . '</textarea>';

} // end tw_textarea_element_callback

function tw_checkbox_element_callback() {

	$options = get_option( 'tw_theme_input_examples' );

	$html = '<input type="checkbox" id="checkbox_example" name="tw_theme_input_examples[checkbox_example]" value="1"' . checked( 1, $options['checkbox_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="checkbox_example">This is an example of a checkbox</label>';

	echo $html;

} // end tw_checkbox_element_callback

function tw_radio_element_callback() {

	$options = get_option( 'tw_theme_input_examples' );

	$html = '<input type="radio" id="radio_example_one" name="tw_theme_input_examples[radio_example]" value="1"' . checked( 1, $options['radio_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="radio_example_one">Option One</label>';
	$html .= '&nbsp;';
	$html .= '<input type="radio" id="radio_example_two" name="tw_theme_input_examples[radio_example]" value="2"' . checked( 2, $options['radio_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="radio_example_two">Option Two</label>';

	echo $html;

} // end tw_radio_element_callback

function tw_select_element_callback() {

	$options = get_option( 'tw_theme_input_examples' );

	$html = '<select id="time_options" name="tw_theme_input_examples[time_options]">';
		$html .= '<option value="default">' . __( 'Select a time option...', 'tw' ) . '</option>';
		$html .= '<option value="never"' . selected( $options['time_options'], 'never', false) . '>' . __( 'Never', 'tw' ) . '</option>';
		$html .= '<option value="sometimes"' . selected( $options['time_options'], 'sometimes', false) . '>' . __( 'Sometimes', 'tw' ) . '</option>';
		$html .= '<option value="always"' . selected( $options['time_options'], 'always', false) . '>' . __( 'Always', 'tw' ) . '</option>';	$html .= '</select>';

	echo $html;

} // end tw_radio_element_callback



/* ------------------------------------------------------------------------ *
 * Setting Callbacks
 * ------------------------------------------------------------------------ */

/**
 * Sanitization callback for the social options. Since each of the social options are text inputs,
 * this function loops through the incoming option and strips all tags and slashes from the value
 * before serializing it.
 *
 * @params	$input	The unsanitized collection of options.
 *
 * @returns			The collection of sanitized values.
 */
function tw_theme_sanitize_social_options( $input ) {

	// Define the array for the updated options
	$output = array();

	// Loop through each of the options sanitizing the data
	foreach( $input as $key => $val ) {

		if( isset ( $input[$key] ) ) {
			//$output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		} // end if

	} // end foreach

	// Return the new collection
	return apply_filters( 'tw_theme_sanitize_social_options', $output, $input );

} // end tw_theme_sanitize_social_options

function tw_theme_validate_input_examples( $input ) {

	// Create our array for storing the validated options
	$output = array();

	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {

		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {

			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );

		} // end if

	} // end foreach

	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'tw_theme_validate_input_examples', $output, $input );

} // end tw_theme_validate_input_examples

function tw_get_logo_default_options() {
	$options = array(
		'logo' => get_stylesheet_directory_uri().'/assets/img/logo.png',
		'favicon' => get_stylesheet_directory_uri().'/assets/img/favicon.png',
		'apple-icon'=>get_stylesheet_directory_uri().'/assets/img/apple-touch-icon.png',
		'apple-icon-72'=>get_stylesheet_directory_uri().'/assets/img/apple-touch-icon-72x72.png',
		'apple-icon-114'=>get_stylesheet_directory_uri().'/assets/img/apple-touch-icon-114x114.png',
		'apple-icon-144'=>get_stylesheet_directory_uri().'/assets/img/apple-touch-icon-144x144.png',
	);
	return $options;
}

function tw_theme_validate_logo_upload($input){
  $output = array();
  $default_options = tw_get_logo_default_options();
	$valid_input = $default_options;
	$options = get_option('tw_theme_logo_options');

  foreach( $input as $key => $value ) {
    if( isset( $input[$key] ) ) {

      $submit = true;
    	$reset = false;
    	$delete_logo = false;
    	if($input[$key]=='Delete'){
      	$delete_logo = true;
      	$submit = false;
    	}
    	if($input[$key]=='Reset'){
      	$reset = true;
      	$submit = false;
    	}
      if ( $submit ) {
    		if ( $options[$key] != $input[$key]  && $options[$key] != '' ){
      		delete_image( $options[$key] );
    		}
    		$output[$key] = $input[$key];
    	} elseif ( $reset ) {
    		delete_image( $options[$key] );
    		$output[$key] = $default_options[$key];
    	} elseif ( $delete_logo ) {
    		delete_image( $options[$key] );
    		$output[$key] = '';
    	}

    }
  }

	return apply_filters( 'tw_theme_validate_logo_upload', $output, $input );
}


function delete_image( $image_url ) {
	global $wpdb;

	// We need to get the image's meta ID..
	$query = "SELECT ID FROM wp_posts where guid = '" . esc_url($image_url) . "' AND post_type = 'attachment'";
	$results = $wpdb -> get_results($query);

	// And delete them (if more than one attachment is in the Library
	foreach ( $results as $row ) {
		wp_delete_attachment( $row -> ID );
	}
}


/* ------------------------------------------------------------------------ *
 * General Options
 * ------------------------------------------------------------------------ */
function tw_theme_default_general_options() {
	$defaults = array(
  	'slide' =>'slide'
	);
	return apply_filters( 'tw_theme_default_general_options', $defaults );
} // end tw_theme_default_general_options


function tw_general_options_callback() {
	echo '<p>' . __( 'Theme General Options', 'tw' ) . '</p>';
} // end tw_general_options_callback

function tw_general_widget_options_callback() {
	echo '<p>' . __( 'Enable sidebars and widget areas', 'tw' ) . '</p>';
} // end tw_general_widget_options_callback

function tw_general_menu_options_callback() {
	echo '<p>' . __( 'Select extra menus to enable', 'tw' ) . '</p>';
} // end tw_general_menu_options_callback

function tw_slider_options_callback(){
  //echo '';
}

function tw_footer_options_callback(){

}

function tw_initialize_theme_options() {

	// If the theme options don't exist, create them.
	if( false == get_option( 'tw_theme_general_options' ) ) {
		add_option( 'tw_theme_general_options', apply_filters( 'tw_theme_default_general_options', tw_theme_default_general_options() ) );
	} // end if

  /**
  * Menu Options
  */

	add_settings_section(
		'menu_settings_section',			// ID used to identify this section and with which to register options
		__( 'Menu Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_general_menu_options_callback',	// Callback used to render the description of the section
		'tw_theme_general_options'		// Page on which to add this section of options
	);

  add_settings_field(
		'enable_top_menu',
		__( 'Top Menu', 'tw' ),
		'tw_enable_top_menu_callback',
		'tw_theme_general_options',
		'menu_settings_section',
		array(
			__( 'Enable a menu area above the main navigation.', 'tw' ),
		)
	);
	add_settings_field(
		'enable_footer_menu',
		__( 'Footer Menu', 'tw' ),
		'tw_enable_footer_menu_callback',
		'tw_theme_general_options',
		'menu_settings_section',
		array(
			__( 'Enable a menu area in the footer.', 'tw' ),
		)
	);


  /**
  * Sidebar & Widgets Options
  */

	add_settings_section(
		'widget_settings_section',			// ID used to identify this section and with which to register options
		__( 'Sidebars & widgets Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_general_widget_options_callback',	// Callback used to render the description of the section
		'tw_theme_general_options'		// Page on which to add this section of options
	);

	add_settings_field(
		'enable_sidebar',
		__( 'Primary Sidebar', 'tw' ),
		'tw_enable_sidebar_callback',
		'tw_theme_general_options',
		'widget_settings_section',
		array(
			__( 'Enable the primary sidebar area.', 'tw' ),
		)
	);

	add_settings_field(
		'enable_homepage_sidebar',
		__( 'Homepage Widget Area', 'tw' ),
		'tw_enable_homepage_widget_callback',
		'tw_theme_general_options',
		'widget_settings_section',
		array(
			__( 'Enable the homepage template widget area', 'tw' ),
		)
	);

  add_settings_field(
		'enable_footer_widgets',
		__( 'Footer Widgets', 'tw' ),
		'tw_footer_widgets_callback',
		'tw_theme_general_options',
		'widget_settings_section',
		array(
			__( 'Enable the primary sidebar area.', 'tw' ),
		)
	);

  add_settings_section(
		'slide_settings_section',			// ID used to identify this section and with which to register options
		__( 'Slider Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_slider_options_callback',	// Callback used to render the description of the section
		'tw_theme_general_options'		// Page on which to add this section of options
	);

  add_settings_field(
		'slider_style',
		__( 'Slider Style', 'tw' ),
		'tw_slider_style_callback',
		'tw_theme_general_options',
		'slide_settings_section',
		array(
			__( 'Select slider transition style', 'tw' ),
		)
	);


	add_settings_section(
		'footer_settings_section',			// ID used to identify this section and with which to register options
		__( 'Footer Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_footer_options_callback',	// Callback used to render the description of the section
		'tw_theme_general_options'		// Page on which to add this section of options
	);

	add_settings_field(
		'enable_footer_social',
		__( 'Footer Social Icons', 'tw' ),
		'tw_footer_social_icons_callback',
		'tw_theme_general_options',
		'footer_settings_section',
		array(
			__( 'Show social icons in footer.', 'tw' ),
		)
	);


	// Finally, we register the fields with WordPress
	register_setting(
		'tw_theme_general_options',
		'tw_theme_general_options'
	);


} // end tw_initialize_theme_options
add_action( 'admin_init', 'tw_initialize_theme_options' );



function tw_enable_top_menu_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_general_options');

	// Next, we update the name attribute to access this element's ID in the context of the display options array
	// We also access the show_header element of the options collection in the call to the checked() helper function
	$html = '<input type="checkbox" id="enable_top_menu" name="tw_theme_general_options[enable_top_menu]" value="1" ' . checked( 1, isset( $options['enable_top_menu'] ) ? $options['enable_top_menu'] : 0, false ) . '/>';

	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="enable_top_menu">&nbsp;'  . $args[0] . '</label>';

	echo $html;

} // end tw_enable_top_menu_callback

function tw_enable_footer_menu_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_general_options');

	// Next, we update the name attribute to access this element's ID in the context of the display options array
	// We also access the show_header element of the options collection in the call to the checked() helper function
	$html = '<input type="checkbox" id="enable_footer_menu" name="tw_theme_general_options[enable_footer_menu]" value="1" ' . checked( 1, isset( $options['enable_footer_menu'] ) ? $options['enable_footer_menu'] : 0, false ) . '/>';

	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="enable_footer_menu">&nbsp;'  . $args[0] . '</label>';

	echo $html;

} // end tw_enable_top_menu_callback

function tw_enable_sidebar_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_general_options');
	$html = '<input type="checkbox" id="enable_sidebar" name="tw_theme_general_options[enable_sidebar]" value="1" ' . checked( 1, isset( $options['enable_sidebar'] ) ? $options['enable_sidebar'] : 0, false ) . '/>';
	$html .= '<label for="enable_sidebar">&nbsp;'  . $args[0] . '</label>';
	echo $html;

} // end tw_enable_sidebar_callback

function tw_enable_homepage_widget_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_general_options');
	$html = '<input type="checkbox" id="enable_homepage_sidebar" name="tw_theme_general_options[enable_homepage_sidebar]" value="1" ' . checked( 1, isset( $options['enable_homepage_sidebar'] ) ? $options['enable_homepage_sidebar'] : 0, false ) . '/>';
	$html .= '<label for="enable_homepage_sidebar">&nbsp;'  . $args[0] . '</label>';
	echo $html;

} // end tw_enable_sidebar_callback

function tw_footer_widgets_callback() {

	$options = get_option( 'tw_theme_general_options' );

	$html = '<select id="enable_footer_widgets" name="tw_theme_general_options[enable_footer_widgets]">';
		$html .= '<option value="0">' . __( 'Select the number of footer widget areas', 'tw' ) . '</option>';
		$html .= '<option value="0"' . selected( $options['enable_footer_widgets'], '0', false) . '>' . __( 'No Footer Widgets', 'tw' ) . '</option>';
		//$html .= '<option value="1"' . selected( $options['footer_widgets'], '1', false) . '>' . __( '1 Footer Widget Areas', 'tw' ) . '</option>';
		$html .= '<option value="2"' . selected( $options['enable_footer_widgets'], '2', false) . '>' . __( '2 Footer Widget Areas', 'tw' ) . '</option>';
		$html .= '<option value="3"' . selected( $options['enable_footer_widgets'], '3', false) . '>' . __( '3 Footer Widget Areas', 'tw' ) . '</option>';
		$html .= '<option value="4"' . selected( $options['enable_footer_widgets'], '4', false) . '>' . __( '4 Footer Widget Areas', 'tw' ) . '</option>';	$html .= '</select>';

	echo $html;

} // end tw_radio_element_callback

function tw_slider_style_callback($args){

	$options = get_option( 'tw_theme_general_options' );

	$html = '<input type="radio" id="slide" name="tw_theme_general_options[slider_style]" value="slide"' . checked( 'slide', $options['slider_style'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="slide">Slide</label>';
	$html .= '&nbsp;';
	$html .= '<input type="radio" id="fade" name="tw_theme_general_options[slider_style]" value="fade"' . checked( 'fade', $options['slider_style'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="fade">Fade</label>';
  $html .= '<p class="description" >&nbsp;'  . $args[0] . '</p>';
	echo $html;


}


function tw_footer_social_icons_callback($args){
	$options = get_option('tw_theme_general_options');
	$html = '<input type="checkbox" id="enable_footer_social" name="tw_theme_general_options[enable_footer_social]" value="1" ' . checked( 1, isset( $options['enable_footer_social'] ) ? $options['enable_footer_social'] : 0, false ) . '/>';
	$html .= '<label for="enable_footer_social">&nbsp;'  . $args[0] . '</label>';

	echo $html;
}

/* ------------------------------------------------------------------------ *
 * Logos and Images
 * ------------------------------------------------------------------------ */
function tw_theme_default_logo_options() {
	$defaults = array();
	return apply_filters( 'tw_theme_default_logo_options', $defaults );
} // end tw_theme_default_general_options


function tw_logo_options_callback() {
	//echo '<p>' . __( '', 'tw' ) . '</p>';
} // end tw_general_options_callback

function tw_favicon_options_callback() {
	//echo '<p>' . __( '', 'tw' ) . '</p>';
} // end tw_general_widget_options_callback

function tw_apple_icons_options_callback() {
	//echo '<p>' . __( 'Upload Apple Homescreen Icons', 'tw' ) . '</p>';
} // end tw_general_menu_options_callback

function tw_initialize_logo_options() {

	// If the theme options don't exist, create them.
	if( false == get_option( 'tw_theme_logo_options' ) ) {
		add_option( 'tw_theme_logo_options', apply_filters( 'tw_theme_default_logo_options', tw_theme_default_logo_options() ) );
	} // end if

	add_settings_section(
		'logo_settings_section',			// ID used to identify this section and with which to register options
		__( 'Logo Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_logo_options_callback',	// Callback used to render the description of the section
		'tw_theme_logo_options'		// Page on which to add this section of options
	);

  add_settings_field(
		'logo',
		__( 'Site Logo', 'tw' ),
		'tw_logo_upload_callback',
		'tw_theme_logo_options',
		'logo_settings_section',
		array(
			__( '</br>Upload site logo.<br/> Best results with a transparent png with max height of 100px.', 'tw' ),
		)
	);

  add_settings_section(
		'favicon_settings_section',			// ID used to identify this section and with which to register options
		__( 'Favicon Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_favicon_options_callback',	// Callback used to render the description of the section
		'tw_theme_logo_options'		// Page on which to add this section of options
	);


	add_settings_field(
		'favicon',
		__( 'Site Favicon', 'tw' ),
		'tw_favicon_upload_callback',
		'tw_theme_logo_options',
		'favicon_settings_section',
		array(
			__( '</br>Upload site favicon.<br/> Best results with a transparent png with max 16x16px.', 'tw' ),
		)
	);

	add_settings_section(
		'apple_icon_settings_section',			// ID used to identify this section and with which to register options
		__( 'Apple Home Screen Icon Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_apple_icons_options_callback',	// Callback used to render the description of the section
		'tw_theme_logo_options'		// Page on which to add this section of options
	);

  add_settings_field(
		'apple-icon',
		__( 'Apple iPhone 3', 'tw' ),
		'tw_apple_icon_upload_callback',
		'tw_theme_logo_options',
		'apple_icon_settings_section',
		array(
			__( '<br/> Best results with a square png with max 54x54px.', 'tw' ),
		)
	);

	add_settings_field(
		'apple-icon-72',
		__( 'Apple iPhone 4', 'tw' ),
		'tw_apple_icon_72_upload_callback',
		'tw_theme_logo_options',
		'apple_icon_settings_section',
		array(
			__( '<br/> Best results with a square png with max 72x72px.', 'tw' ),
		)
	);

	add_settings_field(
		'apple-icon-114',
		__( 'Apple iPhone Retina', 'tw' ),
		'tw_apple_icon_114_upload_callback',
		'tw_theme_logo_options',
		'apple_icon_settings_section',
		array(
			__( '<br/> Best results with a square png with max 114x114px.', 'tw' ),
		)
	);

	add_settings_field(
		'apple-icon-144',
		__( 'Apple iPad', 'tw' ),
		'tw_apple_icon_144_upload_callback',
		'tw_theme_logo_options',
		'apple_icon_settings_section',
		array(
			__( '<br/> Best results with a square png with max 144x144px.', 'tw' ),
		)
	);

	// Finally, we register the fields with WordPress
	register_setting(
		'tw_theme_logo_options',
		'tw_theme_logo_options',
		'tw_theme_validate_logo_upload'
	);


} // end tw_initialize_theme_options
add_action( 'admin_init', 'tw_initialize_logo_options' );


function tw_logo_upload_callback($args){
  $options = get_option('tw_theme_logo_options');
  $html = '<div id="logo_wrapper">';
    $html .='<input type="text" id="logo_url" name="tw_theme_logo_options[logo]" value="'.esc_url( $options['logo'] ).'" />
		<input id="upload_logo_button" type="button" class="button" value="'.__( 'Upload', 'tw' ).'" />';
		if ( '' != $options['logo'] ){
			$html .='<input id="delete_logo_button" name="tw_theme_logo_options[logo]" type="submit" class="button" value="'.__( 'Delete', 'tw' ).'" />';
		}
		$html .='<input id="reset_logo_button" name="tw_theme_logo_options[logo]" type="submit" class="button" value="'.__( 'Reset', 'tw' ).'" />';
    $html .= '<label for="logo_url" class="description">&nbsp;'  . $args[0] . '</label>';
		$html .= '<div id="logo_url_preview" style="min-height: 10px;"><img style="max-width:500px; max-height:300px;" src="'.esc_url( $options['logo'] ).'" /></div>';
		$html .= '</div>';
  echo $html;
}

function tw_favicon_upload_callback($args){
  $options = get_option('tw_theme_logo_options');
  $html ='<input type="text" id="favicon_url" name="tw_theme_logo_options[favicon]" value="'.esc_url( $options['favicon'] ).'" />
		<input id="upload_favicon_button" type="button" class="button" value="'.__( 'Upload', 'tw' ).'" />';
		if ( '' != $options['logo'] ){
			$html .='<input id="delete_favicon_button" name="tw_theme_logo_options[favicon]" type="submit" class="button" value="'.__( 'Delete', 'tw' ).'" />';
		}
		$html .='<input id="reset_favicon_button" name="tw_theme_logo_options[favicon]" type="submit" class="button" value="'.__( 'Reset', 'tw' ).'" />';
    $html .= '<label for="favicon_url" class="description">&nbsp;'  . $args[0] . '</label>';
		$html .= '<div id="favicon_url_preview" style="min-height: 10px;">
		<img style="max-width:16px; max-height:16px;" src="'.esc_url( $options['favicon'] ).'" />
	</div>';
  echo $html;
}

function tw_apple_icon_upload_callback($args){
  $options = get_option('tw_theme_logo_options');
  $html ='<input type="text" id="apple_icon_url" name="tw_theme_logo_options[apple-icon]" value="'.esc_url( $options['apple-icon'] ).'" />
		<input id="upload_apple_icon_button" type="button" class="button" value="'.__( 'Upload', 'tw' ).'" />';
		if ( '' != $options['apple-icon'] ){
			$html .='<input id="delete_apple_icon_button" name="tw_theme_logo_options[apple-icon]" type="submit" class="button" value="'.__( 'Delete', 'tw' ).'" />';
		}
		$html .='<input id="reset_apple_icon_button" name="tw_theme_logo_options[apple-icon]" type="submit" class="button" value="'.__( 'Reset', 'tw' ).'" />';
    $html .= '<label for="apple_icon_url" class="description">&nbsp;'  . $args[0] . '</label>';
		$html .= '<div id="apple_icon_url_preview" style="min-height: 10px;">
		<img style="max-width:54px; max-height:54px;" src="'.esc_url( $options['apple-icon'] ).'" />
	</div>';
  echo $html;
}

function tw_apple_icon_72_upload_callback($args){
  $options = get_option('tw_theme_logo_options');
  $html ='<input type="text" id="apple_icon_72_url" name="tw_theme_logo_options[apple-icon-72]" value="'.esc_url( $options['apple-icon-72'] ).'" />
		<input id="upload_apple_icon_72_button" type="button" class="button" value="'.__( 'Upload', 'tw' ).'" />';
		if ( '' != $options['apple-icon-72'] ){
			$html .='<input id="delete_apple_icon_72_button" name="tw_theme_logo_options[apple-icon-72]" type="submit" class="button" value="'.__( 'Delete', 'tw' ).'" />';
		}
		$html .='<input id="reset_apple_icon_72_button" name="tw_theme_logo_options[apple-icon-72]" type="submit" class="button" value="'.__( 'Reset', 'tw' ).'" />';
    $html .= '<label for="apple_icon_72_url" class="description">&nbsp;'  . $args[0] . '</label>';
		$html .= '<div id="apple_icon_72_url_preview" style="min-height: 10px;">
		<img style="max-width:72px; max-height:72px;" src="'.esc_url( $options['apple-icon-72'] ).'" />
	</div>';
  echo $html;
}

function tw_apple_icon_114_upload_callback($args){
  $options = get_option('tw_theme_logo_options');
  $html ='<input type="text" id="apple_icon_114_url" name="tw_theme_logo_options[apple-icon-114]" value="'.esc_url( $options['apple-icon-114'] ).'" />
		<input id="upload_apple_icon_114_button" type="button" class="button" value="'.__( 'Upload', 'tw' ).'" />';
		if ( '' != $options['apple-icon-114'] ){
			$html .='<input id="delete_apple_icon_114_button" name="tw_theme_logo_options[apple-icon-114]" type="submit" class="button" value="'.__( 'Delete', 'tw' ).'" />';
		}
		$html .='<input id="reset_apple_icon_114_button" name="tw_theme_logo_options[apple-icon-114]" type="submit" class="button" value="'.__( 'Reset', 'tw' ).'" />';
    $html .= '<label for="apple_icon_114_url" class="description">&nbsp;'  . $args[0] . '</label>';
		$html .= '<div id="apple_icon_114_url_preview" style="min-height: 10px;">
		<img style="max-width:114px; max-height:114px;" src="'.esc_url( $options['apple-icon-114'] ).'" />
	</div>';
  echo $html;
}

function tw_apple_icon_144_upload_callback($args){
  $options = get_option('tw_theme_logo_options');
  $html ='<input type="text" id="apple_icon_144_url" name="tw_theme_logo_options[apple-icon-144]" value="'.esc_url( $options['apple-icon-144'] ).'" />
		<input id="upload_apple_icon_144_button" type="button" class="button" value="'.__( 'Upload', 'tw' ).'" />';
		if ( '' != $options['apple-icon-144'] ){
			$html .='<input id="delete_apple_icon_144_button" name="tw_theme_logo_options[apple-icon-144]" type="submit" class="button" value="'.__( 'Delete', 'tw' ).'" />';
		}
		$html .='<input id="reset_apple_icon_144_button" name="tw_theme_logo_options[apple-icon-144]" type="submit" class="button" value="'.__( 'Reset', 'tw' ).'" />';
    $html .= '<label for="apple_icon_144_url" class="description">&nbsp;'  . $args[0] . '</label>';
		$html .= '<div id="apple_icon_144_url_preview" style="min-height: 10px;">
		<img style="max-width:144px; max-height:144px;" src="'.esc_url( $options['apple-icon-144'] ).'" />
	</div>';
  echo $html;
}

/* ------------------------------------------------------------------------ *
 * Blog Options
 * ------------------------------------------------------------------------ */
/**
 * Provides default values for the Social Options.
 */
function tw_theme_default_blog_options() {

	$defaults = array(
		//'aside',   // title less blurb
		'gallery', // gallery of images
		//'link',    // quick link to other site
		//'image',   // an image
		'quote',   // a quick quote
		//'status',  // a Facebook like status update
		'video',   // video
		'audio',   // audio
		//'chat'     // chat transcript
	);

	return apply_filters( 'tw_theme_default_blog_options', $defaults );

}

/**
 * This function provides a simple description for the Social Options page.
 *
 * It's called from the 'tw_theme_initialize_social_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function tw_blog_options_callback() {
	echo '<p>' . __( 'Post format options', 'tw' ) . '</p>';
} // end tw_general_options_callback
function tw_blog_comments_callback() {
	echo '<p>' . __( '', 'tw' ) . '</p>';
} // end tw_blog_comments_callback

function tw_blog_related_posts_callback() {
	echo '<p>' . __( '', 'tw' ) . '</p>';
} // end tw_blog_related_posts_callback


/**
 * Initializes the theme's blog options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
function tw_theme_initialize_blog_options() {

  if( false == get_option( 'tw_theme_blog_options' ) ) {
		add_option( 'tw_theme_blog_options', apply_filters( 'tw_theme_default_blog_options', tw_theme_default_blog_options() ) );
	} // end if

  $post_formats = array(
		//'aside'   => 'Aside',   // title less blurb
		'gallery' => 'Gallery', // gallery of images
		//'link'    => "Link",    // quick link to other site
		//'image'   => "Image",   // an image
		'quote'   => "Quote",   // a quick quote
		//'status'  => "Status",  // a Facebook like status update
		'video'   => "Video",   // video
		'audio'   => "Audio",   // audio
		//'chat'    => "Chat" // chat transcript
	);

	add_settings_section(
		'blog_settings_section',			// ID used to identify this section and with which to register options
		__( 'Blog Post Formats', 'tw' ),		// Title to be displayed on the administration page
		'tw_blog_options_callback',	// Callback used to render the description of the section
		'tw_theme_blog_options'		// Page on which to add this section of options
	);

  foreach($post_formats as $k => $v){
    add_settings_field(
  		$k,
  		$v,
  		'tw_post_format_field_callback',
  		'tw_theme_blog_options',
  		'blog_settings_section',
  		array('format'=>$k)
  	);
  }


  add_settings_section(
		'blog_related_posts_settings_section',			// ID used to identify this section and with which to register options
		__( 'Blog Single Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_blog_related_posts_callback',	// Callback used to render the description of the section
		'tw_theme_blog_options'		// Page on which to add this section of options
	);

  add_settings_field(
		'enable_blog_sidebar',
		__( 'Post Sidebar', 'tw' ),
		'tw_enable_post_sidebar_callback',
		'tw_theme_blog_options',
		'blog_related_posts_settings_section',
		array(
			__( 'Enabled Sidebar in a single blog post', 'tw' ),
		)
	);

  add_settings_field(
		'enable_related_posts',
		__( 'Related Posts', 'tw' ),
		'tw_enable_related_posts_callback',
		'tw_theme_blog_options',
		'blog_related_posts_settings_section',
		array(
			__( 'Enabled Related Posts in a single blog post', 'tw' ),
		)
	);



	register_setting(
		'tw_theme_blog_options',
		'tw_theme_blog_options',
		'tw_theme_validate_input_examples'
	);

}
add_action( 'admin_init', 'tw_theme_initialize_blog_options' );


function tw_post_format_field_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_blog_options');

	// Next, we update the name attribute to access this element's ID in the context of the display options array
	// We also access the show_header element of the options collection in the call to the checked() helper function
	$html = '<input type="checkbox" id="'.$args['format'].'" name="tw_theme_blog_options['.$args['format'].']" value="1" ' . checked( 1, isset( $options[$args['format']] ) ? $options[$args['format']] : 0, false ) . '/>';

	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="'.$args['format'].'">&nbsp; <i>Enable '.$args['format'].' blog post format</i></label>';

	echo $html;

}

function tw_enable_post_sidebar_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_blog_options');
	$html = '<input type="checkbox" id="tw_theme_blog_options" name="tw_theme_blog_options[enable_blog_sidebar]" value="1" ' . checked( 1, isset( $options['enable_blog_sidebar'] ) ? $options['enable_blog_sidebar'] : 0, false ) . '/>';
	$html .= '<label for="tw_theme_blog_options">&nbsp;'  . $args[0] . '</label>';
	echo $html;

} // end tw_enable_sidebar_callback

function tw_enable_related_posts_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_blog_options');
	$html = '<input type="checkbox" id="tw_theme_blog_options" name="tw_theme_blog_options[enable_related_posts]" value="1" ' . checked( 1, isset( $options['enable_related_posts'] ) ? $options['enable_related_posts'] : 0, false ) . '/>';
	$html .= '<label for="tw_theme_blog_options">&nbsp;'  . $args[0] . '</label>';
	echo $html;

} // end tw_enable_sidebar_callback

function tw_enable_facebook_comments_callback($args) {

	// First, we read the options collection
	$options = get_option('tw_theme_social_options');
	$html = '<input type="checkbox" id="tw_theme_social_options" name="tw_theme_social_options[enable_fb_comments]" value="1" ' . checked( 1, isset( $options['enable_fb_comments'] ) ? $options['enable_fb_comments'] : 0, false ) . '/>';
	$html .= '<label for="tw_theme_social_options">&nbsp;'  . $args[0] . '</label>';
	echo $html;

} // end tw_enable_sidebar_callback


function tw_enable_comments_options_callback($args) {

	$options = get_option( 'tw_theme_social_options' );

	$html = '<select id="tw_theme_social_options" name="tw_theme_social_options[comment_options]">';
		$html .= '<option value="default">' . __( 'Select a comments option...', 'tw' ) . '</option>';
		$html .= '<option value="wp_comments"' . selected( $options['comment_options'], 'wp_comments', false) . '>' . __( 'Wordpress Comments Only', 'tw' ) . '</option>';
		$html .= '<option value="fb_comments"' . selected( $options['comment_options'], 'fb_comments', false) . '>' . __( 'Facebook Comments Only', 'tw' ) . '</option>';
		$html .= '<option value="wp_fb_comments"' . selected( $options['comment_options'], 'wp_fb_comments', false) . '>' . __( 'Wordpress & Facebook Comments', 'tw' ) . '</option>';
		$html .= '</select>';
		$html .= '<label for="tw_theme_social_options"><br/>'  . $args[0] . '</label>';

	echo $html;

} // end tw_radio_element_callback


/* ------------------------------------------------------------------------ *
 * Social Options
 * ------------------------------------------------------------------------ */
/**
 * Provides default values for the Social Options.
 */
function tw_theme_default_social_options() {

	$defaults = array(
		'fb_app_id'		=>	'',
		'fb_page'		=>	'',
		'twitter'		=>	'',
		'instagram'		=>	'',
		'pinterest'		=>	'',
		'linkedin'		=>	'',
		'googleplus'	=>	'',
	);

	return apply_filters( 'tw_theme_default_social_options', $defaults );

}

function tw_social_options_callback() {
	echo '<p>' . __( 'Provide the details of each social network', 'tw' ) . '</p>';
}
function tw_social_api_options_callback() {
	echo '<p>' . __( 'Social API Details', 'tw' ) . '</p>';
}
function tw_twitter_api_options_callback() {
	echo '<p>' . __( 'Twitter API Details', 'tw' ) . '</p>';
}
/**
 * Initializes the theme's social options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
function tw_theme_initialize_social_options() {

	if( false == get_option( 'tw_theme_social_options' ) ) {
		add_option( 'tw_theme_social_options', apply_filters( 'tw_theme_default_social_options', tw_theme_default_social_options() ) );
	} // end if

  $social_apis = array(
    'fb_app_id'		=>	'Facebook App ID',
    'sharedcount_id' => 'Shared Count API Key',
  );

  $twitter_apis = array(
    'twitter_oauth_access_token'		    => 'Access Token',
    'twitter_oauth_access_token_secret' => 'Access Token Secret',
    'twitter_consumer_key'        => 'Consumer Key',
    'twitter_consumer_secret'     => 'Consumer Secret',
  );

  $social_networks = array(
		//'fb_app_id'		=>	'Facebook App ID',
		'fb_page'		  =>	'Facebook Page URL',
		'twitter'		  =>	'Twitter @name',
		'instagram'		=>	'Instagram @name',
		'pinterest'		=>	'Pinterest URL',
		'tumblr'	    =>	'Tumblr Page URL',
		'linkedin'		=>	'Linkedin Profile URL',
		'slideshare'	=>	'Slideshare Page URL',
		'googleplus'	=>	'Google+ Page URL',
		'youtube'	    =>	'Youtube Channel URL',
		'vimeo'	      =>	'Vimeo Page URL',
	);

	add_settings_section(
		'social_settings_section',			// ID used to identify this section and with which to register options
		__( 'Social Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_social_options_callback',	// Callback used to render the description of the section
		'tw_theme_social_options'		// Page on which to add this section of options
	);

  foreach($social_networks as $k => $v){
    add_settings_field(
  		$k,
  		$v,
  		'tw_social_field_callback',
  		'tw_theme_social_options',
  		'social_settings_section',
  		array('network'=>$k)
  	);
  }

  add_settings_section(
		'social_api_settings_section',			// ID used to identify this section and with which to register options
		__( 'Social API Options', 'tw' ),		// Title to be displayed on the administration page
		'tw_social_api_options_callback',	// Callback used to render the description of the section
		'tw_theme_social_options'		// Page on which to add this section of options
	);

  foreach($social_apis as $k => $v){
    add_settings_field(
  		$k,
  		$v,
  		'tw_social_api_field_callback',
  		'tw_theme_social_options',
  		'social_api_settings_section',
  		array('api'=>$k)
  	);
  }

	add_settings_field(
		'enable_fb_comments',
		__( 'Commenting System', 'tw' ),
		//'tw_enable_facebook_comments_callback',
		'tw_enable_comments_options_callback',
		'tw_theme_social_options',
		'social_api_settings_section',
		array(
			__( 'Choose the type of commenting you would like.<br/> Facebook comments needs to have a valid Facebook App ID. <br/> Default is the Wordpress comments.', 'tw' ),
		)
	);


	// Twitter Options
	if(class_exists('TwitterAPIExchange')){
  	add_settings_section(
  		'twitter_api_settings_section',			// ID used to identify this section and with which to register options
  		__( 'Twitter Options', 'tw' ),		// Title to be displayed on the administration page
  		'tw_twitter_api_options_callback',	// Callback used to render the description of the section
  		'tw_theme_social_options'		// Page on which to add this section of options
  	);

  	foreach($twitter_apis as $k => $v){
      add_settings_field(
    		$k,
    		$v,
    		'tw_social_api_field_callback',
    		'tw_theme_social_options',
    		'twitter_api_settings_section',
    		array('api'=>$k)
    	);
    }


	}


	register_setting(
		'tw_theme_social_options',
		'tw_theme_social_options',
		'tw_theme_sanitize_social_options'
	);

}
add_action( 'admin_init', 'tw_theme_initialize_social_options' );

function tw_social_field_callback($args){
  $options = get_option( 'tw_theme_social_options' );
  $url = '';
  if( isset( $options[$args['network']] ) ) {
    if($args['network']!=='fb_app_id' && $args['network']!=='twitter' && $args['network']!=='instagram' ){
      $url = esc_url( $options[$args['network']] );
    }else{
      $url = trim($options[$args['network']]);
    }
	} // end if

	// Render the output
	echo '<input type="text" id="'.$args['network'].'" name="tw_theme_social_options['.$args['network'].']" value="' . $url . '" />';
}

function tw_social_api_field_callback($args){
  $options = get_option('tw_theme_social_options');

  echo '<input type="text" id="'.$args['api'].'" name="tw_theme_social_options['.$args['api'].']" value="' . trim($options[$args['api']]) . '" />';
}

/* ------------------------------------------------------------------------ *
 * Contact Options
 * ------------------------------------------------------------------------ */
function tw_theme_default_contact_options() {
  $defaults = array();
	return apply_filters( 'tw_theme_default_contact_options', $defaults );

}

/**
 * This function provides a simple description for the Blog Options page.
 *
 * It's called from the 'tw_theme_initialize_blog_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function tw_contact_options_callback() {
	echo '<p>' . __( 'Enter general contact information where applicable', 'tw' ) . '</p>';
}

function tw_contact_address_options_callback(){
  echo '<p>' . __( 'Enter business address details', 'tw' ) . '</p>';
}


/**
 * Initializes the theme's social options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
function tw_theme_initialize_contact_options() {

	if( false == get_option( 'tw_theme_contact_options' ) ) {
		add_option( 'tw_theme_contact_options', apply_filters( 'tw_theme_contact_options', tw_theme_default_contact_options() ) );
	} // end if

  $contact_fields = array(
		'phone'		  =>	'Phone',
		'toll_free'	=>	'Phone (Toll Free)',
		'fax'		    =>	'Fax',
		'Email'		  =>	'Email',
	);

	$address_fields = array(
		'address_1'	=>	'Address',
		'address_2'	=>	'Address (Line 2)',
		'city'		  =>	'City',
		'state'		  =>	'State/Province',
		'postcode'  =>	'Zip/Postcode',
		'country'		=>	'Country',
	);

  add_settings_section(
		'contact_settings_contact',			// ID used to identify this section and with which to register options
		__( 'Contact Details', 'tw' ),		// Title to be displayed on the administration page
		'tw_contact_options_callback',	// Callback used to render the description of the section
		'tw_theme_contact_options'		// Page on which to add this section of options
	);

  foreach($contact_fields as $k => $v){
    add_settings_field(
  		$k,
  		$v,
  		'tw_contact_callback',
  		'tw_theme_contact_options',
  		'contact_settings_contact',
  		array('contact'=>$k)
  	);
  }


  add_settings_section(
		'contact_settings_address',			// ID used to identify this section and with which to register options
		__( 'Address Details', 'tw' ),		// Title to be displayed on the administration page
		'tw_contact_address_options_callback',	// Callback used to render the description of the section
		'tw_theme_contact_options'		// Page on which to add this section of options
	);

  foreach($address_fields as $k => $v){
    add_settings_field(
  		$k,
  		$v,
  		'tw_contact_address_callback',
  		'tw_theme_contact_options',
  		'contact_settings_address',
  		array('address'=>$k)
  	);
  }

	register_setting(
		'tw_theme_contact_options',
		'tw_theme_contact_options',
		'tw_theme_validate_input_examples'
	);

}
add_action( 'admin_init', 'tw_theme_initialize_contact_options' );

function tw_contact_callback($args){
  $options = get_option('tw_theme_contact_options');

  echo '<input type="text" id="'.$args['contact'].'" name="tw_theme_contact_options['.$args['contact'].']" value="' . trim($options[$args['contact']]) . '" />';
}

function tw_contact_address_callback($args){
  $options = get_option('tw_theme_contact_options');

  echo '<input type="text" id="'.$args['address'].'" name="tw_theme_contact_options['.$args['address'].']" value="' . trim($options[$args['address']]) . '" />';
}