<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold
     * @package           almas-gold sale bill html
     */

    ?>
        <form method="post">
            <div class="billing_results">
                <div class="biliing-form">
                    <div class="biliing-icon" style="background-color: #FBE7EB">
                        <i class=" prk-direct-send" style="color: #DC143C;font-size: 24px;"></i>
                    </div>
                    <span class="billing-icon-title">
                        <i class=" prk-shop-remove" style="font-weight: 600;"></i>
                        <h4 class="title"><?= esc_html__('فروش طلا', 'almas-gold'); ?></h4>
                    </span>
                    <uid class="detail"><?php echo esc_attr($unique_sale_id); ?></uid>
                </div>
                <div class="biliing-form">
                    <div class="biliing-form-list-ul">
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
                        <li class="biliing-form-list-li">
                            <div style="width: 50%">
                                <textarea 
                                id="description" 
                                name="description" 
                                style="width: 100%;position: relative;"
                                z-index= "2";
                                placeholder="توضیحات سفارش"
                                ><?php echo esc_attr($description); ?></textarea>
                            </div>
                            <div class="qrcode">
                                <?php echo '<img src="' . $qrcode_image_url . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">'; ?>
                                <div class="qrc_placeholder">
                                    <i class="fa-solid fa-qrcode"></i>
                                </div>
                            </div>
                        </li>
                    </div>
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
                            <label>
                                <i class=" prk-receipt-disscount"></i> <?php echo esc_attr(rtrim(rtrim(number_format($sale_tax, 4, '.', ''), '0'), '.')); ?>
                            <suffix><?= esc_html__('درصد مالیات فروش', 'almas-gold'); ?></suffix></label>
                            <span>
                                <span>
                                    <?php echo number_format($total_sale_tax, 0, '.', ','); ?>
                                </span>
                                <suffix>
                                    <?= esc_html__('تومان', 'almas-gold'); ?>
                                </suffix>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="biliing-form">
                    <ul class="biliing-form-list-ul">        
                        <li class="biliing-form-list-li">
                            <?php
                                if ($customer_safe_balance == '0') {
                                    echo '
                                        <label><i class=" prk-strongbox-2"></i>
                                            ' . esc_html__("موجودی گاوصندوق", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . esc_html__("گاوصندوق خالی است", "almas-gold") . '
                                        </span>
                                    ';
                                } else {
                                    echo '
                                        <label><i class=" prk-strongbox-2"></i>
                                            ' . esc_html__("موجودی گاوصندوق", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . rtrim(rtrim(number_format($customer_safe_balance, 4, '.', ''), '0'), '.') . '
                                            <suffix>
                                                ' . esc_html__("گرم", "almas-gold") . '
                                            </suffix>
                                        </span>
                                    ';
                                }
                            ?>
                        </li>
                        <li class="biliing-form-list-li">
                            <?php
                                if ($customer_safe_balance == '0') {
                                    echo '
                                        <label><i class=" prk-direct-send"></i>
                                            ' . esc_html__("موجودی گاو صندوق پس از فروش", "almas-gold") . '
                                        </label>
                                        <span class="vals-gold-weight">
                                            ' . esc_html__("گاو صندوق خالی می‌شود", "almas-gold") . '
                                        </span>
                                    ';
                                } else {
                                    echo '
                                        <label><i class=" prk-direct-send"></i>
                                            ' . esc_html__("موجودی گاو صندوق پس از فروش", "almas-gold") . '
                                        </label>
                                        <span class="vals-gold-weight">
                                            ' . rtrim(rtrim(number_format($remaining_safe_balance, 4, '.', ''), '0'), '.') . '
                                            <suffix>
                                                ' . esc_html__("گرم", "almas-gold") . '
                                            <sufix>
                                        </span>
                                    ';
                                }
                            ?>
                        </li>
                        <li class="biliing-form-list-li">
                            <?php
                                if ($customer_wallet_balance === '0') {
                                    echo '
                                        <label><i class=" prk-wallet-3"></i>
                                            ' . esc_html__("موجودی کیف پول", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . esc_html__("کیف پول خالی است", "almas-gold") . '
                                        </span>
                                    ';
                                } else {
                                    echo '
                                        <label><i class=" prk-wallet-3"></i>
                                            ' . esc_html__("موجودی کیف پول", "almas-gold") . '
                                        </label>
                                        <span>
                                            ' . number_format($customer_wallet_balance, 0, '.', ',') . '
                                            <suffix>
                                                ' . esc_html__("تومان", "almas-gold") . '
                                            </suffix>
                                        </span>
                                    ';
                                }
                            ?>
                        </li>
                        <li class="biliing-form-list-li">
                            <?php
                                if ($initial_final_price > 0) {
                                    echo '
                                        <label><i class=" prk-wallet-minus"></i>
                                            ' . esc_html__("موجودی کیف پول پس از فروش", "almas-gold") . '
                                        </label>
                                        <span class="vals-Order-Price">
                                            ' . number_format($remaining_wallet_balance, 0, '.', ',') . '
                                            <suffix>
                                                ' . esc_html__("تومان", "almas-gold") . '
                                            </suffix>
                                        </span>
                                    ';
                                } else {
                                    echo '
                                        <label><i class=" prk-wallet-minus"></i>
                                            ' . esc_html__("موجودی کیف پول پس از فروش", "almas-gold") . '
                                        </label>
                                        <span class="vals-Order-Price">
                                            ' . esc_html__("کیف پول خالی می‌شود", "almas-gold") . '
                                        </span>
                                    ';
                                }
                            ?>
                        </li>
                    </ul>
                </div>
                <div class="biliing-form biliing-form-last">
                    <div class="cta-row-pay">
                        <div id="pay_all_final_price_online_box" class="flex-space-between-align-center payment_box biliing-form-pay">
                            <div class="biliing-form-list-pay">
                                <li class="biliing-form-list-li biliing-form-list-li-des">
                                    <label>
                                        <?= esc_html__('مبلغ قابل دریافت', 'almas-gold'); ?>
                                    </label>
                                    <span class="vals-Order-Price" style="font-size: 93% !important">
                                        <?php echo number_format($initial_final_price, 0, '.', ','); ?>
                                        <suffix>
                                            <?= esc_html__('تومان', 'almas-gold'); ?>
                                        </suffix>
                                    </span>
                                </li>
                                <li class="biliing-form-list-li">
                                    <div id="almas_gold_sale_submit" class="ag_pay_button lh">
                                        <?= esc_html__('تکمیل سفارش و فروش', 'almas-gold'); ?>
                                    </div>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="biliing-form-list-pay" style="margin-top: 30px;z-index: 1;">
                        <li class="biliing-form-buttons">
                            <button class="almas-gold-billing-button biliing-form-button-gold" type="button" onclick="redirectToPageSaleContinue()">
                                <i class=" prk-edit"></i> ویرایش سفارش
                            </button>
                            <script>
                                function redirectToPageSaleContinue() {
                                    var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                                    if (width <= 768) { // فرض کنید 768px برای موبایل است
                                        window.location.href = 'https://almas.gold/dashboard/';
                                    } else {
                                        window.location.href = 'https://almas.gold/dashboard/trade/sell';
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
                </div>
                <div id="ag_pay_all_price_online_popup">
                    <div class="payment_popup">
                        <h3 style="margin: 0 0 20px 0">
                            <?= esc_html__('آیا از فروش خود مطمئنید؟', 'almas-gold'); ?>
                        </h3>
                        <div style="margin-bottom: 30px">
                            <label class="checkbox_cont">
                                <input type="checkbox" id="rules_agreement">
                                <span class="checkmark"></span>
                                <label for="rules_agreement" style="margin-right: 25px">
                                    <?= esc_html__('من با همه ', 'almas-gold'); ?>
                                    <a href="/terms" target="blank"><?= esc_html__('شرایط و قوانین سایت', 'almas-gold'); ?></a>
                                    <?= esc_html__('موافقم.', 'almas-gold'); ?>
                                </label>
                            </label>
                        </div>
                        <div class="biliing-form-list-pay">
                            <li class="biliing-form-list-li biliing-form-list-li-des">
                                <?php wp_nonce_field('almas_gold_sale_submit_nonce', 'nonce_field_almas_gold_sale'); ?>
                                <button type="submit" name="almas_gold_sale_submit" disabled>
                                    <?= esc_html__('تکمیل سفارش و فروش', 'almas-gold'); ?>
                                </button>
                            </li>
                            <li class="biliing-form-list-li biliing-form-list-li-des" style="width: fit-content;">
                                <div id="close_popup" class="ag_pay_button close_popup lh">
                                    <?= esc_html__('خیر', 'almas-gold'); ?>
                                </div>
                            </li>
                        </div>
                    </div>
                    <div class="payment_overlay"></div>
                </div>
            </div>
        </form>
    <?php
    