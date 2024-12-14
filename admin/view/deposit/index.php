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
if(!function_exists("almas_gold_admin_deposit_list")) {
    function almas_gold_admin_deposit_list()
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
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile, card_number, iban_number FROM " . $table_name . " WHERE user_id = %d", $user_id));
        $mobile = $customer_data->mobile;
        $card_number = $customer_data->card_number;
        $iban_number = $customer_data->iban_number;
        $table_name_deposit = $wpdb->prefix . "almas_gold_deposit";
        $items_per_page = 10;
        $current_page = isset($_GET["paged"]) ? abs((int) $_GET["paged"]) : 1;
        $offset = ($current_page - 1) * $items_per_page;
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM " . $table_name_deposit);
        $total_pages = ceil($total_items / $items_per_page);
        $deposit_orders = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name_deposit . " ORDER BY id DESC LIMIT %d OFFSET %d", $items_per_page, $offset));
        if($deposit_orders) {
            require_once "view/index-html.php";
            if(isset($_GET["page"]) && $_GET["page"] === "almas-gold-deposit") {
                $deposit_id = isset($_GET["deposit_id"]) ? $_GET["deposit_id"] : "";
                if(!empty($deposit_id)) {
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
almas_gold_admin_deposit_list();
function almas_gold_show_deposit_details()
{
    global $wpdb;
    $table_name_deposit = $wpdb->prefix . "almas_gold_deposit";
    $core_data = $wpdb->get_row("SELECT \r\n                gold_unit_to_bills,\r\n                lists_date_display \r\n            FROM " . $wpdb->prefix . "almas_gold_core \r\n            ORDER BY id DESC \r\n            LIMIT 1");
    $gold_unit_to_bills = $core_data->gold_unit_to_bills;
    $unit_display = $gold_unit_to_bills;
    if(isset($_GET["deposit_id"])) {
        $deposit_id = intval($_GET["deposit_id"]);
        $table_name_deposit = $wpdb->prefix . "almas_gold_deposit";
        $order_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name_deposit . " WHERE deposit_id = %d", $deposit_id));
        $qrcode_image_url = $order_details->qrcode_image_url;
        $unique_deposit_id = $order_details->unique_deposit_id;
        $firstname = $order_details->firstname;
        $lastname = $order_details->lastname;
        $deposit_date = $order_details->deposit_date;
        $deposit_amount = $order_details->deposit_amount;
        $final_deposit_amount = $order_details->final_deposit_amount;
        $deposit_fee = $order_details->deposit_fee;
        $description = $order_details->description;
        $deposit_status = $order_details->deposit_status;
        if($order_details) {
            require_once "view/order-detailes-html.php";
        } else {
            echo "<p>سفارش مورد نظر یافت نشد.</p>";
        }
    }
}
function format_deposit_date($deposit_date)
{
    $current_time = current_time("timestamp", true);
    $date_timestamp = strtotime($deposit_date);
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