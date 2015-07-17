<?php
  add_action( 'after_setup_theme', 'declare_sensei_support' );
  function declare_sensei_support() {
      add_theme_support( 'sensei' );
  }


  global $woothemes_sensei;
  remove_action( 'sensei_before_main_content', array( $woothemes_sensei->frontend, 'sensei_output_content_wrapper' ), 10 );
  remove_action( 'sensei_after_main_content', array( $woothemes_sensei->frontend, 'sensei_output_content_wrapper_end' ), 10 );

  add_action('sensei_before_main_content', 'tw_sensei_theme_wrapper_start', 10);
  add_action('sensei_after_main_content', 'tw_sensei_theme_wrapper_end', 10);

  function tw_sensei_theme_wrapper_start() {
    $primary_sidebar = tw_is_sidebar_enabled();
    echo '<!-- Site Container --><div id="site-content" class="container">';
    echo '<div id="site-container" class="row">';

    //echo '<div id="container"><div id="content" role="main">';
    if($primary_sidebar){
      echo '<div id="primary" class="content-area col-xs-12 col-sm-8 col-md-9">';
    }else{
      echo '<div id="primary" class="content-area col-xs-12 col-sm-12 col-md-12">';
    }
    echo '<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">';
  }

  function tw_sensei_theme_wrapper_end() {
    $primary_sidebar = tw_is_sidebar_enabled();
    echo '</main><!-- .site-main -->';
    echo '</div><!-- .content-area -->';
    if($primary_sidebar){get_sidebar( 'sidebar' );}
    echo '</div><!-- #site-container -->';
    echo '</div><!-- #site-content -->';
  }