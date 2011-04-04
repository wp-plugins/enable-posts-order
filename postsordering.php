<?php
/**
 * @package Enable posts order
 * @version 0.1
 */
/*
Plugin Name: Enable posts order
Plugin URI: http://wordpress.org/plugins/enable-posts-order/
Description: This plugin gives you an easy way for ordering your posts. similar to the generic option in pages.
Author: Ben Yitzhaki
Version: 0.1
Author URI: http://www.benyitzhaki.co.il
*/

function add_selectbox($order,$id) {
$order = (int) $order;
return <<<html
<input type='hidden' name='custom_posts_order[]' id="{$id}" class='postsorder' value="{$id}" />
html;
}


function posts_columns_title($x)
{   

    $x['postsorder'] =  "<strong style='cursor:pointer;border:1px solid grey;background-color:#D3D3D3;padding:4px;' class='update_order'>".__('Save order')."</strong>";

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
        

		$( "#the-list" ).sortable({"opacity": 0.6,"revert": true, "cursor": 'move'});

        $(".update_order").click( function(){
            jQuery(this).css("background-color","#E9E8B7");
            

             var topost = "";
             
             jQuery("#the-list").find("input.postsorder").each(function(){
                
                topost = topost + ','+$(this).val();
                });
                
	var data = {
		action: 'update_posts_order',
		order: topost
	};
    
	jQuery.post(ajaxurl, data, function(response) {
	   $(".update_order").css("background-color","#C6E6CC"); 
       });
                

        
        
        });
        
    
});

</script>
html;
}







function update_posts_order_callback() {
	global $wpdb;
$orders = explode(",",$_POST['order']);
$i = 1;
foreach($orders as $order)
{
    if(intval($order) > 0) {

    $wpdb->query("UPDATE `$wpdb->posts` SET `menu_order` = '".$i."' WHERE `ID`='".$order."'");
       
        
    $i++;
    }
    
}
    
	die('ok');
  
}




function pre_posts_order($wp_query)
{
    $wp_query->set( 'orderby', 'menu_order' );

    $wp_query->set( 'order' , 'ASC' );
    return $wp_query;
}


// we want to apply the rules only when in a certain category and inside the admin
if(is_admin()){
add_action('wp_ajax_update_posts_order', 'update_posts_order_callback');

 function my_init_method() {
    wp_deregister_script( 'jquery-ui' );
    wp_register_script( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js');
    wp_enqueue_script( 'jquery-ui' );
}    
 
add_action('init', 'my_init_method');


if(isset($_GET['category_name'])) {
    
    
    add_action('admin_footer', 'auto_update_script');

    add_action('manage_posts_custom_column', 'posts_columns_content', 1, 2);
    add_filter('manage_posts_columns', "posts_columns_title",1);




if(is_admin()) add_filter('pre_get_posts', 'pre_posts_order');





    }
}
else add_filter('pre_get_posts', 'pre_posts_order');






    
    
?>
