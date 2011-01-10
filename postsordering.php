<?php
/**
 * @package Enable posts order
 * @version 0.1
 */
/*
Plugin Name: Enable posts order
Plugin URI: http://wordpress.org/#
Description: This plugin gives you an easy way for ordering your posts. similar to the generic option in pages.
Author: Ben Yitzhaki
Version: 0.1
Author URI: http://www.benyitzhaki.co.il
*/

function add_selectbox($order,$id) {
$order = (int) $order;
return <<<html
<input type='text' name='custom_posts_order' id="{$id}" class='postsorder' value="{$order}" />
html;
}


function posts_columns_title($x)
{   

    $x['postsorder'] =  __('Order');

    return $x;
    
}

function posts_columns_content($name, $postid) {

    if( $name == 'postsorder' ) {
        echo add_selectbox(get_post_meta($postid,'custom_posts_order',TRUE),$postid);
    }
}

function auto_update_script()
{
echo <<<html
<script>
jQuery(document).ready(function($) {
    
    jQuery('.postsorder').focus(function() {
    jQuery(this).css("background-color","#fff");
    });
    
    jQuery('.postsorder').blur(function() {
        
        
	  jQuery(this).css("background-color","#F0F0C4");
      var savename = jQuery(this);
       var data = {
		action: 'update_posts_order',
	   	order: jQuery(this).val(),
        id: jQuery(this).attr('id')
    	};

    	jQuery.post(ajaxurl, data, function(response) {
    		if(response == 'ok') jQuery(savename).css("background-color","#C6E6CC");
            else {
                jQuery(savename).css("background-color","#EAD8D8");
                alert("an error has occured. it may have occured because slow connection or db error");
                }
    	});

        
    });
    
});

</script>
html;
}




function create_custom_field($postid) {
	global $wpdb; 

    // I validate that the custom field has inserted to the db because later, in your query, posts without that field wont appear.
    if($wpdb->query("SELECT * FROM `$wpdb->postmeta` WHERE `post_id`='".$postid."' AND `meta_key`='custom_posts_order'") == 0)
    {
     $wpdb->query("INSERT INTO `$wpdb->postmeta`(`meta_key`,`meta_value`,`post_id`) VALUES('custom_posts_order','0','".$postid."')");

    }
   //$wpdb->query("INSERT INTO `$wpdb->postmeta`(`meta_key`,`meta_value`,`post_id`) VALUES('custom_posts_order','0','".$postid."') ON DUPLICATE KEY UPDATE `meta_value`=meta_value");

}


function update_posts_order_callback() {
	global $wpdb; 

	$postid = (int) $_POST['id'];
    $order = (int) $_POST['order'];
    if($wpdb->query("UPDATE `$wpdb->postmeta` SET `meta_value` = '".$order."' WHERE `post_id`='".$postid."' AND meta_key='custom_posts_order'") == 0)
    {
        
    if($wpdb->query("INSERT INTO `$wpdb->postmeta`(`meta_key`,`meta_value`,`post_id`) VALUES('custom_posts_order','".$order."','".$postid."')")) echo "ok";
    else echo "0";
    
    }
    else echo "ok";
    
	die();
}


 
add_action('wp_ajax_update_posts_order', 'update_posts_order_callback');

add_action('publish_post', 'create_custom_field');

add_action('wp_ajax_my_special_action', 'update_posts_order');
add_action('manage_posts_custom_column', 'posts_columns_content', 1, 2);
add_filter('manage_posts_columns', "posts_columns_title",1);
add_action('admin_footer', 'auto_update_script');
?>
