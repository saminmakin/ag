<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin delivery html
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Include jQuery (Toastr requires jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <div>
            <?php almas_gold_show_delivery_details(); ?>
        </div>
        <div class="wrap" id="almas_gold_admin_container_box" style="padding: 25px 7px 0 10px">
            <div class="list_head">
                <div>
                    <h4>
                        <?php esc_html_e('لیست تحویل ها', 'almas-gold'); ?>
                    </h4>
                </div>
                <div>
                    <div style="display: flex;align-items: center;">
                        <div style="margin-left: 37px; color: #195a21;">
                            <?php
                                if (isset($_POST['delete_all_orders_submit'])) {
                                    global $wpdb;
                                    $table_name_delivery = $wpdb->prefix . 'almas_gold_delivery';
                                    $wpdb->query($wpdb->prepare("DELETE FROM $table_name_delivery WHERE transaction_processed != '1'"));
                                    ?>
                                    <script>
                                        var pageSlug = '<?php echo esc_js($_GET['page']); ?>';
                                        var pageUrl = '<?php echo esc_js(admin_url('admin.php?page=')); ?>' + pageSlug;
                                        setTimeout(function() {
                                            window.location.href = pageUrl;
                                        }, 600);
                                    </script>
                                    <?php echo 'سفارشهای منقضی با موفقیت حذف شدند';
                                    exit;
                                }
                            ?>
                        </div>
                        <div>
                            <div class="submit_edit_delivery_status">
                                <a href="<?php echo admin_url(); ?>admin.php?page=almas-gold-edit-delivery-status">
                                    <?= esc_html__('تحویل طلا به مشتری', 'almas-gold'); ?>
                                </a>
                            </div>
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
                        <td><?= esc_html__('شناسه تحویل', 'almas-gold'); ?></td>
                        <td><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></td>
                        <td><?= esc_html__('مشتری', 'almas-gold'); ?></td>
                        <td><?= esc_html__('نوع طلا', 'almas-gold'); ?></td>
                        <td><?= esc_html__('وزن', 'almas-gold'); ?></td>
                        <td>
                            <?= esc_html__('مبلغ طلا', 'almas-gold'); ?>
                            <span style="margin-right: 8px; font-size: 10px; color: #6a6969; font-weight: 100;">
                                تومان
                            </span>
                        </td>
                        <td>
                            <?= esc_html__('مبلغ سفارش', 'almas-gold'); ?>
                            <span style="margin-right: 8px; font-size: 10px; color: #6a6969; font-weight: 100;">
                                تومان
                            </span>
                        </td>
                        <td><?= esc_html__('زمان تراکنش', 'almas-gold'); ?></td>
                        <td><?= esc_html__('وضعیت پرداخت', 'almas-gold'); ?></td>
                        <td><?= esc_html__('شناسه پرداخت', 'almas-gold'); ?></td>
                        <td><?= esc_html__('وضعیت تحویل', 'almas-gold'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $counter = 1;
                        foreach ($delivery_orders as $row) :
                            $delivery_id = $row->delivery_id;
                            $unique_delivery_id = $row->unique_delivery_id;
                            $delivery_request_date = $row->delivery_request_date;
                            $firstname = $row->firstname;
                            $lastname = $row->lastname;
                            $customer_user_id = $row->user_id;
                            $table_name = $wpdb->prefix . "almas_gold_customers";
                            $customer_data = $wpdb->get_row($wpdb->prepare("SELECT mobile FROM " . $table_name . " WHERE user_id = %d", $customer_user_id));
                            $mobile = $customer_data->mobile;
                            $order_status = $row->order_status;
                            if ($order_status == 'delivered') {
                                $gold_weight = $row->delivery_gold_weight;
                                $gold_type = $row->delivery_gold_type;
                                $initial_price = $row->delivery_total_gold_price;
                                $initial_final_price = $row->delivery_gold_type_fee_amount;
                                $final_price = $row->delivery_final_price;
                            } else {
                                $gold_weight = $row->gold_weight;
                                $gold_type = $row->gold_type;
                                $initial_price = $row->initial_price;
                                $initial_final_price = $row->initial_final_price;
                                $final_price = $row->final_price;
                            }
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

                                    <!---وضعیت تحویل--->
                                    <td>
                                        <span>
                                            <?php 
                                                if ($order_status == 'waitforshop') {
                                                    echo '
                                                        '. esc_html__('در انتظار فروشگاه', 'almas-gold').'
                                                    ';
                                                } elseif ($order_status == 'waitforcustomer'){
                                                    echo '
                                                        '. esc_html__('در انتظار مشتری', 'almas-gold').'
                                                    ';
                                                } elseif ($order_status == 'delivered'){
                                                    echo '
                                                        '. esc_html__('تحویل شد', 'almas-gold').'
                                                    ';
                                                }
                                            ?>
                                        </span>
                                    </td>

                                    <!---وضعیت اکس--->
                                    <td>
    <?php 
        if ($order_status == 'waitforshop') {
            // دکمه تایید سفارش برای نمایش پاپ‌آپ
            echo '
                <input type="hidden" name="delivery_id" id="delivery_id_field" value="' . esc_attr($delivery_id) . '">
                <button type="button" class="submit_delivery_to_account_btn show_detailes" data-delivery-id="' . esc_attr($delivery_id) . '" onclick="showConfirmationPopup(this)">
                    ' . esc_html__('تایید سفارش', 'almas-gold') . '
                </button>

                <!-- پاپ‌آپ تایید سفارش -->
                
            ';

            // بررسی ارسال فرم
            if (isset($_POST['submit_delivery_to_account_accept']) && isset($_POST['delivery_id']) && intval($_POST['delivery_id']) > 0) {
                 var_dump($_POST['delivery_id'], $delivery_id);
                global $wpdb;
                $table_name_delivery = $wpdb->prefix . 'almas_gold_delivery';
                $delivery_id_post = isset($_POST['delivery_id']) ? intval($_POST['delivery_id']) : 0;

                // به‌روزرسانی وضعیت سفارش
                $update_order_status = $wpdb->update(
                    $table_name_delivery,
                    array('order_status' => 'waitforcustomer'), 
                    array('delivery_id' => $delivery_id_post), 
                    array('%s'), 
                    array('%d') 
                );

                if ($update_order_status !== false) {
                    // ارسال پیامک و ذخیره پیام تایید
                    almas_gold_send_delivery_wait_for_customer_sms($delivery_id_post); 
                    $_SESSION['order_update_msg'] = 'وضعیت به تایید شده تغییر کرد';
                } else {
                    $_SESSION['order_update_msg'] = 'ناموفق! خطا در تغییر وضعیت.';
                }

                echo "<script>setTimeout(function() { location.reload(); }, 600);</script>";
            }

            // نمایش پیام تایید بعد از رفرش
            if (isset($_SESSION['order_update_msg'])) {
                echo '
        <style>
        .toast-progress {
            background: #007bff; /* رنگ خط پیشرفت */
            height: 5px; /* ارتفاع خط پیشرفت */
        }
    </style>
    
    <script>
        $(document).ready(function() {
            var toast = toastr.success("' . addslashes(esc_html($_SESSION['order_update_msg'])) . '", {
                timeOut: 3000, // زمان نمایش نوتیفیکیشن به میلی‌ثانیه
                extendedTimeOut: 10000, // زمان اضافی هنگام هاور روی نوتیفیکیشن
                closeButton: true,
                progressBar: true, // نمایش خط پیشرفت
                positionClass: "toast-top-right", // موقعیت نوتیفیکیشن
                onShown: function() {
                    // شروع پر کردن خط پیشرفت
                    var $toast = $(this);
                    var progressBar = $toast.find(".toast-progress");
                    progressBar.css("width", "100%").delay(3000).fadeOut(300);
                }
            });

            // متوقف کردن خط پیشرفت در زمان هاور
            $(document).on("mouseenter", ".toast", function() {
                $(this).stop().find(".toast-progress").stop().fadeIn();
            }).on("mouseleave", ".toast", function() {
                $(this).find(".toast-progress").fadeOut();
            });
        });
    </script>
    ';
                unset($_SESSION['order_update_msg']);
            }
        }
    ?>
</td>
                                    

                                    <td>
                                        <?php 
                                            if ($transaction_status !== 'approved') {
                                                echo '
                                                    <span>
                                                    </span>
                                                ';
                                            } else {
                                                echo '
                                                    <a class="show_detailes" href="'. admin_url('admin.php?page=almas-gold-delivery&delivery_id=' . esc_attr($delivery_id)).'">
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
            <?php
            echo '
            <div id="confirmationPopup">
                    <form method="post">
                        <div class="submit_delivery_to_account_popup">
                            <h3 style="margin: 0 0 40px 0">
                                ' . esc_html__('آیا از تایید سفارش مطمئنید؟', 'almas-gold') . '
                            </h3>
                            <div style="margin-bottom: 30px; display: flex; justify-content: space-between;">
                                <input type="hidden" name="delivery_id" id="delivery_id_field_popup">
                                <button type="submit" name="submit_delivery_to_account_accept" class="submit_delivery_to_account_btn show_detailes">
                                    ' . esc_html__('تایید سفارش', 'almas-gold') . '
                                </button>
                                
                                <div class="submit_delivery_to_account_close_popup delete_order_close_popup" onclick="hideConfirmationPopup()">
                                    ' . esc_html__('خیر', 'almas-gold') . '
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                ';
                ?>
                <script>
                                        function showConfirmationPopup(button) {
    const deliveryId = button.getAttribute('data-delivery-id');
    console.log("Opening popup for delivery ID:", deliveryId);
    document.getElementById("delivery_id_field").value = deliveryId;
    document.getElementById("delivery_id_field_popup").value = deliveryId;
    $('.submit_delivery_to_account_popup').fadeIn(100);
}
                                        
                                        function hideConfirmationPopup() {
                                            $('.submit_delivery_to_account_popup').fadeOut(100);
                                        }
                                    </script>
        </div>
    <?php
