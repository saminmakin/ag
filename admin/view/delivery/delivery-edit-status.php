<?php

defined("ABSPATH") or exit();
if(!current_user_can("manage_options")) {
    return NULL;
}
require_once ABSPATH . "wp-admin/includes/upgrade.php";
almas_gold_admin_edit_delivery_status();
function almas_gold_admin_edit_delivery_status()
{
    $user_id = get_current_user_id();
    global $wpdb;
    $core_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
    $gold_price = $core_data->gold_price;
    $broken_gold_fee = $core_data->broken_gold_fee;
    $without_fee_gold_fee = $core_data->without_fee_gold_fee;
    $low_fee_gold_fee = $core_data->low_fee_gold_fee;
    $sequins_gold_fee = $core_data->sequins_gold_fee;
    $bullion_gold_fee = $core_data->bullion_gold_fee;
    $bullion_gold_limit = $core_data->bullion_gold_limit;
    $gold_unit_to_bills = $core_data->gold_unit_to_bills;
    $unit_display = $gold_unit_to_bills;
    $table_name_customers = $wpdb->prefix . "almas_gold_customers";
    /*$customer_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_customers . " WHERE user_id = %d", $user_id));
    $customer_wallet_balance = $customer_data->wallet_balance ?? 0;
    $customer_safe_balance = $customer_data->safe_balance ?? 0;*/
    $table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
    $delivery_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_delivery . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    require_once "delivery-normal-receive.php";
    require_once "delivery-normal-pay.php";
    require_once "delivery-cgt-receive.php";
    require_once "delivery-cgt-pay.php";
    require_once "view/delivery-edit-search-form-html.php";
    if(isset($_POST["order_id_search_input"])) {
        $order_id = intval($_POST["order_id_search_input"]);
        $delivery_data = $wpdb->get_row($wpdb->prepare("SELECT * \r\n                FROM " . $wpdb->prefix . "almas_gold_delivery \r\n                WHERE delivery_id = %d", $order_id));
        $customer_user_id = $delivery_data->user_id;
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_customers . " WHERE user_id = %d", $customer_user_id));
    $customer_wallet_balance = $customer_data->wallet_balance ?? 0;
    $customer_safe_balance = $customer_data->safe_balance ?? 0;
        if($delivery_data) {
            require_once "view/delivery-edit-status-html.php";
        } else {
            echo "<div class=\"error\"><p>سفارش یافت نشد!</p></div>";
        }
    }
}

?>