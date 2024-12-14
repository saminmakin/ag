<?php

defined("ABSPATH") or exit();
almas_gold_shop_paywall();
function almas_gold_shop_process_post_data()
{
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $code = isset($_POST["code"]) ? sanitize_text_field($_POST["code"]) : "";
        $authority = isset($_POST["authority"]) ? sanitize_text_field($_POST["authority"]) : "";
        $price = isset($_POST["price"]) ? sanitize_text_field($_POST["price"]) : "";
        $orderId = isset($_POST["orderId"]) ? sanitize_text_field($_POST["orderId"]) : "";
        return ["code" => $code, "authority" => $authority, "price" => $price, "orderId" => $orderId];
    }
    return "درخواست نامعتبر است.";
}
function almas_gold_shop_paywall()
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    global $wpdb;
    global $table_name_shop;
    $core_data = $wpdb->get_row("SELECT gold_unit_to_bills FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
    $gold_unit_to_bills = $core_data->gold_unit_to_bills;
    $unit_display = $gold_unit_to_bills;
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT safe_balance, wallet_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $user_id));
    $customer_safe_balance = $customer_data->safe_balance;
    $customer_wallet_balance = $customer_data->wallet_balance;
    $table_name_shop = $wpdb->prefix . "almas_gold_shop";
    $shop_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_shop . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    $gold_weight = $shop_data->gold_weight;
    $new_safe_balance = $shop_data->new_safe_balance;
    $new_wallet_balance = $shop_data->new_wallet_balance;
    $transaction_status = $shop_data->transaction_status;
    $transaction_processed = $shop_data->transaction_processed;
    $authority = $shop_data->authority;
    $shop_id = $shop_data->shop_id;
    $price_payed_by_wallet = $shop_data->price_payed_by_wallet;
    $final_price = $shop_data->final_price;
    $initial_final_price = $shop_data->initial_final_price;
    $price_payed_by_wallet = $shop_data->price_payed_by_wallet;
    $price_payed_online = $shop_data->price_payed_online;
    $post_data = almas_gold_shop_process_post_data();
    if(is_array($post_data)) {
        $code = $post_data["code"];
        $authority = $post_data["authority"];
        $price = $post_data["price"];
        $orderId = $post_data["orderId"];
        if($code === 200) {
            $responseData["status"] === 100;
        }
    } else {
        echo $post_data;
    }
    if($code === "200" && $authority === $shop_data->authority && $orderId === $shop_data->shop_id && intval($price) === intval($shop_data->price_payed_by_wallet)) {
        $responseData["status"] = 100;
        if($responseData["status"]) {
            function generate_refId()
            {
                $prefix = "690";
                $characters = "0123456789";
                $charactersArray = str_split($characters);
                shuffle($charactersArray);
                $random_length = 10 - strlen($prefix);
                $random_string = "";
                while (strlen($random_string) < $random_length) {
                    foreach ($charactersArray as $char) {
                        if(strlen($random_string) < $random_length) {
                            $random_string .= $char;
                        } else {
                            shuffle($charactersArray);
                        }
                    }
                }
                return $prefix . substr($random_string, 0, $random_length);
            }
            $refId = generate_refId();
            $transaction_id = $refId;
            $transaction_date = current_time("mysql");
            $transaction_status = "approved";
            $existing_transaction = $wpdb->get_var($wpdb->prepare("SELECT transaction_status FROM " . $table_name_shop . " WHERE shop_id = %d", $shop_id));
            if($existing_transaction !== "approved") {
                $update_transaction = $wpdb->update($table_name_shop, ["transaction_id" => $transaction_id, "transaction_date" => $transaction_date, "transaction_status" => $transaction_status], ["shop_id" => $shop_id]);
                if($update_transaction === false) {
                }
            }
            if($transaction_processed === "0" && $transaction_status === "approved") {
                if($responseData["status"] === 100 && $transaction_processed === "0") {
                    $existing_processed = $wpdb->get_var($wpdb->prepare("SELECT transaction_processed FROM " . $table_name_shop . " WHERE shop_id = %d", $shop_id));
                    if($existing_processed !== "1") {
                        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT wallet_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $user_id));
                        $customer_wallet_balance = $customer_data->wallet_balance;
                        $update_wallet = $wpdb->update($wpdb->prefix . "almas_gold_customers", ["wallet_balance" => $new_wallet_balance], ["user_id" => $user_id], ["%f"], ["%d"]);
                        if($update_wallet === false) {
                        }
                    }
                }
                if($responseData["status"] === 100 && $transaction_processed === "0") {
                    $existing_processed = $wpdb->get_var($wpdb->prepare("SELECT transaction_processed FROM " . $table_name_shop . " WHERE shop_id = %d", $shop_id));
                    if($existing_processed !== "1") {
                        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT safe_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $user_id));
                        $customer_safe_balance = $customer_data->safe_balance;
                        $update_safe = $wpdb->update($wpdb->prefix . "almas_gold_customers", ["safe_balance" => $new_safe_balance], ["user_id" => $user_id], ["%f"], ["%d"]);
                        if($update_safe === false) {
                        }
                    }
                }
                if($responseData["status"] === 100 && $transaction_processed === "0") {
                    $updated = $wpdb->update($table_name_shop, ["transaction_processed" => 1], ["shop_id" => $shop_id]);
                    if($updated !== false) {
                        echo "<div id=\"payment-loading-overlay\"><div><div class=\"loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-success\">";
                        echo esc_html__("تراکنش با موفقیت انجام شد!", "almas-gold");
                        echo "</h5><h6 style=\"color:#12373f\">";
                        echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
                        echo "</h6></div></div></div>";
                    }
                }
                $table_name_shop = $wpdb->prefix . "almas_gold_shop";
                $shop_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_shop . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
                $transaction_processed = $shop_data->transaction_processed;
                if($shop_data->transaction_processed == 1) {
                    $shop_bill_redirect_url = almas_gols_redirect_urls("shop_bill_redirect_url");
                    echo "                            <script>\r\n                                setTimeout(function() {\r\n                                    ";
                    almas_gold_send_shop_sms();
                    echo "                                }, 1500);\r\n                            </script>\r\n                                                    <script>\r\n                                setTimeout(function() {\r\n                                    window.location.href = '";
                    echo $shop_bill_redirect_url;
                    echo "';\r\n                                }, 1500);\r\n                            </script>\r\n                        ";
                }
            }
        }
    } else {
        echo "<div id=\"payment-loading-overlay\"><div><div class=\"payment-loading-spin\" style=\"color: #FBE7EB !important\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-failed\">پرداخت ناموفق بود.</h5><h6 style=\"color:#12373f\">";
        echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
        echo "</h6></div></div></div><script>setTimeout(function() {window.location.href = '";
        echo wp_login_url();
        echo "';}, 1500);</script>";
    }
}

?>