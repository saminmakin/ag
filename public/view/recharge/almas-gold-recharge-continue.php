<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_recharge_continue_template")) {
    function almas_gold_recharge_continue_template()
    {
        $is_logged_in = false;
        if(isset($_COOKIE)) {
            foreach ($_COOKIE as $key => $value) {
                if(strpos($key, "wordpress_logged_in_") === 0) {
                    $is_logged_in = true;
                }
            }
        }
        if(!$is_logged_in) {
            echo "                    <script>\r\n                        window.location.href = '";
            echo wp_login_url();
            echo "';\r\n                    </script>\r\n                ";
            exit();
        }
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        global $table_name_recharge;
        $customer_data = $wpdb->get_row("SELECT wallet_balance, card_number FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
        $customer_wallet_balance = $customer_data->wallet_balance;
        $card_number = $customer_data->card_number;
        $core_data = $wpdb->get_row("SELECT recharge_fee FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
        $recharge_fee = $core_data->recharge_fee;
        $table_name_recharge = $wpdb->prefix . "almas_gold_recharge";
        $recharge_data = $wpdb->get_row("\r\n                SELECT \r\n                    unique_recharge_id, \r\n                    recharge_id, \r\n                    unique_customer_id, \r\n                    user_id, \r\n                    recharge_date, \r\n                    user_name, \r\n                    firstname, \r\n                    lastname, \r\n                    recharge_amount, \r\n                    description, \r\n                    qrcode_image_url\r\n                FROM " . $table_name_recharge . " \r\n                WHERE user_id = " . $user_id . " \r\n                ORDER BY id DESC \r\n                LIMIT 1\r\n            ");
        if($recharge_data) {
            extract((array) $recharge_data);
            if(isset($_POST["recharge_online_submit"]) && check_admin_referer("recharge_online_submit_nonce", "nonce_field_pay_online")) {
                $order_recharge_date = date(strtotime($recharge_date));
                $initial_recharge_amount = $recharge_amount - $recharge_fee;
                $remaining_wallet_balance = $customer_wallet_balance + $initial_recharge_amount;
                $final_recharge_amount = $recharge_amount - $recharge_fee;
                $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "null";
                $recharge_gateway_redirect_url = almas_gols_redirect_urls("recharge_gateway_redirect_url");
                $amount = $recharge_amount * 10;
                $callbackURL = "https://almas.gold/" . $recharge_gateway_redirect_url . "/?" . $unique_recharge_id . "&" . $recharge_id . "&" . $amount . "&" . $order_recharge_date;
                $merchantID = "4f51f281-3cf8-47d1-98fa-134a40861722";
                //$merchantID = "TEST";
                $cardNumber = $card_number;
                $data = ["merchantID" => $merchantID, "amount" => $amount, "callbackURL" => $callbackURL, "orderId" => $recharge_id, "cardNumber" => $cardNumber];
                $curl = curl_init();
                curl_setopt_array($curl, [CURLOPT_URL => "https://dargaah.com/payment", CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_HTTPHEADER => ["Content-Type: application/json"]]);
                $response = curl_exec($curl);
                if($response !== false) {
                    $decoded_response = json_decode($response, true);
                    if(isset($decoded_response["status"]) && $decoded_response["status"] == 200 && isset($decoded_response["authority"])) {
                        $transaction_status = "pending";
                        $transaction_id = "0";
                        $transaction_processed = "0";
                        $authority = $decoded_response["authority"];
                        $updated = $wpdb->update($table_name_recharge, ["recharge_amount" => $recharge_amount, "new_wallet_balance" => $remaining_wallet_balance, "recharge_fee" => $recharge_fee, "final_recharge_amount" => $final_recharge_amount, "initial_recharge_amount" => $initial_recharge_amount, "transaction_status" => $transaction_status, "transaction_id" => $transaction_id, "transaction_processed" => $transaction_processed, "authority" => $authority, "description" => $description], ["recharge_id" => $recharge_id]);
                        if($updated === false) {
                            echo "خطا در به‌روزرسانی اطلاعات تراکنش.";
                            echo "خطای به‌روزرسانی: " . $wpdb->last_error;
                        }
                        echo "<div id=\"payment-loading-overlay\"><div><div class=\"payment-loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-success\">";
                        echo esc_html__("در حال انتقال به درگاه پرداخت", "almas-gold");
                        echo "</h5><h6 style=\"color:#12373f\">";
                        echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
                        echo "</h6></div></div></div><script>setTimeout(function() {window.location.href = 'https://dargaah.com/ird/startpay/";
                        echo $decoded_response["authority"];
                        echo "';\r\n                                    }, 2000); \r\n                                </script>\r\n                            ";
                        exit();
                    }
                    echo "\r\n                                <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                    <i class=\"fas fa-exclamation-circle\"></i>\r\n                                    خطا در ایجاد ارتباط با درگاه پرداخت!\r\n                                </div>\r\n                            ";
                } else {
                    echo "\r\n                            <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                <i class=\"fas fa-exclamation-circle\"></i>\r\n                                خطا در برقراری ارتباط با درگاه پرداخت!\r\n                            </div>\r\n                        ";
                }
                curl_close($curl);
            }
            $initial_recharge_amount = $recharge_amount - $recharge_fee;
            $remaining_wallet_balance = $customer_wallet_balance + $initial_recharge_amount;
            $final_recharge_amount = $recharge_amount + $recharge_fee;
            require_once "views/almas-gold-recharge-continue-html.php";
            echo "                    <script>    \r\n                        \$(document).ready(function(){\r\n                            \$('#recharge_online_submit').click(function(){\r\n                                \$('.payment_overlay, .payment_popup').fadeIn(100);\r\n                            });\r\n\r\n                            \$('#close_popup').click(function(){\r\n                                \$('.payment_overlay, .payment_popup').fadeOut(100);\r\n                            });\r\n\r\n                            \$('#rules_agreement').change(function(){\r\n                                if(\$(this).is(':checked')){\r\n                                    \$('button[name=\"recharge_online_submit\"]').prop('disabled', false);\r\n                                } else {\r\n                                    \$('button[name=\"recharge_online_submit\"]').prop('disabled', true);\r\n                                }\r\n                            });\r\n                        });\r\n                    </script>\r\n                ";
        } else {
            echo "هیچ داده ای وجود ندارد.";
        }
    }
}
almas_gold_recharge_continue_template();

?>