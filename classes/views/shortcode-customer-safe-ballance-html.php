<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas-gold shortcode customer safe ballance html
     */

     
    ?>
        <div id="ag_user_safe_balance_info">
            <div>
                <label>
                    <?= esc_html__('موجودی', 'almas-gold'); ?>
                </label>
                <span>
                    <?php echo rtrim(rtrim(number_format($safe_balance, 4, '.', ''), '0'), '.'); ?>
                </span>
                <suffix>
                    <?= esc_html__($unit, 'almas-gold'); ?>
                </suffix>
            </div>
            <div>
                <label>
                    <?= esc_html__('معادل', 'almas-gold'); ?>
                </label>
                <span>
                    <?php echo number_format($customer_safe_value_amount, 0, '.', ','); ?>
                </span>
                <suffix>
                    <?= esc_html__('تومان', 'almas-gold'); ?>
                </suffix>
            </div>
        </div>    
    <?php