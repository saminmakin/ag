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
        <div id="delivery_cgt_order" style="display: none">
            <div class="new_gold_weight_submit_box" style="margin-top: 25px !important">
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <div>   
                            <span style="margin-left: 12px">
                                <?= esc_html__('وزن طلا', 'almas-gold'); ?>
                            </span>
                            <input 
                                type="number"
                                min="0"
                                max="100"
                                step="0.001"
                                name="CGT_delivery_gold_weight_input" 
                                id="CGT_delivery_gold_weight_input" 
                                value="<?php echo esc_attr($delivery_data->gold_weight); ?>"
                                placeholder="وزن"
                            >
                            <suffix>
                                <?= esc_html__('گرم', 'almas-gold'); ?>
                            </suffix>
                        </div>
                        <div style="margin-right: 15px">
                            <div>
                                <label id="CGT_gold_weight_diff_value" style="display: none">
                                    <?php echo rtrim(rtrim(number_format(abs($gold_weiht_diff), 4, '.', ''), '0'), '.'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <span style="margin-left: 12px"><?= esc_html__('نوع طلا', 'almas-gold'); ?></span>
                        <select class="minimal" name="CGT_delivery_gold_type" id="CGT_delivery_gold_type">
                            <option value="broken" data-fee="<?= $broken_gold_fee; ?>" <?= $core_data->gold_type === 'broken' ? 'selected' : ''; ?>>
                                <?= esc_html__('طلای شکسته', 'almas-gold'); ?>
                            </option>
                            <option value="without_fee" data-fee="<?= $without_fee_gold_fee; ?>" <?= $core_data->gold_type === 'without_fee' ? 'selected' : ''; ?>>
                                <?= esc_html__('طلای بدون اجرت', 'almas-gold'); ?>
                            </option>
                            <option value="low_fee" data-fee="<?= $low_fee_gold_fee; ?>" <?= $core_data->gold_type === 'low_fee' ? 'selected' : ''; ?>>
                                <?= esc_html__('طلای کم اجرت', 'almas-gold'); ?>
                            </option>
                            <option value="sequins" data-fee="<?= $sequins_gold_fee; ?>" <?= $core_data->gold_type === 'sequins' ? 'selected' : ''; ?>>
                                <?= esc_html__('طلای پولک', 'almas-gold'); ?>
                            </option>
                            <option value="bullion" data-fee="<?= $bullion_gold_fee; ?>" <?= $core_data->gold_type === 'bullion' ? 'selected' : ''; ?>>
                                <?= esc_html__('طلای شمش', 'almas-gold'); ?>
                            </option>
                        </select>
                        <span id="CGT_gold_type_fee" style="font-weight: 700; margin-right: 15px">
                            <?php echo esc_attr(rtrim(rtrim(number_format($gold_type_fee, 4, '.', ''), '0'), '.')); ?>
                        </span>
                        <suffix>
                            <?= esc_html__('%', 'almas-gold'); ?>
                        </suffix>
                    </div>
                </div>
                <div class="hrk"></div>
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <span><?= esc_html__('مبلغ طلا', 'almas-gold'); ?></span>
                        <div>
                            <label id="CGT_new_total_gold_price" style="margin-right: 20px">

                            </label>
                            <suffix><?= esc_html__('تومان', 'almas-gold'); ?></suffix>
                        </div>
                    </div>
                    <div class="flex-space-between-align-center">
                        <div class="flex-space-between-align-center">
                            <span>
                                <?= esc_html__('اجرت طلا', 'almas-gold'); ?>
                            </span>
                            <label id="CGT_new_gold_type_fee_amount" style="margin-right: 20px">
                            </label>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                </div>
            </div>
            <div class="payment_submit_box">
                <div id="CGT_deposit_from_customer" >
                    <div class="flex-space-between-align-center" style="margin-top: 8px">
                        <div>
                            <label class="checkbox_cont">
                                <input 
                                    type="checkbox" 
                                    id="CGT_deposit_from_customer_wallet" 
                                    name="CGT_deposit_from_customer_wallet" 
                                    value="1" 
                                    <?php if ($customer_wallet_balance == 0) echo 'disabled'; ?>
                                >
                                <span id="CGT_wallet_dep_ceckbox" class="checkmark <?php if ($customer_wallet_balance == 0) echo 'disabled_checkbox'; ?>"></span>
                                <label id="CGT_wallet_dep_ceckbox_text" class="<?php if ($customer_wallet_balance == 0) echo 'nullwallet'; ?>" for="CGT_deposit_from_customer_wallet" style="margin-right: 35px">
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
                                    id="CGT_deposit_from_customer_safe" 
                                    name="CGT_deposit_from_customer_safe" 
                                    value="1" 
                                    <?php if ($customer_safe_balance == 0) echo 'disabled'; ?>
                                >
                                <span id="CGT_safe_dep_ceckbox" class="checkmark <?php if ($customer_safe_balance == 0) echo 'disabled_checkbox'; ?>"></span>
                                <label 
                                    id="CGT_safe_dep_ceckbox_text"
                                    class="<?php if ($customer_safe_balance == 0) echo 'disabled_text'; ?>" 
                                    for="CGT_deposit_from_customer_safe" 
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
                                            <valsGoldWeight id="CGT_give_from_customer_by_gold">
                                                ' . rtrim(rtrim(number_format(abs($CGT_give_from_customer_by_gold), 4, '.', ''), '0'), '.') . '
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
                <div id="CGT_transfer_to_customer" style="margin-top: 8px; display: none">
                    <div class="flex-space-between-align-center" style="margin-top: 8px">
                        <div>
                            <label class="checkbox_cont">
                                <input 
                                    type="checkbox" 
                                    id="CGT_transfer_to_customer_wallet" 
                                    name="CGT_transfer_to_customer_wallet" 
                                    value="1" 
                                >
                                <span id="CGT_wallet_dep_ceckbox_2" class="checkmark"></span>
                                <label id="CGT_wallet_dep_ceckbox_text_2" for="CGT_transfer_to_customer_wallet" style="margin-right: 35px">
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
                                    id="CGT_transfer_to_customer_safe" 
                                    name="CGT_transfer_to_customer_safe" 
                                    value="1" 
                                >
                                <span id="CGT_safe_dep_ceckbox_2" class="checkmark"></span>
                                <label 
                                    id="CGT_safe_dep_ceckbox_text_2"
                                    for="CGT_transfer_to_customer_safe" 
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
                                            <valsGoldWeight id="CGT_pay_to_customer_by_gold">
                                                ' . rtrim(rtrim(number_format(abs($CGT_pay_to_customer_by_gold), 4, '.', ''), '0'), '.') . '
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
                        <span id="CGT_receive_amount" style="display: none">
                            <?= esc_html__('مبلغ قابل دریافت', 'almas-gold'); ?>
                        </span>
                        <span id="CGT_payable_amount" style="display: none">
                            <?= esc_html__('مبلغ قابل پرداخت', 'almas-gold'); ?>
                        </span>

                        <span id="CGT_null_amount">
                            <?= esc_html__('مبلغ', 'almas-gold'); ?>
                        </span>
                        <label id="CGT_new_final_price" style="margin-right: 20px">

                        </label>
                        <suffix><?= esc_html__('تومان', 'almas-gold'); ?></suffix>
                    </div>
                    <div id="CGT_receive_submit" class="ag_pay_button lh">
                        <?= esc_html__('دریافت و تحویل طلا', 'almas-gold'); ?>
                    </div>
                    <div id="CGT_pay_submit" class="ag_pay_button lh" style="display: none">
                        <?= esc_html__('پرداخت و تحویل طلا', 'almas-gold'); ?>
                    </div>
                </div>
                <div>
                    <div id="gold_to_customer_receive_popup" class="payment_popup">
                        <h3 style="margin: 0 0 40px 0">
                            <?= esc_html__('آیا از درخواست خود مطمئنید؟', 'almas-gold'); ?>
                        </h3>
                        <div class="flex-space-between-align-center" style="margin-bottom: 30px">
                            <div>
                                <input type="hidden" name="order_id" value="<?php echo esc_attr($delivery_data->delivery_id); ?>">
                                <?php wp_nonce_field('CGT_receive_submit_nonce', 'CGT_receive_submit_nonce_field'); ?>
                                <input type="submit" name="CGT_receive_submit" value="<?= esc_html__('دریافت و تحویل طلا', 'almas-gold'); ?>">
                            </div>
                            <div id="CGT_gold_to_customer_receive_close_popup" class="ag_pay_button close_popup lh">
                                <?= esc_html__('خیر', 'almas-gold'); ?>
                            </div>
                        </div>
                    </div>
                    <div id="gold_to_customer_pay_popup" class="payment_popup">
                        <h3 style="margin: 0 0 40px 0">
                            <?= esc_html__('آیا از درخواست خود مطمئنید؟', 'almas-gold'); ?>
                        </h3>
                        <div class="flex-space-between-align-center" style="margin-bottom: 30px">
                            <div>
                                <input type="hidden" name="order_id" value="<?php echo esc_attr($delivery_data->delivery_id); ?>">
                                <?php wp_nonce_field('CGT_pay_submit_nonce', 'CGT_pay_submit_nonce_field'); ?>
                                <input type="submit" name="CGT_pay_submit" value="<?= esc_html__('پرداخت و تحویل طلا', 'almas-gold'); ?>">
                            </div>
                            <div id="CGT_gold_to_customer_pay_close_popup" class="ag_pay_button close_popup lh">
                                <?= esc_html__('خیر', 'almas-gold'); ?>
                            </div>
                        </div>
                    </div>
                    <div id="CGT_gold_to_customer_overlay" class="payment_overlay"></div>
                </div>
            </div>
        </div>
        <?php
            $live_gold_price = $core_data->gold_price;
            $order_gold_price = $delivery_data->gold_price;
            $order_gold_weight = $delivery_data->gold_weight;
            $order_gold_type_fee = ($delivery_data->gold_type_fee) / 100;
            $order_initial_fee_price = $delivery_data->initial_final_price;
            $order_initial_price = $delivery_data->initial_price;
        ?>
        <script>
            $(document).ready(function() {
                $('#change_gold_type_checkbox').change(function() {
                    if ($(this).is(':checked')) {
                        function updateCalculations() {
                            var CGTnewGoldWeightInput = $('#CGT_delivery_gold_weight_input').val();
                            var CGTliveGoldPrice = parseFloat('<?php echo $live_gold_price; ?>');
                            var CGTorderGoldPrice = parseFloat('<?php echo $order_gold_price; ?>');
                            var CGTorderGoldWeight = parseFloat('<?php echo $order_gold_weight; ?>');
                            var CGTorderGoldTypeFee = parseFloat('<?php echo $order_gold_type_fee; ?>');
                            var CGTdeliveryGoldTypeFee = parseFloat($("#CGT_delivery_gold_type").find(':selected').data('fee')) / 100;
                            var CGTorderInitialFeePrice = parseFloat('<?php echo $order_initial_fee_price; ?>');
                            var CGTorderInitialPrice = parseFloat('<?php echo $order_initial_price; ?>');
                            var CGTorderInitialFinalPrice = (CGTorderInitialFeePrice + CGTorderInitialPrice);
                            var CGTcustomerSafeBalance = parseFloat('<?php echo $customer_safe_balance; ?>');
                            var CGTcustomerWalletBalance = parseFloat('<?php echo $customer_wallet_balance; ?>');
        
                            var CGTnewGoldWeight = parseFloat(CGTnewGoldWeightInput) || 0;
                            var CGTweightDifference = (CGTnewGoldWeight - CGTorderGoldWeight);
                            var CGTabsDiff = Math.abs(CGTweightDifference);
                            var CGTnewTotalGoldPrice = Math.round(Math.abs(CGTnewGoldWeight * CGTliveGoldPrice));
                            
                            var CGTgoldTypeFeeAmount = Math.round(Math.abs(CGTnewTotalGoldPrice * CGTdeliveryGoldTypeFee));
                            var CGTnewFinalPrice = Math.round(Math.abs((CGTnewTotalGoldPrice + CGTgoldTypeFeeAmount) - CGTorderInitialFinalPrice));
                            var CGTpricePayByGoldWeight = (CGTnewFinalPrice / CGTliveGoldPrice);
                            $('#CGT_delivery_gold_weight_input').val(CGTnewGoldWeight.toLocaleString('en-US'));
                            $('#CGT_new_gold_type_fee_amount').text(CGTgoldTypeFeeAmount.toLocaleString('en-US'));
                            $('#CGT_new_total_gold_price').text(CGTnewTotalGoldPrice.toLocaleString('en-US'));
                            $('#CGT_new_final_price').text(CGTnewFinalPrice.toLocaleString('en-US'));
                            $('#CGT_give_from_customer_by_gold').text(CGTpricePayByGoldWeight.toLocaleString('en-US'));
                            $('#CGT_pay_to_customer_by_gold').text(CGTpricePayByGoldWeight.toLocaleString('en-US'));
                            if (CGTcustomerSafeBalance < CGTpricePayByGoldWeight) {
                                $('#CGT_deposit_from_customer_safe').prop('disabled', true);
                                $('#CGT_safe_dep_ceckbox').addClass('disabled_checkbox_x');
                                $('#CGT_safe_dep_ceckbox_text').addClass('disabled_text_x');
                            } else if (CGTcustomerSafeBalance >= CGTpricePayByGoldWeight) {
                                $('#CGT_deposit_from_customer_safe').prop('disabled', false);
                                $('#CGT_safe_dep_ceckbox').removeClass('disabled_checkbox_x');
                                $('#CGT_safe_dep_ceckbox_text').removeClass('disabled_text_x');
                            }
                            if (CGTcustomerWalletBalance < CGTnewFinalPrice) {
                                $('#CGT_deposit_from_customer_wallet').prop('disabled', true);
                                $('#CGT_wallet_dep_ceckbox').addClass('disabled_checkbox_x');
                                $('#CGT_wallet_dep_ceckbox_text').addClass('disabled_text_x');
                            } else if (CGTcustomerWalletBalance >= CGTnewFinalPrice) {
                                $('#CGT_deposit_from_customer_wallet').prop('disabled', false);
                                $('#CGT_wallet_dep_ceckbox').removeClass('disabled_checkbox_x');
                                $('#CGT_wallet_dep_ceckbox_text').removeClass('disabled_text_x');
                            }
                            if (CGTweightDifference > 0) {
                                $('#CGT_receive_amount').show();
                                $('#CGT_receive_submit').show();
                                $('#CGT_pay_submit').hide();
                                $('#CGT_payable_amount').hide();
                                $('#CGT_payable_amount_submit').hide();
                                $('#CGT_null_amount').hide();
                                $('#CGT_transfer_to_customer').hide();
                                $('#CGT_deposit_from_customer').show();
                                $('#CGT_gold_weight_diff_value').show().css('background-color', '#90efc2');
                                $('#CGT_gold_weight_diff_value').text(CGTabsDiff.toLocaleString('en-US'));
                            } else if (CGTweightDifference < 0) {
                                $('#CGT_receive_amount').hide();
                                $('#CGT_receive_submit').hide();
                                $('#CGT_pay_submit').show();
                                $('#CGT_payable_amount').show();
                                $('#CGT_payable_amount_submit').show();
                                $('#CGT_null_amount').hide();
                                $('#CGT_transfer_to_customer').show();
                                $('#CGT_deposit_from_customer').hide();
                                $('#CGT_gold_weight_diff_value').show().css('background-color', '#f5bdcf');
                                $('#CGT_gold_weight_diff_value').text(CGTabsDiff.toLocaleString('en-US'));
                            } else {
                                $('#CGT_deposit_from_customer_wallet').prop('disabled', true);
                                $('#CGT_receive_amount').hide();
                                $('#CGT_payable_amount').hide();
                                $('#CGT_null_amount').show();
                                $('#CGT_transfer_to_customer').hide();
                                $('#CGT_deposit_from_customer').show();
                            }
                        }
                        $('#CGT_delivery_gold_weight_input').change(function() {
                            updateCalculations();
                        });
                        $('#CGT_delivery_gold_type').change(function() {
                            CGT_currentGoldType = $(this).val(); 
                            CGT_deliveryGoldType = $(this).val();
                            CGT_goldTypeFee = parseFloat($(this).find(':selected').data('fee'));
                            $('#CGT_gold_type_fee').text(CGT_goldTypeFee.toFixed(0));
                            updateCalculations(); 
                        });
                        $('#CGT_deposit_from_customer_wallet').change(function() {
                            if ($(this).is(':checked')) {
                                $('#CGT_deposit_from_customer_safe').prop('disabled', true);
                                $('#CGT_safe_dep_ceckbox').addClass('disabled_checkbox_x');
                                $('#CGT_safe_dep_ceckbox_text').addClass('disabled_text_x');
                            } else {
                                $('#CGT_deposit_from_customer_safe').prop('disabled', false);
                                $('#CGT_safe_dep_ceckbox').removeClass('disabled_checkbox_x');
                                $('#CGT_safe_dep_ceckbox_text').removeClass('disabled_text_x');
                            }
                        });
                        $('#CGT_deposit_from_customer_safe').change(function() {
                            if ($(this).is(':checked')) {
                                $('#CGT_deposit_from_customer_wallet').prop('disabled', true);
                                $('#CGT_wallet_dep_ceckbox').addClass('disabled_checkbox_x');
                                $('#CGT_wallet_dep_ceckbox_text').addClass('disabled_text_x');
                            } else {
                                $('#CGT_deposit_from_customer_wallet').prop('disabled', false);
                                $('#CGT_wallet_dep_ceckbox').removeClass('disabled_checkbox_x');
                                $('#CGT_wallet_dep_ceckbox_text').removeClass('disabled_text_x');
                            }
                        });
                        $('#CGT_transfer_to_customer_wallet').change(function() {
                            if ($(this).is(':checked')) {
                                $('#CGT_transfer_to_customer_safe').prop('disabled', true);
                                $('#CGT_safe_dep_ceckbox_2').addClass('disabled_checkbox_x');
                                $('#CGT_safe_dep_ceckbox_text_2').addClass('disabled_text_x');
                            } else {
                                $('#CGT_transfer_to_customer_safe').prop('disabled', false);
                                $('#CGT_safe_dep_ceckbox_2').removeClass('disabled_checkbox_x');
                                $('#CGT_safe_dep_ceckbox_text_2').removeClass('disabled_text_x');
                            }
                        });
                        $('#CGT_transfer_to_customer_safe').change(function() {
                            if ($(this).is(':checked')) {
                                $('#CGT_transfer_to_customer_wallet').prop('disabled', true);
                                $('#CGT_wallet_dep_ceckbox_2').addClass('disabled_checkbox_x');
                                $('#CGT_wallet_dep_ceckbox_text_2').addClass('disabled_text_x');
                            } else {
                                $('#CGT_transfer_to_customer_wallet').prop('disabled', false);
                                $('#CGT_wallet_dep_ceckbox_2').removeClass('disabled_checkbox_x');
                                $('#CGT_wallet_dep_ceckbox_text_2').removeClass('disabled_text_x');
                            }
                        });
                        $('#CGT_receive_submit').click(function(){
                            $('#CGT_gold_to_customer_overlay, #gold_to_customer_receive_popup').fadeIn(100);
                        });
                        $('#CGT_gold_to_customer_receive_close_popup').click(function(){
                            $('#CGT_gold_to_customer_overlay, #gold_to_customer_receive_popup').fadeOut(100);
                        });
                        $('#CGT_pay_submit').click(function(){
                            $('#CGT_gold_to_customer_overlay, #gold_to_customer_pay_popup').fadeIn(100);
                        });
                        $('#CGT_gold_to_customer_pay_close_popup').click(function(){
                            $('#CGT_gold_to_customer_overlay, #gold_to_customer_pay_popup').fadeOut(100);
                        });
                    }
                });
            });
        </script>
    <?php
