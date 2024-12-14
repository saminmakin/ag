<?php
    defined('ABSPATH') || exit;

    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold class admin settings html
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
        <form method="post">
            <div id="almas_gold_core_settings">
                <div class="header_box">
                    <div class="tab">
                        <a class="tablinks active" data-tab="tab-shop"><?php esc_html_e('فروش', 'almas-gold'); ?></a>
                        <a class="tablinks" data-tab="tab-sale"><?php esc_html_e('خرید', 'almas-gold'); ?></a>
                        <a class="tablinks" data-tab="tab-delivery"><?php esc_html_e('دریافت', 'almas-gold'); ?></a>
                        <a class="tablinks" data-tab="tab-deposit"><?php esc_html_e('درخواست واریز وجه', 'almas-gold'); ?></a>
                        <a class="tablinks" data-tab="tab-recharge"><?php esc_html_e('شارژ کیف پول', 'almas-gold'); ?></a>
                        <a class="tablinks" data-tab="tab-general"><?php esc_html_e('تنظیمات عمومی', 'almas-gold'); ?></a>
                    </div>
                    <div>
                        <button class="save_button" type="submit" name="almas_gold_save_core_settings">
                            <?php esc_html_e('ذخیره تنظیمات', 'almas-gold'); ?>
                        </button>
                    </div>
                </div>
                <div id="almas_gold_admin_box">
                    <div id="tab-shop" class="tabcontent active">
                        <h4><?php esc_html_e('تنظیمات فروش', 'almas-gold'); ?></h4>
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <label><?php esc_html_e('مالیات', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="shop_tax" value="<?php echo esc_attr($core_data->shop_tax); ?>" />
                                <span><?= esc_html__('درصد', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?php esc_html_e('حداقل وزن', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="shop_lowest_limit" value="<?php echo esc_attr($core_data->shop_lowest_limit); ?>" />
                                <span><?= esc_html__('گرم', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?php esc_html_e('حداکثر مبلغ فروش', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="1" name="shop_highest_price_limit" value="<?php echo esc_attr($core_data->shop_highest_price_limit); ?>" />
                                <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div id="tab-sale" class="tabcontent">
                        <h4><?php esc_html_e('تنظیمات خرید', 'almas-gold'); ?></h4>
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <label><?php esc_html_e('مالیات', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="sale_tax" value="<?php echo esc_attr($core_data->sale_tax); ?>" />
                                <span><?= esc_html__('درصد', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?php esc_html_e('حداقل وزن', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="sale_lowest_limit" value="<?php echo esc_attr($core_data->sale_lowest_limit); ?>" />
                                <span><?= esc_html__('گرم', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?php esc_html_e('حداکثر وزن', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="sale_highest_limit" value="<?php echo esc_attr($core_data->sale_highest_limit); ?>" />
                                <span><?= esc_html__('گرم', 'almas-gold'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div id="tab-delivery" class="tabcontent">
                        <h4><?php esc_html_e('تنظیمات دریافت', 'almas-gold'); ?></h4>
                        <div style="display: flex;justify-content: space-between;flex-direction: row;flex-wrap: wrap;">
                            <div>
                                <label><?php esc_html_e('حداقل وزن', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="delivery_lowest_limit" value="<?php echo esc_attr($core_data->delivery_lowest_limit); ?>" />
                                <span><?= esc_html__('گرم', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?php esc_html_e('حداکثر وزن', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="delivery_highest_limit" value="<?php echo esc_attr($core_data->delivery_highest_limit); ?>" />
                                <span><?= esc_html__('گرم', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?= esc_html__('سود شکسته', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="broken_gold_fee" value="<?php echo esc_attr($core_data->broken_gold_fee); ?>" />
                                <span><?= esc_html__('درصد', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?= esc_html__('سود ساخته بدون اجرت', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="without_fee_gold_fee" value="<?php echo esc_attr($core_data->without_fee_gold_fee); ?>" />
                                <span><?= esc_html__('درصد', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?= esc_html__('سود ساخته کم اجرت', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="low_fee_gold_fee" value="<?php echo esc_attr($core_data->low_fee_gold_fee); ?>" />
                                <span><?= esc_html__('درصد', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?php esc_html_e('سود پولک', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="sequins_gold_fee" value="<?php echo esc_attr($core_data->sequins_gold_fee); ?>" />
                                <span><?= esc_html__('درصد', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?php esc_html_e('سود شمش', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="bullion_gold_fee" value="<?php echo esc_attr($core_data->bullion_gold_fee); ?>" />
                                <span><?= esc_html__('درصد', 'almas-gold'); ?></span>
                            </div>
                            <div>
                                <label><?php esc_html_e('محدودیت وزن تحویل شمش', 'almas-gold'); ?></label>
                                <input class="valux" type="number" step="0.001" name="bullion_gold_limit" value="<?php echo esc_attr($core_data->bullion_gold_limit); ?>" />
                                <span><?= esc_html__('گرم', 'almas-gold'); ?></span>    
                            </div>
                        </div>
                    </div>
                    <div id="tab-deposit" class="tabcontent">
                        <h4><?php esc_html_e('تنظیمات درخواست واریز وجه', 'almas-gold'); ?></h4>
                        <label><?php esc_html_e('کارمزد واریز وجه', 'almas-gold'); ?></label>
                        <br>
                        <input class="amiux" type="number" name="deposit_fee" value="<?php echo esc_attr($core_data->deposit_fee); ?>" />
                        <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                        <br>
                        <label><?php esc_html_e('حداقل مبلغ واریز وجه', 'almas-gold'); ?></label>
                        <br>
                        <input class="amiux" type="number" name="deposit_lowest_limit" value="<?php echo esc_attr($core_data->deposit_lowest_limit); ?>" />
                        <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                        <br>
                        <label><?php esc_html_e('حداکثر مبلغ واریز وجه', 'almas-gold'); ?></label>
                        <br>
                        <input class="amiux" type="number" name="deposit_highest_limit" value="<?php echo esc_attr($core_data->deposit_highest_limit); ?>" />
                        <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                    </div>
                    <div id="tab-recharge" class="tabcontent">
                        <h4><?php esc_html_e('تنظیمات شارژ کیف پول', 'almas-gold'); ?></h4>
                        <label><?php esc_html_e('کارمزد شارژ', 'almas-gold'); ?></label>
                        <br>
                        <input class="amiux" type="number" name="recharge_fee" value="<?php echo esc_attr($core_data->recharge_fee); ?>" />
                        <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                        <br>
                        <label><?php esc_html_e('حداقل مبلغ شارژ', 'almas-gold'); ?></label>
                        <br>
                        <input class="amiux" type="number" name="recharge_lowest_limit" value="<?php echo esc_attr($core_data->recharge_lowest_limit); ?>" />
                        <span><?= esc_html__('تومان', 'almas-gold'); ?><span>
                        <br>
                        <label><?php esc_html_e('حداکثر مبلغ شارژ', 'almas-gold'); ?></label>
                        <br>
                        <input class="amiux" type="number" name="recharge_highest_limit" value="<?php echo esc_attr($core_data->recharge_highest_limit); ?>" />
                        <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                    </div>
                    <div id="tab-general" class="tabcontent">
                        <h4><?php esc_html_e('تنظیمات عمومی', 'almas-gold'); ?></h4>
                        <label><?php esc_html_e('قیمت هر گرم', 'almas-gold'); ?></label>
                        <br>
                        <input class="amiux" type="number" name="gold_price" value="<?php echo esc_attr($core_data->gold_price); ?>" />
                        <span><?= esc_html__('تومان', 'almas-gold'); ?></span>
                        <br>
                        <br>
                        <input type="checkbox" name="gold_unit_to_customer" <?php checked($core_data->gold_unit_to_customer, 1); ?> />
                        <label><?php esc_html_e('نمایش سوت بجای گرم در پروفایل کاربر', 'almas-gold'); ?></label>
                        <br>
                        <br>
                        <input type="checkbox" name="gold_unit_to_bills" <?php checked($core_data->gold_unit_to_bills, 1); ?> />
                        <label><?php esc_html_e('نمایش سوت بجای گرم در صورت‌حساب‌ها', 'almas-gold'); ?></label>
                        <br>
                        <br>
                        <input type="checkbox" name="lists_date_display" <?php checked($core_data->lists_date_display, 1); ?> />
                        <label><?php esc_html_e('نمایش زمانی ساده برای لیست سفارش ها', 'almas-gold'); ?></label>
                        <br>
                        <br>
                        <input class="amiux" type="text" name="admin_phone" value="<?php echo esc_attr($core_data->admin_phone); ?>" />
                        <label><?php esc_html_e('شماره موبایل ادمین ها', 'almas-gold'); ?></label>
                    </div>
                </div>
            </div>
        </form>
        <script>
            jQuery(document).ready(function($) {
                $('.tablinks').click(function() {
                    var tab_id = $(this).attr('data-tab');
                    $('.tablinks').removeClass('active');
                    $(this).addClass('active');
                    $('.tabcontent').removeClass('active').hide();
                    $('#' + tab_id).addClass('active').fadeIn();
                });
            });
        </script>
    <?php