<?php

defined("ABSPATH") or exit();

add_action("admin_init", "redirect_non_admin_users");
add_action("after_setup_theme", "disable_admin_bar_for_non_admins");
add_action("wp_login", "almasno_add_customer_row_on_login", 10, 2);
add_action('wp_ajax_get_shop_details', 'get_shop_details');
add_action('wp_ajax_nopriv_get_shop_details', 'get_shop_details');
add_action('init', function () {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        // بررسی نقش کاربر
        $allowed_roles = ['subscriber', 'customer']; // نقش‌هایی که اجازه اجرا دارند
        if (array_intersect($allowed_roles, $current_user->roles)) {

            // بررسی متای ورود برای جلوگیری از چندباره اجرا شدن
            if (!get_user_meta($current_user->ID, 'almas_gold_logged_once', true)) {
                // به‌روزرسانی متای کاربر
                update_user_meta($current_user->ID, 'almas_gold_logged_once', true);

                // اجرای فانکشن اصلی
                almasno_add_customer_row_on_login($current_user->user_login, $current_user);
            }
        }
    }
});

$almasGoldExtraFields = new almasGoldExtraFields();
new Almas_Gold_Dashboard_Widget();
class almasGoldExtraFields
{
    public function __construct()
    {
        add_action("show_user_profile", [$this, "add_custom_user_profile_fields"]);
        add_action("edit_user_profile", [$this, "add_custom_user_profile_fields"]);
        add_action("personal_options_update", [$this, "save_custom_user_profile_fields"]);
        add_action("edit_user_profile_update", [$this, "save_custom_user_profile_fields"]);
    }
    public function add_custom_user_profile_fields($user)
    {
        echo "                <h3>فیلدهای الماس گلد</h3>\n                <table class=\"form-table\">\n                    <tr>\n                        <th><label for=\"mobile\">شماره موبایل</label></th>\n                        <td>\n                            <input type=\"text\" name=\"mobile\" id=\"mobile\" value=\"";
        echo esc_attr(get_the_author_meta("mobile", $user->ID));
        echo "\" class=\"regular-text\" /><br />\n                        </td>\n                    </tr>\n                    <tr>\n                        <th><label for=\"national_code\">کد ملی</label></th>\n                        <td>\n                            <input type=\"text\" name=\"national_code\" id=\"national_code\" value=\"";
        echo esc_attr(get_the_author_meta("national_code", $user->ID));
        echo "\" class=\"regular-text\" /><br />\n                        </td>\n                    </tr>\n                    <tr>\n                        <th><label for=\"bank_name\">نام بانک</label></th>\n                        <td>\n                            <input type=\"text\" name=\"bank_name\" id=\"bank_name\" value=\"";
        echo esc_attr(get_the_author_meta("bank_name", $user->ID));
        echo "\" class=\"regular-text\" /><br />\n                        </td>\n                    </tr>\n                    <tr>\n                        <th><label for=\"card_number\">شماره کارت بانکی</label></th>\n                        <td>\n                            <input type=\"text\" name=\"card_number\" id=\"card_number\" value=\"";
        echo esc_attr(get_the_author_meta("card_number", $user->ID));
        echo "\" class=\"regular-text\" /><br />\n                        </td>\n                    </tr>\n                    <tr>\n                        <th><label for=\"iban_number\">شماره شبا</label></th>\n                        <td>\n                            <input type=\"text\" name=\"iban_number\" id=\"iban_number\" value=\"";
        echo esc_attr(get_the_author_meta("iban_number", $user->ID));
        echo "\" class=\"regular-text\" /><br />\n                        </td>\n                    </tr>\n                    <tr>\n                        <th><label for=\"state\">";
        echo esc_html__("استان", "almas-gold");
        echo "</label></th>\n                        <td>\n                            <input type=\"text\" name=\"state\" id=\"state\" value=\"";
        echo esc_attr(get_the_author_meta("state", $user->ID));
        echo "\" class=\"regular-text\" /><br />\n                        </td>\n                    </tr>\n                    <tr>\n                        <th><label for=\"city\">";
        echo esc_html__("شهر", "almas-gold");
        echo "</label></th>\n                        <td>\n                            <input type=\"text\" name=\"city\" id=\"city\" value=\"";
        echo esc_attr(get_the_author_meta("city", $user->ID));
        echo "\" class=\"regular-text\" /><br />\n                        </td>\n                    </tr>\n                    <tr>\n                        <th><label for=\"postal_code\">";
        echo esc_html__("کد پستی", "almas-gold");
        echo "</label></th>\n                        <td>\n                            <input type=\"text\" name=\"postal_code\" id=\"postal_code\" value=\"";
        echo esc_attr(get_the_author_meta("postal_code", $user->ID));
        echo "\" class=\"regular-text\" /><br />\n                        </td>\n                    </tr>\n                    <tr>\n                        <th><label for=\"address\">نشانی</label></th>\n                        <td>\n                            <textarea type=\"text\" name=\"address\" id=\"address\" class=\"regular-text\" >";
        echo esc_attr(get_the_author_meta("address", $user->ID));
        echo "</textarea><br />\n                        </td>\n                    </tr>\n                </table>\n            ";
    }
    public function save_custom_user_profile_fields($user_id)
    {
        if(!current_user_can("edit_user", $user_id)) {
            return false;
        }
        update_user_meta($user_id, "mobile", sanitize_text_field($_POST["mobile"]));
        update_user_meta($user_id, "national_code", sanitize_text_field($_POST["national_code"]));
        update_user_meta($user_id, "bank_name", sanitize_text_field($_POST["bank_name"]));
        update_user_meta($user_id, "card_number", sanitize_text_field($_POST["card_number"]));
        update_user_meta($user_id, "iban_number", sanitize_text_field($_POST["iban_number"]));
        update_user_meta($user_id, "state", sanitize_text_field($_POST["state"]));
        update_user_meta($user_id, "city", sanitize_text_field($_POST["city"]));
        update_user_meta($user_id, "postal_code", sanitize_text_field($_POST["postal_code"]));
        update_user_meta($user_id, "address", sanitize_text_field($_POST["address"]));
    }
}
class Almas_Gold_Dashboard_Widget
{
    public function __construct()
    {
        add_action("wp_dashboard_setup", [$this, "add_dashboard_widget"]);
    }
    public function add_dashboard_widget()
    {
        wp_add_dashboard_widget("almas_gold_dashboard_widget", "الماس گلد | موجودی کیف پول و گاوصندوق", [$this, "render_dashboard_widget"]);
    }
    public function render_dashboard_widget()
    {
        echo "                <div class=\"almas-gold-widget-container\">\n                    <div class=\"almas-gold-widget-section\">\n                        <h4>";
        echo esc_html__("موجودی گاوصندوق", "almas-gold");
        echo "</h4>\n                        ";
        $this->render_safe_balance_section();
        echo "                    </div>\n                    <div class=\"almas-gold-widget-section\">\n                        <h4>";
        echo esc_html__("موجودی کیف پول", "almas-gold");
        echo "</h4>\n                        ";
        $this->render_wallet_balance_section();
        echo "                    </div>\n                </div>\n            ";
    }
    private function render_safe_balance_section()
    {
        global $wpdb;
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        $table_core = $wpdb->prefix . "almas_gold_core";
        $core_data = $wpdb->get_row("SELECT gold_unit_to_customer, gold_price FROM " . $table_core . " ORDER BY id DESC LIMIT 1");
        $gold_unit_to_customer = $core_data->gold_unit_to_customer;
        $gold_price = $core_data->gold_price;
        $table_name = $wpdb->prefix . "almas_gold_customers";
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT safe_balance FROM " . $table_name . " WHERE user_id = %d", $user_id));
        if($customer_data) {
            $safe_balance = $customer_data->safe_balance;
            $customer_safe_value_amount = $safe_balance * $gold_price;
            $unit_display = $gold_unit_to_customer;
            if($safe_balance == 0) {
                echo "                        <div class=\"almas-gold-empty\">\n                            <p>";
                echo esc_html__("گاوصندوق خالی است", "almas-gold");
                echo "</p>\n                        </div>    \n                    ";
            } else {
                if($unit_display == 1) {
                    if($safe_balance < 1) {
                        $safe_balance = $safe_balance * 1000;
                        $unit = "سوت";
                    } else {
                        $unit = "گرم";
                    }
                } else {
                    $unit = "گرم";
                }
                echo "                        <div class=\"almas-gold-balance-info\">\n                            <div>\n                                <label>\n                                    ";
                echo esc_html__("موجودی", "almas-gold");
                echo "                                </label>\n                                <span>\n                                    ";
                echo rtrim(rtrim(number_format($safe_balance, 4, ".", ""), "0"), ".");
                echo "                                </span>\n                                <suffix>\n                                    ";
                echo esc_html__($unit, "almas-gold");
                echo "                                </suffix>\n                            </div>\n                            <div>\n                                <label>\n                                    ";
                echo esc_html__("معادل", "almas-gold");
                echo "                                </label>\n                                <span>\n                                    ";
                echo number_format($customer_safe_value_amount, 0, ".", ",");
                echo "                                </span>\n                                <suffix>\n                                    ";
                echo esc_html__("تومان", "almas-gold");
                echo "                                </suffix>\n                            </div>\n                        </div>    \n                    ";
            }
        }
    }
    private function render_wallet_balance_section()
    {
        global $wpdb;
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        $table_core = $wpdb->prefix . "almas_gold_core";
        $core_data = $wpdb->get_row("SELECT gold_unit_to_customer, gold_price FROM " . $table_core . " ORDER BY id DESC LIMIT 1");
        $gold_unit_to_customer = $core_data->gold_unit_to_customer;
        $gold_price = $core_data->gold_price;
        $table_name = $wpdb->prefix . "almas_gold_customers";
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT wallet_balance, safe_balance FROM " . $table_name . " WHERE user_id = %d", $user_id));
        if($customer_data) {
            $wallet_balance = $customer_data->wallet_balance;
            $safe_balance = $customer_data->safe_balance;
            $customer_wallet_value_amount = $wallet_balance / $gold_price;
            $unit_display = $gold_unit_to_customer;
            if($wallet_balance == 0) {
                echo "                        <div class=\"almas-gold-empty\">\n                            <p>";
                echo esc_html__("کیف پول خالی است", "almas-gold");
                echo "</p>\n                        </div>    \n                    ";
            } else {
                if($unit_display == 1) {
                    if($customer_wallet_value_amount < 1) {
                        $customer_wallet_value_amount = $customer_wallet_value_amount * 1000;
                        $unit = "سوت";
                    } else {
                        $unit = "گرم";
                    }
                } else {
                    $unit = "گرم";
                }
                echo "                        <div class=\"almas-gold-balance-info\">\n                            <div>\n                                <label>\n                                    ";
                echo esc_html__("موجودی کیف پول", "almas-gold");
                echo "                                </label>\n                                <span>\n                                    ";
                echo rtrim(rtrim(number_format($customer_wallet_value_amount, 4, ".", ""), "0"), ".");
                echo "                                </span>\n                                <suffix>\n                                    ";
                echo esc_html__($unit, "almas-gold");
                echo "                                </suffix>\n                            </div>\n                            <div>\n                                <label>\n                                    ";
                echo esc_html__("معادل", "almas-gold");
                echo "                                </label>\n                                <span>\n                                    ";
                echo number_format($wallet_balance, 0, ".", ",");
                echo "                                </span>\n                                <suffix>\n                                    ";
                echo esc_html__("تومان", "almas-gold");
                echo "                                </suffix>\n                            </div>\n                        </div>    \n                    ";
            }
        }
    }
}

