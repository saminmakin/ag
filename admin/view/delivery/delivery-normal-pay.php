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
if(isset($_POST["normal_pay_submit"]) && check_admin_referer("normal_pay_submit_nonce", "normal_pay_submit_nonce_field")) {
    $order_id = intval($_POST["order_id"]);
    $delivery_gold_weight = floatval($_POST["delivery_gold_weight_input"]);
    $live_gold_price = $core_data->gold_price ?? 0;
    global $wpdb;
    $table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
    $delivery_order = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_delivery . " WHERE delivery_id = %d", $order_id));
    $customer_user_id = $delivery_order->user_id;
    $order_initial_fee_price = $delivery_order->initial_final_price;
    $order_initial_price = $delivery_order->initial_price;
    $order_gold_type_fee = $delivery_order->gold_type_fee / 100;
    $order_initial_final_price = $order_initial_price + $order_initial_fee_price;
    $delivery_total_gold_price = abs($delivery_gold_weight * $live_gold_price);
    $delivery_gold_type_fee_amount = abs($delivery_total_gold_price * $order_gold_type_fee);
    $delivery_final_price = abs($delivery_total_gold_price + $delivery_gold_type_fee_amount - $order_initial_final_price);
    $price_pay_by_gold_weight = abs($delivery_final_price / $live_gold_price);
    $order_status = "delivered";
    $delivery_status = "finished";
    $delivery_gold_type = $delivery_order->gold_type;
    $description = isset($_POST["delivery_description"]) ? sanitize_text_field($_POST["delivery_description"]) : "";
    $update_delivery_data = $wpdb->update($table_name_delivery, ["delivery_gold_weight" => $delivery_gold_weight, "live_gold_price" => $live_gold_price, "delivery_gold_type_fee_amount" => $delivery_gold_type_fee_amount, "delivery_total_gold_price" => $delivery_total_gold_price, "delivery_final_price" => $delivery_final_price, "order_status" => $order_status, "delivery_status" => $delivery_status, "delivery_gold_type" => $delivery_gold_type, "description" => $description], ["delivery_id" => $order_id], ["%f", "%f", "%f", "%f", "%f", "%s", "%s", "%s", "%s"], ["%d"]);
    if(isset($_POST["transfer_to_customer_wallet"]) && $_POST["transfer_to_customer_wallet"] === "1" && $update_delivery_data !== false) {
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT wallet_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $customer_user_id));
        $customer_wallet_balance = $customer_data->wallet_balance;
        $new_wallet_balance = abs($customer_wallet_balance + $delivery_final_price);
        $update_wallet = $wpdb->update($wpdb->prefix . "almas_gold_customers", ["wallet_balance" => $new_wallet_balance], ["user_id" => $customer_user_id], ["%f"], ["%d"]);
        if($update_wallet !== false) {
            echo "\r\n                        <p style=\"color: green; font-weight: bold\">\r\n                            موفق! کیف پول کاربر افزوده شد\r\n                        </p>\r\n                    ";
        } else {
            echo "\r\n                        <p style=\"color: red; font-weight: bold\">\r\n                            خطا! به‌روزرسانی کیف پول کاربر ناموفق بود \r\n                        </p>\r\n                    ";
        }
    }
    if(isset($_POST["transfer_to_customer_safe"]) && $_POST["transfer_to_customer_safe"] === "1" && $update_delivery_data !== false) {
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT safe_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $customer_user_id));
        $customer_safe_balance = $customer_data->safe_balance;
        $new_safe_balance = abs($customer_safe_balance + $price_pay_by_gold_weight);
        $update_safe = $wpdb->update($wpdb->prefix . "almas_gold_customers", ["safe_balance" => $new_safe_balance], ["user_id" => $customer_user_id], ["%f"], ["%d"]);
        if($update_safe !== false) {
            echo "\r\n                        <p style=\"color: green; font-weight: bold\">\r\n                            موفق! گاوصندوق کاربر افزوده شد\r\n                        </p>\r\n                    ";
        } else {
            echo "\r\n                        <p style=\"color: red; font-weight: bold\">\r\n                            خطا! به‌روزرسانی گاوصندوق کاربر ناموفق بود \r\n                        </p>\r\n                    ";
        }
    }
    if($update_delivery_data !== false) {
    }
    $message = $update_delivery_data !== false ? "<div class=\"updated\"><p style=\"color: green; font-weight: bold\">وضعیت با موفقیت به تحویل شده تغییر کرد</p></div>" : "<div class=\"error\"><p style=\"color: red; font-weight: bold\">خطا! تغییر وضعیت تحویل ناموفق بود</p></div>";
    echo $message;
}

?>