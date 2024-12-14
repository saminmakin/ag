<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold
     * @package           almas.gold class almas gold admin orders page
     */

    ?>
        <div id="almas_gold_customer_container_box">
            <div class="list_head">
                <h4>
                    <i class=" prk-book"></i><?php esc_html_e('لیست واریزها', 'almas-gold'); ?>
                </h4>
            </div>
                <!--div>
                    <?php
                    $table_name_recharge = $wpdb->prefix . 'almas_gold_recharge';
                    $count_approved_orders = $wpdb->get_var("SELECT COUNT(*) FROM $table_name_recharge WHERE transaction_status = 'approved'");
                    ?>
                    <?php esc_html_e('سفارشهای تایید شده', 'almas-gold'); ?>: <?php echo esc_html($count_approved_orders); ?>
                </div-->
            <div class="list_body">
                <table class="customer_list_table wp-list-table widefat striped">
                    <thead>
                        <tr>
                            <td width="50"><span style="margin-right: 15px"><?= esc_html__('#', 'almas-gold'); ?></span></td>
                            <td><?= esc_html__('شناسه شارژ', 'almas-gold'); ?></td>
                            <td><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></td>
                            <td>
                                <?= esc_html__('مبلغ شارژ', 'almas-gold'); ?>
                                <span style="margin-right: 1px; font-size: 10px; color: #fff; font-weight: 100;">
                                    (تومان)
                                </span>
                            </td>
                            <td><?= esc_html__('وضعیت پرداخت', 'almas-gold'); ?></td>
                            <td><?= esc_html__('زمان تراکنش', 'almas-gold'); ?></td>
                            <td><?= esc_html__('شناسه پرداخت', 'almas-gold'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $counter = 1;
                            foreach ($recharge_orders as $row) :
                                $recharge_id = $row->recharge_id;
                                $recharge_date = $row->recharge_date;
                                $firstname = $row->firstname;
                                $lastname = $row->lastname;
                                $recharge_amount = $row->recharge_amount;
                                $final_recharge_amount = $row->final_recharge_amount;
                                $initial_recharge_amount = $row->initial_recharge_amount;
                                $transaction_id = $row->transaction_id;
                                $transaction_date = $row->transaction_date;
                                $transaction_status = $row->transaction_status;
                                ?>
                                    <tr class="customer_list_table_row">
                                        <td>
                                            <span style="margin-right: 15px">
                                                <?php echo $counter; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo esc_attr($recharge_id); ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($initial_recharge_amount === '0') {
                                                    if ($lists_date_display === '1') {
                                                        echo '
                                                            <span style="font-size: 12px !important;color: #b3b2b2;">
                                                                '. format_recharge_date($recharge_date) .'
                                                            </span>
                                                        ';
                                                    } else {
                                                        echo '
                                                            <span style="font-size: 13px !important; color: #b3b2b2">
                                                                '. jdate('d F - H:i', strtotime($recharge_date)) .'
                                                            </span>
                                                        ';
                                                    }
                                                } else {
                                                    if ($lists_date_display === '1') {
                                                        echo '
                                                            <span style="font-size: 12px !important;color: #000;">
                                                                '. format_recharge_date($recharge_date) .'
                                                            </span>
                                                        ';
                                                    } else {
                                                        echo '
                                                            <span style="font-size: 13px !important; color: #000">
                                                                '. jdate('d F - H:i', strtotime($recharge_date)) .'
                                                            </span>
                                                        ';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($initial_recharge_amount === '0') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 300;color: #000">
                                                        -
                                                        </span>
                                                    ';
                                                } else {

                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 700;color: #000">
                                                            ' . number_format($initial_recharge_amount, 0, '.', ',') . '
                                                        </span>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($transaction_status !== 'approved') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 600;padding: 6px 8px;
                                                        border-radius: 6px;background-color: #fbe7eb;color: #DC143C">
                                                            '. esc_html__('در انتظار', 'almas-gold').'
                                                        </span>
                                                    ';
                                                } else {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 600;padding: 6px 8px;
                                                        border-radius: 6px;background-color: #e6f4f1;color: #0D9277">
                                                            '. esc_html__('تایید شد', 'almas-gold') .'
                                                        </span>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($transaction_status !== 'approved') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 600;padding: 6px 8px;
                                                        border-radius: 6px;background-color: #fbe7eb;color: #DC143C">
                                                            '. esc_html__('در انتظار', 'almas-gold').'
                                                        </span>
                                                    ';
                                                } else {
                                                    echo '
                                                        <span style="font-size: 12px !important;color: #000;">
                                                            '. jdate('d F - H:i:s', strtotime($transaction_date)) .'
                                                        </span>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($transaction_status !== 'approved') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 600;padding: 6px 8px;
                                                        border-radius: 6px;background-color: #fbe7eb;color: #DC143C">
                                                            '. esc_html__('در انتظار', 'almas-gold').'
                                                        </span>
                                                    ';
                                                } else {
                                                    echo '
                                                        <span style="font-size: 13px !important;color: #000">
                                                            '. esc_attr($transaction_id) .'
                                                        </span>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php 
                            $counter++;
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php