function redirect_non_admin_users()
{
    // اگر درخواست از نوع Ajax یا REST API است، از هدایت جلوگیری کن
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    if (defined('REST_REQUEST') && REST_REQUEST) {
        return;
    }

    // هدایت کاربران غیرمدیر از پنل مدیریت به صفحه اصلی
    if (!current_user_can('administrator') && is_admin()) {
        wp_redirect(home_url('/'));
        exit();
    }
}

function disable_admin_bar_for_non_admins()
{
    if(!current_user_can("administrator")) {
        show_admin_bar(false);
    }
}
function generate_unique_id($prefix)
{
    $characters = "0123456789abcdefghijkmnprstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";
    $random_string = "";
    $length = 16;
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $prefix . $random_string;
}
function generate_unique_shop_id()
{
    return generate_unique_id("ags.");
}
function generate_unique_sale_id()
{
    return generate_unique_id("agb.");
}
function generate_unique_recharge_id()
{
    return generate_unique_id("agw.");
}
function generate_unique_deposit_id()
{
    return generate_unique_id("agd.");
}
function generate_unique_delivery_id()
{
    return generate_unique_id("agr.");
}
function generate_customer_unique_id()
{
    $prefix = "agc.";
    $characters = "23456789DGHMUVXZ";
    $random_string = "";
    $length = 6;
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $prefix . $random_string;
}
function generate_qrcode($unique_id, $folder_name)
{
    $upload_dir = wp_upload_dir();
    $qr_folder = $upload_dir["basedir"] . "/" . $folder_name . "/";
    if(!file_exists($qr_folder)) {
        mkdir($qr_folder, 493, true);
    }
    $qr_file_name = $unique_id . ".png";
    $qr_file_path = $qr_folder . $qr_file_name;
    $external_qr_url = "http://api.qrserver.com/v1/create-qr-code/?size=75x75&data=" . urlencode($unique_id);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $external_qr_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $qr_content = curl_exec($ch);
    if(curl_errno($ch)) {
        error_log("cURL error: " . curl_error($ch));
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    $result = file_put_contents($qr_file_path, $qr_content);
    if($result === false) {
        error_log("Failed to save QR code to file: " . $qr_file_path);
        return false;
    }
    $qrcode_image_url = $upload_dir["baseurl"] . "/" . $folder_name . "/" . $qr_file_name;
    return $qrcode_image_url;
}
function generate_shop_qrcode($unique_shop_id)
{
    return generate_qrcode($unique_shop_id, "almas-gold-shop");
}
function generate_sale_qrcode($unique_sale_id)
{
    return generate_qrcode($unique_sale_id, "almas-gold-sale");
}
function generate_delivery_qrcode($unique_delivery_id)
{
    return generate_qrcode($unique_delivery_id, "almas-gold-delivery");
}
function generate_deposit_qrcode($unique_deposit_id)
{
    return generate_qrcode($unique_deposit_id, "almas-gold-deposit");
}
function generate_recharge_qrcode($unique_recharge_id)
{
    return generate_qrcode($unique_recharge_id, "almas-gold-recharge");
}
function almasno_add_customer_row_on_login($user_login, $user)
{
    global $wpdb;
    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $user_id = $user->ID;
    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_name_customer . " WHERE user_id = %d", $user_id));
    if($exists == 0) {
        $user_name = $user->user_login;
        $unique_id = generate_customer_unique_id();
        $firstname = $user->user_firstname;
        $lastname = $user->user_lastname;
        $firstname = !empty($user->user_firstname) ? $user->user_firstname : "کاربر";
        $lastname = !empty($user->user_lastname) ? $user->user_lastname : "الماس گلد";
        $safe_balance = "0.006";
        $wallet_balance = "20000";
        $email = $user->user_email;
        $meta_keys = ["faname", "mobile", "national_code", "birthday", "state", "city", "address", "postal_code", "bank_name", "card_number", "iban_number"];
        $meta_values = [];
        foreach ($meta_keys as $meta_key) {
            $meta_values[$meta_key] = get_user_meta($user_id, $meta_key, true);
        }
        $faname = !empty($meta_values["faname"]) ? $meta_values["faname"] : "نام پدر";
        $card_number = !empty($meta_values["card_number"]) ? $meta_values["card_number"] : "1234123412341234";
        $mobile = !empty($meta_values["mobile"]) ? $meta_values["mobile"] : "";
        $national_code = !empty($meta_values["national_code"]) ? $meta_values["national_code"] : "";
        $birthday = !empty($meta_values["birthday"]) ? $meta_values["birthday"] : "";
        $state = !empty($meta_values["state"]) ? $meta_values["state"] : "استان";
        $city = !empty($meta_values["city"]) ? $meta_values["city"] : "شهر";
        $address = !empty($meta_values["address"]) ? $meta_values["address"] : "نشانی";
        $postal_code = !empty($meta_values["postal_code"]) ? $meta_values["postal_code"] : "";
        $bank_name = !empty($meta_values["bank_name"]) ? $meta_values["bank_name"] : "نام بانک";
        $iban_number = !empty($meta_values["iban_number"]) ? $meta_values["iban_number"] : "";
        $description = $user->description;
        $inserted = $wpdb->insert($table_name_customer, ["user_id" => $user_id, "user_name" => $user_name, "unique_id" => $unique_id, "firstname" => $firstname, "lastname" => $lastname, "safe_balance" => $safe_balance, "wallet_balance" => $wallet_balance, "email" => $email, "mobile" => $mobile, "national_code" => $national_code, "state" => $state, "city" => $city, "address" => $address, "postal_code" => $postal_code, "bank_name" => $bank_name, "card_number" => $card_number, "iban_number" => $iban_number, "description" => $description], ["%d", "%s", "%s", "%s", "%s", "%f", "%f", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s"]);
    }
}
function convNumsToPersian($string)
{
    $en_numbers = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ","];
    $fa_numbers = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "،"];
    return str_replace($en_numbers, $fa_numbers, $string);
}
function almas_gols_redirect_urls($key)
{
    $redirect_urls = [
        "shop_continue_redirect_url" => "shop-continue",
        "shop_gateway_redirect_url" => "shop-gateway",
        "shop_paywall_redirect_url" => "shop-paywall",
        "shop_bill_redirect_url" => "shop-bill",
        "sale_continue_redirect_url" => "sale-continue",
        "sale_paywall_redirect_url" => "sale-paywall",
        "sale_bill_redirect_url" => "sale-bill",
        "delivery_continue_redirect_url" => "delivery-request-continue",
        "delivery_gateway_redirect_url" => "delivery-request-gateway",
        "delivery_paywall_redirect_url" => "delivery-request-paywall",
        "delivery_bill_redirect_url" => "delivery-request-bill",
        "deposit_continue_redirect_url" => "deposit-request-continue",
        "deposit_paywall_redirect_url" => "deposit-request-paywall",
        "deposit_bill_redirect_url" => "deposit-request-bill",
        "recharge_continue_redirect_url" => "recharge-continue",
        "recharge_gateway_redirect_url" => "recharge-gateway",
        "recharge_bill_redirect_url" => "recharge-bill"
    ];
    if(array_key_exists($key, $redirect_urls)) {
        // چک کردن وجود "continue" در کلید
        if (strpos($key, 'continue') !== false) {
            return home_url('/' . $redirect_urls[$key]);
        } else {
            return $redirect_urls[$key]; // بازگرداندن مسیر نسبی
        }
    }
    return "";
}
function almas_gold_create_page()
{
    $pages = [["post_title" => "خرید", "post_content" => "[almas_gold_shop_form_sc]", "post_name" => "shop-gold"], ["post_title" => "ادامه خرید", "post_content" => "[almas_gold_shop_continue_sc]", "post_name" => "shop-continue"], ["post_title" => "پردازش اطلاعات پرداخت آنلاین خرید", "post_content" => "[almas_gold_shop_gateway_sc]", "post_name" => "shop-gateway"], ["post_title" => "پردازش اطلاعات پرداخت با کیف پول خرید", "post_content" => "[almas_gold_shop_paywall_sc]", "post_name" => "shop-paywall"], ["post_title" => "صورتحساب خرید", "post_content" => "[almas_gold_shop_bill_sc]", "post_name" => "shop-bill"], ["post_title" => "فروش", "post_content" => "[almas_gold_sale_form_sc]", "post_name" => "sale"], ["post_title" => "ادامه فروش", "post_content" => "[almas_gold_sale_continue_sc]", "post_name" => "sale-continue"], ["post_title" => "پردازش اطلاعات واریز به کیف پول فروش", "post_content" => "[almas_gold_sale_paywall_sc]", "post_name" => "sale-paywall"], ["post_title" => "صورتحساب فروش", "post_content" => "[almas_gold_sale_bill_sc]", "post_name" => "sale-bill"], ["post_title" => "شارژ کیف پول", "post_content" => "[almas_gold_recharge_form_sc]", "post_name" => "recharge-wallet"], ["post_title" => "ادامه شارژ کیف پول", "post_content" => "[almas_gold_recharge_continue_sc]", "post_name" => "recharge-continue"], ["post_title" => "پردازش اطلاعات پرداخت آنلاین شارژ کیف پول", "post_content" => "[almas_gold_recharge_gateway_sc]", "post_name" => "recharge-gateway"], ["post_title" => "صورتحساب شارژ کیف پول", "post_content" => "[almas_gold_recharge_bill_sc]", "post_name" => "recharge-bill"], ["post_title" => "درخواست واریز وجه", "post_content" => "[almas_gold_deposit_form_sc]", "post_name" => "deposit-request"], ["post_title" => "ادامه درخواست واریز وجه", "post_content" => "[almas_gold_deposit_continue_sc]", "post_name" => "deposit-request-continue"], ["post_title" => "پردازش اطلاعات پرداخت با کیف پول درخواست واریز وجه", "post_content" => "[almas_gold_deposit_paywall_sc]", "post_name" => "deposit-request-paywall"], ["post_title" => "صورتحساب درخواست واریز وجه", "post_content" => "[almas_gold_deposit_bill_sc]", "post_name" => "deposit-request-bill"], ["post_title" => "درخواست دریافت طلا", "post_content" => "[almas_gold_delivery_form_sc]", "post_name" => "delivery-request"], ["post_title" => "ادامه درخواست دریافت طلا", "post_content" => "[almas_gold_delivery_continue_sc]", "post_name" => "delivery-request-continue"], ["post_title" => "پردازش اطلاعات پرداخت آنلاین درخواست دریافت طلا", "post_content" => "[almas_gold_delivery_gateway_sc]", "post_name" => "delivery-request-gateway"], ["post_title" => "پردازش اطلاعات پرداخت با کیف پول درخواست دریافت طلا", "post_content" => "[almas_gold_delivery_paywall_sc]", "post_name" => "delivery-request-paywall"], ["post_title" => "صورتحساب درخواست دریافت طلا", "post_content" => "[almas_gold_delivery_bill_sc]", "post_name" => "delivery-request-bill"], ["post_title" => "پروفایل", "post_content" => "[almas_gold_customer_profile_sc]", "post_name" => "customer-profile"], ["post_title" => "مشتری- خریدها", "post_content" => "[almas_gold_customer_shops_sc]", "post_name" => "customer-shops"], ["post_title" => "مشتری- فروش‌ها", "post_content" => "[almas_gold_customer_sales_sc]", "post_name" => "customer-sales"], ["post_title" => "مشتری- دریافت‌ها", "post_content" => "[almas_gold_customer_receive_sc]", "post_name" => "customer-receives"], ["post_title" => "مشتری- درخواست‌های واریز وجه", "post_content" => "[almas_gold_customer_deposit_sc]", "post_name" => "customer-deposits"], ["post_title" => "مشتری- شارژهای کیف پول", "post_content" => "[almas_gold_customer_recharge_sc]", "post_name" => "customer-recharges"]];
    if(current_user_can("manage_options")) {
        $current_user_id = get_current_user_id();
        foreach ($pages as $page) {
            if(!get_page_by_path($page["post_name"])) {
                wp_insert_post(["post_title" => $page["post_title"], "post_content" => $page["post_content"], "post_status" => "publish", "post_type" => "page", "post_name" => $page["post_name"], "post_author" => $current_user_id, "post_date" => current_time("mysql", true), "post_modified" => current_time("mysql", true)]);
            }
        }
    } else {
        error_log("کاربر جاری دسترسی کافی برای ایجاد صفحات را ندارد.");
    }
    almas_gold_create_menu();
    almas_gold_create_customer_menu();
}
function almas_gold_create_menu()
{
    $menu_name = "منوی الماس گلد";
    $menu_exists = wp_get_nav_menu_object($menu_name);
    if(!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        $pages = ["shop-gold", "sale", "delivery-request", "deposit-request", "recharge-wallet"];
        foreach ($pages as $post_name) {
            $page = get_page_by_path($post_name);
            if($page) {
                $page_id = $page->ID;
                wp_update_nav_menu_item($menu_id, 0, ["menu-item-title" => get_the_title($page_id), "menu-item-classes" => "menu-item", "menu-item-url" => get_permalink($page_id), "menu-item-status" => "publish"]);
            }
        }
    } else {
        $menu_id = $menu_exists->term_id;
        foreach ($pages as $post_name) {
            $page = get_page_by_path($post_name);
            if($page) {
                $page_id = $page->ID;
                $menu_items = wp_get_nav_menu_items($menu_id);
                $exists = false;
                foreach ($menu_items as $item) {
                    if($item->object_id == $page_id) {
                        $exists = true;
                        if(!$exists) {
                            wp_update_nav_menu_item($menu_id, 0, ["menu-item-title" => get_the_title($page_id), "menu-item-classes" => "menu-item", "menu-item-url" => get_permalink($page_id), "menu-item-status" => "publish"]);
                        }
                    }
                }
            }
        }
    }
}
function almas_gold_create_customer_menu()
{
    $menu_name = "منوی مشتری الماس گلد";
    $menu_exists = wp_get_nav_menu_object($menu_name);
    if(!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        $pages = ["customer-profile", "customer-shops", "customer-sales", "customer-receives", "customer-deposits", "customer-recharges"];
        foreach ($pages as $post_name) {
            $page = get_page_by_path($post_name);
            if($page) {
                $page_id = $page->ID;
                wp_update_nav_menu_item($menu_id, 0, ["menu-item-title" => get_the_title($page_id), "menu-item-classes" => "menu-item", "menu-item-url" => get_permalink($page_id), "menu-item-status" => "publish"]);
            }
        }
    } else {
        $menu_id = $menu_exists->term_id;
        foreach ($pages as $post_name) {
            $page = get_page_by_path($post_name);
            if($page) {
                $page_id = $page->ID;
                $menu_items = wp_get_nav_menu_items($menu_id);
                $exists = false;
                foreach ($menu_items as $item) {
                    if($item->object_id == $page_id) {
                        $exists = true;
                        if(!$exists) {
                            wp_update_nav_menu_item($menu_id, 0, ["menu-item-title" => get_the_title($page_id), "menu-item-classes" => "menu-item", "menu-item-url" => get_permalink($page_id), "menu-item-status" => "publish"]);
                        }
                    }
                }
            }
        }
    }
}
/*add_action('mnsfpt_section_after_inquiried', function ($user, $section) {
    global $wpdb;
    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $user_id = $user->get_id();

	if ($section->get_key() === 'mnsfpt_section_9') {

		$mobile = $user->get_field_value('mnsfpt_field_30');
		$wpdb->update($table_name_customer, ["mobile" => $mobile], ["user_id" => $user_id],["%s"], ["%d"]);

	} elseif ($section->get_key() === 'mnsfpt_section_6') {

		$firstname = $user->get_field_value('mnsfpt_field_20');
		$lastname = $user->get_field_value('mnsfpt_field_21');
		$faname = $user->get_field_value('mnsfpt_field_22');
		$birthday = $user->get_field_value('mnsfpt_field_25');
		$national_code = $user->get_field_value('mnsfpt_field_26');
		$wpdb->update($table_name_customer, ["firstname" => $firstname, "lastname" => $lastname, "faname" => $faname, "birthday" => $birthday, "national_code" => $national_code], ["user_id" => $user_id],["%s", "%s", "%s", "%s", "%s"], ["%d"]);

	} elseif ($section->get_key() === 'mnsfpt_section_7') {
	    
		$card_number = $user->get_field_value('mnsfpt_field_31');
		$card_inquiry = $user->get_inquiry('zibal', 'cardToIban', 0);
		$iban = $card_inquiry['response']['IBAN'];
		$bank_name = $card_inquiry['response']['bankName'];
		$wpdb->update($table_name_customer, ["bank_name" => $bank_name, "card_number" => $card_number, "iban_number" => $iban], ["user_id" => $user_id],["%s", "%s", "%s"], ["%d"]);
	
	}
}, 10, 2);*/

