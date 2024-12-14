<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold
     * @package           almas-gold delivery bill html
     */

    ?>
        <form method="post">
            <div class="billing_results">
                <div class="biliing-form">
                    <div class="biliing-icon" style="background-color: #F0E9DD">
                        <i class=" prk-directbox-default" style="color: #7E623F;font-size: 24px;"></i>
                    </div>
                    <span class="billing-icon-title">
                        <i class=" prk-gift" style="font-weight: 600;"></i>
                        <h4 class="title"><?= esc_html__('دریافت طلا', 'almas-gold'); ?></h4>
                    </span>
                    <uid class="detail"><?php echo esc_attr($unique_delivery_id); ?></uid>
                </div>
                <div class="biliing-form">
                    <div class="biliing-form-list-ul">
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-receipt-2-1"></i> <?= esc_html__('شناسه سفارش', 'almas-gold'); ?>
                            </label>
                            <span>
                                <?php echo esc_attr($delivery_id); ?>
                            </span>
                        </li>
                        <li class="biliing-form-list-li">
                            <label>
                                <i class=" prk-calendar-1"></i> <?= esc_html__('تاریخ سفارش', 'almas-gold'); ?>
                            </label>
                            <span>
                                <?= jdate('d F - H:i', strtotime($delivery_request_date . ' +03:30')); ?>
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
                            <label><i class=" prk-money-4"></i><?= esc_html__('مبلغ طلا', 'almas-gold'); ?></label>
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
                            <div class="biliing-form-list-type-li">
                                <label class="biliing-form-list-type-li">
                                    <i class=" prk-tag-2"></i><?= esc_html__('نوع طلا', 'almas-gold'); ?>
                                </label>
                                <select class="minimal" name="gold_type" id="gold_type" style="position: relative;z-index:2;">
                                    <option value="without_fee" data-fee="<?= $without_fee_gold_fee; ?>"><?= esc_html__('طلای بدون اجرت', 'almas-gold'); ?></option>
                                    <option value="low_fee" data-fee="<?= $low_fee_gold_fee; ?>"><?= esc_html__('طلای کم اجرت', 'almas-gold'); ?></option>
                                    <option value="sequins" data-fee="<?= $sequins_gold_fee; ?>"><?= esc_html__('طلای پولک', 'almas-gold'); ?></option>
                                    <?php
                                        if ($bullion_gold_limit <= $customer_safe_balance  && $bullion_gold_limit <= $gold_weight ) {
                                            echo '<option value="bullion" data-fee="' . $bullion_gold_fee . '">طلای شمش</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <span class="biliing-form-list-type-fee-li">
                                <perfix>
                                    <?= esc_html__('کارمزد:', 'almas-gold'); ?>
                                </perfix>
                                <span id="total_gold_type_fee" style="font-weight: 700">
                                    <?php echo number_format($initial_final_price, 0, '.', ','); ?> 
                                </span>
                                <suffix>
                                    <?= esc_html__('تومان', 'almas-gold'); ?>
                                </suffix>
                                <div style="font-size: 10px !important;margin-right: 3px;">(
                                    <span id="gold_type_fee" style="font-weight: 700;">
                                        <?php echo esc_attr(rtrim(rtrim(number_format($gold_type_fee, 4, '.', ''), '0'), '.')); ?>
                                    </span>
                                    <suffix>
                                        <?= esc_html__('درصد', 'almas-gold'); ?>)
                                    </suffix>
                                </div>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="biliing-form">
                    <ul class="biliing-form-list-ul">        
                        <li class="biliing-form-list-li">
                            <?php
                                if ($customer_safe_balance === '0') {
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
                            <label><i class=" prk-direct-send"></i>
                                <?= esc_html__('موجودی پس از دریافت','almas-gold'); ?>
                            </label>
                            <span>
                                <?= rtrim(rtrim(number_format($remaining_safe_balance, 4, '.', ''), '0'), '.'); ?>
                                <suffix>
                                    <?= esc_html__('گرم', 'almas-gold'); ?>
                                </suffix>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="biliing-form biliing-form-last">
                    <div class="cta-row-pay">
                        <li class="biliing-form-list-li">
                            <label class="checkbox_cont">
                                <input type="checkbox" id="delivery_activate_pay_with_wallet" name="delivery_activate_pay_with_wallet" value="1" <?php if ($customer_wallet_balance == 0) echo 'disabled'; ?>>
                                <span class="checkmark <?php if ($customer_wallet_balance == 0) echo 'disabled_checkbox'; ?>"></span>
                                <label class="<?php if ($customer_wallet_balance == 0) echo 'nullwallet'; ?>" for="delivery_activate_pay_with_wallet" style="margin-right: 25px; font-weight: 700">
                                    <?= esc_html__('پرداخت با کیف پول', 'almas-gold'); ?>
                                </label>
                            </label>
                            <?php
                                if ($customer_wallet_balance === '0') {
                                    echo '
                                        <span>
                                            <perfix class="muted">
                                                ' . esc_html__("کیف پول خالی است", "almas-gold") . '
                                            </perfix>
                                        </span>
                                    ';
                                } else {
                                    echo '
                                        <span>
                                            <perfix>
                                                ' . esc_html__("موجودی", "almas-gold") . '
                                            </perfix>
                                            <span >
                                                ' . number_format($customer_wallet_balance, 0, '.', ',') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__("تومان", "almas-gold") . '
                                            </suffix>
                                        </span>
                                    ';
                                }
                            ?>
                        </li>
                        <div id="delivery_pay_all_online_box" class="flex-space-between-align-center payment_box biliing-form-pay">
                            <div class="biliing-form-list-pay">
                                <li class="biliing-form-list-li">
                                    <label>
                                        <?= esc_html__('مبلغ قابل پرداخت', 'almas-gold'); ?>
                                    </label>
                                    <span id="initial_final_price_display_online" class="vals-Order-Price">
                                        <?php echo number_format($initial_final_price, 0, '.', ','); ?>
                                        <suffix>
                                            <?= esc_html__('تومان', 'almas-gold'); ?>
                                        </suffix>
                                    </span>
                                </li>
                                <li class="biliing-form-list-li">
                                    <div id="delivery_pay_all_online_submit" class="ag_pay_button lh">
                                        <?= esc_html__('پرداخت و تکمیل سفارش', 'almas-gold'); ?>
                                    </div>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="cta-row-pay">
                        
                        <?php
                            if ($initial_final_price <= $customer_wallet_balance) {
                        ?>
                        <div id="delivery_pay_by_wallet_box" class="payment_box biliing-form-pay" style="display: none;">
                            <div class="flex-space-between-align-center ag_payment_wallet_box biliing-form-list-pay" style="flex-direction: column;">
                                <div class="biliing-form-list-pay">
                                    <li class="biliing-form-list-li">
                                        <perfix>
                                            <?= esc_html__('پرداخت با کیف پول', 'almas-gold'); ?>
                                        </perfix>
                                        <span>
                                            <valsorderpricepayed>
                                                <?= number_format($initial_final_price, 0, '.', ','); ?>
                                            </valsorderpricepayed>
                                            <suffix>
                                                <?= esc_html__('تومان', 'almas-gold'); ?>
                                            </suffix>
                                        </span>
                                    </li>
                                    <li class="biliing-form-list-li" style="    direction: rtl !important;">
                                        <perfix>
                                            <?= esc_html__('موجودی پس از پرداخت', 'almas-gold'); ?>
                                        </perfix>
                                        <span>
                                            <?= number_format($remaining_wallet_balance, 0, '.', ','); ?>
                                            <suffix>
                                                <?= esc_html__('تومان', 'almas-gold'); ?>
                                            </suffix>
                                        </span>
                                    </li>
                                </div>
                            </div>
                            <div class="biliing-form-list-pay" style="margin-top: 30px;">
                                <li class="biliing-form-list-li biliing-form-list-li-des">
                                    <label>
                                        <?= esc_html__('مبلغ قابل پرداخت', 'almas-gold'); ?>
                                    </label>
                                    <span class="vals-Order-Price" class="vals-Order-Price" id="initial_final_price_display_wallet">
                                        <valsorderpricepayed>
                                            <?php echo number_format($initial_final_price, 0, '.', ','); ?>
                                        </valsorderpricepayed>
                                        <suffix>
                                            <?= esc_html__('تومان', 'almas-gold'); ?>
                                        </suffix>
                                    </span>
                                </li>
                                <li class="biliing-form-list-li biliing-form-list-li-des">    
                                    <div id="delivery_pay_all_by_wallet_submit" class="ag_pay_button lh">
                                        <?= esc_html__('پرداخت با کیف پول', 'almas-gold'); ?>
                                    </div>
                                </li>
                            </div>
                        </div>
                        <?php
                            }
                            if ($initial_final_price > $customer_wallet_balance) {
                        ?>
                        <div id="delivery_pay_by_wallet_box" class="payment_box" style="display: none">
                            <div class="flex-space-between-align-center ag_payment_wallet_box biliing-form-list-pay">
                                <div class="biliing-form-list-pay">
                                    <li class="biliing-form-list-li">
                                        <perfix>
                                            <?= esc_html__('پرداخت با کیف پول', 'almas-gold'); ?>
                                        </perfix>
                                        <span>
                                            <valsorderpricepayed>
                                                <?= number_format($customer_wallet_balance, 0, '.', ','); ?>
                                            </valsorderpricepayed>
                                            <suffix>
                                                <?= esc_html__('تومان', 'almas-gold'); ?>
                                            </suffix>
                                        </span>
                                    </li>
                                    <li class="biliing-form-list-li" style="    direction: rtl !important;">
                                        <perfix>
                                            <?= esc_html__('موجودی پس از پرداخت', 'almas-gold'); ?>
                                        </perfix>
                                        <span>
                                            <?= esc_html__('0', 'almas-gold'); ?>
                                            <suffix>
                                                <?= esc_html__('تومان', 'almas-gold'); ?>
                                            </suffix>
                                        </span>
                                    </li>
                                </div>
                            </div>
                            <div class="biliing-form-list-pay" style="margin-top: 30px">
                                <li class="biliing-form-list-li biliing-form-list-li-des">
                                    <label>
                                        <?= esc_html__('مبلغ قابل پرداخت', 'almas-gold'); ?>
                                    </label>
                                    <valsorderpricepayed class="vals-Order-Price" id="initial_final_price_display_wallet">
                                        <?php echo number_format($price_payed_online, 0, '.', ','); ?>
                                    </valsorderpricepayed>
                                    <suffix>
                                        <?= esc_html__('تومان', 'almas-gold'); ?>
                                    </suffix>
                                </li>
                                <li class="biliing-form-list-li biliing-form-list-li-des">
                                    <div id="delivery_pay_remaining_online_submit" class="ag_pay_button lh">
                                        <?= esc_html__('پرداخت و تکمیل سفارش', 'almas-gold'); ?>
                                    </div>
                                </li>
                            </div>
                        </div>
                            
                        <?php
                            }
                        ?>
                        
                        
                        
                    </div>
                    <div class="biliing-form-list-pay" style="margin-top: 30px;z-index: 1;">
                        <li class="biliing-form-buttons">
                            <button class="almas-gold-billing-button biliing-form-button-gold" type="button" onclick="redirectToPageDeliveryContinue()">
                                <i class=" prk-edit"></i> ویرایش سفارش
                            </button>
                            <script>
                                function redirectToPageDeliveryContinue() {
                                    var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                                    if (width <= 768) { // فرض کنید 768px برای موبایل است
                                        window.location.href = 'https://almas.gold/dashboard/';
                                    } else {
                                        window.location.href = 'https://almas.gold/dashboard/safebox/delivery';
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
                <div class="online-payment">
                    <div id="online_payment_popup" class="payment_popup">
                        <h3 style="margin: 0 0 20px 0">
                            <?= esc_html__('آیا از درخواست خود مطمئنید؟', 'almas-gold'); ?>
                        </h3>
                        <div style="margin-bottom: 30px">
                            <label class="checkbox_cont">
                                <input type="checkbox" id="by_online_rules_agreement">
                                <span class="checkmark"></span>
                                <label for="by_online_rules_agreement" style="margin-right: 25px">
                                    <?= esc_html__('من با همه ', 'almas-gold'); ?>
                                    <a href="/terms" target="blank"><?= esc_html__('شرایط و قوانین سایت', 'almas-gold'); ?></a>
                                    <?= esc_html__('موافقم.', 'almas-gold'); ?>
                                </label>
                            </label>
                        </div>
                        <div class="biliing-form-list-pay">
                            <li class="biliing-form-list-li biliing-form-list-li-des">
                                <?php wp_nonce_field('delivery_pay_all_online_submit_nonce', 'nonce_field_delivery_pay_all_online'); ?>
                                <button type="submit" name="delivery_pay_all_online_submit" disabled>
                                    <?= esc_html__('پرداخت و تکمیل سفارش', 'almas-gold'); ?>
                                </button>
                            </li>
                            <li class="biliing-form-list-li biliing-form-list-li-des" style="width: fit-content;">
                                <div id="online_payment_close_popup" class="ag_pay_button close_popup lh">
                                    <?= esc_html__('خیر', 'almas-gold'); ?>
                                </div>
                            </li>
                        </div>
                    </div>
                    <div id="online_payment_overlay" class="payment_overlay"></div>
                </div>
                <div class="online-payment">
                    <div id="pay_all_by_wallet_popup" class="payment_popup">
                        <h3 style="margin: 0 0 20px 0">
                            <?= esc_html__('آیا از خرید خود مطمئنید؟', 'almas-gold'); ?>
                        </h3>
                        <div style="margin-bottom: 30px">
                            <label class="checkbox_cont">
                                <input type="checkbox" id="by_wallet_rules_agreement">
                                <span class="checkmark"></span>
                                <label for="by_wallet_rules_agreement" style="margin-right: 25px">
                                    <?= esc_html__('من با همه ', 'almas-gold'); ?>
                                    <a href="/terms" target="blank"><?= esc_html__('شرایط و قوانین سایت', 'almas-gold'); ?></a>
                                    <?= esc_html__('موافقم.', 'almas-gold'); ?>
                                </label>
                            </label>
                        </div>
                        <div class="biliing-form-list-pay">
                            <li class="biliing-form-list-li biliing-form-list-li-des">
                                <?php wp_nonce_field('delivery_pay_all_by_wallet_submit_nonce', 'nonce_field_delivery_pay_all_by_wallet'); ?>
                                <button type="submit" name="delivery_pay_all_by_wallet_submit" disabled>
                                    <?= esc_html__('پرداخت با کیف پول', 'almas-gold'); ?>
                                </button>
                            </li>
                            <li class="biliing-form-list-li biliing-form-list-li-des" style="width: fit-content;">
                                <div id="pay_all_by_wallet_close_popup" class="ag_pay_button close_popup lh">
                                    <?= esc_html__('خیر', 'almas-gold'); ?>
                                </div>
                            </li>
                        </div>
                    </div>
                    <div id="pay_all_by_wallet_overlay" class="payment_overlay"></div>
                </div>
                <div  class="online-payment">
                    <div id="pay_remaining_online_popup" class="payment_popup">
                        <h3 style="margin: 0 0 20px 0">
                            <?= esc_html__('آیا از درخواست خود مطمئنید؟', 'almas-gold'); ?>
                        </h3>
                        <div style="margin-bottom: 30px">
                            <label class="checkbox_cont">
                                <input type="checkbox" id="pay_remaining_online_rules_agreement">
                                <span class="checkmark"></span>
                                <label for="pay_remaining_online_rules_agreement" style="margin-right: 25px">
                                    <?= esc_html__('من با همه ', 'almas-gold'); ?>
                                    <a href="/terms" target="blank"><?= esc_html__('شرایط و قوانین سایت', 'almas-gold'); ?></a>
                                    <?= esc_html__('موافقم.', 'almas-gold'); ?>
                                </label>
                            </label>
                        </div>
                        <div class="biliing-form-list-pay">
                            <li class="biliing-form-list-li biliing-form-list-li-des">
                                <?php wp_nonce_field('delivery_pay_remaining_online_submit_nonce', 'nonce_field_delivery_pay_remaining_online'); ?>
                                <button type="submit" name="delivery_pay_remaining_online_submit" disabled>
                                    <?= esc_html__('پرداخت و تکمیل سفارش', 'almas-gold'); ?>
                                </button>
                            </li>
                            <li class="biliing-form-list-li biliing-form-list-li-des" style="width: fit-content;">
                                <div id="all_wallet_payment_close_popup" class="ag_pay_button close_popup lh">
                                    <?= esc_html__('خیر', 'almas-gold'); ?>
                                </div>
                            </li>
                        </div>
                    </div>
                    <div id="pay_remaining_online_overlay" class="payment_overlay"></div>
                </div>
            </div>
        </form>
    <?php
