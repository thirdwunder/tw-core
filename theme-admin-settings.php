<?php

add_action("admin_menu", "tw_setup_theme_admin_menus");
function tw_setup_theme_admin_menus() {
  add_menu_page(
                'Theme settings',         //page title
                'TW Theme Settings',       //menu title
                'manage_settings',         //capability
                'tw_theme_settings',      //meny slug
                'tw_theme_settings_page'  //callback function
              );

  add_submenu_page(
                'tw_theme_settings',
                'Contact',
                'Contact',
                'manage_settings',
                'tw-contact-settings',
                'tw_theme_contact_settings'
                );
  add_submenu_page(
                'tw_theme_settings',
                'Blog',
                'Blog',
                'manage_settings',
                'tw-blog-settings',
                'tw_theme_blog_settings'
                );
}



/*********************************
******** Blog Options **********
*********************************/
function tw_theme_blog_settings(){
  if (!current_user_can('manage_options')) {
      wp_die('You do not have sufficient permissions to access this page.');
  }

  $post_formats = array(
    'aside'   => 'A title-less post blurbs used for notifications and notes',
    'gallery' => 'A post that includes a gallery of a series of images',
    'link'    => 'Links are a quick links to other sites',
    'image'   => 'A post that features a singular image',
    'quote'   => 'A post that comprises of a quotation from a source',
    'status'  => 'A status is like a facebook status message or tweet',
    'video'   => 'A post that includes a featured video',
    'audio'   => 'A post that includes a featured audio recording or podcast',
    'chat'    => 'A post that is mainly a chat transcript',
  );

  if(isset($_POST['update_settings'])){
    $tw_theme_blog_options = array();

    /*** Post Format Options ***/
    $tw_theme_blog_post_formats = array();
    foreach($post_formats as $name => $description){
      $tw_theme_blog_post_formats[$name] = esc_attr($_POST["tw_theme_blog_$name"])!='' ? true:false ;
    }
    update_option("tw_theme_blog_post_formats", $tw_theme_blog_post_formats);
  ?>
    <div id="message" class="updated"><?php echo __('Settings saved','tw'); ?></div>
  <?php
  }
  $tw_theme_blog_post_formats  = get_option('tw_theme_blog_post_formats');
  ?>
  <div class="wrap">
    <h2><?php echo __('Blog Options','tw'); ?></h2>
    <br/>
    <h3><?php echo __('Settings','tw'); ?></h3>
    <form method="post" action="">
      <input type="hidden" name="update_settings" value="Y" />

      <h3><?php echo __('Post Formats','tw'); ?></h3>
      <table class="wp-list-table widefat fixed">
        <tbody>
          <?php foreach($post_formats as $name => $description):?>

            <tr valign="top">
              <th width="200px"><label for="tw_theme_blog_<?php echo $name;?>"><?php echo __('Enable ','tw').ucfirst($name); ?></label></th>
              <td>
                <input type="checkbox" value="1" id="tw_theme_blog_<?php echo $name;?>" name="tw_theme_blog_<?php echo $name;?>" <?php checked($tw_theme_blog_post_formats[$name],1);?> />
                <i><?php echo $description; ?></i>
              </td>
            </tr>

          <?php endforeach; ?>
        </tbody>
      </table>
      <br/>

      <p><input type="submit" value="Save settings" class="button-primary"/></p>
    </form>
  </div>
  <?php
}