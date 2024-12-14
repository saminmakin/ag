<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas-gold delivery form html
     */

    ?>
        <form method="post">
            <div class="action_controls">
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <div class="flex-delivery-form-des-weight">
                            <i class=" prk-weight"></i>
                            <input 
                                type="text" 
                                id="delivery_gold_weight" 
                                name="delivery_gold_weight" 
                                step="0.001"
                                inputmode="decimal"
                                pattern="[0-9.]*"
                                lang="en"
                                required
                                autocomplete="off"
                                min="<?= is_user_logged_in() ? esc_attr($delivery_lowest_limit) : ''; ?>"
                                max="<?= is_user_logged_in() ? esc_attr($new_delivery_highest_limit) : ''; ?>"
                                <?php if (is_user_logged_in() && $customer_safe_balance < $delivery_lowest_limit) echo 'disabled'; ?>
                                oninput="updateInitialPrice()"
                            >
                            <div class="flex-delivery-form-des-weight-parts">
                            	<div class="flex-delivery-form-des-weight-parts-first"></div>
                            		<div class="flex-delivery-form-des-weight-parts-sec" style="">
                            			<label class="flex-delivery-form-des-weight-label" style="">وزن</label>
                            		</div>
                            	<div class="flex-delivery-form-des-weight-parts-third"></div>
                            </div>
                            <suffix>
                                <?= esc_html__('گرم', 'almas-gold'); ?>
                            </suffix>
                        </div>
                        <div class="flex-delivery-form-des-price">
                            <i class=" prk-moneys"></i>
                            <input 
                                type="text" 
                                id="delivery_initial_price_display" 
                                autocomplete="off"
                                inputmode="decimal"
                                oninput="updateGoldWeight()"
                                <?php if (is_user_logged_in() && $customer_safe_balance < $delivery_lowest_limit) echo 'disabled'; ?>
                            >
                            <div class="flex-delivery-form-des-price-parts">
                            	<div class="flex-delivery-form-des-price-parts-first"></div>
                            		<div class="flex-delivery-form-des-price-parts-sec" style="">
                            			<label class="flex-delivery-form-des-price-label" style="">مبلغ</label>
                            		</div>
                            	<div class="flex-delivery-form-des-price-parts-third"></div>
                            </div>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                    <?php wp_nonce_field('almas_gold_delivery_submit_nonce', 'nonce_field_almas_gold_delivery'); ?>
                    <button type="submit" name="almas_gold_delivery_submit" class="almas-gold-delivery-button"
                        <?php if (is_user_logged_in() && $customer_safe_balance < $delivery_lowest_limit) echo 'disabled'; ?> >
                        <i class=" prk-directbox-default"></i> <?= esc_html__('ادامه دریافت', 'almas-gold'); ?>
                    </button>
                </div>
                <div>
                    <?php
                        if (is_user_logged_in()) {  
                            if ($customer_safe_balance == 0) {
                                echo '
                                    <div class="failed">
                                        <i class=" prk-forbidden-2"></i>
                                        گاوصندوق شما خالی است.
                                    </div>
                                ';
                            } else {
                                echo '
                                    <div class="almas-gold-shop-info">
                                    <i class=" prk-information"></i>
                                    <perfix style="display: grid;">    
                                        <perfix style="margin-bottom:15px;">
                                            <perfix>
                                                ' . esc_html__('حداقل وزن دریافت طلا:', 'almas-gold') . '
                                            </perfix>
                                            <span>
                                                ' . rtrim(rtrim(number_format($delivery_lowest_limit, 4, '.', ''), '0'), '.') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__($unit  , 'almas-gold') . '
                                            </suffix>
                                        </perfix>
                                        <perfix>
                                            <perfix>
                                                ' . esc_html__('حداکثر وزن دریافت طلا:', 'almas-gold') . '
                                            </perfix>
                                            <span>
                                                ' . rtrim(rtrim(number_format($new_delivery_highest_limit, 4, '.', ''), '0'), '.') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__('گرم', 'almas-gold') . '
                                            </suffix>
                                        </perfix>
                                    </perfix>
                                </div>
                                ';
                            }
                        }
                    ?>
                </div> 
            </div>
        </form>
    <?php
