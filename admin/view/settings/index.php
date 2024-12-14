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
if(!function_exists("almas_gold_save_core_settings_function")) {
    function almas_gold_save_core_settings_function()
    {
        require_once ABSPATH . "wp-admin/includes/upgrade.php";
        global $wpdb;
        $table_name_core = $wpdb->prefix . "almas_gold_core";
        $core_data = $wpdb->get_row("SELECT * FROM " . $table_name_core . " ORDER BY id DESC LIMIT 1");
        if(isset($_POST["almas_gold_save_core_settings"])) {
            $gold_price = isset($_POST["gold_price"]) ? sanitize_text_field($_POST["gold_price"]) : "";
            $gold_unit_to_customer = isset($_POST["gold_unit_to_customer"]) ? 1 : 0;
            $gold_unit_to_bills = isset($_POST["gold_unit_to_bills"]) ? 1 : 0;
            $lists_date_display = isset($_POST["lists_date_display"]) ? 1 : 0;
            $broken_gold_fee = isset($_POST["broken_gold_fee"]) ? sanitize_text_field($_POST["broken_gold_fee"]) : "";
            $without_fee_gold_fee = isset($_POST["without_fee_gold_fee"]) ? sanitize_text_field($_POST["without_fee_gold_fee"]) : "";
            $low_fee_gold_fee = isset($_POST["low_fee_gold_fee"]) ? sanitize_text_field($_POST["low_fee_gold_fee"]) : "";
            $sequins_gold_fee = isset($_POST["sequins_gold_fee"]) ? sanitize_text_field($_POST["sequins_gold_fee"]) : "";
            $bullion_gold_fee = isset($_POST["bullion_gold_fee"]) ? sanitize_text_field($_POST["bullion_gold_fee"]) : "";
            $bullion_gold_limit = isset($_POST["bullion_gold_limit"]) ? sanitize_text_field($_POST["bullion_gold_limit"]) : "";
            $shop_lowest_limit = isset($_POST["shop_lowest_limit"]) ? sanitize_text_field($_POST["shop_lowest_limit"]) : "";
            $shop_highest_price_limit = isset($_POST["shop_highest_price_limit"]) ? sanitize_text_field($_POST["shop_highest_price_limit"]) : "";
            $shop_tax = isset($_POST["shop_tax"]) ? sanitize_text_field($_POST["shop_tax"]) : "";
            $shop_highest_limit = ($shop_highest_price_limit / ($gold_price * (1 + $shop_tax/100))) - 0.01;
            $sale_lowest_limit = isset($_POST["sale_lowest_limit"]) ? sanitize_text_field($_POST["sale_lowest_limit"]) : "";
            $sale_highest_limit = isset($_POST["sale_highest_limit"]) ? sanitize_text_field($_POST["sale_highest_limit"]) : "";
            $sale_tax = isset($_POST["sale_tax"]) ? sanitize_text_field($_POST["sale_tax"]) : "";
            $deposit_fee = isset($_POST["deposit_fee"]) ? sanitize_text_field($_POST["deposit_fee"]) : "";
            $deposit_lowest_limit = isset($_POST["deposit_lowest_limit"]) ? sanitize_text_field($_POST["deposit_lowest_limit"]) : "";
            $deposit_highest_limit = isset($_POST["deposit_highest_limit"]) ? sanitize_text_field($_POST["deposit_highest_limit"]) : "";
            $delivery_lowest_limit = isset($_POST["delivery_lowest_limit"]) ? sanitize_text_field($_POST["delivery_lowest_limit"]) : "";
            $delivery_highest_limit = isset($_POST["delivery_highest_limit"]) ? sanitize_text_field($_POST["delivery_highest_limit"]) : "";
            $recharge_lowest_limit = isset($_POST["recharge_lowest_limit"]) ? sanitize_text_field($_POST["recharge_lowest_limit"]) : "";
            $recharge_highest_limit = isset($_POST["recharge_highest_limit"]) ? sanitize_text_field($_POST["recharge_highest_limit"]) : "";
            $recharge_fee = isset($_POST["recharge_fee"]) ? sanitize_text_field($_POST["recharge_fee"]) : "";
            $admin_phone = isset($_POST["admin_phone"]) ? sanitize_text_field($_POST["admin_phone"]) : "";
            $wpdb->update($table_name_core, ["gold_price" => $gold_price, "gold_unit_to_customer" => $gold_unit_to_customer, "gold_unit_to_bills" => $gold_unit_to_bills, "lists_date_display" => $lists_date_display, "broken_gold_fee" => $broken_gold_fee, "without_fee_gold_fee" => $without_fee_gold_fee, "low_fee_gold_fee" => $low_fee_gold_fee, "sequins_gold_fee" => $sequins_gold_fee, "bullion_gold_fee" => $bullion_gold_fee, "bullion_gold_limit" => $bullion_gold_limit, "shop_lowest_limit" => $shop_lowest_limit, "shop_highest_limit" => $shop_highest_limit, "shop_tax" => $shop_tax, "sale_lowest_limit" => $sale_lowest_limit, "sale_highest_limit" => $sale_highest_limit, "sale_tax" => $sale_tax, "deposit_fee" => $deposit_fee, "deposit_lowest_limit" => $deposit_lowest_limit, "deposit_highest_limit" => $deposit_highest_limit, "delivery_lowest_limit" => $delivery_lowest_limit, "delivery_highest_limit" => $delivery_highest_limit, "recharge_lowest_limit" => $recharge_lowest_limit, "recharge_highest_limit" => $recharge_highest_limit, "recharge_fee" => $recharge_fee, "admin_phone" => $admin_phone], ["id" => 1], ["%f", "%d", "%d", "%d", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f", "%f"], ["%d"]);
            echo "\r\n                    <script>\r\n                        jQuery(document).ready(function(\$) {\r\n                            var originalText = \$(\".save_button\").text();\r\n                            \$(\".save_button\").text(\"ذخیره شد!\");\r\n                            setTimeout(function() {\r\n                                \$(\".save_button\").text(originalText);\r\n                            }, 1500);\r\n                        });\r\n                    </script>\r\n                ";
        }
        require_once "view/index-html.php";
    }
}
almas_gold_save_core_settings_function();

?>