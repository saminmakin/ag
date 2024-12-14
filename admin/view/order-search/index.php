<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ Offline decoder for php versions: 40/74
 * @ Decoder version: 1.1.1
 * @ Release: 29/08/2024
 */

// Decoded file for php version 74.
defined("ABSPATH") or exit();
if(!current_user_can("manage_options")) {
    return NULL;
}
if(!function_exists("almas_gold_order_details_page")) {
    function almas_gold_order_details_page()
    {
        require_once "view/search-form-html.php";
        if(isset($_POST["search_order_id"])) {
            $order_id = intval($_POST["search_order_id"]);
            global $wpdb;
            $core_data = $wpdb->get_row("SELECT * \r\n                    FROM " . $wpdb->prefix . "almas_gold_core \r\n                    ORDER BY id DESC \r\n                    LIMIT 1\r\n                ");
            $gold_price = $core_data->gold_price;
            $gold_unit_to_bills = $core_data->gold_unit_to_bills;
            $unit_display = $gold_unit_to_bills;
            $user_id = get_current_user_id();
            $table_name_customers = $wpdb->prefix . "almas_gold_customers";
            $customer_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_customers . " WHERE user_id = %d", $user_id));
            $email = $customer_data->email;
            $mobile = $customer_data->mobile;
            $shop_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "almas_gold_shop WHERE shop_id = %d", $order_id));
            if($shop_data) {
                require_once "view/shop-search-html.php";
            }
            $sale_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "almas_gold_sale WHERE sale_id = %d", $order_id));
            if($sale_data) {
                require_once "view/sale-search-html.php";
            }
            $delivery_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "almas_gold_delivery WHERE delivery_id = %d", $order_id));
            if($delivery_data) {
                require_once "view/delivery-search-html.php";
            }
            $deposit_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "almas_gold_deposit WHERE deposit_id = %d", $order_id));
            if($deposit_data) {
                require_once "view/deposit-search-html.php";
            }
            $recharge_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "almas_gold_recharge WHERE recharge_id = %d", $order_id));
            if($recharge_data) {
                require_once "view/recharge-search-html.php";
            }
        }
    }
}
almas_gold_order_details_page();

?>