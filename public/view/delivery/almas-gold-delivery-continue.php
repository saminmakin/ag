<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_delivery_bill_template")) {
    function almas_gold_delivery_bill_template()
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
        file_put_contents( WP_CONTENT_DIR . '/debug.log', '' );
        error_log('User ID: ' . $user_id);
        global $wpdb;
        global $table_name_delivery;
        $core_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
        $gold_price = $core_data->gold_price;
        $broken_gold_fee = $core_data->broken_gold_fee;
        $without_fee_gold_fee = $core_data->without_fee_gold_fee;
        $low_fee_gold_fee = $core_data->low_fee_gold_fee;
        $sequins_gold_fee = $core_data->sequins_gold_fee;
        $bullion_gold_fee = $core_data->bullion_gold_fee;
        $bullion_gold_limit = $core_data->bullion_gold_limit;
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $customer_data = $wpdb->get_row("SELECT safe_balance, wallet_balance, card_number FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
        $customer_safe_balance = $customer_data->safe_balance;
        $customer_wallet_balance = $customer_data->wallet_balance;
        $card_number = $customer_data->card_number;
        $table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
        $delivery_data = $wpdb->get_row($wpdb->prepare("SELECT \r\n                unique_delivery_id, \r\n                delivery_id, \r\n                unique_customer_id, \r\n                user_id, \r\n                delivery_request_date, \r\n                user_name, \r\n                firstname, \r\n                lastname, \r\n                payment_status,\r\n                gold_weight,\r\n                    previous_wallet_balance,\r\n                description, \r\n                qrcode_image_url\r\n                FROM " . $table_name_delivery . " \r\n                WHERE user_id = %d \r\n                ORDER BY id DESC LIMIT 1", $user_id));
        if($delivery_data) {
            extract((array) $delivery_data);
            $unit_display = $gold_unit_to_bills;
            $gold_type = isset($_POST["gold_type"]) ? sanitize_text_field($_POST["gold_type"]) : "";
            $gold_type_fee = $broken_gold_fee;
            if($gold_type === "broken") {
                $gold_type_fee = $broken_gold_fee;
            } elseif($gold_type === "without_fee") {
                $gold_type_fee = $without_fee_gold_fee;
            } elseif($gold_type === "low_fee") {
                $gold_type_fee = $low_fee_gold_fee;
            } elseif($gold_type === "sequins") {
                $gold_type_fee = $sequins_gold_fee;
            } elseif($gold_type === "bullion") {
                $gold_type_fee = $bullion_gold_fee;
            }
            $initial_price = $gold_price * $gold_weight;
            $initial_final_price = $initial_price * $gold_type_fee / 100;
            $initial_final_price_formatted = number_format($initial_final_price, 0, ".", "");
            $remaining_safe_balance = $customer_safe_balance - $gold_weight;
            $remaining_wallet_balance = $customer_wallet_balance - $initial_final_price;
            $previous_wallet_balance = $delivery_data->previous_wallet_balance;
            $price_payed_online = $initial_final_price - $customer_wallet_balance;
            $price_payed_online_formatted = number_format($price_payed_online, 0, ".", "");
            if(isset($_POST["delivery_pay_all_online_submit"]) && check_admin_referer("delivery_pay_all_online_submit_nonce", "nonce_field_delivery_pay_all_online")) {
                $price_payed_by_wallet = 0;
                $delivery_gateway_redirect_url = almas_gols_redirect_urls("delivery_gateway_redirect_url");
                $amount = $initial_final_price_formatted * 10;
                $callbackURL = site_url("/" . $delivery_gateway_redirect_url . "/?" . $unique_delivery_id . "&" . $delivery_id  . "&" . $amount);
                $merchantID = "4f51f281-3cf8-47d1-98fa-134a40861722";
                $cardNumber = $card_number;
                $data = ["amount" => $amount, "callbackURL" => $callbackURL, "merchantID" => $merchantID, "orderId" => $delivery_id, "cardNumber" => $cardNumber];
                $curl = curl_init();
                curl_setopt_array($curl, [CURLOPT_URL => "https://dargaah.com/payment", CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_HTTPHEADER => ["Content-Type: application/json"]]);
                $response = curl_exec($curl);
                
                if($response !== false) {
                    $decoded_response = json_decode($response, true);
                    if(isset($decoded_response["status"]) && $decoded_response["status"] == 200 && isset($decoded_response["authority"])) {
                        $gold_type = isset($_POST["gold_type"]) ? sanitize_text_field($_POST["gold_type"]) : "broken";
                        if($gold_type === "broken") {
                            $gold_type_fee = $broken_gold_fee;
                        } elseif($gold_type === "without_fee") {
                            $gold_type_fee = $without_fee_gold_fee;
                        } elseif($gold_type === "low_fee") {
                            $gold_type_fee = $low_fee_gold_fee;
                        } elseif($gold_type === "sequins") {
                            $gold_type_fee = $sequins_gold_fee;
                        } elseif($gold_type === "bullion") {
                            $gold_type_fee = $bullion_gold_fee;
                        }
                        $price_payed_online = $initial_final_price;
                        $final_price = $initial_final_price;
                        $gold_type_net_profit = $gold_type_fee * $gold_weight / 100;
                        $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "0";
                        $transaction_status = "pending";
                        $transaction_id = 0;
                        $authority = $decoded_response["authority"];
                        $transaction_processed = 0;
                        $delivery_status = "pending";
                        $order_status = "waitforshop";
                        $updated = $wpdb->update($table_name_delivery, ["gold_price" => $gold_price, "initial_price" => $initial_price, "initial_final_price" => $initial_final_price, "final_price" => $final_price, "price_payed_online" => $price_payed_online, "price_payed_by_wallet" => $price_payed_by_wallet, "transaction_id" => $transaction_id, "transaction_processed" => $transaction_processed, "authority" => $authority, "delivery_status" => $delivery_status, "transaction_status" => $transaction_status, "order_status" => $order_status, "gold_type" => $gold_type, "gold_type_fee" => $gold_type_fee, "gold_type_net_profit" => $gold_type_net_profit, "description" => $description], ["delivery_id" => $delivery_id]);
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
                        echo "';}, 500); </script>";
                        exit();
                    }
                    echo "\r\n                                <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                    <i class=\"fas fa-exclamation-circle\"></i>\r\n                                    خطا در برقراری ارتباط با درگاه پرداخت!\r\n                                </div>\r\n                            ";
                } else {
                    echo "\r\n                            <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                <i class=\"fas fa-exclamation-circle\"></i>\r\n                                خطا در برقراری ارتباط با درگاه پرداخت!\r\n                            </div>\r\n                        ";
                }
                curl_close($curl);
            }
            if(isset($_POST["delivery_pay_remaining_online_submit"]) && check_admin_referer("delivery_pay_remaining_online_submit_nonce", "nonce_field_delivery_pay_remaining_online")) {
                $delivery_gateway_redirect_url = almas_gols_redirect_urls("delivery_gateway_redirect_url");
                $amount = $price_payed_online_formatted * 10;
                $callbackURL = site_url("/" . $delivery_gateway_redirect_url . "?" . $unique_delivery_id . "&" . $delivery_id  . "&" . $amount);
                $merchantID = "4f51f281-3cf8-47d1-98fa-134a40861722";
                $cardNumber = $card_number;
                $data = ["amount" => $amount, "callbackURL" => $callbackURL, "merchantID" => $merchantID, "orderId" => $delivery_id, "cardNumber" => $cardNumber];
                $curl = curl_init();
                curl_setopt_array($curl, [CURLOPT_URL => "https://dargaah.com/payment", CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_HTTPHEADER => ["Content-Type: application/json"]]);
                $response = curl_exec($curl);
                if($response !== false) {
                    $decoded_response = json_decode($response, true);
                    if(isset($decoded_response["status"]) && $decoded_response["status"] == 200 && isset($decoded_response["authority"])) {
                        $final_price = $initial_final_price;
                        $gold_type = isset($_POST["gold_type"]) ? sanitize_text_field($_POST["gold_type"]) : "";
                        if($gold_type === "broken") {
                            $gold_type_fee = $broken_gold_fee;
                        } elseif($gold_type === "without_fee") {
                            $gold_type_fee = $without_fee_gold_fee;
                        } elseif($gold_type === "low_fee") {
                            $gold_type_fee = $low_fee_gold_fee;
                        } elseif($gold_type === "sequins") {
                            $gold_type_fee = $sequins_gold_fee;
                        } elseif($gold_type === "bullion") {
                            $gold_type_fee = $bullion_gold_fee;
                        }
                        $price_payed_online = $initial_final_price - $customer_wallet_balance;
                        $final_price = $price_payed_online;
                        $gold_type_net_profit = $gold_type_fee * $gold_weight / 100;
                        $price_payed_by_wallet = $customer_wallet_balance;
                        $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "0";
                        $payment_status = "pending";
                        $transaction_status = "pending";
                        $delivery_status = "pending";
                        $order_status = "waitforshop";
                        $authority = $decoded_response["authority"];
                        $transaction_processed = 0;
                        $payed_online_id = 1;
                        $updated = $wpdb->update($table_name_delivery, ["gold_price" => $gold_price, "initial_price" => $initial_price, "initial_final_price" => $initial_final_price, "final_price" => $final_price, "price_payed_online" => $price_payed_online, "price_payed_by_wallet" => $price_payed_by_wallet, "transaction_processed" => $transaction_processed, "payed_online_id" => $payed_online_id, "authority" => $authority, "transaction_status" => $transaction_status, "order_status" => $order_status, "delivery_status" => $delivery_status, "payment_status" => $payment_status, "gold_type" => $gold_type, "gold_type_fee" => $gold_type_fee, "gold_type_net_profit" => $gold_type_net_profit, "description" => $description], ["delivery_id" => $delivery_id]);
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
                        echo "';}, 500); </script>";
                        exit();
                    }
                    echo "\r\n                                <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                    <i class=\"fas fa-exclamation-circle\"></i>\r\n                                    خطا در برقراری ارتباط با درگاه پرداخت!\r\n                                </div>\r\n                            ";
                } else {
                    echo "\r\n                            <div style=\"padding: 10px 20px 8px 20px;background-color: #f5e6a1;border-radius: 3px;font-weight: 600;font-size: 15px;margin: 25px 0;\">\r\n                                <i class=\"fas fa-exclamation-circle\"></i>\r\n                                خطا در برقراری ارتباط با درگاه پرداخت!\r\n                            </div>\r\n                        ";
                }
                curl_close($curl);
            }
            if(isset($_POST["delivery_pay_all_by_wallet_submit"]) && check_admin_referer("delivery_pay_all_by_wallet_submit_nonce", "nonce_field_delivery_pay_all_by_wallet")) {
                $final_price = $initial_final_price;
                $gold_type = isset($_POST["gold_type"]) ? sanitize_text_field($_POST["gold_type"]) : "";
                if($gold_type === "broken") {
                    $gold_type_fee = $broken_gold_fee;
                } elseif($gold_type === "without_fee") {
                    $gold_type_fee = $without_fee_gold_fee;
                } elseif($gold_type === "low_fee") {
                    $gold_type_fee = $low_fee_gold_fee;
                } elseif($gold_type === "sequins") {
                    $gold_type_fee = $sequins_gold_fee;
                } elseif($gold_type === "bullion") {
                    $gold_type_fee = $bullion_gold_fee;
                }
                $price_payed_by_wallet = $initial_final_price;
                $price_payed_online = 0;
                $gold_type_net_profit = $gold_type_fee * $gold_price;
                $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "0";
                $transaction_status = "pending";
                $transaction_processed = 0;
                $payment_status = "pending";
                $delivery_status = "pending";
                $order_status = "waitforshop";
                function generate_authority()
                {
                    return sprintf("%04x%04x-%04x-%04x-%04x-%04x%04x%04x", mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095) | 16384, mt_rand(0, 16383) | 32768, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                }
                $code = 200;
                $authority = generate_authority();
                $price = $initial_final_price_formatted;
                $orderId = absint($delivery_id);
                $new_wallet_balance = $previous_wallet_balance - $final_price;
                $updated = $wpdb->update($table_name_delivery, ["gold_price" => $gold_price, "new_wallet_balance" => $new_wallet_balance, "initial_price" => $initial_price, "initial_final_price" => $initial_final_price, "final_price" => $final_price, "price_payed_online" => $price_payed_online, "price_payed_by_wallet" => $price_payed_by_wallet, "gold_type" => $gold_type, "gold_type_fee" => $gold_type_fee, "gold_type_net_profit" => $gold_type_net_profit, "transaction_status" => $transaction_status, "transaction_processed" => $transaction_processed, "payment_status" => $payment_status, "delivery_status" => $delivery_status, "order_status" => $order_status, "authority" => $authority, "description" => $description], ["delivery_id" => $delivery_id]);
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
                $delivery_paywall_redirect_url = almas_gols_redirect_urls("delivery_paywall_redirect_url");
                $form_action = esc_url(site_url("/" . $delivery_paywall_redirect_url . "?" . $unique_delivery_id . "&" . $delivery_id . "&" . $delivery_request_date . "&" . $authority));
                $escaped_code = esc_attr($code);
                $escaped_authority = esc_attr($authority);
                $escaped_price = esc_attr($price);
                $escaped_orderId = esc_attr($orderId);
                echo "\r\n                        <form id='hidden_form' method='post' action='" . $form_action . "'>\r\n                            <input type='hidden' name='code' value='" . $escaped_code . "'>\r\n                            <input type='hidden' name='authority' value='" . $escaped_authority . "'>\r\n                            <input type='hidden' name='price' value='" . $escaped_price . "'>\r\n                            <input type='hidden' name='orderId' value='" . $escaped_orderId . "'>\r\n                        </form>\r\n                        <script type='text/javascript'>\r\n                            setTimeout(function() {\r\n                                document.getElementById('hidden_form').submit();\r\n                            }, 0);\r\n                        </script>\r\n                    ";
                exit();
            }
            require_once "views/almas-gold-delivery-continue-html.php";
            echo "                    <script>\r\n                        \$(document).ready(function(){\r\n                            \$('#delivery_activate_pay_with_wallet').change(function() {\r\n                                if (\$(this).is(':checked')) {\r\n                                    \$('#delivery_pay_by_wallet_box').slideDown(250);\r\n                                    \$('#delivery_pay_all_online_box').slideUp(250);\r\n\r\n                                } else {\r\n                                    \$('#delivery_pay_by_wallet_box').slideUp(250);\r\n                                    \$('#delivery_pay_all_online_box').slideDown(250);\r\n                                }\r\n                            });\r\n\r\n                            ///\r\n\r\n                            \$('#delivery_pay_remaining_online_submit').click(function(){\r\n                                \$('#pay_remaining_online_overlay, #pay_remaining_online_popup').fadeIn(100);\r\n                            });\r\n\r\n                            \$('#delivery_pay_all_by_wallet_submit').click(function(){\r\n                                \$('#pay_all_by_wallet_overlay, #pay_all_by_wallet_popup').fadeIn(100);\r\n\r\n                            });\r\n\r\n                            \$('#delivery_pay_all_online_submit').click(function(){\r\n                                \$('#online_payment_overlay, #online_payment_popup').fadeIn(100);\r\n\r\n                            });\r\n\r\n                            \$('#by_online_rules_agreement').change(function(){\r\n                                if(\$(this).is(':checked')){\r\n                                    \$('button[name=\"delivery_pay_all_online_submit\"]').prop('disabled', false);\r\n                                } else {\r\n                                    \$('button[name=\"delivery_pay_all_online_submit\"]').prop('disabled', true);\r\n                                }\r\n                            });\r\n\r\n                            \$('#by_wallet_rules_agreement').change(function(){\r\n                                if(\$(this).is(':checked')){\r\n                                    \$('button[name=\"delivery_pay_all_by_wallet_submit\"]').prop('disabled', false);\r\n                                } else {\r\n                                    \$('button[name=\"delivery_pay_all_by_wallet_submit\"]').prop('disabled', true);\r\n                                }\r\n                            });\r\n\r\n                            \$('#pay_remaining_online_rules_agreement').change(function(){\r\n                                if(\$(this).is(':checked')){\r\n                                    \$('button[name=\"delivery_pay_remaining_online_submit\"]').prop('disabled', false);\r\n                                } else {\r\n                                    \$('button[name=\"delivery_pay_remaining_online_submit\"]').prop('disabled', true);\r\n                                }\r\n                            });\r\n\r\n                            \$('#online_payment_close_popup, #pay_remaining_online_close_popup, #pay_all_by_wallet_close_popup, #all_wallet_payment_close_popup').click(function(){\r\n                                \$('#online_payment_overlay, #online_payment_popup').fadeOut(100);\r\n                                \$('#pay_remaining_online_overlay, #pay_remaining_online_popup').fadeOut(100);\r\n                                \$('#pay_all_by_wallet_overlay, #pay_all_by_wallet_popup').fadeOut(100);\r\n                            });\r\n\r\n                            ///\r\n                \r\n                            \$('#gold_type').change(function() {\r\n                                var goldType = \$(this).val();\r\n                                var goldTypeFee = parseFloat(\$(this).find(':selected').data('fee'));\r\n                                var goldPrice = ";
            echo $gold_price;
            echo ";\r\n                                var goldWeight = ";
            echo $gold_weight;
            echo ";\r\n                                var pricePayedOnline = ";
            echo $price_payed_online;
            echo ";\r\n                                var customerWalletBalance = ";
            echo $customer_wallet_balance;
            echo ";\r\n\r\n                                var initialPrice = goldPrice * goldWeight;\r\n                                var initialFinalPrice = initialPrice * (goldTypeFee / 100);\r\n                                var remainingWalletBalance = customerWalletBalance - initialFinalPrice;\r\n                                var pricePayedOnline = initialFinalPrice - customerWalletBalance;\r\n                                \r\n                                initialPrice = Math.floor(initialPrice);\r\n                                initialFinalPrice = Math.floor(initialFinalPrice);\r\n\r\n                                \$('#gold_type_fee').text(goldTypeFee.toFixed(0));\r\n                                \$('#initial_price').text(initialPrice.toLocaleString('en-US'));\r\n                                \$('#total_gold_type_fee').text(initialFinalPrice.toLocaleString('en-US'));\r\n                                \$('#initial_final_price_display_online').text(initialFinalPrice.toLocaleString('en-US'));\r\n                                \$('#price_payed_online_display').text(pricePayedOnline.toLocaleString('en-US'));\r\n\r\n                                if(customerWalletBalance <= initialFinalPrice){\r\n                                    \$('#remaining_wallet_balance').text(\"0\");\r\n                                    \$('#initial_final_price_by_wallet').text(customerWalletBalance.toLocaleString('en-US'));\r\n                                    \$('#initial_final_price_display_wallet').text(pricePayedOnline.toLocaleString('en-US'));\r\n                                    \$('#delivery_pay_all_by_wallet_submit').hide();\r\n                                    \$('#delivery_pay_remaining_online_submit').show();\r\n                                } else {\r\n                                    \$('#remaining_wallet_balance').text(remainingWalletBalance.toLocaleString('en-US'));\r\n                                    \$('#initial_final_price_by_wallet').text(initialFinalPrice.toLocaleString('en-US'));\r\n                                    \$('#initial_final_price_display_wallet').text(initialFinalPrice.toLocaleString('en-US'));\r\n                                    \$('#delivery_pay_all_by_wallet_submit').show();\r\n                                    \$('#delivery_pay_remaining_online_submit').hide();\r\n                                }\r\n                            });\r\n                        });\r\n                    </script>\r\n                ";
        } else {
            echo "هیچ داده ای وجود ندارد.";
        }
    }
}
almas_gold_delivery_bill_template();

?>