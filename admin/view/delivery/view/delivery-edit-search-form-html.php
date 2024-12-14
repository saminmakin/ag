<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin edit delivery status
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
        <div id="edit_delivery_status_search_box">
            <form method="post">
                <input 
                    type="number" 
                    name="order_id_search_input" 
                    placeholder="<?= esc_html__('شناسه تحویل', 'almas-gold'); ?>"
                    autocomplete="off"
                    autofocus
                >
                <input type="submit" id="order_id_search_submit" value="<?= esc_html__('جستجوی شناسه', 'almas-gold'); ?>">
            </form>
        </div>
    <?php
