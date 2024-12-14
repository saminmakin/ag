<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas-gold shop form html
     */
  
     ?>
        <form method="post">
            <div class="action_controls">
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <div class="flex-shop-form-des-weight">
                            <i class=" prk-weight"></i>
                            <input 
                                type="text" 
                                id="shop_gold_weight" 
                                name="shop_gold_weight"
                                oninput="updateInitialPrice()"
                                step="0.001"   
                                min="<?= esc_attr($shop_lowest_limit); ?>"
                                max="<?= esc_attr($shop_highest_limit); ?>"
                                required
                                autocomplete="off"
                                inputmode="decimal"
                                lang="en"
                                pattern="[0-9.]*"
                            >
                            <div class="flex-shop-form-des-weight-parts">
                            	<div class="flex-shop-form-des-weight-parts-first"></div>
                            		<div class="flex-shop-form-des-weight-parts-sec" style="">
                            			<label class="flex-shop-form-des-weight-label" style="">وزن</label>
                            		</div>
                            	<div class="flex-shop-form-des-weight-parts-third"></div>
                            </div>
                            <suffix>
                                <?= esc_html__('گرم', 'almas-gold'); ?>
                            </suffix>
                        </div>
                        <div class="flex-shop-form-des-price">
                            <i class=" prk-moneys"></i>
                            <input 
                                type="text" 
                                id="shop_initial_price_display" 
                                name="shop_initial_price_display" 
                                oninput="updateShopGoldWeight()" 
                                autocomplete="off"
                                inputmode="decimal"
                            >
                            <div class="flex-shop-form-des-price-parts">
                            	<div class="flex-shop-form-des-price-parts-first"></div>
                            		<div class="flex-shop-form-des-price-parts-sec" style="">
                            			<label class="flex-shop-form-des-price-label" style="">مبلغ</label>
                            		</div>
                            	<div class="flex-shop-form-des-price-parts-third"></div>
                            </div>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                    <?php wp_nonce_field('almas_gold_shop_submit_nonce', 'nonce_field_almas_gold_shop'); ?>
                    <button type="submit" name="almas_gold_shop_submit" class="almas-gold-shop-button">
                        <i class=" prk-direct-inbox"></i> <?= esc_html__('خرید', 'almas-gold'); ?>
                    </button>
                </div>
                <div>
                    <?php
                        if (is_user_logged_in()) {  
                            echo '
                                <div class="almas-gold-shop-info">
                                    <i class=" prk-information"></i>
                                    <perfix style="display: grid;">    
                                        <perfix style="margin-bottom:15px;">
                                            <perfix>
                                                ' . esc_html__('حداقل وزن خرید طلا:', 'almas-gold') . '
                                            </perfix>
                                            <span>
                                                ' . rtrim(rtrim(number_format($shop_lowest_limit_display, 4, '.', ''), '0'), '.') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__($unit  , 'almas-gold') . '
                                            </suffix>
                                        </perfix>
                                        <perfix>
                                            <perfix>
                                                ' . esc_html__('حداکثر وزن خرید طلا:', 'almas-gold') . '
                                            </perfix>
                                            <span>
                                                ' . rtrim(rtrim(number_format($shop_highest_limit, 4, '.', ''), '0'), '.') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__('گرم', 'almas-gold') . '
                                            </suffix>
                                        </perfix>
                                    </perfix>
                                </div>
                            ';
                        }
                    ?>
                </div>
            </div>
        </form>
    <?php