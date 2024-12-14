<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ Offline decoder for php versions: 40/74
 * @ Decoder version: 1.1.1
 * @ Release: 29/08/2024
 */

// Decoded file for php version 74.
defined("ABSPATH") or exit();
if(!function_exists("almas_gold_user_profile_action")) {
    function almas_gold_user_profile_action()
    {
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if(!$user_id) {
            echo "                    <script>\r\n                        window.location.href = '";
            echo wp_login_url();
            echo "';\r\n                    </script>\r\n                ";
        }
        global $wpdb;
        global $table_name;
        if(is_user_logged_in()) {
            $user_id = get_current_user_id();
            $table_name = $wpdb->prefix . "almas_gold_customers";
            $customer_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE user_id = %d", $user_id));
            if($customer_data) {
                $user_name = $customer_data->user_name;
                $unique_id = $customer_data->unique_id;
                $firstname = $customer_data->firstname;
                $lastname = $customer_data->lastname;
                $safe_balance = $customer_data->safe_balance;
                $wallet_balance = $customer_data->wallet_balance;
                $email = $customer_data->email;
                $mobile = $customer_data->mobile;
                $national_code = $customer_data->national_code;
                $state = $customer_data->state;
                $city = $customer_data->city;
                $address = $customer_data->address;
                $postal_code = $customer_data->postal_code;
                $bank_name = $customer_data->bank_name;
                $card_number = $customer_data->card_number;
                $iban_number = $customer_data->iban_number;
                $description = $customer_data->description;
                echo "                        ";
                if(is_user_logged_in() && $customer_data) {
                    echo "                            <h4>پروفایل</h4>\r\n                            <div style=\"display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;\">\r\n                                <div>\r\n                                    <h5>مشخصات فردی</h5>\r\n                                    <ul>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("نام و نام خانوادگی", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($firstname);
                    echo " ";
                    echo esc_attr($lastname);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("شناسه مشتری", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($unique_id);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("ایمیل", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($email);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("شماره موبایل", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($mobile);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("کد ملی", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($national_code);
                    echo "                                        </li>\r\n                                    </ul>\r\n                                </div>\r\n                                <div>\r\n                                    <h5>مشخصات مالی</h5>\r\n                                    <ul>   \r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("نام بانک", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($bank_name);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("شماره کارت بانکی", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($card_number);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("شماره شبا", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($iban_number);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("توضیحات", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($description);
                    echo "                                        </li>\r\n                                    </ul>\r\n                                </div>\r\n                                <div>\r\n                                    <h5>نشانی</h5>\r\n                                    <ul>   \r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("استان", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($state);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("شهر", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($city);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("نشانی", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($address);
                    echo "                                        </li>\r\n                                        <li>\r\n                                            <label>\r\n                                                ";
                    echo esc_html__("کد پستی", "almas-gold");
                    echo "                                            </label>\r\n                                            ";
                    echo esc_attr($postal_code);
                    echo "                                        </li>\r\n                                    </ul>   \r\n                                </div>\r\n                            </div>\r\n                        ";
                }
                echo "                    ";
            }
        }
    }
}
almas_gold_user_profile_action();

?>