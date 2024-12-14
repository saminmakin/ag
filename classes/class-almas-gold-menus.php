<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ Offline decoder for php versions: 40/74
 * @ Decoder version: 1.1.1
 * @ Release: 29/08/2024
 */

// Decoded file for php version 74.
defined("ABSPATH") or exit();
$almasGoldtMenus = new almasGoldtMenus();
class almasGoldtMenus
{
    public function __construct()
    {
        add_action("admin_menu", [$this, "almasno_add_admin_console_menu"]);
    }
    public function almasno_add_admin_console_menu()
    {
        add_menu_page("الماس گلد", "الماس گلد", "manage_options", "almas-gold-settings", [$this, "almas_gold_settings_page_callback"], plugin_dir_url(__FILE__) . "../admin/assets/img/plugin-icon.png", 2);
        add_submenu_page("almas-gold-settings", "تنظیمات الماس گلد", "تنظیمات", "manage_options", "almas-gold-settings", [$this, "almas_gold_settings_page_callback"]);
        add_submenu_page("almas-gold-settings", "لیست فروش طلا به مشتری", "فروش‌ها", "manage_options", "almas-gold-shop", [$this, "almas_gold_shop_page_callback"]);
        add_submenu_page("almas-gold-settings", "لیست خرید طلا از مشتری", "خریدها", "manage_options", "almas-gold-sale", [$this, "almas_gold_sale_page_callback"]);
        add_submenu_page("almas-gold-settings", "لیست تحویل طلا به مشتری", "تحویل‌ها", "manage_options", "almas-gold-delivery", [$this, "almas_gold_delivery_page_callback"]);
        add_submenu_page("almas-gold-settings", "لیست دروخواست وجه", "درخواست‌های واریز وجه", "manage_options", "almas-gold-deposit", [$this, "almas_gold_deposit_page_callback"]);
        add_submenu_page("almas-gold-settings", "لیست شارژ کیف پول مشتری", "شارژهای کیف پول", "manage_options", "almas-gold-recharge", [$this, "almas_gold_recharge_page_callback"]);
        add_submenu_page("almas-gold-settings", "جستجو در سفارش‌ها", "جستجو در سفارش‌ها", "manage_options", "almas-gold-order-search", [$this, "almas_gold_order_search_callback"]);
        add_submenu_page("almas-gold-settings", "ویرایش تحویل طلا به مشتری", "تحویل طلا به مشتری", "manage_options", "almas-gold-edit-delivery-status", [$this, "almas_gold_edit_delivery_status_page_callback"]);
    }
    public function almas_gold_settings_page_callback()
    {
        require_once trailingslashit(dirname(__FILE__)) . "../admin/view/settings/index.php";
    }
    public function almas_gold_shop_page_callback()
    {
        require_once trailingslashit(dirname(__FILE__)) . "../admin/view/shop/index.php";
    }
    public function almas_gold_sale_page_callback()
    {
        require_once trailingslashit(dirname(__FILE__)) . "../admin/view/sale/index.php";
    }
    public function almas_gold_delivery_page_callback()
    {
        require_once trailingslashit(dirname(__FILE__)) . "../admin/view/delivery/index.php";
    }
    public function almas_gold_deposit_page_callback()
    {
        require_once trailingslashit(dirname(__FILE__)) . "../admin/view/deposit/index.php";
    }
    public function almas_gold_recharge_page_callback()
    {
        require_once trailingslashit(dirname(__FILE__)) . "../admin/view/recharge/index.php";
    }
    public function almas_gold_order_search_callback()
    {
        require_once trailingslashit(dirname(__FILE__)) . "../admin/view/order-search/index.php";
    }
    public function almas_gold_edit_delivery_status_page_callback()
    {
        require_once trailingslashit(dirname(__FILE__)) . "../admin/view/delivery/delivery-edit-status.php";
    }
}

?>