add_action('mnsfpt_user_field_changed', function ($user, $field_key, $row, $value) {
    global $wpdb;
    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $user_id = $user->get_id();
    $fields_map = [
        'mnsfpt_field_1' => ['meta_key' => 'mobile', 'db_column' => 'mobile'],
        'mnsfpt_field_2' => ['meta_key' => 'first_name', 'db_column' => 'firstname'],
        'mnsfpt_field_3' => ['meta_key' => 'last_name', 'db_column' => 'lastname'],
        'mnsfpt_field_4' => ['meta_key' => 'faname', 'db_column' => 'faname'],
        'mnsfpt_field_5' => ['meta_key' => 'birthday', 'db_column' => 'birthday'],
        'mnsfpt_field_6' => ['meta_key' => 'national_code', 'db_column' => 'national_code'],
        'mnsfpt_field_7' => ['meta_key' => 'card_number', 'db_column' => 'card_number'],
    ];
    if (isset($fields_map[$field_key])) {
        $meta_key = $fields_map[$field_key]['meta_key'];
        $db_column = $fields_map[$field_key]['db_column'];
        $sanitized_value = sanitize_text_field($value); // تمیز کردن ورودی

        update_user_meta($user_id, $meta_key, $sanitized_value);

        $wpdb->update(
            $table_name_customer, 
            [$db_column => $sanitized_value], // مقادیری که باید آپدیت شوند
            ["user_id" => $user_id], // شرط برای آپدیت
            ["%s"], // نوع داده‌های آپدیت شده (در اینجا یک رشته است)
            ["%d"]  // نوع داده‌های شرط (شناسه کاربر)
        );
    }

}, 10, 4);
add_action('mnswmc_currency_rate_updated', function ($currency, $rate) {
    global $wpdb;
    $table_name_core = $wpdb->prefix . "almas_gold_core"; // نام جدول
    
    // دریافت شناسه ارز
    if ($currency->get_id() == 15) {
        // تنظیم نرخ ارز
        $sanitized_rate = number_format((float)$rate, 0, '.', ''); // دو رقم اعشار برای نرخ طلا

        // شروع ترا Transaction برای بهبود عملکرد
        $wpdb->query('BEGIN;');

        // آپدیت نرخ طلا
        $wpdb->update(
            $table_name_core,
            ["gold_price" => $sanitized_rate],
            ["id" => 1],
            ["%f"],
            ["%d"]
        );

        // دریافت مقادیر سایر ستون‌ها
        $current_record = $wpdb->get_row("SELECT shop_highest_price_limit, shop_tax FROM $table_name_core WHERE id = 1");

        if ($current_record) {
            $shop_highest_price_limit = $current_record->shop_highest_price_limit;
            $shop_tax = $current_record->shop_tax;
            $new_shop_highest_limit = $shop_highest_price_limit / ($sanitized_rate * ($shop_tax / 100 + 1));
            
            // به‌روزرسانی shop_highest_limit
            $wpdb->update(
                $table_name_core,
                ["shop_highest_limit" => number_format($new_shop_highest_limit, 2, '.', '')],
                ["id" => 1],
                ["%f"],
                ["%d"]
            );
        }

        // پایان ترا Transaction
        $wpdb->query('COMMIT;');
    }
}, 10, 2);

