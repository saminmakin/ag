<?php

defined("ABSPATH") or exit();
global $wpdb;
$charset_collate = "DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
$table_name_core = $wpdb->prefix . "almas_gold_core";
$sql_core_table = "CREATE TABLE IF NOT EXISTS " . $table_name_core . " (\r\n        id INT NOT NULL AUTO_INCREMENT,\r\n        gold_price DECIMAL(14) NOT NULL,\r\n        gold_unit_to_customer TINYINT(1) NOT NULL DEFAULT '1', \r\n        gold_unit_to_bills TINYINT(1) NOT NULL DEFAULT '1', \r\n        lists_date_display TINYINT(1) NOT NULL DEFAULT '1', \r\n        broken_gold_fee DECIMAL(14,3) NOT NULL,\r\n        without_fee_gold_fee DECIMAL(14,3) NOT NULL,\r\n        low_fee_gold_fee DECIMAL(14,3) NOT NULL,\r\n        sequins_gold_fee DECIMAL(14,3) NOT NULL,\r\n        bullion_gold_fee DECIMAL(14,3) NOT NULL,\r\n        bullion_gold_limit DECIMAL(6) NOT NULL,\r\n        shop_lowest_limit DECIMAL(14,3) NOT NULL,\r\n        shop_highest_price_limit DECIMAL(14,3) NOT NULL,\r\n        shop_highest_limit DECIMAL(14,3) NOT NULL,\r\n        shop_tax DECIMAL(14,3) NOT NULL,\r\n        sale_lowest_limit DECIMAL(14,3) NOT NULL,\r\n        sale_highest_limit DECIMAL(14,3) NOT NULL,\r\n        sale_tax DECIMAL(14,3) NOT NULL,\r\n        deposit_fee DECIMAL(14) NOT NULL,\r\n        deposit_lowest_limit DECIMAL(14) NOT NULL,\r\n        deposit_highest_limit DECIMAL(14) NOT NULL,\r\n        delivery_lowest_limit DECIMAL(14,3) NOT NULL,\r\n        delivery_highest_limit DECIMAL(14,3) NOT NULL,\r\n        recharge_lowest_limit DECIMAL(14) NOT NULL,\r\n        recharge_highest_limit DECIMAL(14) NOT NULL,\r\n        recharge_fee DECIMAL(14) NOT NULL,\r\n        admin_phone TEXT NOT NULL,\r\n        PRIMARY KEY (id)\r\n    ) " . $charset_collate . ";";
require_once ABSPATH . "wp-admin/includes/upgrade.php";
dbDelta($sql_core_table);
$core_data = $wpdb->get_row("SELECT * FROM " . $table_name_core . " WHERE id = 1");
if(!$core_data) {
    $core_default_values = ["gold_price" => "3475500", "gold_unit_to_customer" => 1, "gold_unit_to_bills" => 1, "lists_date_display" => 0, "broken_gold_fee" => "1.0", "without_fee_gold_fee" => "7.0", "low_fee_gold_fee" => "9.0", "sequins_gold_fee" => "3.0", "bullion_gold_fee" => "3.0", "bullion_gold_limit" => "20", "shop_lowest_limit" => "0.001", "shop_highest_price_limit" => "200000000", "shop_highest_limit" => "50.5", "shop_tax" => "0.5", "sale_lowest_limit" => "0.001", "sale_highest_limit" => "200", "sale_tax" => "0.5", "deposit_fee" => "10000", "deposit_lowest_limit" => "100000", "deposit_highest_limit" => "99900000", "delivery_lowest_limit" => "1", "delivery_highest_limit" => "200", "recharge_lowest_limit" => "50000", "recharge_highest_limit" => "99900000", "recharge_fee" => "3000", "admin_phone" => "09129626682"];
    $wpdb->insert($table_name_core, $core_default_values);
}
$table_name_shop = $wpdb->prefix . "almas_gold_shop";
$sql_shop_table = "CREATE TABLE IF NOT EXISTS " . $table_name_shop . " (\r\n        id INT NOT NULL AUTO_INCREMENT,\r\n        unique_shop_id VARCHAR(30) NOT NULL,\r\n        shop_id INT NOT NULL,\r\n        unique_customer_id VARCHAR(10) NOT NULL,\r\n        user_id INT NOT NULL,\r\n        shop_date DATETIME NOT NULL,\r\n        user_name VARCHAR(50) NOT NULL,\r\n        firstname VARCHAR(30) NOT NULL,\r\n        lastname VARCHAR(30) NOT NULL,\r\n        gold_price DECIMAL(14) NOT NULL,\r\n        initial_price DECIMAL(14) NOT NULL,\r\n        gold_weight DECIMAL(14,3) NOT NULL,\r\n        initial_final_price DECIMAL(14) NOT NULL,\r\n        price_payed_by_wallet DECIMAL(14) NOT NULL,\r\n        price_payed_online DECIMAL(14) NOT NULL,\r\n        final_price DECIMAL(14) NOT NULL,\r\n        total_shop_tax DECIMAL(14) NOT NULL,\r\n        description VARCHAR(350),\r\n        transaction_id VARCHAR(30) NOT NULL,\r\n        payed_online_id VARCHAR(1) NOT NULL,\r\n        authority VARCHAR(36) NOT NULL,\r\n        transaction_date DATETIME NOT NULL,\r\n        transaction_status VARCHAR(20) NOT NULL,\r\n        transaction_processed VARCHAR(2) NOT NULL,\r\n        qrcode_image_url VARCHAR(500) NOT NULL,\r\n        PRIMARY KEY (id)\r\n    ) " . $charset_collate . ";";
require_once ABSPATH . "wp-admin/includes/upgrade.php";
dbDelta($sql_shop_table);
$table_name_sale = $wpdb->prefix . "almas_gold_sale";
$sql_sale_table = "CREATE TABLE IF NOT EXISTS " . $table_name_sale . " (\r\n        id INT NOT NULL AUTO_INCREMENT,\r\n        unique_sale_id VARCHAR(30) NOT NULL,\r\n        sale_id INT NOT NULL,\r\n        unique_customer_id VARCHAR(10) NOT NULL,\r\n        user_id INT NOT NULL,\r\n        sale_date DATETIME NOT NULL,\r\n        user_name VARCHAR(50) NOT NULL,\r\n        firstname VARCHAR(30) NOT NULL,\r\n        lastname VARCHAR(30) NOT NULL,\r\n        gold_price DECIMAL(14) NOT NULL,\r\n        initial_price DECIMAL(14) NOT NULL,\r\n        gold_weight DECIMAL(14,3) NOT NULL,\r\n        initial_final_price DECIMAL(14) NOT NULL,\r\n        price_received_by_wallet DECIMAL(14) NOT NULL,\r\n        price_received_online DECIMAL(14) NOT NULL,\r\n        final_price DECIMAL(14) NOT NULL,\r\n        total_sale_tax DECIMAL(14) NOT NULL,\r\n        description VARCHAR(350),\r\n        authority VARCHAR(36) NOT NULL,\r\n        transaction_date DATETIME NOT NULL,\r\n        transaction_id VARCHAR(30) NOT NULL,\r\n        transaction_status VARCHAR(20) NOT NULL,\r\n        transaction_processed VARCHAR(2) NOT NULL,\r\n        payment_date DATETIME NOT NULL,\r\n        payment_status VARCHAR(20) NOT NULL,\r\n        qrcode_image_url VARCHAR(500) NOT NULL,\r\n        PRIMARY KEY (id)\r\n    ) " . $charset_collate . ";";
require_once ABSPATH . "wp-admin/includes/upgrade.php";
dbDelta($sql_sale_table);
$table_name_delivery = $wpdb->prefix . "almas_gold_delivery";
$sql_delivery_table = "CREATE TABLE IF NOT EXISTS " . $table_name_delivery . " (\r\n        id INT NOT NULL AUTO_INCREMENT,\r\n        unique_delivery_id VARCHAR(30) NOT NULL,\r\n        delivery_id INT NOT NULL,\r\n        unique_customer_id VARCHAR(10) NOT NULL,\r\n        user_id INT NOT NULL,\r\n        delivery_request_date DATETIME NOT NULL,\r\n        user_name VARCHAR(50) NOT NULL,\r\n        firstname VARCHAR(30) NOT NULL,\r\n        lastname VARCHAR(30) NOT NULL,\r\n        gold_price DECIMAL(14) NOT NULL,\r\n        live_gold_price DECIMAL(14) NOT NULL,\r\n        gold_weight DECIMAL(14,3) NOT NULL,\r\n        delivery_gold_weight DECIMAL(14,3) NOT NULL,\r\n        initial_price DECIMAL(14) NOT NULL,\r\n        initial_final_price DECIMAL(14) NOT NULL,\r\n        final_price DECIMAL(14) NOT NULL,\r\n        delivery_final_price DECIMAL(14,3) NOT NULL,\r\n        price_payed_by_wallet DECIMAL(14) NOT NULL,\r\n        price_payed_online DECIMAL(14) NOT NULL,\r\n        gold_type VARCHAR(20) NOT NULL,\r\n        delivery_gold_type VARCHAR(20) NOT NULL,\r\n        gold_type_fee DECIMAL(14,3) NOT NULL,\r\n        delivery_gold_type_fee DECIMAL(14,3) NOT NULL,\r\n        delivery_gold_type_fee_amount DECIMAL(14,3) NOT NULL,\r\n        delivery_total_gold_price DECIMAL(14,3) NOT NULL,\r\n        gold_type_net_profit DECIMAL(14,3) NOT NULL,\r\n        order_status VARCHAR(20) NOT NULL, \r\n        delivery_status VARCHAR(20) NOT NULL,\r\n        description VARCHAR(350),\r\n        payment_date DATETIME NOT NULL,\r\n        payment_status VARCHAR(20) NOT NULL, \r\n        transaction_id VARCHAR(30) NOT NULL,\r\n        payed_online_id VARCHAR(1) NOT NULL,\r\n        authority VARCHAR(36) NOT NULL,\r\n        transaction_date DATETIME NOT NULL,\r\n        transaction_status VARCHAR(20) NOT NULL,\r\n        transaction_processed VARCHAR(2) NOT NULL,\r\n        qrcode_image_url VARCHAR(500) NOT NULL,\r\n        PRIMARY KEY (id)\r\n    ) " . $charset_collate . ";";
require_once ABSPATH . "wp-admin/includes/upgrade.php";
dbDelta($sql_delivery_table);
$table_name_deposit = $wpdb->prefix . "almas_gold_deposit";
$sql_deposit_table = "CREATE TABLE IF NOT EXISTS " . $table_name_deposit . " (\r\n        id INT NOT NULL AUTO_INCREMENT,\r\n        unique_deposit_id VARCHAR(30) NOT NULL,\r\n        deposit_id INT NOT NULL,\r\n        unique_customer_id VARCHAR(10) NOT NULL,\r\n        user_id INT NOT NULL,\r\n        deposit_date DATETIME NOT NULL,\r\n        user_name VARCHAR(50) NOT NULL,\r\n        firstname VARCHAR(30) NOT NULL,\r\n        lastname VARCHAR(30) NOT NULL,\r\n        deposit_amount DECIMAL(14) NOT NULL,\r\n        deposit_fee DECIMAL(14) NOT NULL,\r\n        final_deposit_amount DECIMAL(14) NOT NULL,\r\n        description VARCHAR(350),\r\n        transaction_id VARCHAR(30) NOT NULL,\r\n        authority VARCHAR(36) NOT NULL,\r\n        transaction_date DATETIME NOT NULL,\r\n        transaction_status VARCHAR(20) NOT NULL,\r\n        transaction_processed VARCHAR(2) NOT NULL,\r\n        payment_date DATETIME NOT NULL,\r\n        request_status VARCHAR(20) NOT NULL,\r\n        deposit_status VARCHAR(20) NOT NULL,\r\n        qrcode_image_url VARCHAR(500) NOT NULL,\r\n        PRIMARY KEY (id)\r\n    ) " . $charset_collate . ";";
require_once ABSPATH . "wp-admin/includes/upgrade.php";
dbDelta($sql_deposit_table);
$table_name_recharge = $wpdb->prefix . "almas_gold_recharge";
$sql_recharge_table = "CREATE TABLE IF NOT EXISTS " . $table_name_recharge . " (\r\n        id INT NOT NULL AUTO_INCREMENT,\r\n        unique_recharge_id VARCHAR(30) NOT NULL,\r\n        recharge_id INT NOT NULL,\r\n        unique_customer_id VARCHAR(10) NOT NULL,\r\n        user_id INT NOT NULL,\r\n        recharge_date DATETIME NOT NULL,\r\n        user_name VARCHAR(50) NOT NULL,\r\n        firstname VARCHAR(30) NOT NULL,\r\n        lastname VARCHAR(30) NOT NULL,\r\n        recharge_amount DECIMAL(14) NOT NULL,\r\n        recharge_fee DECIMAL(14) NOT NULL,\r\n        final_recharge_amount DECIMAL(14) NOT NULL,\r\n        initial_recharge_amount DECIMAL(14) NOT NULL,\r\n        description VARCHAR(350),\r\n        transaction_id VARCHAR(30) NOT NULL,\r\n        authority VARCHAR(36) NOT NULL,\r\n        transaction_date DATETIME NOT NULL,\r\n        transaction_status VARCHAR(20) NOT NULL,\r\n        transaction_processed VARCHAR(2) NOT NULL,\r\n        qrcode_image_url VARCHAR(500) NOT NULL,\r\n        PRIMARY KEY (id)\r\n    ) " . $charset_collate . ";";
require_once ABSPATH . "wp-admin/includes/upgrade.php";
dbDelta($sql_recharge_table);
$table_name_customer = $wpdb->prefix . "almas_gold_customers";
if($wpdb->get_var("SHOW TABLES LIKE '" . $table_name_customer . "'") != $table_name_customer) {
    $sql = "CREATE TABLE " . $table_name_customer . " (\r\n            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,\r\n            user_id BIGINT(20) UNSIGNED NOT NULL,\r\n            user_name VARCHAR(50) NOT NULL,\r\n            unique_id VARCHAR(10) NOT NULL,\r\n            firstname VARCHAR(100) NOT NULL,\r\n            lastname VARCHAR(100) NOT NULL,\r\n            faname VARCHAR(100) NOT NULL,\r\n            safe_balance DECIMAL(14,3) NOT NULL,\r\n            wallet_balance DECIMAL(14) NOT NULL,\r\n            national_code DECIMAL(10) NOT NULL,\r\n            birthday VARCHAR(100) NOT NULL,\r\n            email VARCHAR(100) NOT NULL,\r\n            mobile VARCHAR(20) NOT NULL,\r\n            state VARCHAR(50) NOT NULL,\r\n            city VARCHAR(50) NOT NULL,\r\n            address TEXT,\r\n            postal_code VARCHAR(10) NOT NULL,\r\n            bank_name VARCHAR(100) NOT NULL,\r\n            card_number VARCHAR(16) NOT NULL,\r\n            iban_number VARCHAR(34) NOT NULL,\r\n            description VARCHAR(350),\r\n            PRIMARY KEY (id),\r\n            UNIQUE KEY (user_id)\r\n        ) " . $charset_collate . ";";
    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta($sql);
}

?>