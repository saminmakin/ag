<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold
     * @package           almas-gold sale callback results html
     */
    
    ?>
        <div class="billing_results">
            <div class="biliing-form">
                <?php
                    if ($transaction_processed === '1') {
                        echo '
                            <div class="biliing-icon" style="background-color: #e6f4f1;">
                                <i class=" prk-tick-square" style="color: #0D9277;font-size: 24px;"></i>
                            </div>
                            <span class="billing-icon-title">
                                <i class=" prk-shop-remove" style="font-weight: 600;"></i>
                                <h4 class="title">' . esc_html__("صورت‌حساب فروش طلا", "almas-gold") . '</h4>
                            </span>
                            <label style="font-weight: 700" class="success massage">
                                ' . esc_html__("فروش طلا با موفقیت انجام شد.", "almas-gold") . '
                            </label>
                        ';
                    } else {
                        if ($transaction_processed !== '1') {
                            echo '
                                <div class="biliing-icon" style="background-color: #FBE7EB;">
                                    <i class=" prk-close-square" style="color: #DC143C;font-size: 24px;"></i>
                                </div>
                                <span class="billing-icon-title">
                                    <i class=" prk-shop-remove" style="font-weight: 600;"></i>
                                    <h4 class="title">' . esc_html__("صورت‌حساب فروش طلا", "almas-gold") . '</h4>
                                </span>
                                <label style="font-weight: 700" class="failed massage">
                                    ' . esc_html__("فروش طلا موفقیت‌آمیز نبود!", "almas-gold") . '
                                </label>
                            ';
                        }
                    }
                ?>
            </div>
            <div class="biliing-form">
                <ul class="biliing-form-list-ul">
                    <li class="biliing-form-list-li">
                        <label>
                            <i class=" prk-receipt-2-1"></i> <?= esc_html__('شناسه سفارش', 'almas-gold'); ?>
                        </label>
                        <span>
                            <?php echo esc_attr($sale_id); ?>
                        </span>
                    </li>
                    <li class="biliing-form-list-li">
                        <label>
                            <i class=" prk-calendar-1"></i> <?= esc_html__('تاریخ سفارش', 'almas-gold'); ?>
                        </label>
                        <span>
                            <?= jdate('d F - H:i', strtotime($sale_date . ' +03:30')); ?>
                        </span>
                    </li>
                    <li class="biliing-form-list-li" style="align-items: center;">
                        <div style="width: 50%">
                            <label><i class=" prk-document-text"></i> 
                                <perfix>
                                    <?= esc_html__("توضیحات سفارش", "almas-gold"); ?>
                                </perfix>
                            </label>
                            <span>
                                <div class="callback_order_description">
                                    <?php
                                        if (!empty($description)) {
                                            echo '
                                            ' . $description . '
                                            ';
                                        } else {
                                            echo '
                                            ' . esc_html__("توضیحاتی وارد نشده‌است!", "almas-gold") . '
                                            ';
                                        }
                                    ?>
                                </div>
                            </span>
                        </div>
                        <div class="qrcode">
                        <?php echo '<img src="' . $qrcode_image_url . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">'; ?>
                            <div class="qrc_placeholder">
                                <i class="fa-solid fa-qrcode"></i>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="biliing-form">
                <ul class="biliing-form-list-ul">
                    <?php
                        if ($transaction_processed === '1') {
                            echo '
                                <li class="biliing-form-list-li">
                                    <span class="success" style="margin: auto;display: flex;align-items: center;gap: 4px;">
                                        <i class=" prk-tick-square" style="font-weight: 600;"></i> ' . esc_html__("واریز موفق!", "almas-gold") . '
                                    </span>
                                </li>
                                <li class="biliing-form-list-li">
                                    <label>
                                        <i class=" prk-receipt-text"></i> ' . esc_html__("شناسه واریز", "almas-gold") . '
                                    </label>
                                    <span>
                                        ' . esc_attr($transaction_id) . '
                                    </span>
                                </li>
                                <li class="biliing-form-list-li">    
                                    <label>
                                        <i class=" prk-calendar-tick"></i> ' . esc_html__("تاریخ تراکنش", "almas-gold") . '
                                    </label>
                                    <span>
                                        ' . jdate('d F Y - H:i:s', strtotime($transaction_date)) . '
                                    </span>
                                </li>
                            ';
                        } else {
                            if ($transaction_processed !== '1') {
                                echo '
                                    <li class="biliing-form-list-li">
                                        <span class="failed" style="margin: auto;display: flex;align-items: center;gap: 4px;">
                                            <i class=" prk-close-square" style="font-weight: 600;"></i>' . esc_html__("وضعیت:", "almas-gold") . '
                                            ' . esc_html__("واریز نشده!", "almas-gold") . '
                                        </span>
                                    </li>
                                    <li class="biliing-form-list-li">
                                        <label>
                                            <i class=" prk-receipt-text"></i> ' . esc_html__("شناسه واریز", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . esc_html__("دریافت نشده", "almas-gold") . '
                                        </span>
                                    </li>
                                    <li class="biliing-form-list-li">
                                        <label>
                                            <i class=" prk-calendar-remove"></i> ' . esc_html__("تاریخ تراکنش", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . esc_html__("دریافت نشده", "almas-gold") . '
                                        </span>
                                    </li>
                                ';
                            }
                        }
                    ?>
                </ul>
            </div>
            <div class="biliing-form">
                <ul class="biliing-form-list-ul">
                    <li class="biliing-form-list-li">
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
                                <label><i class=" prk-money"></i>
                                    ' . esc_html__('وزن طلا', 'almas-gold') . '
                                </label>
                                <span class="vals-gold-weight">
                                    <valsGoldWeight>
                                        ' . rtrim(rtrim(number_format($gold_weight, 4, '.', ''), '0'), '.') . '
                                    </valsGoldWeight>
                                    <suffix>
                                        ' . esc_html__($unit  , 'almas-gold') . '
                                    </suffix>
                                </span>
                            ';
                        ?>
                    </li>
                    <li class="biliing-form-list-li">
                        <label><i class=" prk-money-4"></i><?= esc_html__('مبلغ سفارش', 'almas-gold'); ?></label>
                        <span class="vals-Order-Price">
                            <valsOrderPrice>
                                <?php echo number_format($initial_price, 0, '.', ','); ?>
                            </valsOrderPrice>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </span>
                    </li>
                    <li class="biliing-form-list-li">
                        <label><i class=" prk-receipt-disscount"></i>
                            <perfix>
                                <?= esc_html__('مالیات', 'almas-gold'); ?>
                            </perfix>
                        </label>
                        <span>
                            <span>
                                <?php echo number_format($total_sale_tax, 0, '.', ','); ?>
                            </span>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </span>
                    </li>
                    <li class="biliing-form-list-li">
                        <label><i class="  prk-empty-wallet-tick"></i>
                            <?= esc_html__('واریز به کیف پول', 'almas-gold'); ?>
                        </label>
                        <span>
                            <span>
                                <valsOrderPricePayed>
                                    <?php echo number_format($initial_final_price, 0, '.', ','); ?>
                                </valsOrderPricePayed>
                            </span>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </span>
                    </li>
                    <div class="biliing-form-list-pay" style="margin-top: 30px;z-index: 1;">
                        <li class="biliing-form-buttons">
                            <button class="almas-gold-billing-button biliing-form-button-gold" type="button" onclick="redirectToPageSaleBill()">
                                <i class=" prk-archive-minus"></i> گزارش فروش‌ها
                            </button>
                            <script>
                                function redirectToPageSaleBill() {
                                    var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                                    if (width <= 768) { // فرض کنید 768px برای موبایل است
                                        window.location.href = 'https://almas.gold/dashboard/';
                                    } else {
                                        window.location.href = 'https://almas.gold/dashboard/safebox';
                                    }
                                }
                            </script>
                        </li>
                        <li class="biliing-form-buttons">
                            <button class="almas-gold-billing-button biliing-form-button-green" type="button" onclick="location.href='https://almas.gold/dashboard';">
                                <i class="prk-element-4"></i> بازگشت به داشبورد
                            </button>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
    <?php
