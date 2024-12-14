<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas-gold sale form html
     */

    ?>
        <form method="post">
            <div class="action_controls">
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <div class="flex-sale-form-des-weight">
                            <i class=" prk-weight"></i>
                            <input 
                                type="text" 
                                id="sale_gold_weight" 
                                name="sale_gold_weight" 
                                oninput="updateDisplayedSalePrice()"
                                step="0.001"
                                min="<?= esc_attr($sale_lowest_limit); ?>"
                                max="<?= esc_attr($sale_highest_limit); ?>"
                                required
                                autocomplete="off"
                                inputmode="decimal"
                                pattern="[0-9.]*"
                                lang="en"
                                <?php if ($customer_safe_balance < $sale_lowest_limit   ) echo 'disabled'; ?>
                            >
                            <div class="flex-sale-form-des-weight-parts">
                            	<div class="flex-sale-form-des-weight-parts-first"></div>
                            		<div class="flex-sale-form-des-weight-parts-sec" style="">
                            			<label class="flex-sale-form-des-weight-label" style="">وزن</label>
                            		</div>
                            	<div class="flex-sale-form-des-weight-parts-third"></div>
                            </div>
                            <suffix>
                                <?= esc_html__('گرم', 'almas-gold'); ?>
                            </suffix>
                        </div>
                        <div class="flex-sale-form-des-price" style="width:100%;">
                            <i class=" prk-moneys"></i>
                            <input 
                                type="text" 
                                id="sale_initial_price_display" 
                                style="text-align: center; direction: ltr"
                                oninput="updateSaleGoldWeight()"
                                autocomplete="off"
                                inputmode="decimal"
                                <?php if ($customer_safe_balance < $sale_lowest_limit   ) echo 'disabled'; ?>
                            >
                            <div class="flex-sale-form-des-price-parts">
                            	<div class="flex-sale-form-des-price-parts-first"></div>
                            		<div class="flex-sale-form-des-price-parts-sec" style="">
                            			<label class="flex-sale-form-des-price-label" style="">مبلغ</label>
                            		</div>
                            	<div class="flex-sale-form-des-price-parts-third"></div>
                            </div>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                    <?php wp_nonce_field('almas_gold_sale_submit_nonce', 'nonce_field_almas_gold_sale'); ?>
                        <button type="submit" name="almas_gold_sale_submit" class="almas-gold-sale-button"
                            <?php if ($customer_safe_balance < $sale_lowest_limit   ) echo 'disabled'; ?>
                        >
                        <i class=" prk-direct-send"></i> <?= esc_html__('فروش', 'almas-gold'); ?>
                    </button>
                </div>
                <div>
                    <?php
                        if (is_user_logged_in()) {
                            if ($customer_safe_balance == 0) {
                                echo '
                                    <div class="almas-gold-sale-info">
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
                                                    ' . esc_html__('حداقل وزن فروش طلا:', 'almas-gold') . '
                                                </perfix>
                                                <span>
                                                    ' . rtrim(rtrim(number_format($sale_lowest_limit_display, 4, '.', ''), '0'), '.') . '
                                                </span>
                                                <suffix>
                                                    ' . esc_html__($unit  , 'almas-gold') . '
                                                </suffix>
                                            </perfix>
                                            <perfix>
                                                <perfix>
                                                    ' . esc_html__('حداکثر وزن فروش طلا:', 'almas-gold') . '
                                                </perfix>
                                                <span>
                                                    ' . rtrim(rtrim(number_format($sale_highest_limit, 4, '.', ''), '0'), '.') . '
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
    