<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_deposit_order")) {
    function almas_gold_deposit_order()
    {
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        global $table_name_deposit;
        $core_data = $wpdb->get_row("SELECT \r\n                    deposit_lowest_limit,\r\n                    deposit_highest_limit\r\n                FROM " . $wpdb->prefix . "almas_gold_core \r\n                ORDER BY id DESC \r\n                LIMIT 1");
        $deposit_lowest_limit = $core_data->deposit_lowest_limit;
        $deposit_highest_limit = $core_data->deposit_highest_limit;
        if(isset($_POST["almas_gold_deposit_submit"]) && check_admin_referer("almas_gold_deposit_submit_nonce", "nonce_field_almas_gold_deposit")) {
            if(!$user_id) {
                echo "                        <script>\r\n                            window.location.href = '";
                echo wp_login_url();
                echo "';\r\n                        </script>\r\n                    ";
            }
            $unique_deposit_id = generate_unique_id("agd.");
            $last_deposit = $wpdb->get_var("SELECT MAX(deposit_id) FROM " . $wpdb->prefix . "almas_gold_deposit");
            $deposit_id = $last_deposit ? $last_deposit + 1 : 701550;
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $deposit_date = current_time("mysql");
            $user_name = $current_user->user_login;
            $customer_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
            $customer_wallet_balance = $customer_data->wallet_balance;
            $unique_customer_id = $customer_data->unique_id;
            $firstname = $customer_data->firstname;
            $lastname = $customer_data->lastname;
            $deposit_amount = isset($_POST["deposit_amount"]) ? intval(sanitize_text_field($_POST["deposit_amount"])) : 0;
            $previous_wallet_balance = $customer_data->wallet_balance;
            $safe_balance = $customer_data->safe_balance;
            $qrcode_image_url = generate_deposit_qrcode($unique_deposit_id);
            $wpdb->insert($table_name_deposit, ["unique_deposit_id" => $unique_deposit_id, "deposit_id" => $deposit_id, "unique_customer_id" => $unique_customer_id, "user_id" => $user_id, "deposit_date" => $deposit_date, "user_name" => $user_name, "firstname" => $firstname, "lastname" => $lastname, "deposit_amount" => $deposit_amount, "previous_wallet_balance" => $previous_wallet_balance, "new_wallet_balance" => $previous_wallet_balance, "safe_balance" => $safe_balance, "qrcode_image_url" => $qrcode_image_url]);
            $deposit_continue_redirect_url = almas_gols_redirect_urls("deposit_continue_redirect_url");
            if($wpdb->insert_id) {
                echo "<div id=\"loading-overlay\"><div class=\"loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div></div>";
                echo "<script>window.location.href = '$deposit_continue_redirect_url';</script>";
            } else {
                echo "متاسفانه سفارش ثبت نشد!";
            }
        }
        if(is_user_logged_in()) {
            $customer_data = $wpdb->get_row("SELECT\r\n                        wallet_balance \r\n                    FROM " . $wpdb->prefix . "almas_gold_customers \r\n                    WHERE user_id = " . $user_id);
            $customer_wallet_balance = $customer_data->wallet_balance;
        }
        require_once "views/almas-gold-deposit-form-html.php";
        if(is_user_logged_in()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const depositpriceInput = document.getElementById('deposit_amount');
                    const deposittargetDivSec = document.querySelector('.flex-deposit-form-des-price-parts-sec');
                    const deposittargetDiv = document.querySelector('.flex-deposit-form-des-price-parts');
                    const depositlabelElement = document.querySelector('.flex-deposit-form-des-price-label');
                    const depositpricePartsForm = document.querySelector('.flex-deposit-form-des-price');
                    const depositlowestLimit = " . json_encode($deposit_lowest_limit) . ";
                    const deposithighestLimit = " . json_encode($customer_wallet_balance) . ";
        
                    // فرمت‌بندی و حذف کاماها
                    const formatNumber = num => num.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, ',');
                    const unformatNumber = num => num.replace(/,/g, '');
        
                    // تغییر وضعیت بر اساس مقدار ورودی
                    const updateDepositInputState = () => {
                        const hasDepositValue = depositpriceInput.value.trim() !== '';
                        const isDepositFocused = document.activeElement === depositpriceInput;
        
                        deposittargetDivSec?.style.setProperty('width', hasDepositValue || isDepositFocused ? '31px' : '');
                        depositpricePartsForm?.classList.toggle('flex-deposit-form-des-price-focus', hasDepositValue || isDepositFocused);
                        deposittargetDiv?.classList.toggle('flex-deposit-form-des-price-parts-focus-border', hasDepositValue || isDepositFocused);
                        depositlabelElement?.classList.toggle('flex-deposit-form-des-price-label-focus', hasDepositValue || isDepositFocused);
                    };
        
                    // بررسی بازه و اضافه کردن کلاس error
                    const validateDepositAmount = () => {
                        let inputDepositVal = unformatNumber(depositpriceInput.value);
        
                        // اگر ورودی معتبر بود، فرمت شود
                        if (!isNaN(inputDepositVal) && inputDepositVal.length > 0) {
                            inputDepositVal = parseFloat(inputDepositVal).toFixed(0);
                            depositpriceInput.value = formatNumber(inputDepositVal);
                        } else {
                            depositpriceInput.value = '';
                        }
        
                        // تبدیل مقدار به عدد
                        const depositamount = parseFloat(unformatNumber(depositpriceInput.value));
        
                        // بررسی بازه و اضافه/حذف کلاس‌ها
                        const isDepositValid = depositamount >= depositlowestLimit && depositamount <= deposithighestLimit;
        
                        // فقط اگر ورودی دارای مقداری باشد، کلاس error اضافه شود
                        if (depositpriceInput.value.trim() !== '') {
                            depositpricePartsForm.classList.toggle('error', !isDepositValid);
                            deposittargetDiv.classList.toggle('error', !isDepositValid);
                        } else {
                            depositpricePartsForm.classList.remove('error');
                            deposittargetDiv.classList.remove('error');
                        }
        
                        document.querySelector('button[name=\"almas_gold_deposit_submit\"]').disabled = !isDepositValid;
                    };
        
                    // اضافه کردن رویدادها
                    ['input', 'focus', 'blur'].forEach(event => {
                        depositpriceInput.addEventListener(event, () => {
                            updateDepositInputState();
                            validateDepositAmount();
                        });
                    });
        
                    // حذف کاماها قبل از ارسال فرم
                    \$('form').on('submit', function() {
                        var depositAmountField = \$('#deposit_amount');
                        // حذف کاماها از مقدار ورودی\r\n
                        depositAmountField.val(unformatNumber(depositAmountField.val()));
                    });
        
                    // وضعیت اولیه ورودی
                    updateDepositInputState();
                    validateDepositAmount();
                });
            </script>";
        }
    }
}
almas_gold_deposit_order();

?>