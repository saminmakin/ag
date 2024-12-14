<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin recharge html
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
                        <i class="fa-solid fa-wallet" aria-hidden="true" style="font-size: 15px !important; margin-left: 5px;"></i>
                        <?= esc_html__('جزییات سفارش', 'almas-gold'); ?>
                        </h4>
                    </div>
                    <div>
                        <?php echo esc_attr($unique_recharge_id); ?>
                    </div>
                </div>
                <div class="hrh"></div>
                <div class="flex-space-between">
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
                                    <?php echo esc_attr($recharge_id); ?>
                                </span>
                            </div>
                            <div>
                                <label>
                                    <?= esc_html__('تاریخ سفارش', 'almas-gold'); ?>
                                </label>
                                <span>
                                    <?= jdate('d F - H:i', strtotime($recharge_date)); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <?php
                            if ($transaction_status === 'approved') {
                                echo '
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
                                            ' . esc_html__("شناسه پرداخت", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . esc_attr($transaction_id) . '
                                        </span>
                                    </div>
                                    <div>
                                        <label>
                                            ' . esc_html__("تاریخ تراکنش", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . jdate('d F Y - H:i:s', strtotime($transaction_date)) . '
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
                        <label>
                            <?= esc_html__('کارمزد شارژ', 'almas-gold'); ?>
                        </label>
                        <span>
                            <?php echo number_format($recharge_fee, 0, '.', ','); ?>
                        </span>
                        <suffix>
                            <?= esc_html__('تومان', 'almas-gold'); ?>
                        </suffix>
                        
                    </div>
                    <div>
                        <label>
                            <?= esc_html__('مبلغ سفارش', 'almas-gold'); ?>
                        </label>
                        <valsOrderPrice>
                            <?php echo number_format($recharge_amount, 0, '.', ','); ?>
                        </valsOrderPrice>
                        <suffix>
                            <?= esc_html__('تومان', 'almas-gold'); ?>
                        </suffix>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="flex-space-between-align-center">
                    <div>
                    </div>
                    <div>
                        <label>
                            <?= esc_html__('مبلغ شارژ شده', 'almas-gold'); ?>
                        </label>
                        <valsorderpricepayed>
                            <?php echo number_format($initial_recharge_amount, 0, '.', ','); ?>
                        </valsorderpricepayed>
                        <suffix>
                            <?= esc_html__('تومان', 'almas-gold'); ?>
                        </suffix>
                    </div>
                </div>
                <?php
                    if (!empty($description)) {
                        echo '
                            <div class="hr"></div>
                            <div>
                                <perfix class="small">
                                    ' . esc_html__("توضیحات سفارش", "almas-gold") . '
                                </perfix>
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