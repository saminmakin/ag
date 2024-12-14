<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold
     * @package           almas.gold class almas gold admin orders page
     */

     ?>
        <div>
            <?php almas_gold_customer_shop_details(); ?>
        </div>
        <div id="almas_gold_customer_container_box">
            <div class="list_head">
                <h4>
                    <i class=" prk-book"></i><?php esc_html_e('لیست خریدهای طلا', 'almas-gold'); ?>
                </h4>
            </div>
            <div class="list_body">
                <table class="customer_list_table">
                    <thead>
                        <tr>
                            <td width="50"><span style="margin-right: 15px"><?= esc_html__('#', 'almas-gold'); ?></span></td>
                            <td><?= esc_html__('شناسه خرید', 'almas-gold'); ?></td>
                            <td><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></td>
                            <td><?= esc_html__('وزن', 'almas-gold'); ?></td>
                            <td>
                                <?= esc_html__('مبلغ سفارش', 'almas-gold'); ?>
                                <span style="margin-right: 1px; font-size: 10px; color: #fff; font-weight: 100;">
                                    (تومان)
                                </span>
                            </td>
                            <td>
                                <?= esc_html__('مالیات', 'almas-gold'); ?>
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
                            foreach ($shop_data as $row) :
                                $shop_id = $row->shop_id;
                                $shop_date = $row->shop_date;
                                $gold_weight = $row->gold_weight;
                                $initial_final_price = $row->initial_final_price;
                                $total_shop_tax = $row->total_shop_tax;
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
                                            <?php echo esc_attr($shop_id); ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($initial_final_price === '0') {
                                                    if ($lists_date_display === '1') {
                                                        echo '
                                                            <span style="font-size: 120px !important;color: #b3b2b2;">
                                                                '. format_shop_date($shop_date) .'
                                                            </span>
                                                        ';
                                                    } else {
                                                        echo '
                                                            <span style="font-size: 13px !important; color: #b3b2b2">
                                                                '. jdate('d F - H:i', strtotime($shop_date)) .'
                                                            </span>
                                                        ';
                                                    }
                                                } else {
                                                    if ($lists_date_display === '1') {
                                                        echo '
                                                            <span style="font-size: 120px !important;color: #000;">
                                                                '. format_shop_date($shop_date) .'
                                                            </span>
                                                        ';
                                                    } else {
                                                        echo '
                                                            <span style="font-size: 13px !important; color: #000">
                                                                '. jdate('d F - H:i', strtotime($shop_date)) .'
                                                            </span>
                                                        ';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($unit_display == 1) {
                                                    if ($gold_weight < 1) {
                                                        $gold_weight = $gold_weight * 1000;
                                                        $unit = 'سوت';
                                                    } else {
                                                        $unit = 'گرم';
                                                    }
                                                } else {
                                                    $unit = 'گرم';
                                                }
                                                if ($initial_final_price === '0') {
                                                    echo '
                                                        <span class="almas_gold_customer_weight">
                                                            <span style="font-size: 13px !important;font-weight: 700">
                                                                ' . rtrim(rtrim(number_format($gold_weight, 4, '.', ''), '0'), '.') . '
                                                            </span>
                                                            <span style="font-size: 10px !important; margin-right: 0px">
                                                                ' . esc_html__($unit  , 'almas-gold') . '
                                                            </span>
                                                        </span>
                                                    ';
                                                } else {
                                                    echo '
                                                        <span class="almas_gold_customer_weight">
                                                            <span style="font-size: 13px !important;font-weight: 700">
                                                                ' . rtrim(rtrim(number_format($gold_weight, 4, '.', ''), '0'), '.') . '
                                                            </span>
                                                            <span style="font-size: 10px !important; margin-right: 0px">
                                                                ' . esc_html__($unit  , 'almas-gold') . '
                                                            </span>
                                                        </span>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($initial_final_price === '0') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 300;color: #000">
                                                        -
                                                        </span>
                                                    ';
                                                } else {

                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 700;color: #000">
                                                            ' . number_format($initial_final_price, 0, '.', ',') . '
                                                        </span>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($total_shop_tax === '0') {
                                                    echo '
                                                        <span style="font-size: 13px !important;color: #000">
                                                        -
                                                        </span>
                                                    ';
                                                } else {

                                                    echo '
                                                        <span style="font-size: 13px !important;color: #000">
                                                            ' . number_format($total_shop_tax, 0, '.', ',') . '
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
    