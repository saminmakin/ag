<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin search orders
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
        <div id="order_search_box">
            <form method="post">
                <input 
                    type="number" 
                    id="order_id_search_input" 
                    name="search_order_id" 
                    placeholder="<?= esc_html__('شناسه سفارش', 'almas-gold'); ?>"
                >
                <input type="submit" id="order_id_search_submit" value="<?= esc_html__('جستجو', 'almas-gold'); ?>"></input>
                <?php
                    if (isset($_POST['search_order_id'])) {
                        $order_id = intval($_POST['search_order_id']);
                        echo '
                            <span style="margin-right: 100px; font-size: 14px">
                                ' . esc_html__("نتیجه جستجو برای", "almas-gold") . '
                            </span>
                            <label>' . $order_id . '</label>
                        ';
                    }
                ?>
            </form>
        </div>
    <?php
    