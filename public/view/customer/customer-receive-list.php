<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ Offline decoder for php versions: 40/74
 * @ Decoder version: 1.1.1
 * @ Release: 29/08/2024
 */

// Decoded file for php version 74.
defined("ABSPATH") or exit();
if(!function_exists("almas_gold_customer_delivery_list")) {
    function almas_gold_customer_delivery_list()
    {
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if(!$user_id) {
            echo "                    <script>\r\n                        window.location.href = '";
            echo wp_login_url();
            echo "';\r\n                    </script>\r\n                ";
        }
        global $wpdb;
        global $wpdb;
        $core_data = $wpdb->get_row("SELECT \r\n                    gold_unit_to_bills,\r\n                    lists_date_display \r\n                FROM " . $wpdb->prefix . "almas_gold_core \r\n                ORDER BY id DESC \r\n                LIMIT 1");
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $lists_date_display = $core_data->lists_date_display;
        $unit_display = $gold_unit_to_bills;
        $table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
        $delivery_orders = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name_delivery . " WHERE user_id = %d AND transaction_processed = %d ORDER BY id DESC", $user_id, 1));
        if($delivery_orders) {
            require_once "view/customer-receive-list-html.php";
        } else {
            echo "\r\n                <div style=\"padding: 10px;\">\r\n                    <h4>هیچ سفارشی وجود ندارد!</h4>\r\n                </div>\r\n                ";
        }
    }
}
almas_gold_customer_delivery_list();

?>