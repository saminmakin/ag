<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas-gold recharge html
     */
    
    ?>
        <form method="post">
            <div class="action_controls walle-recharge-deposit">
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                        <div class="flex-recharge-form-des-price">
                            <i class=" prk-moneys"></i>
                            <input 
                                type="text" 
                                id="recharge_amount" 
                                name="recharge_amount" 
                                min="<?= is_user_logged_in() ? esc_attr($recharge_lowest_limit) : ''; ?>"
                                max="<?= is_user_logged_in() ? esc_attr($recharge_highest_limit) : ''; ?>"
                                inputmode="decimal"
                                required
                                autocomplete="off"
                            >
                            <div class="flex-recharge-form-des-price-parts">
                                <div class="flex-recharge-form-des-price-parts-first"></div>
                                    <div class="flex-recharge-form-des-price-parts-sec" style="">
                                        <label class="flex-recharge-form-des-price-label" style="">مبلغ</label>
                                    </div>
                                <div class="flex-recharge-form-des-price-parts-third"></div>
                            </div>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                    <?php wp_nonce_field('almas_gold_recharge_submit_nonce', 'nonce_field_almas_gold_recharge'); ?>
                    <button 
                        type="submit" 
                        name="almas_gold_recharge_submit"
                        class="almas-gold-shop-button"
                        
                    >
                        <i class=" prk-wallet-add-1"></i> <?= esc_html__('واریز', 'almas-gold'); ?>
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
                                            ' . esc_html__('حداقل مبلغ قابل شارژ:', 'almas-gold') . '
                                        </perfix>
                                        <span>
                                            ' . number_format($recharge_lowest_limit, 0, '.', ',') . '
                                        </span>
                                        <suffix>
                                            ' . esc_html__('تومان', 'almas-gold') . '
                                        </suffix>
                                    </perfix>
                                    <perfix>
                                        <perfix>
                                            ' . esc_html__('حداکثر مبلغ قابل شارژ:', 'almas-gold') . '
                                        </perfix>
                                        <span>
                                            ' . number_format($recharge_highest_limit, 0, '.', ',') . '
                                        </span>
                                        <suffix>
                                            ' . esc_html__('تومان', 'almas-gold') . '
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
