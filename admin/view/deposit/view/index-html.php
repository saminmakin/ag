<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin deposit html
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
        <div>
            <?php almas_gold_show_deposit_details(); ?>
        </div>
        <div class="wrap" id="almas_gold_admin_container_box" style="padding: 25px 7px 0 10px">
            <div class="list_head">
                <div style="position: relative">
                    <h4>
                        <?php esc_html_e('لیست درخواست واریز وجه', 'almas-gold'); ?>
                    </h4>
                    <div id="copy-message">کپی شد!</div>
                </div>
                <div>
                    <div style="display: flex;align-items: center;">
                        <div style="margin-left: 37px; color: #195a21;">
                            <?php
                                if (isset($_POST['delete_all_orders_submit'])) {
                                    global $wpdb;
                                    $table_name_deposit = $wpdb->prefix . 'almas_gold_deposit';

                                    $wpdb->query($wpdb->prepare("DELETE FROM $table_name_deposit WHERE transaction_processed != '1'"));
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
                        <td><?= esc_html__('شناسه درخواست', 'almas-gold'); ?></td>
                        <td><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></td>
                        <td><?= esc_html__('مشتری', 'almas-gold'); ?></td>
                        <td>
                            <?= esc_html__('مبلغ درخواست', 'almas-gold'); ?>
                            <span style="margin-right: 8px; font-size: 10px; color: #6a6969; font-weight: 100;">
                                تومان
                            </span>
                        </td>
                        <td><?= esc_html__('وضعیت درخواست', 'almas-gold'); ?></td>
                        <td><?= esc_html__('وضعیت واریز', 'almas-gold'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $counter = 1;
                        foreach ($deposit_orders as $row) :
                            $unique_deposit_id = $row->unique_deposit_id;
                            $deposit_id = $row->deposit_id;
                            $deposit_date = $row->deposit_date;
                            $firstname = $row->firstname;
                            $lastname = $row->lastname;
                            $final_deposit_amount = $row->final_deposit_amount;
                            $request_status = $row->request_status;
                            $payment_date = $row->payment_date;
                            $deposit_status = $row->deposit_status;
                            $transaction_processed = $row->transaction_processed;
                            ?>
                                <tr>
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
                                                    <span style="font-size: 13px !important;color: #b3b2b2">
                                                        '. esc_html__('در انتظار', 'almas-gold').'
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
                                                    <span style="font-size: 13px !important; color: #b3b2b2; display: block; height: 24px">
                                                        ' . esc_attr($firstname); ?> <?php echo esc_attr($lastname) .'
                                                    </span>
                                                    <span style="font-size: 12px !important;color: #b3b2b2;display: block;height: 24px;">
                                                        ' . esc_attr($customer_data->mobile) .'
                                                    </span>
                                                    <span style="font-size: 12px !important;color: #b3b2b2;display: block;height: 24px; font-family: arial !important">
                                                        ' . esc_attr($customer_data->card_number) .'
                                                    </span>
                                                    <span style="font-size: 12px !important;color: #b3b2b2;display: block;height: 24px;margin-bottom: 5px; font-family: arial !important">
                                                        ' . esc_attr($customer_data->iban_number) .'
                                                    </span>
                                                ';
                                            } else {
                                                echo '
                                                    <span style="font-size: 13px !important; color: #000; display: block; height: 24px">
                                                        ' . esc_attr($firstname); ?> <?php echo esc_attr($lastname) .'
                                                    </span>
                                                    <span style="font-size: 12px !important;color: #000;display: block;height: 24px;    ">
                                                        ' . esc_attr($customer_data->mobile) .'
                                                    </span>
                                                    <span class="copy-text" style="font-size: 12px !important;color: #000;display: block;height: 24px; font-family: arial !important">
                                                        ' . esc_attr($customer_data->card_number) .'
                                                    </span>
                                                    <span class="copy-text" style="font-size: 12px !important;color: #000;display: block;height: 24px;margin-bottom: 5px; font-family: arial !important">
                                                        ' . esc_attr($customer_data->iban_number) .'
                                                    </span>
                                                ';
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
                                            if ($deposit_status !== 'approved') {
                                                echo '
                                                    <span style="font-size: 13px !important;color: #b3b2b2">
                                                        '. esc_html__('در انتظار واریز', 'almas-gold').'
                                                    </span>
                                                ';
                                            } else {
                                                echo '
                                                    <span style="font-size: 13px !important;font-weight: 700;color: #42b576">
                                                        '. esc_html__('واریز شد', 'almas-gold') .'
                                                    </span>
                                                ';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if ($request_status !== 'approved') {
                                                echo '
                                                    <span>
                                                    </span>
                                                ';
                                            } else {
                                                echo '
                                                    <a class="show_detailes" href="'. admin_url('admin.php?page=almas-gold-deposit&deposit_id=' . esc_attr($deposit_id)).'">
                                                        ' . esc_html__('نمایش جزئیات', 'almas-gold') . '
                                                    </a>
                                                ';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if ($request_status !== 'approved') {
                                                echo '
                                                    <span>
                                                    </span>
                                                ';
                                            } else {
                                               
                                                ?>
                                                    <div style="margin-left: 37px; color: #195a21;">
                                                        <?php
                                                            if (isset($_POST['submit_deposit_to_account'])) {
                                                                global $wpdb;
                                                                $table_name_deposit = $wpdb->prefix . 'almas_gold_deposit';
                                                                $deposit_id = isset($row->deposit_id) ? $row->deposit_id : '';

                                                                $update_deposit_status = $wpdb->update(
                                                                    $table_name_deposit,
                                                                    array('deposit_status' => 'approved'), 
                                                                    array('deposit_id' => $deposit_id), 
                                                                    array('%s'), 
                                                                    array('%d') 
                                                                );
                                                            
                                                                if ($update_deposit_status !== false) {

                                                                    // almas_gold_send_deposit_sms(); 

                                                                    echo '
                                                                        <p>
                                                                            وضعیت واریز با موفقیت به انجام شده تغییر کرد
                                                                        </p>
                                                                    ';
                                                                } else {
                                                                    echo '
                                                                        <p>
                                                                            ناموفق! خطا در تغییر وضعیت.
                                                                        </p>
                                                                    ';
                                                                }
                                                                $current_screen = get_current_screen();
                                                                $page_slug = str_replace('toplevel_page_', '', $current_screen->base);
                                                                $pageUrl = admin_url('admin.php?page=' . $page_slug);
                                                                echo "<script>setTimeout(function() { window.location.href = '$pageUrl'; }, 600);</script>";
                                                            
                                                                exit; 
                                                            }
                                                        ?>
                                                    </div>
                                                    <?php 
                                                        if ($deposit_status !== 'approved') {
                                                            echo '
                                                                <div>
                                                                    <div class="submit_deposit_to_account_btn show_detailes">
                                                                        '. esc_html__('واریز وجه', 'almas-gold') .'
                                                                    </div>
                                                                </div>
                                                            ';

                                                        }
                                                    ?>
                                                    <div>
                                                        <form method="post">
                                                            <div class="submit_deposit_to_account_popup">
                                                                <h3 style="margin: 0 0 40px 0">
                                                                    <?= esc_html__('آیا از واریز وجه به حساب مشتری مطمئنید؟', 'almas-gold'); ?>
                                                                </h3>
                                                                <div style="margin-bottom: 30px;display: flex;justify-content: space-between;">
                                                                    <div>
                                                                        <button type="submit" name="submit_deposit_to_account">
                                                                            <?= esc_html__('واریز انجام شد', 'almas-gold'); ?>
                                                                        </button>
                                                                    </div>
                                                                    <div class="submit_deposit_to_account_close_popup delete_order_close_popup">
                                                                        <?= esc_html__('خیر', 'almas-gold'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <script>
                                                        $(document).ready(function(){
                                                            $('.submit_deposit_to_account_btn').click(function(){
                                                                $('.submit_deposit_to_account_popup').fadeIn(100);
                                                            });

                                                            $('.submit_deposit_to_account_close_popup').click(function(){
                                                                $('.submit_deposit_to_account_popup').fadeOut(100);
                                                            });
                                                        });
                                                    </script>
                                                <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <script>
                                    document.querySelectorAll('.copy-text').forEach(element => {
                                        element.addEventListener('click', function() {
                                            // متن مورد نظر برای کپی کردن
                                            const text = this.innerText;

                                            // ایجاد یک عنصر موقتی برای کپی کردن متن
                                            const textarea = document.createElement('textarea');
                                            textarea.value = text;
                                            document.body.appendChild(textarea);
                                            textarea.select();
                                            document.execCommand('copy');
                                            document.body.removeChild(textarea);

                                            // نمایش پیام کپی شد
                                            const message = document.getElementById('copy-message');
                                            message.classList.add('show');
                                            
                                            // مخفی کردن پیام بعد از 1 ثانیه با افکت محو شدن
                                            setTimeout(function() {
                                                message.classList.remove('show');
                                            }, 1000);
                                        });
                                    });
                                </script>
                                
                            <?php 
                        $counter++;
                        endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    <?php
