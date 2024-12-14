<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_sale_bill_template")) {
    function almas_gold_sale_bill_template()
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
        global $table_name_sale;
        $core_data = $wpdb->get_row("SELECT gold_price, sale_tax, gold_unit_to_bills FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
        $gold_price = $core_data->gold_price;
        $sale_tax = $core_data->sale_tax;
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $unit_display = $gold_unit_to_bills;
        $customer_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
        $customer_wallet_balance = $customer_data->wallet_balance;
        $customer_safe_balance = $customer_data->safe_balance;
        $table_name_sale = $wpdb->prefix . "almas_gold_sale";
        $sale_data = $wpdb->get_row($wpdb->prepare("SELECT \r\n                unique_sale_id, \r\n                sale_id, \r\n                unique_customer_id, \r\n                user_id, \r\n                sale_date, \r\n                user_name, \r\n                firstname, \r\n                lastname, \r\n                gold_weight, \r\n                description, \r\n                qrcode_image_url\r\n                FROM " . $table_name_sale . " \r\n                WHERE user_id = %d \r\n                ORDER BY id DESC LIMIT 1", $user_id));
        if($sale_data) {
            extract((array) $sale_data);
            $initial_price = $gold_price * $gold_weight;
            $total_sale_tax = $initial_price * $sale_tax / 100;
            $initial_final_price = $initial_price - $total_sale_tax;
            $remaining_wallet_balance = $customer_wallet_balance + $initial_final_price;
            $remaining_safe_balance = $customer_safe_balance - $gold_weight;
            if(isset($_POST["almas_gold_sale_submit"]) && check_admin_referer("almas_gold_sale_submit_nonce", "nonce_field_almas_gold_sale")) {
                $final_price = $initial_final_price;
                $final_price_formatted = number_format($final_price, 0, ".", "");
                $description = isset($_POST["description"]) ? sanitize_text_field($_POST["description"]) : "0";
                function generate_authority()
                {
                    return sprintf("%04x%04x-%04x-%04x-%04x-%04x%04x%04x", mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095) | 16384, mt_rand(0, 16383) | 32768, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                }
                $code = 200;
                $authority = generate_authority();
                $price = $final_price_formatted;
                $orderId = $sale_id;
                $updated = $wpdb->update($table_name_sale, ["gold_price" => $gold_price, "initial_price" => $initial_price, "total_sale_tax" => $total_sale_tax, "initial_final_price" => $initial_final_price, "new_wallet_balance" => $remaining_wallet_balance, "final_price" => $final_price, "price_received_by_wallet" => $final_price, "authority" => $authority, "description" => $description], ["sale_id" => $sale_id]);
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
                $sale_paywall_redirect_url = almas_gols_redirect_urls("sale_paywall_redirect_url");
                $form_action = esc_url(site_url("/" . $sale_paywall_redirect_url . "?" . $unique_sale_id . "&" . $sale_id . "&" . $sale_date . "&" . $sale_amount . "&" . $authority));
                $escaped_code = esc_attr($code);
                $escaped_authority = esc_attr($authority);
                $escaped_price = esc_attr($price);
                $escaped_orderId = esc_attr($orderId);
                echo "\r\n                        <form id='hidden_form' method='post' action='" . $form_action . "'>\r\n                            <input type='hidden' name='code' value='" . $escaped_code . "'>\r\n                            <input type='hidden' name='authority' value='" . $escaped_authority . "'>\r\n                            <input type='hidden' name='price' value='" . $escaped_price . "'>\r\n                            <input type='hidden' name='orderId' value='" . $escaped_orderId . "'>\r\n                        </form>\r\n                        <script type='text/javascript'>\r\n                            setTimeout(function() {\r\n                                document.getElementById('hidden_form').submit();\r\n                            }, 1500);\r\n                        </script>\r\n                    ";
                exit();
            }
            require_once "views/almas-gold-sale-continue-html.php";
            echo "                    <script>\r\n                        \$(document).ready(function(){\r\n\r\n                            \$('#almas_gold_sale_submit').click(function(){\r\n                                \$('.payment_overlay, .payment_popup').fadeIn(100);\r\n                            });\r\n\r\n                            \$('#close_popup').click(function(){\r\n                                \$('.payment_overlay, .payment_popup').fadeOut(100);\r\n                            });\r\n\r\n                            \$('#rules_agreement').change(function(){\r\n                                if(\$(this).is(':checked')){\r\n                                    \$('button[name=\"almas_gold_sale_submit\"]').prop('disabled', false);\r\n                                } else {\r\n                                    \$('button[name=\"almas_gold_sale_submit\"]').prop('disabled', true);\r\n                                }\r\n                            });\r\n                        });\r\n                    </script>\r\n                ";
        } else {
            echo "هیچ داده ای وجود ندارد.";
        }
    }
}
almas_gold_sale_bill_template();

?>