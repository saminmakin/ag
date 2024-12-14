<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_recharge_order")) {
    function almas_gold_recharge_order()
    {
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        global $table_name_recharge;
        $core_data = $wpdb->get_row("SELECT \r\n                    recharge_lowest_limit,\r\n                    recharge_highest_limit\r\n                FROM " . $wpdb->prefix . "almas_gold_core \r\n                ORDER BY id DESC \r\n                LIMIT 1");
        $recharge_lowest_limit = $core_data->recharge_lowest_limit;
        $recharge_highest_limit = $core_data->recharge_highest_limit;
        if(isset($_POST["almas_gold_recharge_submit"]) && check_admin_referer("almas_gold_recharge_submit_nonce", "nonce_field_almas_gold_recharge")) {
            if(!$user_id) {
                echo "                        <script>\r\n                            window.location.href = '";
                echo wp_login_url();
                echo "';\r\n                        </script>\r\n                    ";
            }
            $unique_recharge_id = generate_unique_id("agw.");
            $last_recharge = $wpdb->get_var("SELECT MAX(recharge_id) FROM " . $wpdb->prefix . "almas_gold_recharge");
            $recharge_id = $last_recharge ? $last_recharge + 1 : 901550;
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $recharge_date = current_time("mysql");
            $user_name = $current_user->user_login;
            $customer_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
            $unique_customer_id = $customer_data->unique_id;
            $firstname = $customer_data->firstname;
            $lastname = $customer_data->lastname;
            $recharge_amount = isset($_POST["recharge_amount"]) ? intval(sanitize_text_field($_POST["recharge_amount"])) : 0;
            $previous_wallet_balance = $customer_data->wallet_balance;
            $safe_balance = $customer_data->safe_balance;
            $qrcode_image_url = generate_recharge_qrcode($unique_recharge_id);
            $wpdb->insert($table_name_recharge, ["unique_recharge_id" => $unique_recharge_id, "recharge_id" => $recharge_id, "unique_customer_id" => $unique_customer_id, "user_id" => $user_id, "recharge_date" => $recharge_date, "user_name" => $user_name, "firstname" => $firstname, "lastname" => $lastname, "recharge_amount" => $recharge_amount, "previous_wallet_balance" => $previous_wallet_balance, "new_wallet_balance" => $previous_wallet_balance, "safe_balance" => $safe_balance, "qrcode_image_url" => $qrcode_image_url]);
            $recharge_continue_redirect_url = almas_gols_redirect_urls("recharge_continue_redirect_url");
            if($wpdb->insert_id) {
                echo "<div id=\"loading-overlay\"><div class=\"loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div></div>";
                echo "<script>window.location.href = '$recharge_continue_redirect_url';</script>";
            } else {
                echo "متاسفانه سفارش ثبت نشد!";
            }
        }
        if(is_user_logged_in()) {
            $customer_data = $wpdb->get_row("SELECT\r\n                        wallet_balance \r\n                    FROM " . $wpdb->prefix . "almas_gold_customers \r\n                    WHERE user_id = " . $user_id);
            $customer_wallet_balance = $customer_data->wallet_balance;
        }
        require_once "views/almas-gold-recharge-form-html.php";
        echo "                ";
        if(is_user_logged_in()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const RechargepriceInput = document.getElementById('recharge_amount');
                    const RechargetargetDivSec = document.querySelector('.flex-recharge-form-des-price-parts-sec');
                    const RechargetargetDiv = document.querySelector('.flex-recharge-form-des-price-parts');
                    const RechargelabelElement = document.querySelector('.flex-recharge-form-des-price-label');
                    const RechargepricePartsForm = document.querySelector('.flex-recharge-form-des-price');
                    const RechargelowestLimit = " . json_encode($recharge_lowest_limit) . ";
                    const RechargehighestLimit = " . json_encode($recharge_highest_limit) . ";
        
                    // فرمت‌بندی و حذف کاماها
                    const formatNumber = num => num.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, ',');
                    const unformatNumber = num => num.replace(/,/g, '');
        
                    // تغییر وضعیت بر اساس مقدار ورودی
                    const updateRechargeInputState = () => {
                        const hasRechargeValue = RechargepriceInput.value.trim() !== '';
                        const isRechargeFocused = document.activeElement === RechargepriceInput;
        
                        RechargetargetDivSec?.style.setProperty('width', hasRechargeValue || isRechargeFocused ? '31px' : '');
                        RechargepricePartsForm?.classList.toggle('flex-recharge-form-des-price-focus', hasRechargeValue || isRechargeFocused);
                        RechargetargetDiv?.classList.toggle('flex-recharge-form-des-price-parts-focus-border', hasRechargeValue || isRechargeFocused);
                        RechargelabelElement?.classList.toggle('flex-recharge-form-des-price-label-focus', hasRechargeValue || isRechargeFocused);
                    };
        
                    // بررسی بازه و اضافه کردن کلاس error
                    const validateRechargeAmount = () => {
                        let inputRechargeVal = unformatNumber(RechargepriceInput.value);
        
                        // اگر ورودی معتبر بود، فرمت شود
                        if (!isNaN(inputRechargeVal) && inputRechargeVal.length > 0) {
                            inputRechargeVal = parseFloat(inputRechargeVal).toFixed(0);
                            RechargepriceInput.value = formatNumber(inputRechargeVal);
                        } else {
                            RechargepriceInput.value = '';
                        }
        
                        // تبدیل مقدار به عدد
                        const Rechargeamount = parseFloat(unformatNumber(RechargepriceInput.value));
        
                        // بررسی بازه و اضافه/حذف کلاس‌ها
                        const isRechargeValid = Rechargeamount >= RechargelowestLimit && Rechargeamount <= RechargehighestLimit;
        
                        // فقط اگر ورودی دارای مقداری باشد، کلاس error اضافه شود
                        if (RechargepriceInput.value.trim() !== '') {
                            RechargepricePartsForm.classList.toggle('error', !isRechargeValid);
                            RechargetargetDiv.classList.toggle('error', !isRechargeValid);
                        } else {
                            RechargepricePartsForm.classList.remove('error');
                            RechargetargetDiv.classList.remove('error');
                        }
        
                        document.querySelector('button[name=\"almas_gold_recharge_submit\"]').disabled = !isRechargeValid;
                    };
        
                    // اضافه کردن رویدادها
                    ['input', 'focus', 'blur'].forEach(event => {
                        RechargepriceInput.addEventListener(event, () => {
                            updateRechargeInputState();
                            validateRechargeAmount();
                        });
                    });
        
                    // حذف کاماها قبل از ارسال فرم
                    \$('form').on('submit', function() {
                        var RechargeAmountField = \$('#recharge_amount');
                        // حذف کاماها از مقدار ورودی\r\n
                        RechargeAmountField.val(unformatNumber(RechargeAmountField.val()));
                    });
        
                    // وضعیت اولیه ورودی
                    updateRechargeInputState();
                    validateRechargeAmount();
                });
            </script>";
        }

        echo "            ";
    }
}
almas_gold_recharge_order();

?>