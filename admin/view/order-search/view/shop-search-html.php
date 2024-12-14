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
        <div id="order_search_results_box">
            <div class="order-search-results">
                <div class="order-search-results-order-header">
                    <div>
                        <span style="margin-left: 5px; font-weight: 700; font-size: 14px">
                            <?= esc_html__('نتیجه جستجو در فروش ها: ', 'almas-gold'); ?>
                        </span>
                        <span style="margin-right: 10px"><?= esc_html__('شناسه فروش', 'almas-gold'); ?></span> 
                        <label><?php echo esc_attr($shop_data->shop_id); ?></label>
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
                                    <img src="<?php echo esc_attr($shop_data->qrcode_image_url); ?>" onerror="this.style.display='none'; this.nextElementSibling.s  tyle.display='block';">
                                    <div class="qrc_placeholder">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </div>
                                </div>
                                <label><a href="<?php echo esc_attr($shop_data->qrcode_image_url); ?>" target="_blank"><?php echo esc_attr($shop_data->qrcode_image_url); ?></a></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه یونیک', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($shop_data->unique_shop_id); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه فروش', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($shop_data->shop_id); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></span>
                            <td>
                                <label><?php echo jdate('d F Y - H:i:s', strtotime($shop_data->shop_date)); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('نام کاربری مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($shop_data->user_name); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('نام مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($shop_data->firstname); ?> <?php echo esc_attr($shop_data->lastname); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه مشتری', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label style="font-family: tahoma !important;"><?php echo esc_attr($shop_data->unique_customer_id); ?></label>
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
                                <label><?php echo esc_attr($shop_data->gold_price); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('مبلغ اولیه', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($shop_data->initial_price, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('وزن طلا', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo rtrim(rtrim(number_format($shop_data->gold_weight, 4, '.', ''), '0'), '.'); ?></label>
                                <sufix><?= esc_html__('گرم', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                            <tr>
                            <td>
                                <span><?= esc_html__('پرداخت با کیف پول', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($shop_data->price_payed_by_wallet, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('پرداخت با شتاب', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($shop_data->price_payed_online, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('مبلغ نهایی اولبه', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($shop_data->initial_final_price, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('مبلغ نهایی', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($shop_data->final_price, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('جمع مالیات', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo number_format($shop_data->total_shop_tax, 0, '.', ','); ?></label>
                                <sufix><?= esc_html__('تومان', 'almas-gold'); ?></sufix>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('وضعیت پرداخت', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label>
                                    <?php 
                                        if($shop_data->transaction_status === "pending") {
                                            echo esc_html__('در انتظار', 'almas-gold'); 
                                        } else {
                                            echo esc_html__('تایید شده', 'almas-gold'); 
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
                                <label><?php echo jdate('d F Y - H:i:s', strtotime($shop_data->transaction_date)); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('شناسه پرداخت', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label><?php echo esc_attr($shop_data->transaction_id); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><?= esc_html__('وضعیت پردازش سفارش', 'almas-gold'); ?></span>
                            </td>
                            <td>
                                <label>
                                    <?php 
                                        if($shop_data->transaction_processed === "0") {

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
                                        if (empty($shop_data->description) || $shop_data->description === "null") {
                                            echo esc_html__('ندارد', 'almas-gold'); 
                                        } else {
                                            echo esc_attr($shop_data->description); 
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
    