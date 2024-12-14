<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin shop html
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
        <div>
            <?php almas_gold_show_shop_details(); ?>
        </div>
        <div class="wrap" id="almas_gold_admin_container_box" style="padding: 25px 7px 0 10px">
            <div class="list_head">
                <div>
                    <h4>
                        <?php esc_html_e('لیست فروش ها', 'almas-gold'); ?>
                    </h4>
                </div>

                <div>
                    <div style="display: flex;align-items: center;">
                        <div style="margin-left: 37px; color: #195a21;">
                            <?php
                                if (isset($_POST['delete_all_orders_submit'])) {
                                    global $wpdb;
                                    $table_name_shop = $wpdb->prefix . 'almas_gold_shop';

                                    $wpdb->query($wpdb->prepare("DELETE FROM $table_name_shop WHERE transaction_processed != '1'"));
                                    ?>
                                        <script>
                                            var currentPageSlug = '<?php
                                                $current_screen = get_current_screen();
                                                $page_slug = str_replace( 'toplevel_page_', '', $current_screen->base );
                                                echo esc_js( $page_slug );
                                            ?>';
                                            var pageUrl = '<?php echo esc_js( admin_url( 'admin.php?page=' ) ); ?>' + currentPageSlug;

                                            setTimeout(function() {
                                                window.location.href = pageUrl;
                                            }, 600);
                                        </script>
                                    <?php
                                    echo 'سفارشهای منقضی با موفقیت حذف شدند.';
                                    exit;
                                }
                            ?>
                        </div>
                        <div>
                            <div class="submit_delete_all_expired_orders">
                                <?= esc_html__('حذف همه سفارشهای منقضی', 'almas-gold'); ?>
                            </div>
                        </div>
                        <div>
                            <form method="post">
                                <div class="delete_order_popup_all">
                                    <h3 style="margin: 0 0 40px 0">
                                        <?= esc_html__('آیا از حذف همه سفارشهای منقضی مطمئنید؟', 'almas-gold'); ?>
                                    </h3>
                                    <div style="margin-bottom: 30px;display: flex;justify-content: space-between;">
                                        <div>
                                            <button type="submit" name="delete_all_orders_submit">
                                                <?= esc_html__('حذف همه سفارشهای منقضی', 'almas-gold'); ?>
                                            </button>
                                        </div>
                                        <div class="delete_order_close_popup_all delete_order_close_popup">
                                            <?= esc_html__('خیر', 'almas-gold'); ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('.submit_delete_all_expired_orders').click(function(){
                                    $('.delete_order_popup_all').fadeIn(100);
                                });

                                $('.delete_order_close_popup_all').click(function(){
                                    $('.delete_order_popup_all').fadeOut(100);
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
            <table class="wp-list-table widefat striped" style="width: 100%">
                <thead>
                    <tr>
                        <td width="50"><span style="margin-right: 15px"><?= esc_html__('#', 'almas-gold'); ?></span></td>
                        <td><?= esc_html__('شناسه فروش', 'almas-gold'); ?></td>
                        <td><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></td>
                        <td><?= esc_html__('مشتری', 'almas-gold'); ?></td>
                        <td><?= esc_html__('وزن', 'almas-gold'); ?></td>
                        <td>
                            <?= esc_html__('مبلغ سفارش', 'almas-gold'); ?>
                            <span style="margin-right: 8px; font-size: 10px; color: #6a6969; font-weight: 100;">
                                تومان
                            </span>
                        </td>
                        <td>
                            <?= esc_html__('مالیات', 'almas-gold'); ?>
                            <span style="margin-right: 8px; font-size: 10px; color: #6a6969; font-weight: 100;">
                                تومان
                            </span>
                        </td>
                        <td><?= esc_html__('زمان تراکنش', 'almas-gold'); ?></td>
                        <td><?= esc_html__('وضعیت پرداخت', 'almas-gold'); ?></td>
                        <td><?= esc_html__('شناسه پرداخت', 'almas-gold'); ?></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $counter = 1;
                        foreach ($shop_orders as $row) :
                            $shop_id = $row->shop_id;
                            $shop_date = $row->shop_date;
                            $firstname = $row->firstname;
                            $lastname = $row->lastname;
                            $customer_user_id = $row->user_id;
                            $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile FROM " . $table_name . " WHERE user_id = %d", $customer_user_id));
                            $mobile = $customer_data->mobile;
                            $gold_weight = $row->gold_weight;
                            $initial_final_price = $row->initial_final_price;
                            $total_shop_tax = $row->total_shop_tax;
                            $transaction_id = $row->transaction_id;
                            $transaction_date = $row->transaction_date;
                            $transaction_status = $row->transaction_status;
                            $transaction_processed = $row->transaction_processed;
                            ?>
                                <tr>
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
                                                        <span style="font-size: 12px !important;color: #b3b2b2;">
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
                                                        <span style="font-size: 12px !important;color: #000;">
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
                                            if ($initial_final_price === '0') {
                                                echo '
                                                    <span style="font-size: 13px !important; color: #b3b2b2; display: block; height: 24px">
                                                        ' . esc_attr($firstname); ?> <?php echo esc_attr($lastname) .'
                                                    </span>
                                                    <span style="font-size: 12px !important;color: #b3b2b2;display: block;height: 24px;margin-bottom: 5px;">
                                                        ' . esc_attr($mobile) .'
                                                    </span>
                                                ';
                                            } else {
                                                echo '
                                                    <span style="font-size: 13px !important; color: #000; display: block; height: 24px">
                                                        ' . esc_attr($firstname); ?> <?php echo esc_attr($lastname) .'
                                                    </span>
                                                    <span style="font-size: 12px !important;color: #000;display: block;height: 24px;margin-bottom: 5px;">
                                                        ' . esc_attr($mobile) .'
                                                    </span>
                                                ';
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
                                                    <span style="font-size: 13px !important;font-weight: 300;color: #000">
                                                        ' . rtrim(rtrim(number_format($gold_weight, 4, '.', ''), '0'), '.') . '
                                                    </span>
                                                    <span style="font-size: 10px !important; margin-right: 10px">
                                                        ' . esc_html__($unit  , 'almas-gold') . '
                                                    </span>
                                                ';
                                            } else {
                                                echo '
                                                    <span style="font-size: 13px !important;font-weight: 700;color: #000">
                                                        ' . rtrim(rtrim(number_format($gold_weight, 4, '.', ''), '0'), '.') . '
                                                    </span>
                                                    <span style="font-size: 10px !important; margin-right: 10px">
                                                        ' . esc_html__($unit  , 'almas-gold') . '
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
                                                    <span style="font-size: 13px !important;color: #b3b2b2">
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
                                                    <span style="font-size: 13px !important;color: #b3b2b2">
                                                        '. esc_html__('در انتظار', 'almas-gold').'
                                                    </span>
                                                ';
                                            } else {
                                                echo '
                                                    <span style="font-size: 13px !important;font-weight: 700;color: #42b576">
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
                                                    <span style="font-size: 13px !important;color: #b3b2b2">
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
                                                echo '<span></span>';
                                            } else {
                                                echo '
                                                    <a class="show_detailes" href="'. admin_url('admin.php?page=almas-gold-shop&shop_id=' . esc_attr($shop_id)).'">
                                                        ' . esc_html__('نمایش جزئیات', 'almas-gold') . '
                                                    </a>
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
    <?php
