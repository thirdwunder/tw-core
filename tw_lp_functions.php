<?php

add_filter('lp_extension_data','lp_rebuild_old_data_configurations_to_suit_new_convention');
function lp_rebuild_old_data_configurations_to_suit_new_convention($lp_data){

   $lp_data['default']['settings']['video'] =   array(
        'label' => "Video URL",
        'description' => "URL of a promotional video to show in the header",
        'id'  => 'video_url',
        'type'  => 'text',
        //'default'  => 'off',
        //'options' => array('off'=>'Turn off','on'=>'Trun On'),
        'context'  => 'normal'
  );
  return $lp_data;
}