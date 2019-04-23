<?php
// assests
function us_enq_script($hook) {
    wp_enqueue_script('us-script', plugin_dir_url(__FILE__) . '../script.js' ,'', '', true);
    wp_enqueue_style('us_bootstrap', plugin_dir_url(__FILE__) . '../css/bootstrap.min.css');
    wp_enqueue_style('us_style', plugin_dir_url(__FILE__) . '../style.css');
}
add_action('wp_enqueue_scripts', 'us_enq_script',10);


add_action( 'admin_enqueue_scripts', 'uc_load_admin_style' );
function uc_load_admin_style() {
    wp_enqueue_style('us_bootstrap', plugin_dir_url(__FILE__) . '../css/admin-style.css
    ');
 }