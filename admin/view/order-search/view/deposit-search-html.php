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
                            <?= esc_html__('نتیجه جستجو در درخواست های واریز: ', 'almas-gold'); ?>
                        </span>
                        <span style="margin-right: 10px"><?= esc_html__('شناسه درخواست', 'almas-gold'); ?></span> 
                        <label><?php echo esc_attr($deposit_data->deposit_id); ?></label>
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
                                    <img src="<?php echo esc_attr($deposit_data->qrcode_image_url); ?>" onerror="this.style.display='none'; this.nextElementSibling.s  tyle.display='block';">
                                    <div class="qrc_placeholder">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </div>
                                </div>
                                <label><a href="<?php echo esc_attr($deposit_data->qrcode_image_url); ?>" target="_blank"><?php echo esc_attr($deposit_data->qrcode_image_url); ?></a></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه یونیک', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($deposit_data->unique_deposit_id); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه درخواست', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($deposit_data->deposit_id); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></span>
                            <td>
                                <label><?php echo jdate('d F Y - H:i:s', strtotime($deposit_data->deposit_date)); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('نام کاربری مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($deposit_data->user_name); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('نام مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($deposit_data->firstname); ?> <?php echo esc_attr($deposit_data->lastname); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($deposit_data->unique_customer_id); ?></label>
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
                                <span><?= esc_html__('مبلغ قابل واریز', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($deposit_data->final_deposit_amount, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('مبلغ سفارش', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($deposit_data->deposit_amount, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('کارمزد واریز', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($deposit_data->deposit_fee, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('وضعیت واریز', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label>
                                    <?php 
                                        if($deposit_data->deposit_status === "pending") {

                                            echo esc_html__('در انتظار واریز', 'almas-gold'); 

                                        } else {

                                            echo esc_html__('واریز شد', 'almas-gold'); 

                                        }
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('تاریخ واریز', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo jdate('d F Y - H:i:s', strtotime($deposit_data->payment_date)); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('وضعیت پردازش سفارش', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label>
                                    <?php 
                                        if($deposit_data->transaction_processed === "0") {
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
                                        if (empty($deposit_data->description) || $deposit_data->description === "null") {
                                            echo esc_html__('ندارد', 'almas-gold'); 
                                        } else {
                                            echo esc_attr($deposit_data->description); 
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
    