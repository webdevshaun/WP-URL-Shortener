<?php
/*
Template Name: Short URLS
Template Post Type: post, page
*/
while ( have_posts() ) :
    the_post();
    $content=get_the_content();
    header('Location: '.str_replace("&amp;","&",$content));
    exit();
endwhile;