<?php

defined("ABSPATH") or exit();
if(!function_exists("almas_gold_shop_order")) {
    function almas_gold_shop_order()
    {
        global $wpdb;
        global $table_name_shop;
        $core_data = $wpdb->get_row("SELECT \r\n                    gold_price, \r\n                    shop_tax, \r\n                    shop_lowest_limit , \r\n                    shop_highest_limit,\r\n                    gold_unit_to_bills \r\n                FROM " . $wpdb->prefix . "almas_gold_core \r\n                ORDER BY id DESC \r\n                LIMIT 1");
        if(!$core_data) {
            echo "داده‌های هسته فروشگاه یافت نشد.";
        } else {
            $gold_price = $core_data->gold_price;
            $shop_tax = $core_data->shop_tax;
            $shop_lowest_limit = $core_data->shop_lowest_limit;
            $shop_highest_limit = $core_data->shop_highest_limit;
            $gold_unit_to_bills = $core_data->gold_unit_to_bills;
            $unit_display = $gold_unit_to_bills;
            if(isset($_POST["almas_gold_shop_submit"]) && check_admin_referer("almas_gold_shop_submit_nonce", "nonce_field_almas_gold_shop")) {
                global $wpdb;
                $current_user = wp_get_current_user();
                $user_id = get_current_user_id();
                if(!$user_id) {
                    echo "<script>window.location.href = '" . wp_login_url() . "';</script>";
                    return NULL;
                }
                $last_shop = $wpdb->get_var("SELECT MAX(shop_id) FROM {$wpdb->prefix}almas_gold_shop");
                $shop_id = $last_shop ? $last_shop + 1 : 101550; // مقدار پیش‌فرض 101550
                $shop_date = current_time("mysql"); // استفاده از منطقه زمانی محلی
                $customer_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}almas_gold_customers WHERE user_id = %d", $user_id));
                if(!$customer_data) {
                    echo "اطلاعات مشتری یافت نشد.";
                    return NULL;
                }
                $shop_gold_weight = floatval(sanitize_text_field($_POST["shop_gold_weight"] ?? 0));
                $previous_safe_balance = $customer_data->safe_balance;
                $new_safe_balance = $previous_safe_balance + $shop_gold_weight;
                $previous_wallet_balance = $customer_data->wallet_balance;
                $unique_shop_id = generate_unique_id("ags.");
                $wpdb->insert("{$wpdb->prefix}almas_gold_shop", [
                    "unique_shop_id"          => $unique_shop_id,
                    "shop_id"                 => $shop_id,
                    "unique_customer_id"      => $customer_data->unique_id,
                    "user_id"                 => $user_id,
                    "shop_date"               => $shop_date,
                    "user_name"               => $current_user->user_login,
                    "firstname"               => $customer_data->firstname,
                    "lastname"                => $customer_data->lastname,
                    "gold_weight"             => $shop_gold_weight,
                    "previous_safe_balance"   => $previous_safe_balance,
                    "new_safe_balance"        => $new_safe_balance,
                    "previous_wallet_balance" => $previous_wallet_balance,
                    "new_wallet_balance"      => $previous_wallet_balance,
                    "qrcode_image_url"        => generate_shop_qrcode($unique_shop_id),
                    "transaction_status"      => "pending",
                    "transaction_processed"   => "0"
                ]);
                $shop_continue_redirect_url = almas_gols_redirect_urls("shop_continue_redirect_url");
                if($wpdb->insert_id) {
                    echo "<div id=\"loading-overlay\"><div class=\"loading-spin\"><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div><div class=\"slice\"></div></div></div>";
                    echo "<script>window.location.href = '{$shop_continue_redirect_url}';</script>";
                } else {
                    echo "متاسفانه سفارش ثبت نشد!";
                }
            }
            if(is_user_logged_in()) {
                $shop_lowest_limit_display = $shop_lowest_limit;
                if($unit_display == 1) {
                    if($shop_lowest_limit < 1) {
                        $shop_lowest_limit_display = $shop_lowest_limit * 1000;
                        $unit = "سوت";
                    } else {
                        $unit = "گرم";
                    }
                } else {
                    $unit = "گرم";
                }
            }
            require_once "views/almas-gold-shop-form-html.php";
            echo "<script>
            const goldShopPrice = ";
            echo $gold_price;
            echo ";
            const goldShopLowestLimit = ";
            echo $shop_lowest_limit;
            echo ";
            const goldShopHighestLimit = ";
            echo $shop_highest_limit;
            echo ";
                \$(document).ready(function() {
                    var isInitialShopPriceFocused = false;
            
                    // تابع برای فرمت‌بندی اعداد به صورت سه رقمی با کاما
                    function formatNumber(number) {
                        return number.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, \",\");
                    }
            
                    // تابع برای حذف کاماها از مقدار ورودی
                    function cleanNumberInput(value) {
                        return value.replace(/,/g, '');
                    }
            
                    // تابع برای اعتبارسنجی ورودی‌ها و اضافه کردن/حذف کلاس error به بخش‌های مشخص‌شده
                    function validateShopInputs() {
                        const goldShopWeight = parseFloat(\$('#shop_gold_weight').val());
                        const InitialShopPrice = cleanNumberInput(\$('#shop_initial_price_display').val());
                        const calculatedShopGoldWeight = parseFloat(InitialShopPrice) / goldShopPrice;
                    
                        const ShopweightIsValid = !isNaN(goldShopWeight) && goldShopWeight >= goldShopLowestLimit && goldShopWeight <= goldShopHighestLimit;
                        const ShoppriceIsValid = !isNaN(calculatedShopGoldWeight) && calculatedShopGoldWeight >= goldShopLowestLimit && calculatedShopGoldWeight <= goldShopHighestLimit;
                    
                        const ShopweightIsNotEmpty = \$('#shop_gold_weight').val().trim() !== '';
                        const ShoppriceIsNotEmpty = \$('#shop_initial_price_display').val().trim() !== '';
                    
                        const ShopweightPartsForm = \$('.flex-shop-form-des-weight');
                        const ShopweightPartsDiv = \$('.flex-shop-form-des-weight-parts');

                        const ShoppricePartsForm = \$('.flex-shop-form-des-price');
                        const ShoppricePartsDiv = \$('.flex-shop-form-des-price-parts');
                    
                        // اگر مقدار وارد شده خالی نبود و نامعتبر بود، کلاس error اضافه شود
                        if ((ShopweightIsNotEmpty && !ShopweightIsValid) || (ShoppriceIsNotEmpty && !ShoppriceIsValid)) {
                            ShopweightPartsForm.addClass('error');
                            ShopweightPartsDiv.addClass('error');

                            ShoppricePartsForm.addClass('error');
                            ShoppricePartsDiv.addClass('error');
                        } else {
                            ShopweightPartsForm.removeClass('error');
                            ShopweightPartsDiv.removeClass('error');

                            ShoppricePartsForm.removeClass('error');
                            ShoppricePartsDiv.removeClass('error');
                        }
                    }
                    
            
                    // تابع برای به‌روزرسانی قیمت نمایشی بر اساس وزن طلا
                    function updateDisplayedShopPrice() {
                        const goldShopWeight = parseFloat(\$('#shop_gold_weight').val());
            
                        if (goldShopWeight >= goldShopLowestLimit && goldShopWeight <= goldShopHighestLimit) {
                            \$('#shop_initial_price_display').val(formatNumber((goldShopWeight * goldShopPrice).toFixed(0)));
                        } else if (!isInitialShopPriceFocused) {
                            \$('#shop_initial_price_display').val('');
                        }
            
                        validateShopInputs(); // اعتبارسنجی برای هر دو اینپوت
                    }
            
                    // تابع برای به‌روزرسانی وزن طلا بر اساس مبلغ اولیه
                    function updateShopGoldWeight() {
                        const InitialShopPrice = cleanNumberInput(\$('#shop_initial_price_display').val());
            
                        if (InitialShopPrice) {
                            \$('#shop_gold_weight').val((InitialShopPrice / goldShopPrice).toFixed(3));
                        } else {
                            \$('#shop_gold_weight').val('');
                        }
            
                        validateShopInputs(); // اعتبارسنجی برای هر دو اینپوت
                    }
            
                    // رویداد برای ورودی قیمت
                    \$('#shop_initial_price_display').on('input', function() {
                        isInitialShopPriceFocused = true;
                        updateShopGoldWeight();
                    });

                    $('#shop_initial_price_display').on('input', function() {
                        isInitialShopPriceFocused = true;
                        // فرمت‌بندی مقدار ورودی به صورت سه رقم سه رقم
                        this.value = formatNumber(cleanNumberInput(this.value));
                        updateShopGoldWeight();
                    });
            
                    // رویداد برای ورودی وزن طلا
                    \$('#shop_gold_weight').on('input', updateDisplayedShopPrice);
            
                    // کد ترکیبی مربوط به اضافه کردن کلاس‌های focus و بررسی مقدار اینپوت‌ها
                    const shopweightinputElement = document.getElementById('shop_gold_weight');
                    const shoppriceinputElement = document.getElementById('shop_initial_price_display');
                    
                    const shopweightformElement = document.querySelector('.flex-shop-form-des-weight');
                    const shopweighttargetDivSec = document.querySelector('.flex-shop-form-des-weight-parts-sec');
                    const shopweighttargetDiv = document.querySelector('.flex-shop-form-des-weight-parts');
                    const shopweightlabelElement = document.querySelector('.flex-shop-form-des-weight-label');
                
                    const shoppriceformElement = document.querySelector('.flex-shop-form-des-price');
                    const shoppricetargetDivSec = document.querySelector('.flex-shop-form-des-price-parts-sec');
                    const shoppricetargetDiv = document.querySelector('.flex-shop-form-des-price-parts');
                    const shoppricelabelElement = document.querySelector('.flex-shop-form-des-price-label');
            
                    function updateShopInputState(inputElement, formElement, targetDivSec, targetDiv, labelElement, formClass, widthValue, borderClass, labelClass) {
                        const shophasValue = inputElement.value.trim() !== '';
                        const shopisFocused = document.activeElement === inputElement;
            
                        if (shophasValue || shopisFocused) {
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
            
                    function updateBothShopInputs() {
                        setTimeout(() => {
                            updateShopInputState(shopweightinputElement, shopweightformElement, shopweighttargetDivSec, shopweighttargetDiv, shopweightlabelElement, 'flex-shop-form-des-weight-focus', '27px', 'flex-shop-form-des-weight-parts-focus-border', 'flex-shop-form-des-weight-label-focus');
                            updateShopInputState(shoppriceinputElement, shoppriceformElement, shoppricetargetDivSec, shoppricetargetDiv, shoppricelabelElement, 'flex-shop-form-des-price-focus', '31px', 'flex-shop-form-des-price-parts-focus-border', 'flex-shop-form-des-price-label-focus');
                        }, 100);
                    }
            
                    // بررسی مقدار اینپوت‌ها در زمان بارگذاری صفحه
                    updateBothShopInputs();
            
                    // رویدادهای focus و blur برای هر دو اینپوت
                    [shopweightinputElement, shoppriceinputElement].forEach(input => {
                        input.addEventListener('focus', updateBothShopInputs);
                        input.addEventListener('blur', updateBothShopInputs);
                        input.addEventListener('input', updateBothShopInputs);
                    });
                });
            </script>
            ";
        }
    }
}
almas_gold_shop_order();

?>