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
                    <i class=" prk-book"></i><?php esc_html_e('لیست دریافت‌های طلا', 'almas-gold'); ?>
                </h4>
            </div>
            <div class="list_body">
                <table class="customer_list_table wp-list-table widefat striped">
                    <thead>
                        <tr>
                            <td width="50"><span style="margin-right: 15px"><?= esc_html__('#', 'almas-gold'); ?></span></td>
                            <td><?= esc_html__('شناسه تحویل', 'almas-gold'); ?></td>
                            <td><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></td>
                            <td><?= esc_html__('نوع طلا', 'almas-gold'); ?></td>
                            <td><?= esc_html__('وزن', 'almas-gold'); ?></td>
                            <td>
                                <?= esc_html__('مبلغ طلا', 'almas-gold'); ?>
                                <span style="margin-right: 1px; font-size: 10px; color: #fff; font-weight: 100;">
                                    (تومان)
                                </span>
                            </td>
                            <td>
                                <?= esc_html__('مبلغ سفارش', 'almas-gold'); ?>
                                <span style="margin-right: 1px; font-size: 10px; color: #fff; font-weight: 100;">
                                    (تومان)
                                </span>
                            </td>
                            <td><?= esc_html__('زمان تراکنش', 'almas-gold'); ?></td>
                            <td><?= esc_html__('وضعیت پرداخت', 'almas-gold'); ?></td>
                            <td><?= esc_html__('شناسه پرداخت', 'almas-gold'); ?></td>
                            <td><?= esc_html__('وضعیت تحویل', 'almas-gold'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $counter = 1;
                            foreach ($delivery_orders as $row) :
                                $delivery_id = $row->delivery_id;
                                $delivery_request_date = $row->delivery_request_date;
                                $firstname = $row->firstname;
                                $lastname = $row->lastname;
                                $gold_weight = $row->gold_weight;
                                $initial_final_price = $row->initial_final_price;
                                $initial_price = $row->initial_price;
                                $final_price = $row->final_price;
                                $transaction_id = $row->transaction_id;
                                $transaction_date = $row->transaction_date;
                                $transaction_status = $row->transaction_status;
                                $payment_status = $row->payment_status;
                                $delivery_status = $row->delivery_status;
                                $order_status = $row->order_status;
                                $gold_type = $row->gold_type;
                                ?>
                                    <tr class="customer_list_table_row">
                                        <td>
                                            <span style="margin-right: 15px">
                                                <?php echo $counter; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo esc_attr($delivery_id); ?>
                                        </td>
                                        <td>

                                            <?php 
                                                if ($initial_final_price === '0') {
                                                    if ($lists_date_display === '1') {
                                                        echo '
                                                            <span style="font-size: 12px !important;color: #b3b2b2;">
                                                                '. format_delivery_date($delivery_request_date) .'
                                                            </span>
                                                        ';
                                                    } else {
                                                        echo '
                                                            <span style="font-size: 13px !important; color: #b3b2b2">
                                                                '. jdate('d F - H:i', strtotime($delivery_request_date)) .'
                                                            </span>
                                                        ';
                                                    }
                                                } else {
                                                    if ($lists_date_display === '1') {
                                                        echo '
                                                            <span style="font-size: 12px !important;color: #000;">
                                                                '. format_delivery_date($delivery_request_date) .'
                                                            </span>
                                                        ';
                                                    } else {
                                                        echo '
                                                            <span style="font-size: 13px !important; color: #000">
                                                                '. jdate('d F - H:i', strtotime($delivery_request_date)) .'
                                                            </span>
                                                        ';
                                                    }
                                                }
                                            ?>
                                            
                                        </td>
                                        <td>
                                            <?php 
                                                if ($transaction_status !== 'approved') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 300;color: #000">
                                                        -
                                                        </span>
                                                    ';
                                                } else {
                                                    ?>
                                                        <span style="font-size: 12px !important;font-weight: 300;color: #000">  
                                                            <?php
                                                                if ($gold_type === 'broken') {
                                                                    echo esc_html__('شکسته', 'almas-gold');
                                                                } elseif ($gold_type === 'without_fee') {
                                                                    echo esc_html__('بدون اجرت', 'almas-gold');
                                                                } elseif ($gold_type === 'low_fee') {
                                                                    echo esc_html__('کم اجرت', 'almas-gold');
                                                                } elseif ($gold_type === 'sequins') {
                                                                    echo esc_html__('پولک', 'almas-gold');
                                                                } elseif ($gold_type === 'bullion') {
                                                                    echo esc_html__('شمش', 'almas-gold');
                                                                }
                                                            ?>
                                                        </span>
                                                    <?php
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
                                                            ' . number_format($initial_price, 0, '.', ',') . '
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
                                                            ' . number_format($final_price, 0, '.', ',') . '
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
                                                        <span style="font-size: 13px !important;color: #000">
                                                            '. esc_attr($transaction_id) .'
                                                        </span>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($transaction_status !== 'approved') {

                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 300;color: #000">
                                                        -
                                                        </span>
                                                    ';
                                                    
                                                } else {

                                                    if ($order_status == 'waitforshop') {
                                                        echo '
                                                            <span style="font-size: 13px !important;color: #DC143C;display: flex;align-items: center;gap: 4px;">
                                                                <i class=" prk-timer" style="font-weight: 600;"></i> ' . esc_html__("در انتظار فروشگاه", "almas-gold") . '
                                                            </span>
                                                        ';
                                                    } elseif ($order_status == 'waitforcustomer'){
                                                        echo '
                                                            <span style="font-size: 13px !important;color: #DC143C;display: flex;align-items: center;gap: 4px;">
                                                                <i class=" prk-timer" style="font-weight: 600;"></i> '. esc_html__('در انتظار مشتری', 'almas-gold').'
                                                            </span>
                                                        ';
                                                    } elseif ($order_status == 'delivered'){
                                                        echo '
                                                            <span style="font-size: 13px !important;color: #0D9277;display: flex;align-items: center;gap: 4px;">
                                                                <i class=" prk-tick-square" style="font-weight: 600;"></i> '. esc_html__('تحویل شد', 'almas-gold').'
                                                            </span>
                                                        ';
                                                    }
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