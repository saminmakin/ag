<?php

defined("ABSPATH") or exit();
$is_logged_in = false;
if(isset($_COOKIE)) {
    foreach ($_COOKIE as $key => $value) {
        if(strpos($key, "wordpress_logged_in_") === 0) {
            $is_logged_in = true;
        }
    }
}
if(!function_exists("almas_gold_delivery_billing_preview_template")) {
    function almas_gold_delivery_billing_preview_template()
    {
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        global $table_name_delivery;
        $customer_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
        $customer_wallet_balance = $customer_data->wallet_balance;
        $core_data = $wpdb->get_row("SELECT gold_unit_to_bills FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $unit_display = $gold_unit_to_bills;
        $table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
        $delivery_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_delivery . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
        if($delivery_data) {
            extract((array) $delivery_data);
            require_once "views/almas-gold-delivery-bill-html.php";
        } else {
            echo "هیچ داده ای وجود ندارد.                    <script>\r\n                        window.location.href = '";
            echo home_url();
            echo "';\r\n                    </script>\r\n                ";
        }
    }
}
almas_gold_delivery_billing_preview_template();

?>