<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin search delivery
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
        <div id="order_search_results_box">
            <div class="order-search-results">
                <div class="order-search-results-order-header">
                    <div>
                        <span style="margin-left: 5px; font-weight: 700; font-size: 14px">
                            <?= esc_html__('نتیجه جستجو در تحویل ها: ', 'almas-gold'); ?>
                        </span>
                        <span style="margin-right: 10px"><?= esc_html__('شناسه تحویل', 'almas-gold'); ?></span> 
                        <label><?php echo esc_attr($delivery_data->delivery_id); ?></label>
                    </div>
                    <div>
                        <p href="#" id="print_order"><?= esc_html__('چاپ سفارش', 'almas-gold'); ?></p>
                    </div>
                </div>
                <table class="wp-list-table widefat striped">
                    <tbody>
                        <tr>
                            <td>
                                <span><?= esc_html__('QR Code', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <div class="qrcode">
                                    <img src="<?php echo esc_attr($delivery_data->qrcode_image_url); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div class="qrc_placeholder">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </div>
                                </div>
                                <label><a href="<?php echo esc_attr($delivery_data->qrcode_image_url); ?>" target="_blank"><?php echo esc_attr($delivery_data->qrcode_image_url); ?></a></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه یونیک', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($delivery_data->unique_delivery_id); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه خرید', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($delivery_data->delivery_id); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo jdate('d F Y - H:i:s', strtotime($delivery_data->delivery_request_date)); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('نام کاربری مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($delivery_data->user_name); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('نام مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($delivery_data->firstname); ?> <?php echo esc_attr($delivery_data->lastname); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($delivery_data->unique_customer_id); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شماره موبایل مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($customer_data->mobile); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('ایمیل مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($customer_data->email); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('قیمت طلا', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($delivery_data->gold_price); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('مبلغ طلا', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($delivery_data->initial_price, 0, '.', ','); ?></label>
                                <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('وزن طلا', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo rtrim(rtrim(number_format($delivery_data->gold_weight, 4, '.', ''), '0'), '.'); ?></label>
                                <span><?= esc_html__('گرم', 'almas-gold'); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('مبلغ اولیه نهایی', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($delivery_data->initial_final_price, 0, '.', ','); ?></label>
                                <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('پرداخت با کیف پول', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($delivery_data->price_payed_by_wallet, 0, '.', ','); ?></label>
                                <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('پرداخت با شتاب', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($delivery_data->price_payed_online, 0, '.', ','); ?></label>
                                <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('نوع طلا', 'almas-gold'); ?></span>
                                </td>
                            <td>
                                <label>
                                    <?php
                                        if ($delivery_data->gold_type === 'broken') {
                                            echo esc_html__('شکسته', 'almas-gold');
                                        } elseif ($delivery_data->gold_type === 'without_fee') {
                                            echo esc_html__('بدون اجرت', 'almas-gold');
                                        } elseif ($delivery_data->gold_type === 'low_fee') {
                                            echo esc_html__('کم اجرت', 'almas-gold');
                                        } elseif ($delivery_data->gold_type === 'sequins') {
                                            echo esc_html__('پولک', 'almas-gold');
                                        } elseif ($delivery_data->gold_type === 'bullion') {
                                            echo esc_html__('شمش', 'almas-gold');
                                        }
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('سود خالص به وزن', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <?php
                                    if ($unit_display == 1) {
                                        if ($delivery_data->gold_type_net_profit < 1) {
                                            $delivery_data->gold_type_net_profit = $delivery_data->gold_type_net_profit * 1000;
                                            $unit = 'سوت';
                                        } else {
                                            $unit = 'گرم';
                                        }
                                    } else {
                                        $unit = 'گرم';
                                    }
                                    echo '
                                        <div>
                                            <label>
                                                ' . rtrim(rtrim(number_format($delivery_data->gold_type_net_profit, 4, '.', ''), '0'), '.') . '
                                            </label>
                                            <suffix>
                                                ' . esc_html__($unit  , 'almas-gold') . '
                                            </suffix>
                                        </div>
                                    ';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('سود خالص', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($delivery_data->final_price, 0, '.', ','); ?></label>
                                <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('وضعیت پرداخت', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label>
                                    <?php 
                                        if($delivery_data->transaction_status === "pending") {

                                            echo esc_html__('در انتظار', 'almas-gold'); 

                                        } else {

                                            echo esc_html__('تایید شد', 'almas-gold'); 

                                        }
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('تاریخ پرداخت', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo jdate('d F Y - H:i:s', strtotime($delivery_data->transaction_date)); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('وضعیت پردازش سفارش', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label>
                                    <?php 
                                        if($delivery_data->transaction_processed === "0") {

                                            echo esc_html__('در انتظار', 'almas-gold'); 

                                        } else {

                                            echo esc_html__('تکمیل شده', 'almas-gold'); 

                                        }
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('توضیحات سفارش', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label>
                                    <?php 
                                        if (empty($delivery_data->description) || $delivery_data->description === "null") {
                                            echo esc_html__('ندارد', 'almas-gold'); 
                                        } else {
                                            echo esc_attr($delivery_data->description); 
                                        }
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            /// print order
            document.getElementById('print_order').addEventListener('click', function(event) {
                event.preventDefault();
                var printContents = document.querySelector('.order-search-results').innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = '<div class="printable-area">' + printContents + '</div>';
                window.print();
                document.body.innerHTML = originalContents;
            });
        </script>
    <?php
