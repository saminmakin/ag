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
                    <i class=" prk-book"></i><?php esc_html_e('لیست برداشت‌ها', 'almas-gold'); ?>
                </h4>
            </div>
            <div class="list_body">
                <table class="customer_list_table">
                    <thead>
                        <tr>
                            <td width="50"><span style="margin-right: 15px"><?= esc_html__('#', 'almas-gold'); ?></span></td>
                            <td><?= esc_html__('شناسه درخواست', 'almas-gold'); ?></td>
                            <td><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></td>
                            <td>
                                <?= esc_html__('مبلغ درخواست', 'almas-gold'); ?>
                                <span style="margin-right: 1px; font-size: 10px; color: #fff; font-weight: 100;">
                                    (تومان)
                                </span>
                            </td>
                            <td><?= esc_html__('وضعیت درخواست', 'almas-gold'); ?></td>
                            <td><?= esc_html__('وضعیت واریز', 'almas-gold'); ?></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $counter = 1;
                            foreach ($deposit_orders as $row) :
                                $deposit_id = $row->deposit_id;
                                $deposit_date = $row->deposit_date;
                                $firstname = $row->firstname;
                                $lastname = $row->lastname;
                                $final_deposit_amount = $row->final_deposit_amount;
                                $request_status = $row->request_status;
                                $payment_date = $row->payment_date;
                                $deposit_status = $row->deposit_status;
                                ?>
                                    <tr class="customer_list_table_row">
                                        <td>
                                            <span style="margin-right: 15px">
                                                <?php echo $counter; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo esc_attr($deposit_id); ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($request_status !== 'approved') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 600;padding: 6px 8px;
                                                        border-radius: 6px;background-color: #fbe7eb;color: #DC143C">
                                                            '. esc_html__('در انتظار تایید', 'almas-gold').'
                                                        </span>
                                                    ';
                                                } else {
                                                    if ($lists_date_display === '1') {
                                                        echo '
                                                            <span style="font-size: 12px !important;color: #000; payment_date">
                                                                '. format_deposit_date($deposit_date) .'
                                                            </span>
                                                        ';
                                                    } else {
                                                        echo '
                                                            <span style="font-size: 12px !important;color: #000; payment_date">
                                                                '. jdate('d F - H:i', strtotime($deposit_date)) .'
                                                            </span>
                                                        ';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <?php 
                                                if ($final_deposit_amount === '0') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 300;color: #000">
                                                        -
                                                        </span>
                                                    ';
                                                } else {

                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 700;color: #000">
                                                            ' . number_format($final_deposit_amount, 0, '.', ',') . '
                                                        </span>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($request_status !== 'approved') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 600;padding: 6px 8px;
                                                        border-radius: 6px;background-color: #fbe7eb;color: #DC143C">
                                                            '. esc_html__('در انتظار تایید', 'almas-gold').'
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
                                                if ($deposit_status !== 'approved') {
                                                    echo '
                                                        <span style="font-size: 13px !important;font-weight: 600;padding: 6px 8px;
                                                        border-radius: 6px;background-color: #fbe7eb;color: #DC143C">
                                                            '. esc_html__('در انتظار واریز', 'almas-gold').'
                                                        </span>
                                                    ';
                                                } else {
                                                    echo '
                                                        <span style="font-size: 13px !important;color: #0D9277;display: flex;align-items: center;gap: 4px;">
                                                                <i class=" prk-tick-square" style="font-weight: 600;"></i> '. esc_html__('واریز شد', 'almas-gold').'
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