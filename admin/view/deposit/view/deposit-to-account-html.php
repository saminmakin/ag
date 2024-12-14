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
        <div class="admin_order_details_box">
            <div class="flex-space-between">
                <div>
                    <h4 style="margin-bottom: 5px !important">
                    <i class="fa-solid fa-money-bill-transfer" aria-hidden="true" style="font-size: 15px !important; margin-left: 5px"></i>
                    <?= esc_html__('صورت‌حساب واریز وجه', 'almas-gold'); ?>
                    </h4>
                </div>
                <div>
                    <?php echo esc_attr($unique_deposit_id); ?>
                </div>
            </div>
            <div class="hrh"></div>
            <div class="flex-space-between">
                <div class="flex">
                    <div>
                        <span>
                            <?php echo esc_attr($firstname); ?> <?php echo esc_attr($lastname); ?>
                        </span>
                        <div>
                            <label>
                                <?= esc_html__('شناسه سفارش', 'almas-gold'); ?>
                            </label>
                            <span>
                                <?php echo esc_attr($deposit_id); ?>
                            </span>
                        </div>
                        <div>
                            <label>
                                <?= esc_html__('تاریخ سفارش', 'almas-gold'); ?>
                            </label>
                            <span>
                                <?= jdate('d F - H:i', strtotime($deposit_date)); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div></div>
            </div>
            <div class="hr"></div>
            <div class="flex-space-between-align-center">
                <div>
                    <label>
                        <?= esc_html__('کارمزد شارژ', 'almas-gold'); ?>
                    </label>
                    <span>
                        <?php echo number_format($deposit_fee, 0, '.', ','); ?>
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
                        <?php echo number_format($deposit_amount, 0, '.', ','); ?>
                    </valsOrderPrice>
                    <suffix>
                        <?= esc_html__('تومان', 'almas-gold'); ?>
                    </suffix>
                </div>
            </div>
            <div class="hr"></div>
            <div class="flex-space-between-align-center">
                <div>
                <?php
                    if ($deposit_status === 'approved') {
                        echo '
                            <div>
                                <label>
                                    ' . esc_html__("وضعیت واریز", "almas-gold") . '
                                </label>
                                <span style="color: MediumSeaGreen">
                                    ' . esc_html__("واریز شد", "almas-gold") . '
                                </span>
                            </div>
                        ';
                    } else {
                        echo '
                            <div>
                                <label>
                                    ' . esc_html__("وضعیت واریز", "almas-gold") . '
                                </label>
                                <span style="color: green; font-weight: 700; font-size: 14px; color: DodgerBlue">
                                    ' . esc_html__("در انتظار واریز", "almas-gold") . '
                                </span>
                            </div>
                        ';
                    }
                    ?>
                </div>
                <div>
                    <label>
                        <?= esc_html__('مبلغ قابل واریز', 'almas-gold'); ?>
                    </label>
                    <valsorderpricepayed>
                        <?php echo number_format($final_deposit_amount, 0, '.', ','); ?>
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
    <?php
    