<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ Offline decoder for php versions: 40/74
 * @ Decoder version: 1.1.1
 * @ Release: 29/08/2024
 */

// Decoded file for php version 74.
defined("ABSPATH") or exit();
$almasGoldtEnqueueFunctions = new almasGoldtEnqueueFunctions();
class almasGoldtEnqueueFunctions
{
    public function __construct()
    {
        add_action("admin_enqueue_scripts", [$this, "ycc_enqueue_admin_css"]);
        add_action("wp_enqueue_scripts", [$this, "ycc_enqueue_custom_scripts"]);
    }
    public function ycc_enqueue_admin_css()
    {
        wp_enqueue_style("almas-gold-custom-dashboard-css", plugin_dir_url(__FILE__) . "../admin/assets/css/admin-style.css");
        wp_enqueue_script("almas-gold-admin-jquery", plugin_dir_url(__FILE__) . "../admin/assets/js/jquery-3.7.1.min.js", ["jquery"], true, false);
        wp_enqueue_script("almas-gold-admin-custom-script", plugin_dir_url(__FILE__) . "../admin/assets/js/admin-extra-scripts.js", ["jquery"], true, true);
    }
    public function ycc_enqueue_custom_scripts()
    {
        wp_enqueue_script("almas-gold-public-jquery", plugin_dir_url(__FILE__) . "../public/assets/js/jquery-3.7.1.min.js", ["jquery"], true, false);
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        
        wp_enqueue_script('date-fns', 'https://cdn.jsdelivr.net/npm/date-fns@2.29.1/dist/date-fns.min.js', array(), null, true);

// بارگذاری chartjs-adapter-date-fns
wp_enqueue_script('chartjs-adapter-date-fns', 'https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3', array('chartjs', 'date-fns'), null, true);
wp_enqueue_script('jalaali-js', 'https://cdnjs.cloudflare.com/ajax/libs/jalaali-js/1.1.3/jalaali.min.js', [], null, true);



        wp_enqueue_style("almas-gold-custom-public-css", plugin_dir_url(__FILE__) . "../public/assets/css/public-style.css");
        wp_enqueue_style("almas-gold-fontawesome-free-6.5.2-web", plugin_dir_url(__FILE__) . "../public/assets/vendor/fontawesome-free-6.5.2-web/css/all.min.css");
    }
}

?>