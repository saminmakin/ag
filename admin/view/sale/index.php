<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ Offline decoder for php versions: 40/74
 * @ Decoder version: 1.1.1
 * @ Release: 29/08/2024
 */

// Decoded file for php version 74.
defined("ABSPATH") or exit();
if(!current_user_can("manage_options")) {
    return NULL;
}
if(!function_exists("almas_gold_admin_sale_list")) {
    function almas_gold_admin_sale_list()
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
        $table_name = $wpdb->prefix . "almas_gold_customers";
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile FROM " . $table_name . " WHERE user_id = %d", $user_id));
        $mobile = $customer_data->mobile;
        $table_name_sale = $wpdb->prefix . "almas_gold_sale";
        $items_per_page = 10;
        $current_page = isset($_GET["paged"]) ? abs((int) $_GET["paged"]) : 1;
        $offset = ($current_page - 1) * $items_per_page;
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM " . $table_name_sale);
        $total_pages = ceil($total_items / $items_per_page);
        $sale_orders = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name_sale . " ORDER BY id DESC LIMIT %d OFFSET %d", $items_per_page, $offset));
        if($sale_orders) {
            require_once "view/index-html.php";
            if(isset($_GET["page"]) && $_GET["page"] === "almas-gold-sale") {
                $sale_id = isset($_GET["sale_id"]) ? $_GET["sale_id"] : "";
                if(!empty($sale_id)) {
                    echo "\r\n                            <style>\r\n                                #almas_gold_admin_container_box,\r\n                                .pagination {\r\n                                    display: none; \r\n                                }\r\n                            </style>\r\n                        ";
                }
            }
            if($items_per_page < $total_items) {
                $pagination_args = ["base" => add_query_arg("paged", "%#%"), "format" => "?paged=%#%", "total" => $total_pages, "current" => $current_page, "show_all" => false, "end_size" => 1, "mid_size" => 1, "prev_next" => true, "prev_text" => __("«"), "next_text" => __("»"), "type" => "plain"];
                echo "\r\n                        <div class=\"pagination\" style=\"margin-top: 20px;width: 100%;height: 30px;text-align: center;\">\r\n                            " . paginate_links($pagination_args) . "\r\n                        </div>\r\n                    ";
            }
        } else {
            echo "\r\n                    <div style=\"padding: 10px;\">\r\n                        <h4>هیچ سفارشی وجود ندارد!</h4>\r\n                    </div>\r\n                ";
        }
    }
}
almas_gold_admin_sale_list();
function almas_gold_show_sale_details()
{
    global $wpdb;
    $table_name_sale = $wpdb->prefix . "almas_gold_sale";
    $core_data = $wpdb->get_row("SELECT \r\n                gold_unit_to_bills,\r\n                lists_date_display \r\n            FROM " . $wpdb->prefix . "almas_gold_core \r\n            ORDER BY id DESC \r\n            LIMIT 1");
    $gold_unit_to_bills = $core_data->gold_unit_to_bills;
    $unit_display = $gold_unit_to_bills;
    if(isset($_GET["sale_id"])) {
        $sale_id = intval($_GET["sale_id"]);
        $table_name_sale = $wpdb->prefix . "almas_gold_sale";
        $order_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_sale . " WHERE sale_id = %d", $sale_id));
        $qrcode_image_url = $order_details->qrcode_image_url;
        $unique_sale_id = $order_details->unique_sale_id;
        $firstname = $order_details->firstname;
        $lastname = $order_details->lastname;
        $sale_date = $order_details->sale_date;
        $description = $order_details->description;
        $gold_weight = $order_details->gold_weight;
        $initial_final_price = $order_details->initial_final_price;
        $total_sale_tax = $order_details->total_sale_tax;
        $payment_status = $order_details->payment_status;
        $payment_date = $order_details->payment_date;
        if($order_details) {
            require_once "view/order-detailes-html.php";
        } else {
            echo "<p>سفارش مورد نظر یافت نشد.</p>";
        }
    }
}
function format_sale_date($sale_date)
{
    $current_time = current_time("timestamp", true);
    $date_timestamp = strtotime($sale_date);
    $time_diff = $current_time - $date_timestamp;
    if($time_diff < 60) {
        return __("هم اکنون", "almas-gold");
    }
    if($time_diff < 3600) {
        $time_diff = round($time_diff / 60);
        return sprintf(_n("%d دقیقه قبل", "%d دقیقه قبل", $time_diff, "almas-gold"), $time_diff);
    }
    if($time_diff < 86400) {
        $time_diff = round($time_diff / 3600);
        return sprintf(_n("%d ساعت قبل", "%d ساعت قبل", $time_diff, "almas-gold"), $time_diff);
    }
    if($time_diff < 604800) {
        $time_diff = round($time_diff / 86400);
        return sprintf(_n("%d روز قبل", "%d روز قبل", $time_diff, "almas-gold"), $time_diff);
    }
    return jdate("d F - H:i", strtotime($date_timestamp));
}

?>