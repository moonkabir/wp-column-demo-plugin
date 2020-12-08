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
