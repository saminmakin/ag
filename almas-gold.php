<?php
    defined('ABSPATH') || exit;
    /**
     * almas gold
     *
     * @link              https://almas.gold
     * @since             1.0.0
     * @package           almas-gold
     *
     * Requires at least: 6.0
     * Requires PHP:      7.4
     * @wordpress-plugin
     * Plugin Name:       الماس گلد
     * Description:       کنسول پرداخت الماس گلد
     * Version:           1.0.0
     * Update URI:        https://almas.gold
     * Plugin URI:        https://almas.gold
     * Author:            Erfan Akbari - AlmasGold Maneger
     * Author URI:        https://almas.gold
     * Text Domain:       almas-gold
     * Domain Path:       /languages
     */
    $plugin_dir = plugins_url('/', __FILE__);

    require_once trailingslashit(dirname(__FILE__)) . '/classes/class-almas-gold-databases.php';
    require_once trailingslashit(dirname(__FILE__)) . '/classes/class-almas-gold-enqueue.php';
    require_once trailingslashit(dirname(__FILE__)) . '/classes/class-almas-gold-shortcodes.php';
    require_once trailingslashit(dirname(__FILE__)) . '/classes/class-almas-gold-menus.php';
    require_once trailingslashit(dirname(__FILE__)) . '/classes/class-almas-gold-functions.php';
    require_once trailingslashit(dirname(__FILE__)) . '/classes/class-almas-gold-send-messages.php';
    require_once trailingslashit(dirname(__FILE__)) . '/vendor/jdate/jdf.php';

    register_activation_hook(__FILE__, 'almas_gold_create_page');
