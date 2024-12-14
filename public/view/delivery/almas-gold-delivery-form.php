<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_delivery_order")) {
    function almas_gold_delivery_order()
    {
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        global $table_name_delivery;
        $core_data = $wpdb->get_row("SELECT \r\n                    gold_price, \r\n                    delivery_lowest_limit, \r\n                    delivery_highest_limit,\r\n                    gold_unit_to_bills\r\n                FROM " . $wpdb->prefix . "almas_gold_core \r\n                ORDER BY id DESC \r\n                LIMIT 1");
        $gold_price = $core_data->gold_price;
        $delivery_lowest_limit = $core_data->delivery_lowest_limit;
        $delivery_highest_limit = $core_data->delivery_highest_limit;
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $unit_display = $gold_unit_to_bills;
        if(isset($_POST["almas_gold_delivery_submit"]) && check_admin_referer("almas_gold_delivery_submit_nonce", "nonce_field_almas_gold_delivery")) {
            if(!$user_id) {
                echo "                        <script>\r\n                            window.location.href = '";
                echo wp_login_url();
                echo "';\r\n                        </script>\r\n                    ";
            }
            $unique_delivery_id = generate_unique_id("agr.");
            $last_delivery = $wpdb->get_var("SELECT MAX(delivery_id) FROM " . $wpdb->prefix . "almas_gold_delivery");
            $delivery_id = $last_delivery ? $last_delivery + 1 : 501550;
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $delivery_request_date = current_time("mysql");
            $user_name = $current_user->user_login;
            $customer_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "almas_gold_customers WHERE user_id = " . $user_id);
            $unique_customer_id = $customer_data->unique_id;
            $firstname = $customer_data->firstname;
            $lastname = $customer_data->lastname;
            $customer_safe_balance = $customer_data->safe_balance;
            $customer_wallet_balance = $customer_data->wallet_balance;
            $gold_weight = isset($_POST["delivery_gold_weight"]) ? floatval(sanitize_text_field($_POST["delivery_gold_weight"])) : 0;
            $new_safe_balance = $customer_safe_balance - $gold_weight;
            $qrcode_image_url = generate_delivery_qrcode($unique_delivery_id);
            $wpdb->insert($table_name_delivery, ["unique_delivery_id" => $unique_delivery_id, "delivery_id" => $delivery_id, "unique_customer_id" => $unique_customer_id, "user_id" => $user_id, "delivery_request_date" => $delivery_request_date, "user_name" => $user_name, "firstname" => $firstname, "lastname" => $lastname, "gold_weight" => $gold_weight, "previous_safe_balance" => $customer_safe_balance, "new_safe_balance" => $new_safe_balance, "previous_wallet_balance" => $customer_wallet_balance, "new_wallet_balance" => $customer_wallet_balance, "qrcode_image_url" => $qrcode_image_url]);
            $delivery_continue_redirect_url = almas_gols_redirect_urls("delivery_continue_redirect_url");
            if($wpdb->insert_id) {
                echo "<div id=\"loading-overlay\"><div class=\"loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div></div>";
                echo "<script>window.location.href = '$delivery_continue_redirect_url';</script>";
            } else {
                echo "متاسفانه سفارش ثبت نشد!";
            }
        }
        if(is_user_logged_in()) {
            $table_name_customer = $wpdb->prefix . "almas_gold_customers";
            $customer_data = $wpdb->get_row($wpdb->prepare("SELECT \r\n                        safe_balance \r\n                    FROM " . $table_name_customer . " \r\n                    WHERE user_id = %d", $user_id));
            $customer_safe_balance = $customer_data->safe_balance;
            if($unit_display == 1) {
                if($delivery_lowest_limit < 1) {
                    $delivery_lowest_limit_display = $delivery_lowest_limit * 1000;
                    $unit = "سوت";
                } else {
                    $unit = "گرم";
                }
            } else {
                $unit = "گرم";
            }
            if($customer_safe_balance < $delivery_lowest_limit) {
                $new_delivery_highest_limit = $delivery_highest_limit;
            } elseif($delivery_lowest_limit < $customer_safe_balance && $customer_safe_balance < $delivery_highest_limit) {
                $new_delivery_highest_limit = $customer_safe_balance;
            } else {
                $new_delivery_highest_limit = $delivery_highest_limit;
            }
        }
        require_once "views/almas-gold-delivery-form-html.php";
        echo "<script>
        const goldDeliveryPrice = ";
        echo $gold_price;
        echo ";
        const goldDeliveryLowestLimit = ";
        echo $delivery_lowest_limit;
        echo ";
        const goldDeliveryHighestLimit = ";
        echo $new_delivery_highest_limit;
        echo ";
            \$(document).ready(function() {
                var isInitialDeliveryPriceFocused = false;
        
                // تابع برای فرمت‌بندی اعداد به صورت سه رقمی با کاما
                function formatNumber(number) {
                    return number.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, \",\");
                }
        
                // تابع برای حذف کاماها از مقدار ورودی
                function cleanNumberInput(value) {
                    return value.replace(/,/g, '');
                }
        
                // تابع برای اعتبارسنجی ورودی‌ها و اضافه کردن/حذف کلاس error به بخش‌های مشخص‌شده
                function validateDeliveryInputs() {
                    const goldDeliveryWeight = parseFloat(\$('#delivery_gold_weight').val());
                    const InitialDeliveryPrice = cleanNumberInput(\$('#delivery_initial_price_display').val());
                    const calculatedDeliveryGoldWeight = parseFloat(InitialDeliveryPrice) / goldDeliveryPrice;
                
                    const DeliveryweightIsValid = !isNaN(goldDeliveryWeight) && goldDeliveryWeight >= goldDeliveryLowestLimit && goldDeliveryWeight <= goldDeliveryHighestLimit;
                    const DeliverypriceIsValid = !isNaN(calculatedDeliveryGoldWeight) && calculatedDeliveryGoldWeight >= goldDeliveryLowestLimit && calculatedDeliveryGoldWeight <= goldDeliveryHighestLimit;
                
                    const DeliveryweightIsNotEmpty = \$('#delivery_gold_weight').val().trim() !== '';
                    const DeliverypriceIsNotEmpty = \$('#delivery_initial_price_display').val().trim() !== '';
                
                    const DeliveryweightPartsForm = \$('.flex-delivery-form-des-weight');
                    const DeliveryweightPartsDiv = \$('.flex-delivery-form-des-weight-parts');

                    const DeliverypricePartsForm = \$('.flex-delivery-form-des-price');
                    const DeliverypricePartsDiv = \$('.flex-delivery-form-des-price-parts');
                
                    // اگر مقدار وارد شده خالی نبود و نامعتبر بود، کلاس error اضافه شود
                    if ((DeliveryweightIsNotEmpty && !DeliveryweightIsValid) || (DeliverypriceIsNotEmpty && !DeliverypriceIsValid)) {
                        DeliveryweightPartsForm.addClass('error');
                        DeliveryweightPartsDiv.addClass('error');

                        DeliverypricePartsForm.addClass('error');
                        DeliverypricePartsDiv.addClass('error');
                    } else {
                        DeliveryweightPartsForm.removeClass('error');
                        DeliveryweightPartsDiv.removeClass('error');

                        DeliverypricePartsForm.removeClass('error');
                        DeliverypricePartsDiv.removeClass('error');
                    }
                }
                
        
                // تابع برای به‌روزرسانی قیمت نمایشی بر اساس وزن طلا
                function updateDisplayedDeliveryPrice() {
                    const goldDeliveryWeight = parseFloat(\$('#delivery_gold_weight').val());
        
                    if (goldDeliveryWeight >= goldDeliveryLowestLimit && goldDeliveryWeight <= goldDeliveryHighestLimit) {
                        \$('#delivery_initial_price_display').val(formatNumber((goldDeliveryWeight * goldDeliveryPrice).toFixed(0)));
                    } else if (!isInitialDeliveryPriceFocused) {
                        \$('#delivery_initial_price_display').val('');
                    }
        
                    validateDeliveryInputs(); // اعتبارسنجی برای هر دو اینپوت
                }
        
                // تابع برای به‌روزرسانی وزن طلا بر اساس مبلغ اولیه
                function updateDeliveryGoldWeight() {
                    const InitialDeliveryPrice = cleanNumberInput(\$('#delivery_initial_price_display').val());
        
                    if (InitialDeliveryPrice) {
                        \$('#delivery_gold_weight').val((InitialDeliveryPrice / goldDeliveryPrice).toFixed(3));
                    } else {
                        \$('#delivery_gold_weight').val('');
                    }
        
                    validateDeliveryInputs(); // اعتبارسنجی برای هر دو اینپوت
                }
        
                // رویداد برای ورودی قیمت
                \$('#delivery_initial_price_display').on('input', function() {
                    isInitialDeliveryPriceFocused = true;
                    updateDeliveryGoldWeight();
                });

                $('#delivery_initial_price_display').on('input', function() {
                    isInitialDeliveryPriceFocused = true;
                    // فرمت‌بندی مقدار ورودی به صورت سه رقم سه رقم
                    this.value = formatNumber(cleanNumberInput(this.value));
                    updateDeliveryGoldWeight();
                });
        
                // رویداد برای ورودی وزن طلا
                \$('#delivery_gold_weight').on('input', updateDisplayedDeliveryPrice);
        
                // کد ترکیبی مربوط به اضافه کردن کلاس‌های focus و بررسی مقدار اینپوت‌ها
                const DeliveryweightinputElement = document.getElementById('delivery_gold_weight');
                const DeliverypriceinputElement = document.getElementById('delivery_initial_price_display');
                
                const DeliveryweightformElement = document.querySelector('.flex-delivery-form-des-weight');
                const DeliveryweighttargetDivSec = document.querySelector('.flex-delivery-form-des-weight-parts-sec');
                const DeliveryweighttargetDiv = document.querySelector('.flex-delivery-form-des-weight-parts');
                const DeliveryweightlabelElement = document.querySelector('.flex-delivery-form-des-weight-label');
            
                const DeliverypriceformElement = document.querySelector('.flex-delivery-form-des-price');
                const DeliverypricetargetDivSec = document.querySelector('.flex-delivery-form-des-price-parts-sec');
                const DeliverypricetargetDiv = document.querySelector('.flex-delivery-form-des-price-parts');
                const DeliverypricelabelElement = document.querySelector('.flex-delivery-form-des-price-label');
        
                function updateDeliveryInputState(inputElement, formElement, targetDivSec, targetDiv, labelElement, formClass, widthValue, borderClass, labelClass) {
                    const DeliveryhasValue = inputElement.value.trim() !== '';
                    const DeliveryisFocused = document.activeElement === inputElement;
        
                    if (DeliveryhasValue || DeliveryisFocused) {
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
        
                function updateBothDeliveryInputs() {
                    setTimeout(() => {
                        updateDeliveryInputState(DeliveryweightinputElement, DeliveryweightformElement, DeliveryweighttargetDivSec, DeliveryweighttargetDiv, DeliveryweightlabelElement, 'flex-delivery-form-des-weight-focus', '27px', 'flex-delivery-form-des-weight-parts-focus-border', 'flex-delivery-form-des-weight-label-focus');
                        updateDeliveryInputState(DeliverypriceinputElement, DeliverypriceformElement, DeliverypricetargetDivSec, DeliverypricetargetDiv, DeliverypricelabelElement, 'flex-delivery-form-des-price-focus', '31px', 'flex-delivery-form-des-price-parts-focus-border', 'flex-delivery-form-des-price-label-focus');
                    }, 100);
                }
        
                // بررسی مقدار اینپوت‌ها در زمان بارگذاری صفحه
                updateBothDeliveryInputs();
        
                // رویدادهای focus و blur برای هر دو اینپوت
                [DeliveryweightinputElement, DeliverypriceinputElement].forEach(input => {
                    input.addEventListener('focus', updateBothDeliveryInputs);
                    input.addEventListener('blur', updateBothDeliveryInputs);
                    input.addEventListener('input', updateBothDeliveryInputs);
                });
            });
        </script>
        ";
    }
}
almas_gold_delivery_order();

?>