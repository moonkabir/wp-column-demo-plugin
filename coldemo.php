<?php
/*
Plugin Name: Column Demo
Plugin URI: https://github.com/moonkabir/wp-column-demo-plugin
Description: WordPress Column Extra Field Add
Version: 1.0
Author: Moon Kabir
Author URI: https://moonkabir.xyz
License: GPLv2 or later
Text Domain: column-demo
Domain Path: /languages/
*/

function coldemo_bootstrap()
{
    load_plugin_textdomain('column-demo', false, dirname(__FILE__) . "/languages");
}
add_action('plugins_loaded', 'coldemo_bootstrap');

function coldemo_post_columns($columns)
{
    /*print_r($columns);
    unset($columns['title']);
    // remove tag columns from posts menu
    unset($columns['tags']);*/
    // comment go at the end 
    unset($columns['comments']);
    $columns['comments'] = "Comments";
    // new Columnd Add
    $columns['id'] = __('Post ID', 'Column-demo');
    $columns['thumbnail'] = __('Thumbnail', 'Column-demo');
    $columns['wordcount'] = __('Word Count', 'Column-demo');
    return $columns;
}
add_filter('manage_posts_columns', 'coldemo_post_columns');
add_filter('manage_pages_columns', 'coldemo_post_columns');

function coldemo_post_column_data($columns, $post_id)
{
    if ('id' == $columns) {
        echo $post_id;
    } elseif ('thumbnail' == $columns) {
        // thumbnail or array(100,100)
        $thumbnail = get_the_post_thumbnail($post_id, array(100, 100));
        echo $thumbnail;
    } elseif ('wordcount' == $columns) {
        /*$_post = get_post($post_id);
        $content = $_post->post_content;
        $wordn = str_word_count(strip_tags($content));*/
        $wordn = get_post_meta($post_id, 'wordn', true);
        echo $wordn;
    }
}
add_action('manage_posts_custom_column', 'coldemo_post_column_data', 10, 2);
add_action('manage_pages_custom_column', 'coldemo_post_column_data', 10, 2);

function coldemo_sortable_column($columns)
{
    $columns['wordcount'] = 'wordn';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'coldemo_sortable_column');
add_filter('manage_edit-page_sortable_columns', 'coldemo_sortable_column');

// just one time run this code for meta value update

// function coldemo_set_word_count()
// {
//     $_posts = get_posts(array(
//         'posts_per_page' => -1,
//         'post_type' => 'post',
//         'post_type' => 'page',
//         // 'post_status' => 'any'
//     ));
//     foreach ($_posts as $post) {
//         $content = $post->post_content;
//         $wordn = str_word_count(strip_tags($content));
//         update_post_meta($post->ID, 'wordn', $wordn);
//     }
// }
// add_action('init', 'coldemo_set_word_count');

function coldemo_sort_column_data($wpquery)
{
    if (!is_admin()) {
        return;
    }
    $orderby = $wpquery->get('orderby');
    $wpquery->set('meta_key', 'wordn');
    $wpquery->set('orderby', 'meta_value_num');
}
add_action('pre_get_posts', 'coldemo_sort_column_data');
add_action('pre_get_pages', 'coldemo_sort_column_data');

function coldemo_update_wordcount_on_post_save($post_id)
{
    $post = get_post($post_id);
    $content = $post->post_content;
    $wordn = str_word_count(strip_tags($content));
    update_post_meta($post->ID, 'wordn', $wordn);
}
add_action('save_post', 'coldemo_update_wordcount_on_post_save');
add_action('save_page', 'coldemo_update_wordcount_on_post_save');
