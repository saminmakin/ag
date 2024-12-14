<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_shop_continue_template")) {
    function almas_gold_shop_continue_template()
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
        global $table_name_shop;
        $core_data = $wpdb->get_row("SELECT gold_price, shop_tax, gold_unit_to_bills FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
        $gold_price = $core_data->gold_price;
        $shop_tax = $core_data->shop_tax;
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $customer_data = $wpdb->get_row("SELECT safe_balance, wallet_balance, card_number FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
        $customer_safe_balance = $customer_data->safe_balance;
        $customer_wallet_balance = $customer_data->wallet_balance;
        $card_number = $customer_data->card_number;
        $table_name_shop = $wpdb->prefix . "almas_gold_shop";
        $shop_data = $wpdb->get_row("SELECT \r\n                    unique_shop_id, \r\n                    shop_id, \r\n                    unique_customer_id, \r\n                    user_id, \r\n                    shop_date, \r\n                    user_name, \r\n                    firstname, \r\n                    initial_price,\r\n                    lastname, \r\n                    gold_weight, \r\n                    previous_wallet_balance, \r\n                    description, \r\n                    qrcode_image_url\r\n                FROM " . $table_name_shop . " \r\n                WHERE user_id = " . $user_id . " \r\n                ORDER BY id DESC \r\n                LIMIT 1\r\n            ");
        if($shop_data) {
            extract((array) $shop_data);
            
            $unit_display = $gold_unit_to_bills;
            $initial_price = $gold_price * $gold_weight;
            $total_shop_tax = $initial_price * $shop_tax / 100;
            $initial_final_price = $initial_price + $total_shop_tax;
            $initial_final_price_formatted = number_format($initial_final_price, 0, ".", "");
            $price_payed_online = abs($initial_final_price - $customer_wallet_balance);
            $price_payed_online_formatted = number_format($price_payed_online, 0, ".", "");
            $remaining_wallet_balance = $customer_wallet_balance - $initial_final_price;
            $previous_wallet_balance = $shop_data->previous_wallet_balance;
            if(isset($_POST["pay_all_price_online_submit"]) && check_admin_referer("pay_all_price_online_submit_nonce", "nonce_field_pay_online")) {
                $shop_gateway_redirect_url = almas_gols_redirect_urls("shop_gateway_redirect_url");
                $amount = $initial_final_price_formatted * 10;
                $callbackURL = site_url( $shop_gateway_redirect_url . "/?" . $unique_shop_id . "&" . $shop_id  . "&" . $amount);
                $merchantID = "4f51f281-3cf8-47d1-98fa-134a40861722";
                $cardNumber = $card_number;
                $data = ["amount" => $amount, "callbackURL" => $callbackURL, "merchantID" => $merchantID, "orderId" => $shop_id, "cardNumber" => $cardNumber];
                $curl = curl_init();
                curl_setopt_array($curl, [CURLOPT_URL => "https://dargaah.com/payment", CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_HTTPHEADER => ["Content-Type: application/json"]]);
                $response = curl_exec($curl);
                if($response !== false) {
                    $decoded_response = json_decode($response, true);
                    
                    if(isset($decoded_response["status"]) && $decoded_response["status"] == 200 && isset($decoded_response["authority"])) {
                        $transaction_processed = "0";
                        $authority = $decoded_response["authority"];
                        $final_price = $initial_final_price;
                        $price_payed_online = $final_price;
                        $price_payed_by_wallet = 0;
                        $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "0";
                        $transaction_status = "pending";
                        $payed_online_id = 0;
                        $updated = $wpdb->update($table_name_shop, ["gold_price" => $gold_price, "total_shop_tax" => $total_shop_tax, "initial_price" => $initial_price, "initial_final_price" => $initial_final_price, "final_price" => $final_price, "price_payed_online" => $price_payed_online, "price_payed_by_wallet" => $price_payed_by_wallet, "payed_online_id" => $payed_online_id, "transaction_processed" => $transaction_processed, "authority" => $authority, "transaction_status" => $transaction_status, "description" => $description], ["shop_id" => $shop_id]);
                        if($updated === false) {
                            echo "خطا در به‌روزرسانی اطلاعات پرداخت.";
                            echo "خطای به‌روزرسانی: " . $wpdb->last_error;
                        }
                        echo "<div id=\"payment-loading-overlay\"><div><div class=\"payment-loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-success\">";
                        echo esc_html__("در حال انتقال به درگاه پرداخت", "almas-gold");
                        echo "</h5><h6 style=\"color:#12373f\">";
                        echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
                        echo "</h6></div></div></div><script>setTimeout(function() {window.location.href = 'https://dargaah.com/ird/startpay/";
                        echo $decoded_response["authority"];
                        echo "';}, 2000); </script>";
                        exit();
                    }
                    echo "\r\n                                <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                    <i class=\"fas fa-exclamation-circle\"></i>\r\n                                    خطا در برقراری ارتباط با درگاه پرداخت!\r\n                                </div>\r\n                            ";
                } else {
                    echo "\r\n                            <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                <i class=\"fas fa-exclamation-circle\"></i>\r\n                                خطا در برقراری ارتباط با درگاه پرداخت!\r\n                            </div>\r\n                        ";
                }
                curl_close($curl);
            }
            if(isset($_POST["pay_remaining_price_online_submit"]) && check_admin_referer("pay_remaining_price_online_submit_nonce", "nonce_field_wallet_hi")) {
                $shop_gateway_redirect_url = almas_gols_redirect_urls("shop_gateway_redirect_url");
                $amount = $price_payed_online_formatted * 10;
                $callbackURL = site_url("https://almas.gold/" . $shop_gateway_redirect_url . "?" . $unique_shop_id . "&" . $shop_id  . "&" . $amount);
                $merchantID = "4f51f281-3cf8-47d1-98fa-134a40861722";
                $cardNumber = $card_number;
                $data = ["amount" => $amount, "callbackURL" => $callbackURL, "merchantID" => $merchantID, "orderId" => $shop_id, "cardNumber" => $cardNumber];
                $curl = curl_init();
                curl_setopt_array($curl, [CURLOPT_URL => "https://dargaah.com/payment", CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_HTTPHEADER => ["Content-Type: application/json"]]);
                $response = curl_exec($curl);
                if($response !== false) {
                    $decoded_response = json_decode($response, true);
                    if(isset($decoded_response["status"]) && $decoded_response["status"] == 200 && isset($decoded_response["authority"])) {
                        $authority = $decoded_response["authority"];
                        $price_payed_online = $initial_final_price - $customer_wallet_balance;
                        $price_payed_by_wallet = $customer_wallet_balance;
                        $final_price = $price_payed_online;
                        $transaction_status = "pending";
                        $transaction_processed = "0";
                        $payed_online_id = 1;
                        $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "0";
                        $new_wallet_balance = 0;
                        $updated = $wpdb->update($table_name_shop, ["gold_price" => $gold_price, "new_wallet_balance" => $new_wallet_balance, "total_shop_tax" => $total_shop_tax, "initial_price" => $initial_price, "initial_final_price" => $initial_final_price, "final_price" => $final_price, "price_payed_online" => $price_payed_online, "price_payed_by_wallet" => $price_payed_by_wallet, "transaction_processed" => $transaction_processed, "payed_online_id" => $payed_online_id, "authority" => $authority, "transaction_status" => $transaction_status, "description" => $description], ["shop_id" => $shop_id]);
                        if($updated === false) {
                            echo "خطا در به‌روزرسانی اطلاعات پرداخت.";
                            echo "خطای به‌روزرسانی: " . $wpdb->last_error;
                        }
                        echo "<div id=\"payment-loading-overlay\"><div><div class=\"payment-loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-success\">";
                        echo esc_html__("در حال انتقال به درگاه پرداخت", "almas-gold");
                        echo "</h5><h6 style=\"color:#12373f\">";
                        echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
                        echo "</h6></div></div></div><script>setTimeout(function() {window.location.href = 'https://dargaah.com/ird/startpay/";
                        echo $decoded_response["authority"];
                        echo "';}, 2000); </script>";
                        exit();
                    }
                    echo "\r\n                                <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                    <i class=\"fas fa-exclamation-circle\"></i>\r\n                                    خطا در برقراری ارتباط با درگاه پرداخت!\r\n                                </div>\r\n                            ";
                } else {
                    echo "\r\n                            <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                <i class=\"fas fa-exclamation-circle\"></i>\r\n                                خطا در برقراری ارتباط با درگاه پرداخت!\r\n                            </div>\r\n                        ";
                }
                curl_close($curl);
            }
            if(isset($_POST["pay_all_price_from_wallet_submit"]) && check_admin_referer("pay_all_price_from_wallet_submit_nonce", "nonce_field_pay_from_wallet")) {
                $final_price = $initial_final_price;
                $price_payed_online = 0;
                $price_payed_by_wallet = $final_price;
                $price_payed_by_wallet_formatted = number_format($price_payed_by_wallet, 0, ".", "");
                $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "0";
                function generate_authority()
                {
                    return sprintf("%04x%04x-%04x-%04x-%04x-%04x%04x%04x", mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095) | 16384, mt_rand(0, 16383) | 32768, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                }
                $code = 200;
                $authority = generate_authority();
                $price = $price_payed_by_wallet_formatted;
                $orderId = absint($shop_id);
                $payed_online_id = 0;
                $new_wallet_balance = $previous_wallet_balance - $final_price;
                $updated = $wpdb->update($table_name_shop, ["gold_price" => $gold_price, "new_wallet_balance" => $new_wallet_balance, "total_shop_tax" => $total_shop_tax, "initial_price" => $initial_price, "initial_final_price" => $initial_final_price, "final_price" => $final_price, "price_payed_online" => $price_payed_online, "price_payed_by_wallet" => $price_payed_by_wallet, "payed_online_id" => $payed_online_id, "authority" => $authority, "description" => $description], ["shop_id" => $orderId]);
                if($updated !== false) {
                    echo "<div id=\"payment-loading-overlay\"><div><div class=\"payment-loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-success\">";
                    echo esc_html__("در حال پردازش درخواست", "almas-gold");
                    echo "</h5><h6 style=\"color:#12373f\">";
                    echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
                    echo "</h6></div></div></div>";
                } else {
                    echo "خطا در به‌روزرسانی اطلاعات پرداخت.";
                    echo "خطای به‌روزرسانی: " . esc_html($wpdb->last_error);
                }
                $shop_paywall_redirect_url = almas_gols_redirect_urls("shop_paywall_redirect_url");
                $form_action = esc_url(site_url("/" . $shop_paywall_redirect_url . "?" . $unique_shop_id . "&" . $shop_id . "&" . $shop_date . "&" . $authority));
                $escaped_code = esc_attr($code);
                $escaped_authority = esc_attr($authority);
                $escaped_price = esc_attr($price);
                $escaped_orderId = esc_attr($orderId);
                echo "\r\n                        <form id='hidden_form' method='post' action='" . $form_action . "'>\r\n                            <input type='hidden' name='code' value='" . $escaped_code . "'>\r\n                            <input type='hidden' name='authority' value='" . $escaped_authority . "'>\r\n                            <input type='hidden' name='price' value='" . $escaped_price . "'>\r\n                            <input type='hidden' name='orderId' value='" . $escaped_orderId . "'>\r\n                        </form>\r\n                        <script type='text/javascript'>\r\n                            setTimeout(function() {\r\n                                document.getElementById('hidden_form').submit();\r\n                            }, 1500);\r\n                        </script>\r\n                    ";
                exit();
            }
            require_once "views/almas-gold-shop-continue-html.php";
            echo "                    <script>\r\n                        \$(document).ready(function() {\r\n                            \$('#activate_pay_some_with_wallet').change(function() {\r\n                                if (\$(this).is(':checked')) {\r\n                                    \$('#pay_remaining_price_online_box').slideDown(250);\r\n                                    \$('#pay_all_final_price_online_box').slideUp(250);\r\n                                } else {\r\n                                    \$('#pay_remaining_price_online_box').slideUp(250);\r\n                                    \$('#pay_all_final_price_online_box').slideDown(250);\r\n                                }\r\n                            });\r\n                        });\r\n\r\n                        \$('#pay_all_price_from_wallet_submit_x').click(function(){\r\n                            \$('#online_payment_overlay').fadeIn(100);\r\n                        });\r\n\r\n                        \$('#pay_all_price_online_submit').click(function(){\r\n                            \$('#online_payment_overlay, #online_payment_popup').fadeIn(100);\r\n                        });\r\n\r\n                        \$('#online_payment_close_popup').click(function(){\r\n                            \$('#online_payment_overlay, #online_payment_popup').fadeOut(100);\r\n                        });\r\n\r\n                        \$('#by_online_rules_agreement').change(function(){\r\n                            if(\$(this).is(':checked')){\r\n                                \$('button[name=\"pay_all_price_online_submit\"]').prop('disabled', false);\r\n                            } else {\r\n                                \$('button[name=\"pay_all_price_online_submit\"]').prop('disabled', true);\r\n                            }\r\n                        });\r\n\r\n                        \$('#pay_all_price_from_wallet_submit').click(function(){\r\n                            \$('#all_wallet_payment_overlay, #all_wallet_payment_popup').fadeIn(100);\r\n                        });\r\n                        \$('#all_wallet_payment_close_popup').click(function(){\r\n                            \$('#all_wallet_payment_overlay, #all_wallet_payment_popup').fadeOut(100);\r\n                        });\r\n                        \$('#by_wallet_rules_agreement').change(function(){\r\n                            if(\$(this).is(':checked')){\r\n                                \$('button[name=\"pay_all_price_from_wallet_submit\"]').prop('disabled', false);\r\n                            } else {\r\n                                \$('button[name=\"pay_all_price_from_wallet_submit\"]').prop('disabled', true);\r\n                            }\r\n                        });\r\n\r\n                        \$(document).ready(function(){\r\n                            \$('#pay_remaining_price_online_submit').click(function(){\r\n                                \$('#pay_remaining_online_payment_overlay, #pay_remaining_online_payment_popup').fadeIn(100);\r\n                            });\r\n                            \$('#pay_remaining_online_payment_close_popup').click(function(){\r\n                                \$('#pay_remaining_online_payment_overlay, #pay_remaining_online_payment_popup').fadeOut(100);\r\n                            });\r\n                            \$('#pay_remaining_online_rules_agreement').change(function(){\r\n                                if(\$(this).is(':checked')){\r\n                                    \$('button[name=\"pay_remaining_price_online_submit\"]').prop('disabled', false);\r\n                                } else {\r\n                                    \$('button[name=\"pay_remaining_price_online_submit\"]').prop('disabled', true);\r\n                                }\r\n                            });\r\n                        });\r\n                    </script>\r\n                ";
        } else {
            echo "هیچ داده ای وجود ندارد.";
        }
    }
}
almas_gold_shop_continue_template();

?>