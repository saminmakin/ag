<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas.gold admin edit delivery status
     */

    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
        <div id="delivery_current_order">
            <div class="new_gold_weight_submit_box" style="margin-top: 25px !important">
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <div>
                            <input 
                                type="number"
                                min="1"
                                max="100"
                                step="0.001"
                                name="delivery_gold_weight_input" 
                                id="delivery_gold_weight_input" 
                                value="<?php echo esc_attr($delivery_data->gold_weight); ?>"
                                
                                placeholder="وزن"
                            >
                            <suffix>
                                <?= esc_html__('گرم', 'almas-gold'); ?>
                            </suffix>
                        </div>
                        <div style="margin-right: 15px">
                            <div>
                                <label id="gold_weight_diff_value" style="display: none">
                                    <?php echo rtrim(rtrim(number_format(abs($gold_weiht_diff), 4, '.', ''), '0'), '.'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex-space-between-align-center">
                        <div class="flex-space-between-align-center">
                            <span>
                                <?= esc_html__('اجرت طلا', 'almas-gold'); ?>
                            </span>
                            <label id="new_gold_type_fee_amount_value" style="margin-right: 20px">
                            </label>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                    <div class="flex-space-between-align-center">
                        <span><?= esc_html__('مبلغ طلا', 'almas-gold'); ?></span>
                        <div>
                            <label id="new_total_gold_price_value" style="margin-right: 20px">
                            </label>
                            <suffix><?= esc_html__('تومان', 'almas-gold'); ?></suffix>
                        </div>
                    </div>
                </div>
            </div>
            <div class="payment_submit_box">
                <div id="deposit_from_customer" >
                    <div class="flex-space-between-align-center" style="margin-top: 8px">
                        <div>
                            <label class="checkbox_cont">
                                <input 
                                    type="checkbox" 
                                    id="deposit_from_customer_wallet" 
                                    name="deposit_from_customer_wallet" 
                                    value="1" 
                                    <?php if ($customer_wallet_balance == 0) echo 'disabled'; ?>
                                >
                                <span id="wallet_dep_ceckbox" class="checkmark <?php if ($customer_wallet_balance == 0) echo 'disabled_checkbox'; ?>"></span>
                                <label id="wallet_dep_ceckbox_text" class="<?php if ($customer_wallet_balance == 0) echo 'nullwallet'; ?>" for="deposit_from_customer_wallet" style="margin-right: 35px">
                                    <?= esc_html__('برداشت از کیف پول', 'almas-gold'); ?>
                                </label>
                            </label>
                        </div>
                        <div>
                        <?php
                            if ($customer_wallet_balance == 0) {
                                echo '
                                    <perfix style="margin-left:0 !important">
                                        ' . esc_html__("کیف پول خالی است", "almas-gold") . '
                                    </perfix>
                                ';
                            } else {
                                echo '
                                    <div>
                                        <perfix>
                                            ' . esc_html__("موجودی", "almas-gold") . '
                                        </perfix>
                                        <span>
                                            ' . number_format($customer_wallet_balance, 0, '.', ',') . '
                                        </span>
                                        <suffix>
                                            ' . esc_html__("تومان", "almas-gold") . '
                                        </suffix>
                                    </div>
                                ';
                            }
                        ?>
                        </div>
                    </div>
                    <div class="flex-space-between-align-center" style="margin-top: 25px">
                        <div>
                            <label class="checkbox_cont">
                                <input 
                                    type="checkbox" 
                                    id="deposit_from_customer_safe" 
                                    name="deposit_from_customer_safe" 
                                    value="1" 
                                    <?php if ($customer_safe_balance == 0) echo 'disabled'; ?>
                                >
                                <span id="safe_dep_ceckbox" class="checkmark <?php if ($customer_safe_balance == 0) echo 'disabled_checkbox'; ?>"></span>
                                <label 
                                    id="safe_dep_ceckbox_text"
                                    class="<?php if ($customer_safe_balance == 0) echo 'disabled_text'; ?>" 
                                    for="deposit_from_customer_safe" 
                                    style="margin-right: 35px">
                                    <?= esc_html__('برداشت از گاوصندوق', 'almas-gold'); ?>
                                </label>
                            </label>
                        </div>
                        <div class="flex-space-between-align-center">
                            <div style="margin-left: 50px">
                                <?php
                                    if ($customer_wallet_balance == 0) {
                                        echo '
                                            
                                        ';
                                    } else {
                                        echo '
                                            <label>
                                                ' . esc_html__('وزن قابل دریافت', 'almas-gold') . '
                                            </label>
                                            <valsGoldWeight id="give_from_customer_by_gold">
                                                ' . rtrim(rtrim(number_format(abs($give_from_customer_by_gold), 4, '.', ''), '0'), '.') . '
                                            </valsGoldWeight>
                                            <suffix>
                                                ' . esc_html__('گرم'  , 'almas-gold') . '
                                            </suffix>
                                        ';
                                    }
                                ?>
                            </div>
                            <div>
                                <?php
                                    if ($customer_safe_balance == 0) {
                                        echo '
                                            <perfix style="margin-left:0 !important">
                                                ' . esc_html__("گاوصندوق خالی است", "almas-gold") . '
                                            </perfix>
                                        ';
                                    } else {
                                        echo '
                                            <perfix>
                                                ' . esc_html__("موجودی ", "almas-gold") . '
                                            </perfix>
                                            <span>
                                                ' . rtrim(rtrim(number_format($customer_safe_balance, 4, '.', ''), '0'), '.') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__("گرم", "almas-gold") . '
                                            </suffix>
                                        ';
                                    }
                                ?>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div id="transfer_to_customer" style="margin-top: 8px; display: none">
                    <div class="flex-space-between-align-center" style="margin-top: 8px">
                        <div>
                            <label class="checkbox_cont">
                                <input 
                                    type="checkbox" 
                                    id="transfer_to_customer_wallet" 
                                    name="transfer_to_customer_wallet" 
                                    value="1" 
                                    <?php if ($customer_wallet_balance == 0) echo 'disabled'; ?>
                                >
                                <span id="wallet_dep_ceckbox_2" class="checkmark <?php if ($customer_wallet_balance == 0) echo 'disabled_checkbox'; ?>"></span>
                                <label id="wallet_dep_ceckbox_text_2" class="<?php if ($customer_wallet_balance == 0) echo 'nullwallet'; ?>" for="transfer_to_customer_wallet" style="margin-right: 35px">
                                    <?= esc_html__('واریز به کیف پول', 'almas-gold'); ?>
                                </label>
                            </label>
                        </div>
                        <div>
                        <?php
                            if ($customer_wallet_balance == 0) {
                                echo '
                                    <perfix style="margin-left:0 !important">
                                        ' . esc_html__("کیف پول خالی است", "almas-gold") . '
                                    </perfix>
                                ';
                            } else {
                                echo '
                                    <div>
                                        <perfix>
                                            ' . esc_html__("موجودی", "almas-gold") . '
                                        </perfix>
                                        <span>
                                            ' . number_format($customer_wallet_balance, 0, '.', ',') . '
                                        </span>
                                        <suffix>
                                            ' . esc_html__("تومان", "almas-gold") . '
                                        </suffix>
                                    </div>
                                ';
                            }
                        ?>
                        </div>
                    </div>
                    <div class="flex-space-between-align-center" style="margin-top: 25px">
                        <div>
                            <label class="checkbox_cont">
                                <input 
                                    type="checkbox" 
                                    id="transfer_to_customer_safe" 
                                    name="transfer_to_customer_safe" 
                                    value="1" 
                                    <?php if ($customer_safe_balance == 0) echo 'disabled'; ?>
                                >
                                <span id="safe_dep_ceckbox_2" class="checkmark <?php if ($customer_safe_balance == 0) echo 'disabled_checkbox'; ?>"></span>
                                <label 
                                    id="safe_dep_ceckbox_text_2"
                                    class="<?php if ($customer_safe_balance == 0) echo 'disabled_text'; ?>" 
                                    for="transfer_to_customer_safe" 
                                    style="margin-right: 35px">
                                    <?= esc_html__('انتقال به گاوصندوق', 'almas-gold'); ?>
                                </label>
                            </label>
                        </div>
                        <div class="flex-space-between-align-center">
                            <div style="margin-left: 50px">
                                <?php
                                    if ($customer_wallet_balance == 0) {
                                        echo '
                                            
                                        ';
                                    } else {
                                        echo '
                                            <label>
                                                ' . esc_html__('وزن قابل انتقال', 'almas-gold') . '
                                            </label>
                                            <valsGoldWeight id="pay_to_customer_by_gold">
                                                ' . rtrim(rtrim(number_format(abs($pay_to_customer_by_gold), 4, '.', ''), '0'), '.') . '
                                            </valsGoldWeight>
                                            <suffix>
                                                ' . esc_html__('گرم'  , 'almas-gold') . '
                                            </suffix>
                                        ';
                                    }
                                ?>
                            </div>
                            <div>
                                <?php
                                    if ($customer_safe_balance == 0) {
                                        echo '
                                            <perfix style="margin-left:0 !important">
                                                ' . esc_html__("گاوصندوق خالی است", "almas-gold") . '
                                            </perfix>
                                        ';
                                    } else {
                                        echo '
                                            <perfix>
                                                ' . esc_html__("موجودی ", "almas-gold") . '
                                            </perfix>
                                            <span>
                                                ' . rtrim(rtrim(number_format($customer_safe_balance, 4, '.', ''), '0'), '.') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__("گرم", "almas-gold") . '
                                            </suffix>
                                        ';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hrk"></div>
                <div class="flex-space-between-align-center">
                    <div>
                        <span id="payable_amount" style="display: none">
                            <?= esc_html__('مبلغ قابل پرداخت', 'almas-gold'); ?>
                        </span>
                        <span id="receive_amount" style="display: none">
                            <?= esc_html__('مبلغ قابل دریافت', 'almas-gold'); ?>
                        </span>
                        <span id="null_amount">
                            <?= esc_html__('مبلغ', 'almas-gold'); ?>
                        </span>
                        <label id="new_final_price_value" style="margin-right: 20px">
                            
                        </label>
                        <suffix><?= esc_html__('تومان', 'almas-gold'); ?></suffix>
                    </div>

                    <div id="normal_receive_submit" class="ag_pay_button lh">
                        <?= esc_html__('دریافت و تحویل طلا', 'almas-gold'); ?>
                    </div>

                    <div id="normal_pay_submit" class="ag_pay_button lh" style="display: none">
                        <?= esc_html__('پرداخت و تحویل طلا', 'almas-gold'); ?>
                    </div>
                </div>
                <div>
                    <div id="gold_to_customer_receive_popup" class="payment_popup">
                        <h3 style="margin: 0 0 40px 0">
                            <?= esc_html__('آیا از درخواست خود مطمئنید؟', 'almas-gold'); ?>
                        </h3>
                        <div class="flex-space-between-align-center">
                            <input type="hidden" name="order_id" value="<?php echo esc_attr($delivery_data->delivery_id); ?>">
                            <?php wp_nonce_field('normal_receive_submit_nonce', 'normal_receive_submit_nonce_field'); ?>
                            <input type="submit" name="normal_receive_submit" value="<?= esc_html__('دریافت و تحویل طلا', 'almas-gold'); ?>">
                            <div id="gold_to_customer_receive_close_popup" class="ag_pay_button close_popup lh">
                                <?= esc_html__('خیر', 'almas-gold'); ?>
                            </div>
                        </div>
                    </div>
                    <div id="gold_to_customer_pay_popup" class="payment_popup">
                        <h3 style="margin: 0 0 40px 0">
                            <?= esc_html__('آیا از درخواست خود مطمئنید؟', 'almas-gold'); ?>
                        </h3>
                        <div class="flex-space-between-align-center">
                            <input type="hidden" name="order_id" value="<?php echo esc_attr($delivery_data->delivery_id); ?>">
                            <?php wp_nonce_field('normal_pay_submit_nonce', 'normal_pay_submit_nonce_field'); ?>
                            <input type="submit" name="normal_pay_submit" value="<?= esc_html__('پرداخت و تحویل طلا', 'almas-gold'); ?>">
                            <div id="gold_to_customer_pay_close_popup" class="ag_pay_button close_popup lh">
                                <?= esc_html__('خیر', 'almas-gold'); ?>
                            </div>
                        </div>
                    </div>
                    <div id="gold_to_customer_overlay" class="payment_overlay"></div>
                </div>
            </div>
        </div>
        <?php
            $live_gold_price = $core_data->gold_price;
            $order_gold_weight = $delivery_data->gold_weight;
            $order_gold_type_fee = ($delivery_data->gold_type_fee) / 100;
        ?>
        <script>
            $(document).ready(function() {
                $('#delivery_gold_weight_input').change(function() {
                    var newGoldWeightInput = $(this).val();
                    var liveGoldPrice = parseFloat('<?php echo $live_gold_price; ?>');
                    var orderGoldWeight = parseFloat('<?php echo $order_gold_weight; ?>');
                    var orderGoldTypeFee = parseFloat('<?php echo $order_gold_type_fee; ?>');
                    var CustomerSafeBalance = parseFloat('<?php echo $customer_safe_balance; ?>');
                    var CustomerWalletBalance = parseFloat('<?php echo $customer_wallet_balance; ?>');
                    var newGoldWeight = parseFloat(newGoldWeightInput) || 0;
                    var weightDifference = newGoldWeight - orderGoldWeight;
                    var absDiff = Math.abs(weightDifference);
                    var newTotalGoldPrice = Math.round(absDiff * liveGoldPrice);
                    var goldTypeFeeAmount = Math.round(absDiff * liveGoldPrice * orderGoldTypeFee);
                    var newFinalPrice = Math.round(newTotalGoldPrice + goldTypeFeeAmount);
                    var pricePayByGoldWeight = (newFinalPrice / liveGoldPrice);
                    $('#delivery_gold_weight_input').val(newGoldWeight.toLocaleString('en-US'));
                    $('#new_gold_type_fee_amount_value').text(goldTypeFeeAmount.toLocaleString('en-US'));
                    $('#new_total_gold_price_value').text(newTotalGoldPrice.toLocaleString('en-US'));
                    $('#new_final_price_value').text(newFinalPrice.toLocaleString('en-US'));
                    $('#give_from_customer_by_gold').text(pricePayByGoldWeight.toLocaleString('en-US'));
                    $('#pay_to_customer_by_gold').text(pricePayByGoldWeight.toLocaleString('en-US'));
                    if (CustomerSafeBalance < pricePayByGoldWeight) {
                        $('#deposit_from_customer_safe').prop('disabled', true);
                        $('#safe_dep_ceckbox').addClass('disabled_checkbox_x');
                        $('#safe_dep_ceckbox_text').addClass('disabled_text_x');
                    } else if (CustomerSafeBalance => pricePayByGoldWeight) {
                        $('#deposit_from_customer_safe').prop('disabled', false);
                        $('#safe_dep_ceckbox').removeClass('disabled_checkbox_x');
                        $('#safe_dep_ceckbox_text').removeClass('disabled_text_x');
                    } if (CustomerWalletBalance < newFinalPrice) {
                        $('#deposit_from_customer_wallet').prop('disabled', true);
                        $('#wallet_dep_ceckbox').addClass('disabled_checkbox_x');
                        $('#wallet_dep_ceckbox_text').addClass('disabled_text_x');
                    } else if (CustomerWalletBalance => newFinalPrice) {
                        $('#deposit_from_customer_wallet').prop('disabled', false);
                        $('#wallet_dep_ceckbox').removeClass('disabled_checkbox_x');
                        $('#wallet_dep_ceckbox_text').removeClass('disabled_text_x');
                    } if (weightDifference > 0) {
                        $('#receive_amount').show();
                        $('#normal_receive_submit').show();
                        $('#normal_pay_submit').hide();
                        $('#payable_amount').hide();
                        $('#null_amount').hide();
                        $('#transfer_to_customer').hide();
                        $('#deposit_from_customer').show();
                        $('#gold_weight_diff_value').show().css('background-color', '#90efc2');
                        $('#gold_weight_diff_value').text(absDiff.toLocaleString('en-US'));
                    } else if (weightDifference < 0) {
                        $('#receive_amount').hide();
                        $('#normal_receive_submit').hide();
                        $('#normal_pay_submit').show();
                        $('#payable_amount').show();
                        $('#null_amount').hide();
                        $('#transfer_to_customer').show();
                        $('#deposit_from_customer').hide();
                        $('#gold_weight_diff_value').show().css('background-color', '#f5bdcf');
                        $('#gold_weight_diff_value').text(absDiff.toLocaleString('en-US'));
                    } else {
                        $('#deposit_from_customer_wallet').prop('disabled', true);
                        $('#receive_amount').hide();
                        $('#payable_amount').hide();
                        $('#null_amount').show();
                        $('#transfer_to_customer').hide();
                        $('#deposit_from_customer').show();
                        $('#gold_weight_diff_value').hide();
                    }
                });
                $('#deposit_from_customer_wallet').change(function() {
                    if ($(this).is(':checked')) {
                        $('#deposit_from_customer_safe').prop('disabled', true);
                        $('#safe_dep_ceckbox').addClass('disabled_checkbox_x');
                        $('#safe_dep_ceckbox_text').addClass('disabled_text_x');
                    } else {
                        $('#deposit_from_customer_safe').prop('disabled', false);
                        $('#safe_dep_ceckbox').removeClass('disabled_checkbox_x');
                        $('#safe_dep_ceckbox_text').removeClass('disabled_text_x');
                    }
                });
                $('#deposit_from_customer_safe').change(function() {
                    if ($(this).is(':checked')) {
                        $('#deposit_from_customer_wallet').prop('disabled', true);
                        $('#wallet_dep_ceckbox').addClass('disabled_checkbox_x');
                        $('#wallet_dep_ceckbox_text').addClass('disabled_text_x');
                    } else {
                        $('#deposit_from_customer_wallet').prop('disabled', false);
                        $('#wallet_dep_ceckbox').removeClass('disabled_checkbox_x');
                        $('#wallet_dep_ceckbox_text').removeClass('disabled_text_x');
                    }
                });
                $('#transfer_to_customer_wallet').change(function() {
                    if ($(this).is(':checked')) {
                        $('#transfer_to_customer_safe').prop('disabled', true);
                        $('#safe_dep_ceckbox_2').addClass('disabled_checkbox_x');
                        $('#safe_dep_ceckbox_text_2').addClass('disabled_text_x');
                    } else {
                        $('#transfer_to_customer_safe').prop('disabled', false);
                        $('#safe_dep_ceckbox_2').removeClass('disabled_checkbox_x');
                        $('#safe_dep_ceckbox_text_2').removeClass('disabled_text_x');
                    }
                });
                $('#transfer_to_customer_safe').change(function() {
                    if ($(this).is(':checked')) {
                        $('#transfer_to_customer_wallet').prop('disabled', true);
                        $('#wallet_dep_ceckbox_2').addClass('disabled_checkbox_x');
                        $('#wallet_dep_ceckbox_text_2').addClass('disabled_text_x');
                    } else {
                        $('#transfer_to_customer_wallet').prop('disabled', false);
                        $('#wallet_dep_ceckbox_2').removeClass('disabled_checkbox_x');
                        $('#wallet_dep_ceckbox_text_2').removeClass('disabled_text_x');
                    }
                });
                $('#normal_receive_submit').click(function(){
                    $('#gold_to_customer_overlay, #gold_to_customer_receive_popup').fadeIn(100);
                });
                $('#normal_pay_submit').click(function(){
                    $('#gold_to_customer_overlay, #gold_to_customer_pay_popup').fadeIn(100);
                });
                $('#gold_to_customer_receive_close_popup').click(function(){
                    $('#gold_to_customer_overlay, #gold_to_customer_receive_popup').fadeOut(100);
                });
                $('#gold_to_customer_pay_close_popup').click(function(){
                    $('#gold_to_customer_overlay, #gold_to_customer_pay_popup').fadeOut(100);
                });
            });
        </script>
    <?php
