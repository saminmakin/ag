<?php
    defined('ABSPATH') || exit;
    /**
     * @link              https://almas.gold
     * @since             1.0.0
     * @plugin name       almas gold 
     * @package           almas-gold deposit html
     */
    
    ?>
        <form method="post">
            <div class="action_controls walle-recharge-deposit">
                <div class="flex-space-between-align-center">
                    <div class="flex-space-between-align-center">
                    <div class="flex-deposit-form-des-price">
                            <i class=" prk-moneys"></i>
                            <input 
                                type="text" 
                                id="deposit_amount" 
                                name="deposit_amount" 
                                min="<?= is_user_logged_in() ? esc_attr($deposit_lowest_limit) : ''; ?>"
                                max="<?= is_user_logged_in() ? esc_attr($customer_wallet_balance) : ''; ?>"
                                inputmode="decimal"
                                required
                                autocomplete="off"
                                <?php if (is_user_logged_in() && $customer_wallet_balance < $deposit_lowest_limit) echo 'disabled'; ?>
                            >
                            <div class="flex-deposit-form-des-price-parts">
                                <div class="flex-deposit-form-des-price-parts-first"></div>
                                    <div class="flex-deposit-form-des-price-parts-sec" style="">
                                        <label class="flex-deposit-form-des-price-label" style="">مبلغ</label>
                                    </div>
                                <div class="flex-deposit-form-des-price-parts-third"></div>
                            </div>
                            <suffix>
                                <?= esc_html__('تومان', 'almas-gold'); ?>
                            </suffix>
                        </div>
                    </div>
                    <?php wp_nonce_field('almas_gold_deposit_submit_nonce', 'nonce_field_almas_gold_deposit'); ?>
                    <button 
                        type="submit" 
                        name="almas_gold_deposit_submit" 
                        class="almas-gold-sale-button"
                        <?php if (is_user_logged_in() && $customer_wallet_balance < $deposit_lowest_limit) echo 'disabled'; ?>
                    >
                        <i class=" prk-wallet-minus"></i><?= esc_html__('برداشت ', 'almas-gold'); ?>
                    </button>
                </div>
                <div>
                    <?php
                        if (is_user_logged_in()) {
                            if ($customer_wallet_balance == 0) {
                                echo '
                                    <div class="almas-gold-shop-info">
                                        <i class=" prk-forbidden-2"></i> کیف پول شما خالی است.
                                    </div>
                                ';
                            } else {
                                echo '
                                    <div class="almas-gold-shop-info">
                                    <i class=" prk-information"></i>
                                    <perfix style="display: grid;">    
                                        <perfix style="margin-bottom:15px;">
                                            <perfix>
                                                ' . esc_html__('حداقل مبلغ قابل برداشت:', 'almas-gold') . '
                                            </perfix>
                                            <span>
                                                ' . number_format($deposit_lowest_limit, 0, '.', ',') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__('تومان', 'almas-gold') . '
                                            </suffix>
                                        </perfix>
                                        <perfix>
                                            <perfix>
                                                ' . esc_html__('حداکثر مبلغ قابل برداشت:', 'almas-gold') . '
                                            </perfix>
                                            <span>
                                                ' . number_format($deposit_highest_limit, 0, '.', ',') . '
                                            </span>
                                            <suffix>
                                                ' . esc_html__('تومان', 'almas-gold') . '
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
    