function get_customer_gold_balance_chart_data($user_id) {
    global $wpdb;
    $table_shop = $wpdb->prefix . "almas_gold_shop";
    $table_sale = $wpdb->prefix . "almas_gold_sale";
    $shop_data = $wpdb->get_results($wpdb->prepare(
        "SELECT shop_date AS date, gold_weight AS weight 
        FROM " . $table_shop . " 
        WHERE user_id = %d AND transaction_processed = 1", 
        $user_id
    ));
    $sale_data = $wpdb->get_results($wpdb->prepare(
        "SELECT sale_date AS date, -gold_weight AS weight 
        FROM " . $table_sale . " 
        WHERE user_id = %d AND transaction_processed = 1", 
        $user_id
    ));
    $shop_count = count($shop_data);
    $sale_count = count($sale_data);
    if ($shop_count === 0 && $sale_count === 0) {
        return [
            'chart_data' => []
        ];
    }
    $transactions = array_merge($shop_data, $sale_data);
    usort($transactions, function($a, $b) {
        return strtotime($a->date) - strtotime($b->date);
    });
    $cumulative_weight = 0;
    $chart_data = [];
    foreach ($transactions as $transaction) {
        $cumulative_weight += $transaction->weight;
        $date = date('Y-m-d', strtotime($transaction->date));
        $chart_data[$date] = round($cumulative_weight, 3);
    }
    $today = date('Y-m-d');
    $keys = array_keys($chart_data);
    if (end($keys) < $today) {
        $chart_data[$today] = end($chart_data);
    }
    return [
        'chart_data' => $chart_data
    ];
}

