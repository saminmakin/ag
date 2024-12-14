<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold
     * @package           almas-gold deposit bill html
     */

    ?>
        <form method="post">
            <div class="billing_results">
                <div class="biliing-form">
                    <div class="biliing-icon" style="background-color: #fbe7eb">
                        <i class=" prk-wallet-minus" style="color: #DC143C;font-size: 24px;"></i>
                    </div>
                    <span class="billing-icon-title">
                        <i class=" prk-wallet-minus" style="font-weight: 600;"></i>
                        <h4 class="title"><?= esc_html__('برداشت وجه', 'almas-gold'); ?></h4>
                    </span>
                    <uid class="detail"><?php echo esc_attr($unique_deposit_id); ?></uid>
                </div>
                <div class="biliing-form">
                    <div class="biliing-form-list-ul">
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-receipt-2-1"></i> <?= esc_html__('شناسه سفارش', 'almas-gold'); ?>
                            </label>
                            <span>
                                <?php echo esc_attr($deposit_id); ?>
                            </span>
                        </li>
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-calendar-1"></i> <?= esc_html__('تاریخ سفارش', 'almas-gold'); ?>
                            </label>
                            <span>
                                <?= jdate('d F - H:i', strtotime($deposit_date . ' +03:30')); ?>

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
                            <label><i class=" prk-money-4"></i><?= esc_html__('مبلغ برداشت', 'almas-gold'); ?></label>
                            <span class="vals-Order-Price">
                                <valsOrderPrice>
                                    <?php echo number_format($deposit_amount, 0, '.', ','); ?>
                                </valsOrderPrice>
                                <suffix>
                                    <?= esc_html__('تومان', 'almas-gold'); ?>
                                </suffix>
                            </span>
                        </li>
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-receipt-disscount"></i>
                            <suffix><?= esc_html__('کارمزد واریز', 'almas-gold'); ?></suffix></label>
                            <?php if ($deposit_fee == 0): ?>
                            <span class="vals-gold-weight">
                                <?= esc_html__('رایگان', 'almas-gold'); ?>
                            </span>
                            <?php else: ?>
                            <span>
                                <?= number_format($deposit_fee, 0, '.', ','); ?>
                                <suffix>
                                    <?= esc_html__('تومان', 'almas-gold'); ?>
                                </suffix>
                            </span>
                            <?php endif; ?>
                        </li>
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-wallet-money"></i>
                                <suffix><?= esc_html__("موجودی کیف پول", "almas-gold"); ?></suffix></label>
                            <span>
                                <?php
                                    if ($deposit_amount == $customer_wallet_balance) {
                                        $final_deposit_amount = $deposit_amount - $deposit_fee;
                                        $remaining_wallet_balance = 0;
                                    } else {
                                        $final_deposit_amount = $deposit_amount;
                                        $remaining_wallet_balance = $customer_wallet_balance - $final_deposit_amount;
                                    }
                                    $final_deposit_amount = ($deposit_amount - $deposit_fee);

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
                                <i class=" prk-wallet-minus"></i>
                            <suffix><?= esc_html__('موجودی پس از واریز', 'almas-gold'); ?></suffix></label>
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
                                        <?php
                                            $final_deposit_amount = $deposit_amount - $deposit_fee;
                                        ?>
                                        <?= esc_html__('مبلغ قابل واریز', 'almas-gold'); ?>
                                    </label>
                                    <span class="vals-Order-Price" style="font-size: 93% !important">
                                        <?= number_format($final_deposit_amount, 0, '.', ','); ?>
                                        <suffix>
                                            <?= esc_html__('تومان', 'almas-gold'); ?>
                                        </suffix>
                                    </span>
                                </li>
                                <li class="biliing-form-list-li">
                                    <div id="deposit_request_submit" class="ag_pay_button lh">
                                        <?= esc_html__('ثبت و تکمیل سفارش', 'almas-gold'); ?>
                                    </div>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="biliing-form-list-pay" style="margin-top: 30px;z-index: 1;">
                        <li class="biliing-form-buttons">
                            <button class="almas-gold-billing-button biliing-form-button-gold" type="button" onclick="redirectToPageDepositContinue()">
                                <i class=" prk-edit"></i> ویرایش سفارش
                            </button>
                            <script>
                                function redirectToPageDepositContinue() {
                                    var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                                    if (width <= 768) { // فرض کنید 768px برای موبایل است
                                        window.location.href = 'https://almas.gold/dashboard/';
                                    } else {
                                        window.location.href = 'https://almas.gold/dashboard/trade/deposit';
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
                <div id="deposit_online_popup">
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
                                <?php wp_nonce_field('deposit_request_submit_nonce', 'nonce_field_deposit_request'); ?>
                                <button type="submit" name="deposit_request_submit" disabled>
                                    <?= esc_html__('ثبت و تکمیل سفارش', 'almas-gold'); ?>
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
    