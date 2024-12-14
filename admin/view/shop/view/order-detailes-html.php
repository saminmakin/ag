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
        <div class="admin_order_details_box">
            <div class="info-box">
                <div class="flex-space-between">
                    <div>
                        <h4 style="margin-bottom: 5px !important">
                        <i class="fa-solid fa-cart-arrow-down" aria-hidden="true" style="font-size: 15px !important; margin-left: 5px;"></i>
                        <?= esc_html__('جزییات سفارش', 'almas-gold'); ?>
                        </h4>
                    </div>
                    <div>
                        <?php echo esc_attr($unique_shop_id); ?>
                    </div>
                </div>
                <div class="hrh"></div>
                <div class="flex-space-between">
                    <div class="flex">
                        <div>
                            <div class="qrcode">
                                <img src="<?php echo $qrcode_image_url; ?>" alt="Placeholder Image" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="qrc_placeholder">
                                    <i class="fa-solid fa-qrcode"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <span>
                                    <?php echo esc_attr($firstname); ?> <?php echo esc_attr($lastname); ?>
                                </span>
                            </div>
                            <div>
                                <label>
                                    <?= esc_html__('شناسه سفارش', 'almas-gold'); ?>
                                </label>
                                <span>
                                    <?php echo esc_attr($shop_id); ?>
                                </span>
                            </div>
                            <div>
                                <label>
                                    <?= esc_html__('تاریخ سفارش', 'almas-gold'); ?>
                                </label>
                                <span>
                                    <?= jdate('d F Y - H:i:s', strtotime($shop_date)); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <?php
                            if ($price_payed_by_wallet !== '0' && $price_payed_online === '0') {
                                if ($transaction_status === 'approved') {
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
                                                ' . jdate('d F - H:i:s', strtotime($transaction_date)) . '
                                            </span>
                                        </div>
                                    ';
                                }
                            } 
                            if ($price_payed_by_wallet !== '0' && $price_payed_online !== '0') {
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
                            } else {
                                if ($transaction_status !== 'approved') {
                                    echo '
                                        <div>
                                            <label>
                                                ' . esc_html__("وضعیت", "almas-gold") . '
                                            </label>
                                            <span>
                                                ' . esc_html__("دریافت نشده", "almas-gold") . '
                                            <span>
                                        </div>
                                        <div>
                                            <label>
                                                ' . esc_html__("شناسه پرداخت", "almas-gold") . '
                                            </label>
                                            <span>
                                                ' . esc_html__("دریافت نشده", "almas-gold") . '
                                            </span>
                                        </div>
                                        <div>
                                            <label>
                                                ' . esc_html__("تاریخ تراکنش", "almas-gold") . '
                                            </label>
                                            <span>
                                                ' . esc_html__("دریافت نشده", "almas-gold") . '
                                            </span>
                                        </div>
                                    ';
                                }
                            }
                            if ($price_payed_by_wallet === '0' && $price_payed_online !== '0') {
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
                            } else {
                                if ($transaction_status !== 'approved') {
                                    echo '
                                        <div>
                                            <label>
                                                ' . esc_html__("وضعیت", "almas-gold") . '
                                            </label>
                                            <span>
                                                ' . esc_html__("دریافت نشده", "almas-gold") . '
                                            </span>
                                        </div>
                                        <div>
                                            <label>
                                                ' . esc_html__("شناسه پرداخت", "almas-gold") . '
                                            </label>
                                            <span>
                                                ' . esc_html__("دریافت نشده", "almas-gold") . '
                                            </span>
                                        </div>
                                        <div>
                                            <label>
                                                ' . esc_html__("تاریخ تراکنش", "almas-gold") . '
                                            </label>
                                            <span>
                                                ' . esc_html__("دریافت نشده", "almas-gold") . '
                                            </span>
                                        </div>
                                    ';
                                }
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
                <div class="flex-space-between">
                    <div>
                        <perfix>
                            <?= esc_html__('مالیات', 'almas-gold'); ?>
                        </perfix>
                        <span>
                            <?php echo number_format($total_shop_tax, 0, '.', ','); ?>
                        </span>
                        <suffix>
                            <?= esc_html__('تومان', 'almas-gold'); ?>
                        </suffix>
                    </div>
                    <div>
                        <?php
                            if ($price_payed_online !== '0' && $price_payed_by_wallet !== '0') {
                                echo '
                                    <div class="flex-space-between">
                                        <div>
                                            
                                        </div>
                                        <div style="direction: ltr">
                                            <div>
                                                <perfix>
                                                    ' . esc_html__("پرداخت با کیف پول", "almas-gold") . '
                                                </perfix>
                                                <valsOrderPricePayed>
                                                    ' . number_format($price_payed_by_wallet, 0, '.', ',') . '
                                                </valsOrderPricePayed>
                                                <suffix>
                                                    ' . esc_html__("تومان", "almas-gold") . '
                                                </suffix>
                                            </div>
                                            <div style="margin-top: 17px">
                                                <perfix>
                                                    ' . esc_html__("پرداخت با شتاب", "almas-gold") . '
                                                </perfix>
                                                <valsOrderPricePayed>
                                                    ' . number_format($price_payed_online, 0, '.', ',') . '
                                                </valsOrderPricePayed>
                                                <suffix>
                                                    ' . esc_html__("تومان", "almas-gold") . '
                                                </suffix>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            } else {
                                if ($price_payed_online === '0') {
                                    echo '
                                        <div class="flex-space-between">
                                            <div></div>
                                            <div>
                                                <perfix>
                                                    ' . esc_html__("پرداخت با کیف پول", "almas-gold") . '
                                                </perfix>
                                                <valsOrderPricePayed>
                                                    ' . number_format($price_payed_by_wallet, 0, '.', ',') . '
                                                </valsOrderPricePayed>
                                                <suffix>
                                                    ' . esc_html__("تومان", "almas-gold") . '
                                                </suffix>
                                            </div>
                                        </div>
                                    ';
                                } 
                                if ($price_payed_by_wallet === '0') {
                                    echo '
                                        <div class="flex-space-between">
                                            <div></div>
                                            <div>
                                                <perfix>
                                                    ' . esc_html__("پرداخت با شتاب", "almas-gold") . '
                                                </perfix>
                                                <valsOrderPricePayed>
                                                    ' . number_format($price_payed_online, 0, '.', ',') . '
                                                </valsOrderPricePayed>
                                                <suffix>
                                                    ' . esc_html__("تومان", "almas-gold") . '
                                                </suffix>
                                            </div>
                                        </div>
                                    ';
                                } 
                            }
                        ?>
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
    