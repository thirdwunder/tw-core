var uploadID = '';
var previewID = '';
jQuery(document).ready(function($){
	$('#upload_logo_button').click(function() {
  	uploadID = $(this).prev('input');
  	previewID = '#'+uploadID.attr('id') + '_preview img';
		tb_show('Upload Header Logo', 'media-upload.php?referer=tw_theme_menu&amp;type=image&amp;TB_iframe=true&amp;post_id=0', false);
		return false;
	});

  $('#upload_favicon_button').click(function() {
  	uploadID = $(this).prev('input');
  	previewID = '#'+uploadID.attr('id') + '_preview img';
		tb_show('Upload Favicon', 'media-upload.php?referer=tw_theme_menu&amp;type=image&amp;TB_iframe=true&amp;post_id=0', false);
		return false;
	});

	$('#upload_apple_icon_button').click(function() {
  	uploadID = $(this).prev('input');
  	previewID = '#'+uploadID.attr('id') + '_preview img';
		tb_show('Upload Apple iPhone 3 Home screen Icon', 'media-upload.php?referer=tw_theme_menu&amp;type=image&amp;TB_iframe=true&amp;post_id=0', false);
		return false;
	});

  $('#upload_apple_icon_72_button').click(function() {
  	uploadID = $(this).prev('input');
  	previewID = '#'+uploadID.attr('id') + '_preview img';
		tb_show('Upload Apple iPhone 4 Home screen Icon', 'media-upload.php?referer=tw_theme_menu&amp;type=image&amp;TB_iframe=true&amp;post_id=0', false);
		return false;
	});

	$('#upload_apple_icon_114_button').click(function() {
  	uploadID = $(this).prev('input');
  	previewID = '#'+uploadID.attr('id') + '_preview img';
		tb_show('Upload Apple Retina Display Home screen icon', 'media-upload.php?referer=tw_theme_menu&amp;type=image&amp;TB_iframe=true&amp;post_id=0', false);
		return false;
	});

	$('#upload_apple_icon_144_button').click(function() {
  	uploadID = $(this).prev('input');
  	previewID = '#'+uploadID.attr('id') + '_preview img';
		tb_show('Upload Apple iPad Home screen icon', 'media-upload.php?referer=tw_theme_menu&amp;type=image&amp;TB_iframe=true&amp;post_id=0', false);
		return false;
	});

	window.send_to_editor = function(html) {
  	//var image_url = $(html).attr('src');
  	image_url = $('img',html).attr('src');
  	uploadID.val(image_url);
  	tb_remove();
  	jQuery(previewID).attr('src',image_url);
  }
});


