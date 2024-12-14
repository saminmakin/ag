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
almas_gold_recharge_payment_process();
function almas_gold_recharge_process_post_data()
{
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $data = ["code" => isset($_POST["code"]) ? intval($_POST["code"]) : NULL, "message" => isset($_POST["message"]) ? htmlspecialchars($_POST["message"]) : NULL, "authority" => isset($_POST["authority"]) ? htmlspecialchars($_POST["authority"]) : NULL, "amount" => isset($_POST["amount"]) ? intval($_POST["amount"]) : NULL, "orderId" => isset($_POST["orderId"]) ? htmlspecialchars($_POST["orderId"]) : NULL];
        return $data;
    }
    wp_die("درخواست نامعتبر است.", "خطای سیستم", ["response" => 400]);
}
function almas_gold_recharge_send_post_data($data, $merchantID)
{
    $url = "https://dargaah.com/verification";
    $curl = curl_init();
    curl_setopt_array($curl, [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_HTTPHEADER => ["Content-Type: application/json"]]);
    $response = curl_exec($curl);
    if($response === false) {
        echo "خطا در ارتباط با سرور: " . curl_error($curl);
    } else {
        $response_data = json_decode($response, true);
        if(json_last_error() === JSON_ERROR_NONE && isset($response_data["message"]) && $response_data["code"] != 200) {
            echo translate_message($response_data["message"]);
        }
    }
    curl_close($curl);
    return $response;
}
function translate_message($message)
{
    $translated_message = isset($translations[$message]) ? $translations[$message] : $message;
    return "\r\n            <div class=\"alert\" style=\"padding: 13px 22px;margin: 10px 0;/* border: 1px solid #c55450; */color: #2c2929;background-color: #ffe3e3;border-radius: 5px;font-size: 15px;/* font-weight: 600; */text-align: center;width: fit-content;margin: 0 auto;\">\r\n                <i class=\"fa-solid fa-circle-exclamation\" style=\"margin-left: 5px;\"></i>\r\n                " . htmlspecialchars($translated_message) . "\r\n            </div>\r\n        ";
}
function almas_gold_recharge_payment_process()
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    global $wpdb;
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT wallet_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = %d", $user_id));
    $customer_wallet_balance = $customer_data->wallet_balance;
    $table_name_recharge = $wpdb->prefix . "almas_gold_recharge";
    $recharge_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_recharge . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    $new_wallet_balance = $recharge_data->new_wallet_balance;
    $initial_recharge_amount = $recharge_data->initial_recharge_amount;
    $authority = $recharge_data->authority;
    $recharge_id = $recharge_data->recharge_id;
    
    $transaction_status = $recharge_data->transaction_status;
    $transaction_processed = $recharge_data->transaction_processed;
    $merchantID = "4f51f281-3cf8-47d1-98fa-134a40861722";
    //$merchantID = "TEST";
    $post_data = almas_gold_recharge_process_post_data();
    
    
    if(is_array($post_data)) {
        $code = $post_data["code"];
        $message = $post_data["message"];
        $authority = $post_data["authority"];
        $amount = $post_data["amount"];
        $orderId = $post_data["orderId"];
    } else {
        echo $post_data;
    }
    

    if ($code === 100 && $authority === $recharge_data->authority && $orderId === $recharge_data->recharge_id) {
        $data = ["merchantID" => $merchantID, "amount" => $amount, "authority" => $authority, "orderId" => $orderId];
        $response = almas_gold_recharge_send_post_data($data, $merchantID);
        $responseData = json_decode($response, true);
        if ($responseData["status"] === 100) {
            $transaction_id = $responseData["refId"];
            $transaction_date = current_time("mysql");
            $transaction_status = "approved";
            $updated = $wpdb->update(
                $table_name_recharge, 
                [
                    "transaction_id" => $transaction_id,
                    "transaction_date" => $transaction_date,
                    "transaction_status" => $transaction_status,
                ], 
                ["recharge_id" => $recharge_id]
            );
    
            if ($updated !== false && $transaction_processed === "0" && $transaction_status === "approved") {
                $wallet_update = $wpdb->update(
                    $wpdb->prefix . "almas_gold_customers", 
                    ["wallet_balance" => $new_wallet_balance], 
                    ["user_id" => $user_id], 
                    ["%f"], 
                    ["%d"]
                );
    
                if ($responseData["status"] === 100 && $transaction_processed === "0") {
                    $updated_processed = $wpdb->update(
                        $table_name_recharge, 
                        ["transaction_processed" => 1], 
                        ["recharge_id" => $recharge_id]
                    );
    
                    if ($updated_processed !== false) {
                        echo "<div id=\"payment-loading-overlay\"><div class=\"payment-loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-success\">";
                        echo esc_html__("تراکنش با موفقیت انجام شد!", "almas-gold");
                        echo "</h5><h6 style=\"color:#12373f\">";
                        echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
                        echo "</h6></div></div></div>";
                    }
                }
    
                $table_name_recharge = $wpdb->prefix . "almas_gold_recharge";
                $recharge_data = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM " . $table_name_recharge . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", 
                    $user_id
                ));
                if ($recharge_data && $recharge_data->transaction_processed == 1) {
                    $recharge_bill_redirect_url = almas_gols_redirect_urls("recharge_bill_redirect_url");
                    echo "                            <script>\r\n                                setTimeout(function() {\r\n                                    ";
                    almas_gold_send_recharge_sms();
                    echo "                                }, 1500);\r\n                            </script>\r\n                                                    <script>\r\n                                setTimeout(function() {\r\n                                    window.location.href = '";
                    echo $recharge_bill_redirect_url;
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