<?php

defined("ABSPATH") or exit();
almas_gold_sale_paywall();
function almas_gold_sale_process_post_data()
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
function almas_gold_sale_paywall()
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    global $wpdb;
    global $table_name_sale;
    $core_data = $wpdb->get_row("SELECT gold_unit_to_bills FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
    $gold_unit_to_bills = $core_data->gold_unit_to_bills;
    $unit_display = $gold_unit_to_bills;
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT safe_balance, wallet_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $user_id));
    $customer_safe_balance = $customer_data->safe_balance;
    $customer_wallet_balance = $customer_data->wallet_balance;
    $table_name_sale = $wpdb->prefix . "almas_gold_sale";
    $sale_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_sale . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    $new_safe_balance = $sale_data->new_safe_balance;
    $new_wallet_balance = $sale_data->new_wallet_balance;
    $transaction_status = $sale_data->transaction_status;
    $final_price = $sale_data->final_price;
    $gold_weight = $sale_data->gold_weight;
    $transaction_id = $sale_data->transaction_id;
    $transaction_processed = $sale_data->transaction_processed;
    $authority = $sale_data->authority;
    $sale_id = $sale_data->sale_id;
    $post_data = almas_gold_sale_process_post_data();
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
    if($code === "200" && $authority === $sale_data->authority && $orderId === $sale_data->sale_id && intval($price) === intval($sale_data->final_price)) {
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
            $payment_status = "pending";
            $existing_transaction = $wpdb->get_var($wpdb->prepare("SELECT transaction_status FROM " . $table_name_sale . " WHERE sale_id = %d", $sale_id));
            if($existing_transaction !== "approved") {
                $update_transaction = $wpdb->update($table_name_sale, ["transaction_id" => $transaction_id, "transaction_date" => $transaction_date, "transaction_status" => $transaction_status, "payment_status" => $payment_status], ["sale_id" => $sale_id]);
                if($update_transaction === false) {
                }
            }
            $updated_transaction = $wpdb->get_var($wpdb->prepare("SELECT transaction_status FROM " . $table_name_sale . " WHERE sale_id = %d", $sale_id));
            if($updated_transaction === "approved") {
                $customer_data = $wpdb->get_row($wpdb->prepare("SELECT wallet_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $user_id));
                $customer_wallet_balance = $customer_data->wallet_balance;
                $update_wallet = $wpdb->update($wpdb->prefix . "almas_gold_customers", ["wallet_balance" => $new_wallet_balance], ["user_id" => $user_id], ["%f"], ["%d"]);
                if($update_wallet === false) {
                }
            }
            $updated_transaction = $wpdb->get_var($wpdb->prepare("SELECT transaction_status FROM " . $table_name_sale . " WHERE sale_id = %d", $sale_id));
            if($updated_transaction === "approved") {
                $customer_data = $wpdb->get_row($wpdb->prepare("SELECT safe_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $user_id));
                $customer_safe_balance = $customer_data->safe_balance;
                $update_safe = $wpdb->update($wpdb->prefix . "almas_gold_customers", ["safe_balance" => $new_safe_balance], ["user_id" => $user_id], ["%f"], ["%d"]);
                if($update_wallet === false) {
                }
            }
            $updated_transaction = $wpdb->get_var($wpdb->prepare("SELECT transaction_status FROM " . $table_name_sale . " WHERE sale_id = %d", $sale_id));
            if($updated_transaction === "approved") {
                $update_order_status = $wpdb->update($table_name_sale, ["transaction_processed" => 1], ["sale_id" => $sale_id]);
                if($update_order_status !== false) {
                    echo "<div id=\"payment-loading-overlay\"><div><div class=\"loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-success\">";
                    echo esc_html__("تراکنش با موفقیت انجام شد!", "almas-gold");
                    echo "</h5><h6 style=\"color:#12373f\">";
                    echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
                    echo "</h6></div></div></div>";
                }
            }
            $updated_transaction_processed = $wpdb->get_var($wpdb->prepare("SELECT transaction_processed FROM " . $table_name_sale . " WHERE sale_id = %d", $sale_id));
            if($updated_transaction_processed === "1") {
                $sale_bill_redirect_url = almas_gols_redirect_urls("sale_bill_redirect_url");
                echo "                        <script>\r\n                            setTimeout(function() {\r\n                                ";
                almas_gold_send_sale_sms();
                echo "                            }, 1500);\r\n                        </script>\r\n                                            <script>\r\n                            setTimeout(function() {\r\n                                window.location.href = '";
                echo $sale_bill_redirect_url;
                echo "';\r\n                            }, 1500);\r\n                        </script>\r\n                    ";
            }
        }
    } else {
        echo "<div id=\"payment-loading-overlay\"><div><div class=\"payment-loading-spin\" style=\"color: #FBE7EB !important\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-failed\">تراکنش ناموفق بود.</h5><h6 style=\"color:#12373f\">";
        echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
        echo "</h6></div></div></div><script>setTimeout(function() {window.location.href = '";
        echo wp_login_url();
        echo "';}, 1500);</script>";
    }
}

?>