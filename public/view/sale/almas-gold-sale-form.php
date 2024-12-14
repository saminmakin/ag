<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_sale_order")) {
    function almas_gold_sale_order()
    {
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        global $table_name_sale;
        $core_data = $wpdb->get_row("SELECT \r\n                    gold_price, \r\n                    sale_lowest_limit, \r\n                    sale_highest_limit,\r\n                    gold_unit_to_bills \r\n                FROM " . $wpdb->prefix . "almas_gold_core \r\n                ORDER BY id DESC \r\n                LIMIT 1");
        $gold_price = $core_data->gold_price;
        $sale_lowest_limit = $core_data->sale_lowest_limit;
        $sale_highest_limit = $core_data->sale_highest_limit;
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $unit_display = $gold_unit_to_bills;
        if(isset($_POST["almas_gold_sale_submit"]) && check_admin_referer("almas_gold_sale_submit_nonce", "nonce_field_almas_gold_sale")) {
            if(!$user_id) {
                echo "                        <script>\r\n                            window.location.href = '";
                echo wp_login_url();
                echo "';\r\n                        </script>\r\n                    ";
            }
            $unique_sale_id = generate_unique_id("agb.");
            $last_sale = $wpdb->get_var("SELECT MAX(sale_id) FROM " . $wpdb->prefix . "almas_gold_sale");
            $sale_id = $last_sale ? $last_sale + 1 : 301550;
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $sale_date = current_time("mysql");
            $user_name = $current_user->user_login;
            $customer_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
            $unique_customer_id = $customer_data->unique_id;
            $firstname = $customer_data->firstname;
            $firstname = $customer_data->firstname;
            $lastname = $customer_data->lastname;
            $customer_safe_balance = $customer_data->safe_balance;
            $customer_wallet_balance = $customer_data->wallet_balance;
            $gold_weight = isset($_POST["sale_gold_weight"]) ? floatval(sanitize_text_field($_POST["sale_gold_weight"])) : 0;
            $new_safe_balance = $customer_safe_balance - $gold_weight;
            $qrcode_image_url = generate_sale_qrcode($unique_sale_id);
            $wpdb->insert($table_name_sale, ["unique_sale_id" => $unique_sale_id, "sale_id" => $sale_id, "unique_customer_id" => $unique_customer_id, "user_id" => $user_id, "sale_date" => $sale_date, "user_name" => $user_name, "firstname" => $firstname, "lastname" => $lastname, "gold_weight" => $gold_weight, "previous_safe_balance" => $customer_safe_balance, "new_safe_balance" => $new_safe_balance, "previous_wallet_balance" => $customer_wallet_balance, "new_wallet_balance" => $customer_wallet_balance, "qrcode_image_url" => $qrcode_image_url]);
            $sale_continue_redirect_url = almas_gols_redirect_urls("sale_continue_redirect_url");
            
            if($wpdb->insert_id) {
                echo "<div id=\"loading-overlay\"><div class=\"loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div></div>";
                echo "<script>window.location.href = '$sale_continue_redirect_url';</script>";
            } else {
                echo "متاسفانه سفارش ثبت نشد!";
            }
        }
        if(is_user_logged_in()) {
            $table_name_customer = $wpdb->prefix . "almas_gold_customers";
            $customer_data = $wpdb->get_row($wpdb->prepare("SELECT \r\n                        safe_balance \r\n                    FROM " . $table_name_customer . " \r\n                    WHERE user_id = %d", $user_id));
            $customer_safe_balance = $customer_data->safe_balance;
            $sale_highest_limit = $customer_safe_balance;
            $sale_lowest_limit_display = $sale_lowest_limit;
            if($unit_display == 1) {
                if($sale_lowest_limit < 1) {
                    $sale_lowest_limit_display = $sale_lowest_limit * 1000;
                    $unit = "سوت";
                } else {
                    $unit = "گرم";
                }
            } else {
                $unit = "گرم";
            }
        } else {
            $customer_safe_balance = $sale_highest_limit;
            $sale_highest_limit = $core_data->sale_highest_limit;
        }
        require_once "views/almas-gold-sale-form-html.php";
        echo "<script>
        const goldSalePrice = ";
        echo $gold_price;
        echo ";
        const goldSaleLowestLimit = ";
        echo $sale_lowest_limit;
        echo ";
        const goldSaleHighestLimit = ";
        echo $sale_highest_limit;
        echo ";
            \$(document).ready(function() {
                var isInitialSalePriceFocused = false;
        
                // تابع برای فرمت‌بندی اعداد به صورت سه رقمی با کاما
                function formatNumber(number) {
                    return number.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, \",\");
                }
        
                // تابع برای حذف کاماها از مقدار ورودی
                function cleanNumberInput(value) {
                    return value.replace(/,/g, '');
                }
        
                // تابع برای اعتبارسنجی ورودی‌ها و اضافه کردن/حذف کلاس error به بخش‌های مشخص‌شده
                function validateSaleInputs() {
                    const goldSaleWeight = parseFloat(\$('#sale_gold_weight').val());
                    const InitialSalePrice = cleanNumberInput(\$('#sale_initial_price_display').val());
                    const calculatedSaleGoldWeight = parseFloat(InitialSalePrice) / goldSalePrice;
                
                    const SaleweightIsValid = !isNaN(goldSaleWeight) && goldSaleWeight >= goldSaleLowestLimit && goldSaleWeight <= goldSaleHighestLimit;
                    const SalepriceIsValid = !isNaN(calculatedSaleGoldWeight) && calculatedSaleGoldWeight >= goldSaleLowestLimit && calculatedSaleGoldWeight <= goldSaleHighestLimit;
                
                    const SaleweightIsNotEmpty = \$('#sale_gold_weight').val().trim() !== '';
                    const SalepriceIsNotEmpty = \$('#sale_initial_price_display').val().trim() !== '';
                
                    const SaleweightPartsForm = \$('.flex-sale-form-des-weight');
                    const SaleweightPartsDiv = \$('.flex-sale-form-des-weight-parts');

                    const SalepricePartsForm = \$('.flex-sale-form-des-price');
                    const SalepricePartsDiv = \$('.flex-sale-form-des-price-parts');
                
                    // اگر مقدار وارد شده خالی نبود و نامعتبر بود، کلاس error اضافه شود
                    if ((SaleweightIsNotEmpty && !SaleweightIsValid) || (SalepriceIsNotEmpty && !SalepriceIsValid)) {
                        SaleweightPartsForm.addClass('error');
                        SaleweightPartsDiv.addClass('error');

                        SalepricePartsForm.addClass('error');
                        SalepricePartsDiv.addClass('error');
                    } else {
                        SaleweightPartsForm.removeClass('error');
                        SaleweightPartsDiv.removeClass('error');

                        SalepricePartsForm.removeClass('error');
                        SalepricePartsDiv.removeClass('error');
                    }
                }
                
        
                // تابع برای به‌روزرسانی قیمت نمایشی بر اساس وزن طلا
                function updateDisplayedSalePrice() {
                    const goldSaleWeight = parseFloat(\$('#sale_gold_weight').val());
        
                    if (goldSaleWeight >= goldSaleLowestLimit && goldSaleWeight <= goldSaleHighestLimit) {
                        \$('#sale_initial_price_display').val(formatNumber((goldSaleWeight * goldSalePrice).toFixed(0)));
                    } else if (!isInitialSalePriceFocused) {
                        \$('#sale_initial_price_display').val('');
                    }
        
                    validateSaleInputs(); // اعتبارسنجی برای هر دو اینپوت
                }
        
                // تابع برای به‌روزرسانی وزن طلا بر اساس مبلغ اولیه
                function updateSaleGoldWeight() {
                    const InitialSalePrice = cleanNumberInput(\$('#sale_initial_price_display').val());
        
                    if (InitialSalePrice) {
                        \$('#sale_gold_weight').val((InitialSalePrice / goldSalePrice).toFixed(3));
                    } else {
                        \$('#sale_gold_weight').val('');
                    }
        
                    validateSaleInputs(); // اعتبارسنجی برای هر دو اینپوت
                }
        
                // رویداد برای ورودی قیمت
                \$('#sale_initial_price_display').on('input', function() {
                    isInitialSalePriceFocused = true;
                    updateSaleGoldWeight();
                });

                $('#sale_initial_price_display').on('input', function() {
                    isInitialSalePriceFocused = true;
                    // فرمت‌بندی مقدار ورودی به صورت سه رقم سه رقم
                    this.value = formatNumber(cleanNumberInput(this.value));
                    updateSaleGoldWeight();
                });
        
                // رویداد برای ورودی وزن طلا
                \$('#sale_gold_weight').on('input', updateDisplayedSalePrice);
        
                // کد ترکیبی مربوط به اضافه کردن کلاس‌های focus و بررسی مقدار اینپوت‌ها
                const SaleweightinputElement = document.getElementById('sale_gold_weight');
                const SalepriceinputElement = document.getElementById('sale_initial_price_display');
                
                const SaleweightformElement = document.querySelector('.flex-sale-form-des-weight');
                const SaleweighttargetDivSec = document.querySelector('.flex-sale-form-des-weight-parts-sec');
                const SaleweighttargetDiv = document.querySelector('.flex-sale-form-des-weight-parts');
                const SaleweightlabelElement = document.querySelector('.flex-sale-form-des-weight-label');
            
                const SalepriceformElement = document.querySelector('.flex-sale-form-des-price');
                const SalepricetargetDivSec = document.querySelector('.flex-sale-form-des-price-parts-sec');
                const SalepricetargetDiv = document.querySelector('.flex-sale-form-des-price-parts');
                const SalepricelabelElement = document.querySelector('.flex-sale-form-des-price-label');
        
                function updateSaleInputState(inputElement, formElement, targetDivSec, targetDiv, labelElement, formClass, widthValue, borderClass, labelClass) {
                    const SalehasValue = inputElement.value.trim() !== '';
                    const SaleisFocused = document.activeElement === inputElement;
        
                    if (SalehasValue || SaleisFocused) {
                        formElement?.classList.add(formClass);
                        targetDivSec?.style.setProperty('width', widthValue);
                        targetDiv?.classList.add(borderClass);
                        labelElement?.classList.add(labelClass);
                    } else {
                        formElement?.classList.remove(formClass);
                        targetDivSec?.style.removeProperty('width');
                        targetDiv?.classList.remove(borderClass);
                        labelElement?.classList.remove(labelClass);
                    }
                }
        
                function updateBothSaleInputs() {
                    setTimeout(() => {
                        updateSaleInputState(SaleweightinputElement, SaleweightformElement, SaleweighttargetDivSec, SaleweighttargetDiv, SaleweightlabelElement, 'flex-sale-form-des-weight-focus', '27px', 'flex-sale-form-des-weight-parts-focus-border', 'flex-sale-form-des-weight-label-focus');
                        updateSaleInputState(SalepriceinputElement, SalepriceformElement, SalepricetargetDivSec, SalepricetargetDiv, SalepricelabelElement, 'flex-sale-form-des-price-focus', '31px', 'flex-sale-form-des-price-parts-focus-border', 'flex-sale-form-des-price-label-focus');
                    }, 100);
                }
        
                // بررسی مقدار اینپوت‌ها در زمان بارگذاری صفحه
                updateBothSaleInputs();
        
                // رویدادهای focus و blur برای هر دو اینپوت
                [SaleweightinputElement, SalepriceinputElement].forEach(input => {
                    input.addEventListener('focus', updateBothSaleInputs);
                    input.addEventListener('blur', updateBothSaleInputs);
                    input.addEventListener('input', updateBothSaleInputs);
                });
            });
        </script>
        ";
    }
}
almas_gold_sale_order();

?>