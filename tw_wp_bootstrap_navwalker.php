<?php
if(class_exists('wp_bootstrap_navwalker')){
  class tw_wp_bootstrap_navwalker extends wp_bootstrap_navwalker{

    /**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 * Modified to use Font Awesome
	 */
  	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
  		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

      // Getting Phone number options from TW Settings
      $contact_options = tw_get_contact_info();
      $phone_enabled = isset($contact_options['enable_phone_in_menu']) && $contact_options['enable_phone_in_menu']!=='no' ? $contact_options['enable_phone_in_menu'] : false;
      $phone_number = isset($contact_options[$phone_enabled]) ? $contact_options[$phone_enabled] : false;
      $phone_number_clean = $phone_number && function_exists('tw_clean_phone_number') ? tw_clean_phone_number($phone_number) : false;
      $has_mobile_search = tw_is_mobile_menu_search_enabled();

  		/**
  		 * Dividers, Headers or Disabled
  		 * =============================
  		 * Determine whether the item is a Divider, Header, Disabled or regular
  		 * menu item. To prevent errors we use the strcasecmp() function to so a
  		 * comparison that is not case sensitive. The strcasecmp() function returns
  		 * a 0 if the strings are equal.
  		 */

  		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
  			$output .= $indent . '<li role="presentation" class="divider">';
  		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
  			$output .= $indent . '<li role="presentation" class="divider">';
  		} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
  			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
  		} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
  			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
  		} else if ( strcasecmp($item->attr_title, 'phone' ) == 0 && $phone_enabled && $phone_number ) {
    		// phone Attribute pulls displays phone number in menu from theme settings
  			$class_names = $value = '';
  			$classes = empty( $item->classes ) ? array() : $item->classes;
  			$classes[] = 'menu-item-' . $item->ID;

  			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

  			$output .= $indent . '<li role="presentation" class="'.$class_names.'"><a href="tel:'.$phone_number_clean.'">' . $phone_number . '</a>';
  		}elseif(strcasecmp($item->attr_title, 'wpml' ) == 0 && function_exists('icl_get_languages') ){

    		$languages = icl_get_languages('skip_missing=0&orderby=code');
    		if(!empty($languages)){

      		$class_names = $value = '';
    			$classes = empty( $item->classes ) ? array() : $item->classes;
    			$classes[] = 'menu-item-' . $item->ID;

    			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

      		foreach($languages as $l){
        		if($l['active'] == 0){
              $output .= $indent . '<li role="presentation" class="'.$class_names.'"><a href="'.$l['url'].'">' . strtoupper($l['language_code']) . '</a>';
        		}
      		}
    		}
      }elseif(strcasecmp($item->attr_title, 'search' ) == 0 && $has_mobile_search){
        $class_names = $value = '';
  			$classes = empty( $item->classes ) ? array() : $item->classes;
  			$classes[] = 'dropdown';
  			$classes[] = 'menu-item-' . $item->ID;
  			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
  			
        $output .= $indent . '<li itemprop="button" class="'.$class_names.'">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-search"></i></a>
        <ul id="main-desktop-search" class="dropdown-menu">
          <li>
          <form class="form-inline hidden-xs hidden-sm hidden-md" role="search" method="get" id="mobile-searchform" action="'. home_url( '/' ).'">
              <div class="form-group">
                  <label class="screen-reader-text sr-only" for="s">'.__('Search for','tw').':</label>
                  <div class="input-group">

                    <input class="form-control" type="text" value="" name="s" id="s" placeholder="'.__('Search','tw').'" autofocus />
                    <div class="input-group-btn">
                          <button class="btn btn-default" type="submit" id="searchsubmit" ><i class="fa fa-search"></i></button>
                    </div>
                  </div>
              </div>

          </form>
          </li>
        </ul>
      </li>';
  		} else {

  			$class_names = $value = '';

  			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
  			$classes[] = 'menu-item-' . $item->ID;

  			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

  			if ( $args->has_children )
  				$class_names .= ' dropdown';

  			if ( in_array( 'current-menu-item', $classes ) )
  				$class_names .= ' active';

  			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

  			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
  			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

  			$output .= $indent . '<li itemprop="url"' . $id . $value . $class_names .'>';

  			$atts = array();
  			$atts['title']  = ! empty( $item->title )	? $item->title	: '';
  			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
  			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

  			// If item has_children add atts to a.
  			if ( $args->has_children && $depth === 0 ) {
  				$atts['href']   		= '#';
  				$atts['data-toggle']	= 'dropdown';
  				$atts['class']			= 'dropdown-toggle';
  				$atts['aria-haspopup']	= 'true';
  			} else {
  				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
  			}

  			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

  			$attributes = '';
  			foreach ( $atts as $attr => $value ) {
  				if ( ! empty( $value ) ) {
  					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
  					$attributes .= ' ' . $attr . '="' . $value . '"';
  				}
  			}

  			$item_output = $args->before;

  			/*
  			 * Font Awesome
  			 * ===========
  			 * Since the the menu item is NOT a Divider or Header we check the see
  			 * if there is a value in the attr_title property. If the attr_title
  			 * property is NOT null we apply it as the class name for the glyphicon.
  			 */
  			if ( ! empty( $item->attr_title ) )
  				$item_output .= '<a'. $attributes .'><i class="fa fa-fw ' . esc_attr( $item->attr_title ) . '"></i>&nbsp;';
  			else
  				$item_output .= '<a'. $attributes .'>';

  			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
  			$item_output .= ( $args->has_children && 0 === $depth ) ? ' <i class="fa fa-fw fa-caret-down"></i></a>' : '</a>';
  			$item_output .= $args->after;
        
  			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  		}
  	}

  }


  class tw_wp_bootstrap_mobile_navwalker extends wp_bootstrap_navwalker{

    /**
    * @see Walker::start_el()
    * @since 3.0.0
    *
    * @param string $output Passed by reference. Used to append additional content.
    * @param object $item Menu item data object.
    * @param int $depth Depth of menu item. Used for padding.
    * @param int $current_page Menu item ID.
    * @param object $args
    * Modified to use Font Awesome
    */
  	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
  		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

  		/**
  		 * Dividers, Headers or Disabled
  		 * =============================
  		 * Determine whether the item is a Divider, Header, Disabled or regular
  		 * menu item. To prevent errors we use the strcasecmp() function to so a
  		 * comparison that is not case sensitive. The strcasecmp() function returns
  		 * a 0 if the strings are equal.
  		 */
  		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
  			$output .= $indent . '<li role="presentation" class="divider">';
  		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
  			$output .= $indent . '<li role="presentation" class="divider">';
  		} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
  			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
  		} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
  			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
  		} else if ( strcasecmp($item->attr_title, 'wpml' ) == 0 && function_exists('icl_get_languages') ){
    		
    		$languages = icl_get_languages('skip_missing=0&orderby=code');
    		if(!empty($languages)){

      		$class_names = $value = '';
    			$classes = empty( $item->classes ) ? array() : $item->classes;
    			$classes[] = 'menu-item-' . $item->ID;

    			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

      		foreach($languages as $l){
        		if($l['active'] == 0){
              $output .= $indent . '<li role="presentation" class="'.$class_names.'"><a href="'.$l['url'].'">' . strtoupper($l['language_code']) . '</a>';
        		}
      		}
    		}
    		
  		} else {

  			$class_names = $value = '';

  			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
  			$classes[] = 'menu-item-' . $item->ID;

  			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

  			if ( $args->has_children )
  				$class_names .= ' dropdown';

  			if ( in_array( 'current-menu-item', $classes ) )
  				$class_names .= ' active';

  			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

  			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
  			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

  			$output .= $indent . '<li itemprop="url"' . $id . $value . $class_names .'>';

  			$atts = array();
  			$atts['title']  = ! empty( $item->title )	? $item->title	: '';
  			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
  			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

  			// If item has_children add atts to a.
  			if ( $args->has_children && $depth === 0 ) {
  				$atts['href']   		= '#';
  				$atts['data-toggle']	= 'dropdown';
  				$atts['class']			= 'dropdown-toggle';
  				$atts['aria-haspopup']	= 'true';
  			} else {
  				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
  			}

  			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

  			$attributes = '';
  			foreach ( $atts as $attr => $value ) {
  				if ( ! empty( $value ) ) {
  					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
  					$attributes .= ' ' . $attr . '="' . $value . '"';
  				}
  			}

  			$item_output = $args->before;

  			/*
  			 * Font Awesome
  			 * ===========
  			 * Since the the menu item is NOT a Divider or Header we check the see
  			 * if there is a value in the attr_title property. If the attr_title
  			 * property is NOT null we apply it as the class name for the glyphicon.
  			 */
  			if ( ! empty( $item->attr_title ) )
  				$item_output .= '<a'. $attributes .'><i class="fa fa-fw ' . esc_attr( $item->attr_title ) . '"></i>&nbsp;';
  			else
  				$item_output .= '<a'. $attributes .'>';

  			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
  			$item_output .= ( $args->has_children && 0 === $depth ) ? ' <i class="fa fa-fw fa-caret-down"></i></a>' : '</a>';
  			$item_output .= $args->after;

  			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  		}
  	}


    /**
    * @see Walker::start_lvl()
    * @since 3.0.0
    *
    * @param string $output Passed by reference. Used to append additional content.
    * @param int $depth Depth of page. Used for padding.
    */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
  		$indent = str_repeat( "\t", $depth );
  		$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu navmenu-nav\">\n";
  	}
  }
}
