<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold
     * @package           almas-gold recharge bill html
     */

    ?>
        <form method="post">
            <div class="billing_results">
                <div class="biliing-form">
                    <div class="biliing-icon" style="background-color: #e6f4f1">
                        <i class=" prk-wallet-add-1" style="color: #0D9277;font-size: 24px;"></i>
                    </div>
                    <span class="billing-icon-title">
                        <i class=" prk-wallet-add-1" style="font-weight: 600;"></i>
                        <h4 class="title"><?= esc_html__('شارژ کیف پول', 'almas-gold'); ?></h4>
                    </span>
                    <uid class="detail"><?php echo esc_attr($unique_recharge_id); ?></uid>
                </div>
                <div class="biliing-form">
                    <div class="biliing-form-list-ul">
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-receipt-2-1"></i> <?= esc_html__('شناسه سفارش', 'almas-gold'); ?>
                            </label>
                            <span>
                                <?php echo esc_attr($recharge_id); ?>
                            </span>
                        </li>
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-calendar-1"></i> <?= esc_html__('تاریخ سفارش', 'almas-gold'); ?>
                            </label>
                            <span>
                                <?= jdate('d F - H:i', strtotime($recharge_date . ' +03:30')); ?>
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
                            <label><i class=" prk-money-4"></i><?= esc_html__('مبلغ شارژ', 'almas-gold'); ?></label>
                            <span class="vals-Order-Price">
                                <valsOrderPrice>
                                    <?php echo number_format($recharge_amount, 0, '.', ','); ?>
                                </valsOrderPrice>
                                <suffix>
                                    <?= esc_html__('تومان', 'almas-gold'); ?>
                                </suffix>
                            </span>
                        </li>
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-receipt-disscount"></i>
                            <suffix><?= esc_html__('کارمزد شارژ', 'almas-gold'); ?></suffix></label>
                            <?php 
                                if (empty($recharge_fee) || $recharge_fee == 0) {
                                    echo '
                                    <span class="vals-gold-weight">
                                        '. esc_html__('رایگان', 'almas-gold') .'
                                    </span>
                                    ';
                                } else {
                                    echo '
                                        <span>
                                            '. number_format($recharge_fee, 0, '.', ',') . '
                                            <suffix>
                                                '. esc_html__('تومان', 'almas-gold') .'
                                            </suffix>
                                        </span>
                                    ';
                                }
                            ?>
                        </li>
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-wallet-money"></i>
                                <suffix><?= esc_html__("موجودی کیف پول", "almas-gold"); ?></suffix></label>
                            <span>
                                <?php
                                    if ($customer_wallet_balance === '0') {
                                        echo '
                                            ' . esc_html__("کیف پول خالی است", "almas-gold") . '
                                        ';
                                    } else {
                                        echo '
                                            <span>
                                                ' . number_format($customer_wallet_balance, 0, '.', ',') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__("تومان", "almas-gold") . '
                                            </suffix>
                                        ';
                                    }
                                ?>
                            </span>
                        </li>
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-wallet-add-1"></i>
                            <suffix><?= esc_html__('موجودی پس از شارژ', 'almas-gold'); ?></suffix></label>
                            <span>
                                <?= number_format($remaining_wallet_balance, 0, '.', ','); ?>
                                <suffix>
                                    <?= esc_html__('تومان', 'almas-gold'); ?>
                                </suffix>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="biliing-form biliing-form-last">
                    <div class="cta-row-pay">
                        <div id="pay_all_final_price_online_box" class="flex-space-between-align-center payment_box biliing-form-pay">
                            <div class="biliing-form-list-pay">
                                <li class="biliing-form-list-li biliing-form-list-li-des">
                                    <label>
                                        <?= esc_html__('مبلغ قابل پرداخت', 'almas-gold'); ?>
                                    </label>
                                    <span class="vals-Order-Price" style="font-size: 93% !important">
                                        <?= number_format($recharge_amount, 0, '.', ','); ?>
                                        <suffix>
                                            <?= esc_html__('تومان', 'almas-gold'); ?>
                                        </suffix>
                                    </span>
                                </li>
                                <li class="biliing-form-list-li">
                                    <div id="recharge_online_submit" class="ag_pay_button lh">
                                        <?= esc_html__('تکمیل سفارش و شارژ', 'almas-gold'); ?>
                                    </div>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="biliing-form-list-pay" style="margin-top: 30px;z-index: 1;">
                        <li class="biliing-form-buttons">
                            <button class="almas-gold-billing-button biliing-form-button-gold" type="button" onclick="redirectToPageRechargeContinue()">
                                <i class=" prk-edit"></i> ویرایش سفارش
                            </button>
                            <script>
                                function redirectToPageRechargeContinue() {
                                    var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                                    if (width <= 768) { // فرض کنید 768px برای موبایل است
                                        window.location.href = 'https://almas.gold/dashboard/';
                                    } else {
                                        window.location.href = 'https://almas.gold/dashboard/trade/recharge';
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
                <div id="recharge_online_popup">
                    <div class="payment_popup">
                        <h3 style="margin: 0 0 20px 0">
                            <?= esc_html__('آیا از درخواست خود مطمئنید؟', 'almas-gold'); ?>
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
                                <?php wp_nonce_field('recharge_online_submit_nonce', 'nonce_field_pay_online'); ?>
                                <button type="submit" name="recharge_online_submit" disabled>
                                    <?= esc_html__('تکمیل سفارش و شارژ', 'almas-gold'); ?>
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
