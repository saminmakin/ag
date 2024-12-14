<?php
defined("ABSPATH") or exit();
if (!function_exists('almas_gold_customer_shop_details')) {    
    function almas_gold_customer_shop_details()
    {
        global $wpdb;
        $table_name_shop = $wpdb->prefix . "almas_gold_shop";
        $core_data = $wpdb->get_row("SELECT \r\n                gold_unit_to_bills,\r\n                lists_date_display \r\n            FROM " . $wpdb->prefix . "almas_gold_core \r\n            ORDER BY id DESC \r\n            LIMIT 1");
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $unit_display = $gold_unit_to_bills;
        if(isset($_GET["shop_id"])) {
            $shop_id = intval($_GET["shop_id"]);
            $table_name_shop = $wpdb->prefix . "almas_gold_shop";
            $order_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_shop . " WHERE shop_id = %d", $shop_id));
            $qrcode_image_url = $order_details->qrcode_image_url;
            $unique_shop_id = $order_details->unique_shop_id;
            $firstname = $order_details->firstname;
            $lastname = $order_details->lastname;
            $shop_date = $order_details->shop_date;
            $description = $order_details->description;
            $gold_weight = $order_details->gold_weight;
            $initial_final_price = $order_details->initial_final_price;
            $total_shop_tax = $order_details->total_shop_tax;
            $price_payed_online = $order_details->price_payed_online;
            $price_payed_by_wallet = $order_details->price_payed_by_wallet;
            $transaction_status = $order_details->transaction_status;
            $transaction_id = $order_details->transaction_id;
            $transaction_date = $order_details->transaction_date;
            if($order_details) {
                if(isset($_GET["page"]) && $_GET["page"] === "customer-shops") {
                    require_once "view/order-detailes-html.php";
                }
            } else {
                echo "<p>سفارش مورد نظر یافت نشد.</p>";
            }
        
    
        }
    }
}
if(!function_exists("almas_gold_customer_shop_list")) {
    function almas_gold_customer_shop_list()
    {
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if(!$user_id) {
            echo "                    <script>\r\n                        window.location.href = '";
            echo wp_login_url();
            echo "';\r\n                    </script>\r\n                ";
        }
        global $wpdb;
        $core_data = $wpdb->get_row("SELECT \r\n                    gold_unit_to_bills,\r\n                    lists_date_display \r\n                FROM " . $wpdb->prefix . "almas_gold_core \r\n                ORDER BY id DESC \r\n                LIMIT 1");
        $gold_unit_to_bills = $core_data->gold_unit_to_bills;
        $lists_date_display = $core_data->lists_date_display;
        $unit_display = $gold_unit_to_bills;
        $table_name_orders_shop = $wpdb->prefix . "almas_gold_shop";
        $shop_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name_orders_shop . " WHERE user_id = %d AND transaction_processed = %d ORDER BY id DESC", $user_id, 1));
		if($shop_data) {
            require_once "view/customer-shop-list-html.php";
        } else {
            echo "\r\n                <div style=\"padding: 10px;\">\r\n                    <h4>هیچ سفارشی وجود ندارد!</h4>\r\n                </div>\r\n                ";
        }
    }
}
almas_gold_customer_shop_list();
?>