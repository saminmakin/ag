<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin edit delivery status
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
       <div class="admin_order_details_box" style="margin: 0 auto !important">
            <div class="delivery_bill_box">
                <div style="display: flex; align-items: center;">
                    <div>
                        <div class="qrcode">
                            <img src="<?php echo esc_attr($delivery_data->qrcode_image_url); ?>" onerror="this.style.display='none'; this.nextElementSibling.s  tyle.display='block';">
                            <div class="qrc_placeholder">
                                <i class="fa-solid fa-qrcode"></i>
                            </div>
                        </div>
                    </div>
                    <div>  
                        <div style="display: flex; align-items: center; margin-bottom: 5px">
                            <span style="margin-left: 15px"><?= esc_html__('مشتری', 'almas-gold'); ?></span>
                            <label><?php echo esc_attr($delivery_data->firstname); ?> <?php echo esc_attr($delivery_data->lastname); ?></label>
                        </div>
                        <div style="display: flex; align-items: center; margin-bottom: 5px">
                            <span style="margin-left: 15px"><?= esc_html__('شناسه تحویل', 'almas-gold'); ?></span>
                            <label><?php echo esc_attr($delivery_data->delivery_id); ?></label>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <span style="margin-left: 15px"><?= esc_html__('تاریخ سفارش', 'almas-gold'); ?></span>
                            <label><?php echo jdate('d F Y - H:i:s', strtotime($delivery_data->delivery_request_date)); ?></label>
                        </div>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <span style="margin-left: 10px">    
                            <?= esc_html__('قیمت طلا', 'almas-gold'); ?>
                        </span>
                        <div>
                            <label>
                                <?php echo number_format($delivery_data->gold_price, 0, '.', ','); ?>
                            </label>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                    <div class="flex-space-between-align-center">
                        <span style="margin-left: 10px">    
                            <?= esc_html__('اجرت طلا', 'almas-gold'); ?>
                        </span>
                        <label>
                            <?php echo number_format($delivery_data->initial_final_price, 0, '.', ','); ?>
                        </label>
                        <suffix>
                            <?= esc_html__('تومان', 'almas-gold'); ?>
                        </suffix>
                    </div>
                    <div>
                        <span style="margin-left: 12px">
                            <?= esc_html__('نوع طلا', 'almas-gold'); ?>
                        </span>
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
                    </div>
                </div>
                <div class="hr"></div>
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <span style="margin-left: 12px">
                            <?= esc_html__('وزن طلا', 'almas-gold'); ?>
                        </span>
                        <div>
                            <label>
                                <?php echo rtrim(rtrim(number_format($delivery_data->gold_weight, 4, '.', ''), '0'), '.'); ?>
                            </label>
                            <suffix>
                                <?= esc_html__('گرم', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <div class="flex-space-between-align-center">
                        <span style="margin-left: 12px">
                            <?= esc_html__('مبلغ طلا', 'almas-gold'); ?>
                        </span>
                        <label>
                            <?php echo number_format($delivery_data->initial_price, 0, '.', ','); ?>
                        </label>
                        <suffix>
                            <?= esc_html__('تومان', 'almas-gold'); ?>
                        </suffix>
                    </div>
                </div>
                <div style="margin-top: 20px">
                    <textarea 
                        name="delivery_description" 
                        style="width: 100%; background-color: #f3f3f3;"
                        placeholder="توضیحات سفارش"
                    ><?php echo esc_attr($delivery_data->description); ?></textarea>
                </div>
            </div>
            <div>
                <div class="new_gold_weight_submit_box flex-space-between-align-center" style="margin-bottom: 25px; border-radius: 8px !important">
                    <div class="flex-space-between-align-center">
                        <div>
                            <span>
                                <?= esc_html__('قیمت زنده طلا', 'almas-gold'); ?>
                            </span>
                            <label style="margin-right: 20px">
                                <?php echo number_format($core_data->gold_price, 0, '.', ','); ?>
                            </label>
                            <suffix><?= esc_html__('تومان', 'almas-gold'); ?></suffix>
                        </div>
                        <div>
                            <?php 
                                $gold_price_diff = abs($core_data->gold_price - $delivery_data->gold_price);
                                if ($core_data->gold_price > $delivery_data->gold_price){
                                    ?>
                                        <label style="color: #181818; margin-right: 20px;padding: 3px 8px 2px 8px;background-color: #90efc2;border-radius: 3px;font-size: 95%;">
                                            <?php echo number_format($gold_price_diff, 0, '.', ','); ?>
                                        </label>
                                    <?php
                                } else {
                                    ?>
                                        <label style="color: #181818;margin-right: 20px;padding: 3px 8px 2px 8px;background-color: #f5bdcf;border-radius: 3px;font-size: 95%;">
                                            <?php echo number_format($gold_price_diff, 0, '.', ','); ?> 
                                        </label>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div>
                        <label class="checkbox_cont">
                            <input type="checkbox" id="change_gold_type_checkbox" name="change_gold_type_checkbox" value="1">
                            <span class="checkmark"></span>
                            <label for="change_gold_type_checkbox" style="margin-right: 35px">
                                <?= esc_html__('تغییر نوع طلا', 'almas-gold'); ?>
                            </label>
                        </label>
                    </div>
                </div>

                <form method="post">

                    <?php require_once 'delivery-normal-html.php'; ?>
                    <?php require_once 'delivery-cgt-html.php'; ?>
                    
                </form>

                <script>
                    $('#change_gold_type_checkbox').change(function() {
                        if ($(this).is(':checked')) {
                            $('#delivery_current_order').slideUp(250);
                            $('#delivery_cgt_order').slideDown(250);
                        } else {
                            $('#delivery_current_order').slideDown(250);
                            $('#delivery_cgt_order').slideUp(250);
                        }
                    });
                </script>
            </div>
        </div>
    <?php
