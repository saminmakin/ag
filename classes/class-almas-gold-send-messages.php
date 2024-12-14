<?php

defined("ABSPATH") or exit();
require_once ABSPATH . "wp-admin/includes/upgrade.php";
function almas_gold_send_shop_sms()
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    include_once plugin_dir_path(__FILE__) . "class-almas-gold-sms-controller.php";
    global $wpdb;
    
    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile, firstname, lastname FROM " . $table_name_customer . " WHERE user_id = %d", $user_id));
    $mobile = $customer_data->mobile;
    $firstname = $customer_data->firstname;
    $lastname = $customer_data->lastname;
    $core_data = $wpdb->get_row("SELECT admin_phone FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
    $admin_phones = explode(',', $core_data->admin_phone);

    $table_name_shop = $wpdb->prefix . "almas_gold_shop";
    $shop_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_shop . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    $gold_weight_display = convNumsToPersian(rtrim(rtrim(number_format($shop_data->gold_weight, 4, ".", ""), "0"), "."));
    $shop_id_display = convNumsToPersian($shop_data->shop_id);
    $final_price_display = convNumsToPersian(number_format($shop_data->final_price, 0, ".", ","));
    $shop_date_display = jdate("d F Y | H:i:s", strtotime($shop_data->shop_date . ' +03:30'));
    
    if ($shop_data->gold_weight < 1) {
    $gold_weight_display = convNumsToPersian((string) round($shop_data->gold_weight * 1000)); // ضرب در 1000 و تبدیل به عدد کامل
    $unit = "سوت";
} else {
    $unit = "گرم";
}
    
    $api = new ConnectToApi($apiMainurl, $username, $password);
    $text_user = "$firstname;$shop_id_display;$gold_weight_display $unit;$final_price_display;$shop_date_display";
    $req_user = [
        "username" => $username,
        "password" => $password,
        "text" => $text_user,
        "to" => $mobile,
        "bodyId" => 266608
    ];
    $response_user = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_user);
    if ($response_user) {
        echo "<p style=\"color: green; font-weight: 700\">پیامک خرید طلا با موفقیت به شماره $mobile ارسال شد.</p>";
    } else {
        echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک خرید طلا به شماره $mobile ارسال نشد.</p>";
    }
    
    $text_admin = "$gold_weight_display $unit;$final_price_display;$shop_date_display";
    foreach ($admin_phones as $admin_phone) {
        $req_admin = [
            "username" => $username,
            "password" => $password,
            "text" => $text_admin,
            "to" => trim($admin_phone),
            "bodyId" => 266631
        ];
        $res_admin = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_admin);
        if ($res_admin) {
            echo "<p style=\"color: green; font-weight: 700\">پیامک با موفقیت به شماره " . $admin_phone . " ارسال شد.</p>";
        } else {
            echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک به شماره " . $admin_phone . " ارسال نشد.</p>";
        }
    }
}
function almas_gold_send_sale_sms()
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    include_once plugin_dir_path(__FILE__) . "class-almas-gold-sms-controller.php";
    global $wpdb;
    
    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile, firstname, lastname FROM " . $table_name_customer . " WHERE user_id = %d", $user_id));
    $mobile = $customer_data->mobile;
    $firstname = $customer_data->firstname;
    $lastname = $customer_data->lastname;
    $core_data = $wpdb->get_row("SELECT admin_phone FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
    $admin_phones = explode(',', $core_data->admin_phone);

    $table_name_sale = $wpdb->prefix . "almas_gold_sale";
    $sale_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_sale . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    $gold_weight_display = convNumsToPersian(rtrim(rtrim(number_format($sale_data->gold_weight, 4, ".", ""), "0"), "."));
    $sale_id_display = convNumsToPersian($sale_data->sale_id);
    $final_price_display = convNumsToPersian(number_format($sale_data->final_price, 0, ".", ","));
    $sale_date_display = jdate("d F Y | H:i:s", strtotime($sale_data->sale_date . ' +03:30'));
    
    if($sale_data->gold_weight < 1) {
        $gold_weight_display = convNumsToPersian((string) round($sale_data->gold_weight * 1000));
        $unit = "سوت";
    } else {
        $unit = "گرم";
    }
    
    $api = new ConnectToApi($apiMainurl, $username, $password);
    $text_user = "$firstname;$sale_id_display;$gold_weight_display $unit;$final_price_display;$sale_date_display";
    $req_user = [
        "username" => $username,
        "password" => $password,
        "text" => $text_user,
        "to" => $mobile,
        "bodyId" => 266610
    ];
    $response_user = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_user);
    if ($response_user) {
        echo "<p style=\"color: green; font-weight: 700\">پیامک خرید طلا با موفقیت به شماره $mobile ارسال شد.</p>";
    } else {
        echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک خرید طلا به شماره $mobile ارسال نشد.</p>";
    }

    $text_admin = "$gold_weight_display $unit;$final_price_display;$sale_date_display";
    foreach ($admin_phones as $admin_phone) {
        $req_admin = [
            "username" => $username,
            "password" => $password,
            "text" => $text_admin,
            "to" => trim($admin_phone),
            "bodyId" => 266632
        ];
        $res_admin = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_admin);
        if ($res_admin) {
            echo "<p style=\"color: green; font-weight: 700\">پیامک با موفقیت به شماره " . $admin_phone . " ارسال شد.</p>";
        } else {
            echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک به شماره " . $admin_phone . " ارسال نشد.</p>";
        }
    }
}
function almas_gold_send_recharge_sms()
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    include_once plugin_dir_path(__FILE__) . "class-almas-gold-sms-controller.php";
    global $wpdb;

    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile, firstname, lastname FROM " . $table_name_customer . " WHERE user_id = %d", $user_id));
    $mobile = $customer_data->mobile;
    $firstname = $customer_data->firstname;
    $lastname = $customer_data->lastname;
    $core_data = $wpdb->get_row("SELECT admin_phone FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
    $admin_phones = explode(',', $core_data->admin_phone);

    $table_name_recharge = $wpdb->prefix . "almas_gold_recharge";
    $recharge_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_recharge . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    $recharge_id_display = convNumsToPersian($recharge_data->recharge_id);
    $initial_recharge_amount_display = convNumsToPersian(number_format($recharge_data->initial_recharge_amount, 0, ".", ","));
    $recharge_date_display = jdate("d F Y | H:i:s", strtotime($recharge_data->recharge_date . ' +03:30'));

    $api = new ConnectToApi($apiMainurl, $username, $password);
    $text_user = "$firstname;$recharge_id_display;$initial_recharge_amount_display;$recharge_date_display";
    $req_user = [
        "username" => $username,
        "password" => $password,
        "text" => $text_user,
        "to" => $mobile,
        "bodyId" => 266612
    ];
    $response_user = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_user);
    if ($response_user) {
        echo "<p style=\"color: green; font-weight: 700\">پیامک خرید طلا با موفقیت به شماره $mobile ارسال شد.</p>";
    } else {
        echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک خرید طلا به شماره $mobile ارسال نشد.</p>";
    }

    $text_admin = "$initial_recharge_amount_display;$recharge_date_display";
    foreach ($admin_phones as $admin_phone) {
        $req_admin = [
            "username" => $username,
            "password" => $password,
            "text" => $text_admin,
            "to" => trim($admin_phone),
            "bodyId" => 266634
        ];
        $res_admin = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_admin);
        if ($res_admin) {
            echo "<p style=\"color: green; font-weight: 700\">پیامک با موفقیت به شماره " . $admin_phone . " ارسال شد.</p>";
        } else {
            echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک به شماره " . $admin_phone . " ارسال نشد.</p>";
        }
    }
}
function almas_gold_send_deposit_sms()
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    include_once plugin_dir_path(__FILE__) . "class-almas-gold-sms-controller.php";
    global $wpdb;

    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile, firstname, lastname FROM " . $table_name_customer . " WHERE user_id = %d", $user_id));
    $mobile = $customer_data->mobile;
    $firstname = $customer_data->firstname;
    $lastname = $customer_data->lastname;
    $core_data = $wpdb->get_row("SELECT admin_phone FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
    $admin_phones = explode(',', $core_data->admin_phone);

    $table_name_deposit = $wpdb->prefix . "almas_gold_deposit";
    $deposit_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_deposit . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    $deposit_id_display = convNumsToPersian($deposit_data->deposit_id);
    $final_deposit_amount_display = convNumsToPersian(number_format($deposit_data->final_deposit_amount, 0, ".", ","));
    $deposit_date_display = jdate("d F Y | H:i:s", strtotime($deposit_data->deposit_date . ' +03:30'));

    $api = new ConnectToApi($apiMainurl, $username, $password);
    $text_user = "$firstname;$deposit_id_display;$final_deposit_amount_display;$deposit_date_display";
    $req_user = [
        "username" => $username,
        "password" => $password,
        "text" => $text_user,
        "to" => $mobile,
        "bodyId" => 266613
    ];
    $response_user = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_user);
    if ($response_user) {
        echo "<p style=\"color: green; font-weight: 700\">پیامک خرید طلا با موفقیت به شماره $mobile ارسال شد.</p>";
    } else {
        echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک خرید طلا به شماره $mobile ارسال نشد.</p>";
    }

    $text_admin = "$final_deposit_amount_display;$deposit_date_display";
    foreach ($admin_phones as $admin_phone) {
        $req_admin = [
            "username" => $username,
            "password" => $password,
            "text" => $text_admin,
            "to" => trim($admin_phone),
            "bodyId" => 266635
        ];
        $res_admin = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_admin);
        if ($res_admin) {
            echo "<p style=\"color: green; font-weight: 700\">پیامک با موفقیت به شماره " . $admin_phone . " ارسال شد.</p>";
        } else {
            echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک به شماره " . $admin_phone . " ارسال نشد.</p>";
        }
    }
}
function almas_gold_send_delivery_wait_for_shop_sms()
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    include_once plugin_dir_path(__FILE__) . "class-almas-gold-sms-controller.php";
    global $wpdb;

    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile, firstname, lastname FROM " . $table_name_customer . " WHERE user_id = %d", $user_id));
    $mobile = $customer_data->mobile;
    $firstname = $customer_data->firstname;
    $lastname = $customer_data->lastname;
    $core_data = $wpdb->get_row("SELECT admin_phone FROM " . $wpdb->prefix . "almas_gold_core ORDER BY id DESC LIMIT 1");
    $admin_phones = explode(',', $core_data->admin_phone);

    $table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
    $delivery_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_delivery . " WHERE user_id = %d ORDER BY id DESC LIMIT 1", $user_id));
    $gold_weight_display = convNumsToPersian(rtrim(rtrim(number_format($delivery_data->gold_weight, 4, ".", ""), "0"), "."));
    $gold_type_display = $delivery_data->gold_type;
    $delivery_id_display = convNumsToPersian($delivery_data->delivery_id);
    $final_price_display = convNumsToPersian(number_format($delivery_data->final_price, 0, ".", ","));
    $delivery_date_display = jdate("d F Y - H:i:s", strtotime($delivery_data->delivery_request_date . ' +03:30'));
    $gold_type_translations = ["broken" => "شکسته", "without_fee" => "بدون اجرت", "low_fee" => "کم اجرت", "sequins" => "پولک", "bullion" => "شمش"];
    if(array_key_exists($gold_type_display, $gold_type_translations)) {
        $gold_type_display = $gold_type_translations[$gold_type_display];
    }
    if($delivery_data->gold_weight < 1) {
        $gold_weight_display = convNumsToPersian((string) round($delivery_data->gold_weight * 1000));
        $unit = "سوت";
    } else {
        $unit = "گرم";
    }

    $api = new ConnectToApi($apiMainurl, $username, $password);
    $text_user = "$firstname;$delivery_id_display;$gold_weight_display $unit;$gold_type_display;$final_price_display;$delivery_date_display";
    $req_user = [
        "username" => $username,
        "password" => $password,
        "text" => $text_user,
        "to" => $mobile,
        "bodyId" => 266614
    ];
    $response_user = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_user);
    if ($response_user) {
        echo "<p style=\"color: green; font-weight: 700\">پیامک خرید طلا با موفقیت به شماره $mobile ارسال شد.</p>";
    } else {
        echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک خرید طلا به شماره $mobile ارسال نشد.</p>";
    }

    $text_admin = "$gold_weight_display $unit;$gold_type_display;$delivery_date_display";
    foreach ($admin_phones as $admin_phone) {
        $req_admin = [
            "username" => $username,
            "password" => $password,
            "text" => $text_admin,
            "to" => trim($admin_phone),
            "bodyId" => 266636
        ];
        $res_admin = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_admin);
        if ($res_admin) {
            echo "<p style=\"color: green; font-weight: 700\">پیامک با موفقیت به شماره " . $admin_phone . " ارسال شد.</p>";
        } else {
            echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک به شماره " . $admin_phone . " ارسال نشد.</p>";
        }
    }
}
function almas_gold_send_delivery_wait_for_customer_sms($delivery_id_post)
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    include_once plugin_dir_path(__FILE__) . "class-almas-gold-sms-controller.php";
    global $wpdb;

    $table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
    $delivery_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_delivery WHERE delivery_id = %d", $delivery_id_post));
    $customer_user_id = $delivery_data->user_id;
    
    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile, firstname, lastname FROM " . $table_name_customer . " WHERE user_id = %d", $customer_user_id));
    $mobile = $customer_data->mobile;
    $firstname = $customer_data->firstname;
    $lastname = $customer_data->lastname;
    
    $gold_weight_display = convNumsToPersian(rtrim(rtrim(number_format($delivery_data->gold_weight, 4, ".", ""), "0"), "."));
    $gold_type_display = $delivery_data->gold_type;
    $delivery_id_display = convNumsToPersian($delivery_id_post);
    $final_price_display = convNumsToPersian(number_format($delivery_data->final_price, 0, ".", ","));
    $delivery_date_display = jdate("d F Y - H:i:s", strtotime($delivery_data->delivery_request_date . ' +03:30'));
    $gold_type_translations = ["broken" => "شکسته", "without_fee" => "بدون اجرت", "low_fee" => "کم اجرت", "sequins" => "پولک", "bullion" => "شمش"];
    if(array_key_exists($gold_type_display, $gold_type_translations)) {
        $gold_type_display = $gold_type_translations[$gold_type_display];
    }
    if($delivery_data->gold_weight < 1) {
        $gold_weight_display = convNumsToPersian((string) round($delivery_data->gold_weight * 1000));
        $unit = "سوت";
    } else {
        $unit = "گرم";
    }
    $delivery_id_x = (string) $delivery_id_display;

    $api = new ConnectToApi($apiMainurl, $username, $password);
    $text_user = "$firstname;$delivery_id_x";
    $req_user = [
        "username" => $username,
        "password" => $password,
        "text" => $text_user,
        "to" => $mobile,
        "bodyId" => 266615
    ];
    $response_user = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_user);
    if ($response_user) {
        echo "<p style=\"color: green; font-weight: 700\">پیامک خرید طلا با موفقیت به شماره $mobile ارسال شد.</p>";
    } else {
        echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک خرید طلا به شماره $mobile ارسال نشد.</p>";
    }
}
function almas_gold_send_delivery_delivered_sms($order_id)
{
    $current_user = wp_get_current_user();
    $user_id = get_current_user_id();
    include_once plugin_dir_path(__FILE__) . "class-almas-gold-sms-controller.php";
    global $wpdb;
    
    $table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
    $delivery_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_delivery WHERE delivery_id = %d", $order_id));
    $customer_user_id = $delivery_data->user_id;

    $table_name_customer = $wpdb->prefix . "almas_gold_customers";
    $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile, firstname, lastname FROM " . $table_name_customer . " WHERE user_id = %d", $customer_user_id));
    $mobile = $customer_data->mobile;
    $firstname = $customer_data->firstname;
    $lastname = $customer_data->lastname;

    $gold_weight_display = convNumsToPersian(rtrim(rtrim(number_format($delivery_data->delivery_gold_weight, 4, ".", ""), "0"), "."));
    $gold_type_display = $delivery_data->delivery_gold_type;
    $delivery_id_display = convNumsToPersian($delivery_data->delivery_id);
    $final_price_display = convNumsToPersian(number_format($delivery_data->delivery_total_gold_price, 0, ".", ","));
    $delivery_date_display = jdate("d F Y - H:i:s", strtotime($delivery_data->delivery_request_date . ' +03:30'));
    $gold_type_translations = ["broken" => "شکسته", "without_fee" => "بدون اجرت", "low_fee" => "کم اجرت", "sequins" => "پولک", "bullion" => "شمش"];
    if(array_key_exists($gold_type_display, $gold_type_translations)) {
        $gold_type_display = $gold_type_translations[$gold_type_display];
    }
    if($delivery_data->delivery_gold_weight < 1) {
        $gold_weight_display = convNumsToPersian((string) round($delivery_data->delivery_gold_weight * 1000));
        $unit = "سوت";
    } else {
        $unit = "گرم";
    }

    $api = new ConnectToApi($apiMainurl, $username, $password);
    $text_user = "$firstname;$delivery_id_display;$gold_weight_display $unit;$gold_type_display;$final_price_display;$delivery_date_display";
    $req_user = [
        "username" => $username,
        "password" => $password,
        "text" => $text_user,
        "to" => $mobile,
        "bodyId" => 266618
    ];
    $response_user = $api->Exec("/post/Send.asmx/SendByBaseNumber2", $req_user);
    if ($response_user) {
        echo "<p style=\"color: green; font-weight: 700\">پیامک خرید طلا با موفقیت به شماره $mobile ارسال شد.</p>";
    } else {
        echo "<p style=\"color: red; font-weight: 700\">ناموفق! پیامک خرید طلا به شماره $mobile ارسال نشد.</p>";
    }
}
?>