function get_customer_delivery_balance_chart_data($user_id) {
    global $wpdb;
    $table_delivery = $wpdb->prefix . "almas_gold_delivery";
    $delivery_data = $wpdb->get_results($wpdb->prepare("SELECT transaction_date AS date, delivery_gold_weight AS weight FROM " . $table_delivery . " WHERE user_id = %d AND delivery_status = %s", $user_id, 'finished'));
    usort($delivery_data, function($a1, $b1) {return strtotime($a1->date) - strtotime($b1->date);});
    $delivery_count = count($delivery_data);
    if ($delivery_count === 0) {
        
            $delivery_chart_data = [];
        
        return $delivery_chart_data;
    }
    $cumdelivery_weight = 0;
    $delivery_chart_data = [];
    foreach ($delivery_data as $delivery_transaction) {
        $cumdelivery_weight += $delivery_transaction->weight;
        $delivery_date = date('Y-m-d', strtotime($delivery_transaction->date));
        $delivery_chart_data[$delivery_date] = round($cumdelivery_weight, 3);
    }
    $delivery_today = date('Y-m-d');
    $delivery_keys = array_keys($delivery_chart_data);
    if (end($delivery_keys) < $delivery_today) {
        $delivery_chart_data[$delivery_today] = end($delivery_chart_data);
    }
    return $delivery_chart_data;
}

function get_user_profile_completion_percentage($user_id) {
    $levels_meta_keys = [
        '_mnsfpt_user_level_status_mnsfpt_form_1_initial',
        '_mnsfpt_user_level_status_mnsfpt_level_2',
        '_mnsfpt_user_level_status_mnsfpt_level_3',
        '_mnsfpt_user_level_status_mnsfpt_level_4',
    ];

    $completed_percentage = 0;

    foreach ($levels_meta_keys as $meta_key) {
        $meta_value = get_user_meta($user_id, $meta_key, true);
        if ($meta_value === 'verified') {
            $completed_percentage += 25;
        }
    }

    return $completed_percentage;
}
function custom_login_url_redirect( $login_url, $redirect, $force_reauth ) {
    // آدرس لاگین سفارشی
    $custom_login_url = 'https://almas.gold/login';

    // اگر نیاز به انتقال به آدرسی خاص پس از لاگین باشد
    if ( ! empty( $redirect ) ) {
        $custom_login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $custom_login_url );
    }

    return $custom_login_url;
}
add_filter( 'login_url', 'custom_login_url_redirect', 10, 3 );
?>