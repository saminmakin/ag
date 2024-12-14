<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin sale html
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
        <div class="admin_order_details_box">
            
            <div class="info-box">
                <div class="flex-space-between">
                    <div>
                        <h4 style="margin-bottom: 5px !important">
                        <i class="fa-solid fa-tag" aria-hidden="true" style="font-size: 15px !important; margin-left: 5px;"></i>
                        <?= esc_html__('جزییات سفارش', 'almas-gold'); ?>
                        </h4>
                    </div>
                    <div>
                        <?php echo esc_attr($unique_sale_id); ?>
                    </div>
                </div>
                <div class="hrh"></div>
                <div class="flex-space-between-align-center">
                    <div class="flex">
                        <div class="qrcode">
                            <?php echo '<img src="' . $qrcode_image_url . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">'; ?>
                            <div class="qrc_placeholder">
                                <i class="fa-solid fa-qrcode"></i>
                            </div>
                        </div>
                        <div>
                            <span>
                                <?php echo esc_attr($firstname); ?> <?php echo esc_attr($lastname); ?>
                            </span>
                            <div>
                                <label>
                                    <?= esc_html__('شناسه سفارش', 'almas-gold'); ?>
                                </label>
                                <span>
                                    <?php echo esc_attr($sale_id); ?>
                                </span>
                            </div>
                            <div>
                                <label>
                                    <?= esc_html__('تاریخ سفارش', 'almas-gold'); ?>
                                </label>
                                <span>
                                    <?= jdate('d F - H:i', strtotime($sale_date)); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -6px">
                        <?php
                            if ($payment_status === 'approved') {
                                echo '
                                    <div>
                                        <label>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="success">
                                            <i class="fa fa-check-circle" aria-hidden="true" style="font-size: 15px !important"></i>
                                        </label>
                                        <span>
                                            ' . esc_html__("پرداخت موفق!", "almas-gold") . '
                                        </span>
                                    </div>
                                    <div>
                                        <label>
                                            ' . esc_html__("تاریخ تراکنش", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . jdate('d F - H:i:s', strtotime($payment_date)) . '
                                        </span>
                                    </div>
                                ';
                            }
                        ?>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="flex-space-between-align-center">
                    <div>
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
                            echo '
                                <div>
                                    <label>
                                        ' . esc_html__('وزن طلا', 'almas-gold') . '
                                    </label>
                                    <valsGoldWeight>
                                        ' . rtrim(rtrim(number_format($gold_weight, 4, '.', ''), '0'), '.') . '
                                    </valsGoldWeight>
                                    <suffix>
                                        ' . esc_html__($unit  , 'almas-gold') . '
                                    </suffix>
                                </div>
                            ';
                        ?>
                    </div>
                    <div>
                        <label>
                            <?= esc_html__('مبلغ سفارش', 'almas-gold'); ?>
                        </label>
                        <valsOrderPrice>
                            <?php echo number_format($initial_final_price, 0, '.', ','); ?>
                        </valsOrderPrice>
                        <suffix>
                            <?= esc_html__('تومان', 'almas-gold'); ?>
                        </suffix>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="flex-space-between-align-center">
                    <div style="width: 30%">
                        <label>
                            <?= esc_html__('مالیات', 'almas-gold'); ?>
                        </label>
                        <span>
                            <?php echo number_format($total_sale_tax, 0, '.', ','); ?>
                        </span>
                        <suffix>
                            <?= esc_html__('تومان', 'almas-gold'); ?>
                        </suffix>
                    </div>
                    <div style="width: 70%">
                        <div class="flex-space-between">
                            <div></div>
                            <div>
                                <label>
                                    <?= esc_html__('واریز به کیف پول', 'almas-gold'); ?>
                                </label>
                                <valsOrderPricePayed>
                                    <?php echo number_format($initial_final_price, 0, '.', ','); ?>
                                </valsOrderPricePayed>
                                <suffix>
                                    <?= esc_html__('تومان', 'almas-gold'); ?>
                                </suffix>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if (!empty($description)) {
                        echo '
                            <div class="hr"></div>
                            <div>
                                <label class="small">
                                    ' . esc_html__("توضیحات سفارش", "almas-gold") . '
                                </label>
                                <div class="callback_order_description">
                                    ' . $description . '
                                </div>
                            </div>
                        ';
                    }
                ?>
            </div>
            
        </div>
    <?php