<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_deposit_continue_template")) {
    function almas_gold_deposit_continue_template()
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
        global $table_name_deposit;
        $customer_data = $wpdb->get_row("SELECT wallet_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
        $customer_wallet_balance = $customer_data->wallet_balance;
        $core_data = $wpdb->get_row("SELECT deposit_fee FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
        $deposit_fee = $core_data->deposit_fee;
        $table_name_deposit = $wpdb->prefix . "almas_gold_deposit";
        $deposit_data = $wpdb->get_row("SELECT \r\n                    unique_deposit_id, \r\n                    deposit_id, \r\n                    unique_customer_id, \r\n                    user_id, \r\n                    deposit_date, \r\n                    user_name, \r\n                    firstname, \r\n                    lastname, \r\n                    deposit_amount, \r\n                    description, \r\n                    qrcode_image_url\r\n                FROM " . $table_name_deposit . " \r\n                WHERE user_id = " . $user_id . " \r\n                ORDER BY id DESC \r\n                LIMIT 1\r\n            ");
        if($deposit_data) {
            extract((array) $deposit_data);
            if(isset($_POST["deposit_request_submit"]) && check_admin_referer("deposit_request_submit_nonce", "nonce_field_deposit_request")) {
                $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "0";
                $deposit_status = "pending";
                $customer_data = $wpdb->get_row("SELECT wallet_balance FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
                $customer_wallet_balance = $customer_data->wallet_balance;
                if($deposit_amount == $customer_wallet_balance) {
                    $final_deposit_amount = $deposit_amount - $deposit_fee;
                    $remaining_wallet_balance = 0;
                } else {
                    $final_deposit_amount = $deposit_amount;
                    $remaining_wallet_balance = $customer_wallet_balance - $final_deposit_amount;
                }
                $final_deposit_amount = $deposit_amount - $deposit_fee;
                $deposit_amount_formatted = number_format($deposit_amount, 0, ".", "");
                $transaction_processed = 0;
                function generate_authority()
                {
                    return sprintf("%04x%04x-%04x-%04x-%04x-%04x%04x%04x", mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095) | 16384, mt_rand(0, 16383) | 32768, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                }
                $code = 200;
                $authority = generate_authority();
                $price = $deposit_amount_formatted;
                $orderId = $deposit_id;
                $updated = $wpdb->update($table_name_deposit, ["deposit_amount" => $deposit_amount, "new_wallet_balance" => $remaining_wallet_balance, "deposit_fee" => $deposit_fee, "final_deposit_amount" => $final_deposit_amount, "authority" => $authority, "deposit_status" => $deposit_status, "transaction_processed" => $transaction_processed, "description" => $description], ["deposit_id" => $deposit_id]);
                if($updated !== false) {
                    echo "<div id=\"payment-loading-overlay\"><div><div class=\"payment-loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div><div style=\"text-align: center; margin-top: 25px\"><h5 class=\"payment-success\">";
                    echo esc_html__("در حال پردازش درخواست", "almas-gold");
                    echo "</h5><h6 style=\"color:#12373f\">";
                    echo esc_html__("لطفا منتظر بمانید...", "almas-gold");
                    echo "</h6></div></div></div>";
                } else {
                    echo "خطا در به‌روزرسانی اطلاعات.";
                    echo "خطای به‌روزرسانی: " . $wpdb->last_error;
                }
                $deposit_paywall_redirect_url = almas_gols_redirect_urls("deposit_paywall_redirect_url");
                $form_action = esc_url(site_url("/" . $deposit_paywall_redirect_url . "?" . $unique_deposit_id . "&" . $deposit_id . "&" . $deposit_date . "&" . $deposit_amount . "&" . $authority));
                $escaped_code = esc_attr($code);
                $escaped_authority = esc_attr($authority);
                $escaped_price = esc_attr($price);
                $escaped_orderId = esc_attr($orderId);
                echo "\r\n                        <form id='hidden_form' method='post' action='" . $form_action . "'>\r\n                            <input type='hidden' name='code' value='" . $escaped_code . "'>\r\n                            <input type='hidden' name='authority' value='" . $escaped_authority . "'>\r\n                            <input type='hidden' name='price' value='" . $escaped_price . "'>\r\n                            <input type='hidden' name='orderId' value='" . $escaped_orderId . "'>\r\n                        </form>\r\n                        <script type='text/javascript'>\r\n                            setTimeout(function() {\r\n                                document.getElementById('hidden_form').submit();\r\n                            }, 1500);\r\n                        </script>\r\n                    ";
                exit();
            }
            require_once "views/almas-gold-deposit-continue-html.php";
            echo "                    <script>\r\n                        \$(document).ready(function(){\r\n\r\n                            ///\r\n                            \$('#deposit_request_submit').click(function(){\r\n                                \$('.payment_overlay, .payment_popup').fadeIn(100);\r\n                            });\r\n\r\n                            ///\r\n                            \$('#close_popup').click(function(){\r\n                                \$('.payment_overlay, .payment_popup').fadeOut(100);\r\n                            });\r\n\r\n                            ///\r\n                            \$('#rules_agreement').change(function(){\r\n                                if(\$(this).is(':checked')){\r\n                                    \$('button[name=\"deposit_request_submit\"]').prop('disabled', false);\r\n                                } else {\r\n                                    \$('button[name=\"deposit_request_submit\"]').prop('disabled', true);\r\n                                }\r\n                            });\r\n                        });\r\n                    </script>\r\n                ";
        } else {
            echo "هیچ داده ای وجود ندارد.";
        }
    }
}
almas_gold_deposit_continue_template();

?>