<?php
/*
Plugin Name: Public URL Shortener
Plugin URI:
Description: Use the shortcode [urlshortener] in a page to use this plugin.
Version: 1
Author: Burgil
Author URI: https://wordpress.org/plugins/public-url-shortener
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/**
 * Front end registration
 */
function urlshortener_func( $atts ){
    if(isset($_POST['url'])&&isset($_POST['custom'])){
        $payload='
        <form method="post">
        <input class="input" type="text" value="'.$_POST['url'].'" name="url" placeholder="Paste long url and shorten it">
        <div class="customurl"><label>'.get_home_url()."/".'</label><input id="customkey" class="input" type="text" value="'.$_POST['custom'].'" name="custom" placeholder="Enter a custom link"></div>
        <input class="button button-primary" id="shortbtn" style="display: inline;" type="submit" value="Shorten">
        </form>
        ';
        $isEmpty=false;
        if(empty($_POST['custom'])){$payload=$payload."<h3 style='color:red;border:3px double;width:auto;background: #0000007a;border-radius: 10px;'>ERROR: You entered an empty Custom Link for your shorten URL!</h3>";$isEmpty=true;}
        if(empty($_POST['url'])){$payload=$payload."<h3 style='color:red;border:3px double;width:auto;background: #0000007a;border-radius: 10px;'>ERROR: You entered an empty URL!</h3>";$isEmpty=true;}
        if($isEmpty==false){
            $urltoshort=$_POST['url'];
            $custom_link = sanitize_title_with_dashes( remove_accents( $_POST['custom'] ) );
            $new_post = array(
                'post_title' => $custom_link,
                'post_content' => $urltoshort,
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => '',
                'post_type' => 'post',
                'post_category' => array(0)
            );
            if(post_exists( $custom_link )==0){
                $post_id = wp_insert_post( $new_post );
                update_post_meta( $post_id, '_wp_page_template', 'shorturl.php' );
                $payload=$payload."<h3 style='color:green;border:3px double;width:auto;background: #0000007a;border-radius: 10px;'>SUCCESS: Custom Link Created: <a href='".get_home_url()."/".$custom_link."'>".get_home_url()."/".$custom_link."</a></h3>";
            }else{
                $payload=$payload."<h3 style='color:red;border:3px double;width:auto;background: #0000007a;border-radius: 10px;'>ERROR: Custom Link '".get_home_url()."/".$custom_link."' Already Exists!</h3>";
            }
        }
    }else{
        $payload='
        <form method="post">
        <input class="input" type="text" value="" name="url" placeholder="Paste long url and shorten it">
        <div class="customurl"><label>https://www.gtamacro.ga/</label><input id="customkey" class="input" type="text" value="" name="custom" placeholder="Enter a custom link"></div>
        <input class="button button-primary" id="shortbtn" style="display: inline;" type="submit" value="Shorten">
        </form>
        ';
    }
    return $payload;
}
add_shortcode( 'urlshortener', 'urlshortener_func' );