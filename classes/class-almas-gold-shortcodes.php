<?php

defined("ABSPATH") or exit();
$almasGoldtMainSortcodes = new almasGoldtMainSortcodes();
new AlmasGoldAdditionalShortcodes();
class almasGoldtMainSortcodes
{
    public function __construct()
    {
        $shortcodes = ["almas_gold_shop_form_sc" => "shop/almas-gold-shop-form.php", "almas_gold_shop_continue_sc" => "shop/almas-gold-shop-continue.php", "almas_gold_shop_gateway_sc" => "shop/almas-gold-shop-gateway.php", "almas_gold_shop_paywall_sc" => "shop/almas-gold-shop-paywall.php", "almas_gold_shop_bill_sc" => "shop/almas-gold-shop-bill.php", "almas_gold_sale_form_sc" => "sale/almas-gold-sale-form.php", "almas_gold_sale_continue_sc" => "sale/almas-gold-sale-continue.php", "almas_gold_sale_paywall_sc" => "sale/almas-gold-sale-paywall.php", "almas_gold_sale_bill_sc" => "sale/almas-gold-sale-bill.php", "almas_gold_recharge_form_sc" => "recharge/almas-gold-recharge-form.php", "almas_gold_recharge_continue_sc" => "recharge/almas-gold-recharge-continue.php", "almas_gold_recharge_gateway_sc" => "recharge/almas-gold-recharge-gateway.php", "almas_gold_recharge_bill_sc" => "recharge/almas-gold-recharge-bill.php", "almas_gold_deposit_form_sc" => "deposit/almas-gold-deposit-form.php", "almas_gold_deposit_continue_sc" => "deposit/almas-gold-deposit-continue.php", "almas_gold_deposit_paywall_sc" => "deposit/almas-gold-deposit-paywall.php", "almas_gold_deposit_bill_sc" => "deposit/almas-gold-deposit-bill.php", "almas_gold_delivery_form_sc" => "delivery/almas-gold-delivery-form.php", "almas_gold_delivery_continue_sc" => "delivery/almas-gold-delivery-continue.php", "almas_gold_delivery_gateway_sc" => "delivery/almas-gold-delivery-gateway.php", "almas_gold_delivery_paywall_sc" => "delivery/almas-gold-delivery-paywall.php", "almas_gold_delivery_bill_sc" => "delivery/almas-gold-delivery-bill.php", "almas_gold_customer_profile_sc" => "customer/customer-profile.php", "almas_gold_customer_shops_sc" => "customer/customer-shop-list.php", "almas_gold_customer_sales_sc" => "customer/customer-sale-list.php", "almas_gold_customer_receive_sc" => "customer/customer-receive-list.php", "almas_gold_customer_recharge_sc" => "customer/customer-recharge-list.php", "almas_gold_customer_deposit_sc" => "customer/customer-deposit-list.php"];
        foreach ($shortcodes as $shortcode => $path) {
            add_shortcode($shortcode, function () use($path) {
                return $this->load_template($path);
            });
        }
    }
    private function load_template($template_path)
    {
        ob_start();
        include plugin_dir_path(__FILE__) . "../public/view/" . $template_path;
        return ob_get_clean();
    }
}
class AlmasGoldAdditionalShortcodes
{
    public function __construct()
    {
        add_shortcode("customer_safe_balance_sc", [$this, "customer_safe_balance"]);
        add_shortcode("customer_wallet_balance_sc", [$this, "customer_wallet_balance"]);
        add_shortcode("customer_wallet_safebox_chart_sc", [$this, "customer_wallet_safebox_chart"]);
        add_shortcode("customer_safebox_balance_chart_sc", [$this, "customer_safebox_balance_chart"]);
        add_shortcode("customer_safebox_weight_transactions_chart_sc", [$this, "customer_safebox_weight_transactions_chart"]);
        add_shortcode("customer_delivery_balance_chart_sc", [$this, "customer_delivery_balance_chart"]);
        add_shortcode("almas_gold_user_transactions_chart_sc", [$this, "almas_gold_user_transactions_chart"]);
        add_shortcode("almas_gold_user_delivery_chart_sc", [$this, "almas_gold_user_delivery_chart"]);
        add_shortcode("almas_gold_user_wallet_transactions_chart_sc", [$this, "almas_gold_user_wallet_transactions_chart"]);
        add_shortcode("customer_wallet_amount_transactions_chart_sc", [$this, "customer_wallet_amount_transactions_chart"]);
        add_shortcode("almas_gold_user_transactions_num_chart_sc", [$this, "almas_gold_user_transactions_num_chart"]);
        add_shortcode("profile_completion_chart", [$this, "display_profile_completion_chart"]);
        add_shortcode("login_logout_links", [$this, "login_logout_links"]);
    }
    public function customer_safe_balance()
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        $table_core = $wpdb->prefix . "almas_gold_core";
        $core_data = $wpdb->get_row("SELECT gold_unit_to_customer, gold_price FROM " . $table_core . " ORDER BY id DESC LIMIT 1");
        $gold_unit_to_customer = $core_data->gold_unit_to_customer;
        $gold_price = $core_data->gold_price;
        $table_name = $wpdb->prefix . "almas_gold_customers";
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT safe_balance FROM " . $table_name . " WHERE user_id = %d", $user_id));
        if($customer_data) {
            $safe_balance = $customer_data->safe_balance;
            $customer_safe_value_amount = $safe_balance * $gold_price;
            $unit_display = $gold_unit_to_customer;
            if($safe_balance == 0) {
                require_once "views/shortcode-customer-safe-ballance-html.php";
            } else {
                if($unit_display == 1) {
                    if($safe_balance < 1) {
                        $safe_balance = $safe_balance * 1000;
                        $unit = "سوت";
                    } else {
                        $unit = "گرم";
                    }
                } else {
                    $unit = "گرم";
                }
                require_once "views/shortcode-customer-safe-ballance-html.php";
            }
        }
        return ob_get_clean();
    }
    public function customer_wallet_balance()
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        $table_core = $wpdb->prefix . "almas_gold_core";
        $core_data = $wpdb->get_row("SELECT gold_unit_to_customer, gold_price FROM " . $table_core . " ORDER BY id DESC LIMIT 1");
        $gold_unit_to_customer = $core_data->gold_unit_to_customer;
        $gold_price = $core_data->gold_price;
        $table_name = $wpdb->prefix . "almas_gold_customers";
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT wallet_balance, safe_balance FROM " . $table_name . " WHERE user_id = %d", $user_id));
        if($customer_data) {
            $wallet_balance = $customer_data->wallet_balance;
            $safe_balance = $customer_data->safe_balance;
            $customer_wallet_value_amount = $wallet_balance / $gold_price;
            $unit_display = $gold_unit_to_customer;
            if($wallet_balance == 0) {
                require_once "views/shortcode-customer-wallet-ballance-html.php";
            } else {
                if($unit_display == 1) {
                    if($customer_wallet_value_amount < 1) {
                        $customer_wallet_value_amount = $customer_wallet_value_amount * 1000;
                        $unit = "سوت";
                    } else {
                        $unit = "گرم";
                    }
                } else {
                    $unit = "گرم";
                }
                require_once "views/shortcode-customer-wallet-ballance-html.php";
            }
        }
        return ob_get_clean();
    }
    public function customer_wallet_safebox_chart($atts)
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        global $wpdb;
        $table_core = $wpdb->prefix . "almas_gold_core";
        $core_data = $wpdb->get_row("SELECT gold_unit_to_customer, gold_price FROM " . $table_core . " ORDER BY id DESC LIMIT 1");
        $gold_unit_to_customer = $core_data->gold_unit_to_customer;
        $gold_price = $core_data->gold_price;
        $table_name = $wpdb->prefix . "almas_gold_customers";
        $customer_data = $wpdb->get_row($wpdb->prepare("SELECT wallet_balance, safe_balance FROM " . $table_name . " WHERE user_id = %d", $user_id));
        if($customer_data) {
            $wallet_balance = $customer_data->wallet_balance;
            $safe_balance = $customer_data->safe_balance;
            $customer_wallet_value_amount = $wallet_balance / $gold_price;
            $unit_display = $gold_unit_to_customer;
            $total_gold = $safe_balance + $customer_wallet_value_amount;
            $safe_percentage = ($safe_balance / $total_gold) * 100;
            $wallet_percentage = ($customer_wallet_value_amount / $total_gold) * 100;
            if($unit_display == 1) {
                if($customer_wallet_value_amount < 1) {
                    $customer_wallet_value_amount = $customer_wallet_value_amount * 1000;
                    $unit = "سوت";
                } else {
                    $unit = "گرم";
                }
            } else {
                $unit = "گرم";
            }
            $chart_id = 'walletSafeboxChart_' . uniqid();    
            ?>
            <canvas id="<?php echo esc_attr($chart_id); ?>" width="200" height="200"></canvas>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var ctx = document.getElementById('<?php echo esc_attr($chart_id); ?>').getContext('2d');
                    var safePercentage = <?php echo $safe_percentage; ?>;
                    var walletSafeboxChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['گاو صندوق', 'کیف پول'],
                            datasets: [{
                                data: [<?php echo $safe_percentage; ?>, <?php echo $wallet_percentage; ?>],
                                backgroundColor: ['#12373F', '#D1B38C'],
                                borderWidth: 0,
                                borderRadius: 15,
                            }]
                        },
                        options: {
                            cutout: '70%',
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    enabled: false,
                                    external: function(context) {
                                        var tooltipModel = context.tooltip;
                                        var tooltipEl = document.getElementById('chartjs-tooltip');
                                        if (!tooltipEl) {
                                            tooltipEl = document.createElement('div');
                                            tooltipEl.id = 'chartjs-tooltip';
                                            tooltipEl.style.position = 'absolute';
                                            tooltipEl.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
                                            tooltipEl.style.borderRadius = '10px';
                                            tooltipEl.style.padding = '10px';
                                            tooltipEl.style.color = 'white';
                                            tooltipEl.style.opacity = 0;
                                            tooltipEl.style.transition = 'opacity 0.2s ease';
                                            tooltipEl.style.fontSize = '12px';
                                            tooltipEl.style.lineHeight = '1.6';
                                            tooltipEl.style.whiteSpace = 'nowrap';
                                            tooltipEl.style.maxWidth = '200px';
                                            document.body.appendChild(tooltipEl);
                                        }
                                        if (tooltipModel.opacity === 0) {
                                            tooltipEl.style.opacity = 0;
                                            return;
                                        }
                                        var label = tooltipModel.dataPoints[0].label;
                                        var value = tooltipModel.dataPoints[0].raw;
                                        var innerHtml = '';
                
                                        if (label === 'گاو صندوق') {
                                            innerHtml += '<div>گاو صندوق: ' + value.toFixed(2) + '%</div>';
                                            innerHtml += '<div>موجودی: <?php echo $safe_balance; ?> <?=esc_html__($unit, 'almas-gold'); ?></div>';
                                        } else if (label === 'کیف پول') {
                                            innerHtml += '<div>کیف پول: ' + value.toFixed(2) + '%</div>';
                                            innerHtml += '<div>موجودی: <?php echo number_format($wallet_balance); ?> تومان</div>';
                                        }
                
                                        tooltipEl.innerHTML = innerHtml;
                                        tooltipEl.style.opacity = 1;
                
                                        var canvasPosition = context.chart.canvas.getBoundingClientRect();
                                        
                                        var tooltipX = canvasPosition.left + window.pageXOffset + tooltipModel.caretX;

                                        tooltipEl.style.left = canvasPosition.left + window.pageXOffset + tooltipModel.caretX + 'px';
                                        tooltipEl.style.top = canvasPosition.top + window.pageYOffset + tooltipModel.caretY + 'px';
                                        
                                        if (tooltipX + tooltipEl.offsetWidth > window.innerWidth) {
                                            tooltipX = window.innerWidth - tooltipEl.offsetWidth - 10; // تنظیم موقعیت به سمت چپ
                                        }
                
                
                                        tooltipEl.style.left = tooltipX + 'px';

                                        tooltipEl.style.direction = 'rtl';
                                    }
                                },
                                legend: {
                                    display: false,
                                    position: 'right',
                                    align: 'center',
                                    labels: {
                                        usePointStyle: true,
                                        rtl: true,
                                        boxWidth: 20,
                                        padding: 20,
                                        generateLabels: function(chart) {
                                            var original = Chart.overrides.doughnut.plugins.legend.labels.generateLabels;
                                            var labelsOriginal = original.call(this, chart);
                                
                                            labelsOriginal.forEach(function(label) {
                                                label.pointStyle = 'circle';
                                            });
                                            return labelsOriginal;
                                        },
                                        font: {
                                            family: 'prk-font',
                                            size: 12
                                        },
                                        filter: function(item, chart) {
                                            return item.text === 'گاو صندوق' || item.text === 'کیف پول';
                                        },
                                        sort: function(a, b) {
                                            // ترتیب‌دهی به‌طوری که گاو صندوق اول نمایش داده شود
                                            return a.text === 'گاو صندوق' ? -1 : 1;
                                        }
                                    }
                                },
                                customCenterText: {
                                    id: 'customCenterText',
                                    beforeDraw(chart) {
                                        const { ctx, chartArea: { width, height } } = chart;
                                        ctx.save();
                                        
                                        // متن درصد گاوصندوق در مرکز دایره
                                        ctx.font = 'bold 18px prk-font';
                                        ctx.fillStyle = '#333';
                                        ctx.textAlign = 'center';
                                        ctx.textBaseline = 'middle';
                                        ctx.fillText(safePercentage.toFixed(2) + '%', width / 2, height / 2 - 10);
                                        
                                        // متن توضیحات در زیر درصد
                                        ctx.font = '10px prk-font';
                                        ctx.fillStyle = '#555';
                                        ctx.fillText('دارایی شما در گاو صندوق', width / 2, height / 2 + 20);
                                        
                                        ctx.restore();
                                    }
                                }
                            }
                        },
                        plugins: [{
                            id: 'customCenterText',
                            beforeDraw(chart) {
                                const { ctx, chartArea: { width, height } } = chart;
                                ctx.save();
                                
                                // متن درصد گاوصندوق در مرکز دایره
                                ctx.font = 'bold 18px prk-font';
                                ctx.fillStyle = '#333';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(safePercentage.toFixed(2) + '%', width / 2, height / 2 - 10);
                                
                                // متن توضیحات در زیر درصد
                                ctx.font = '10px prk-font';
                                ctx.fillStyle = '#555';
                                ctx.fillText('دارایی شما در گاو صندوق', width / 2, height / 2 + 20);
                                
                                ctx.restore();
                            }
                        }]
                    });
                });
            </script>
            <?php
        }
        return ob_get_clean();
    }
    public function customer_safebox_balance_chart($atts)
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if (!$user_id) {
            return '<p>لطفاً ابتدا وارد حساب کاربری خود شوید.</p>';
        }
    
        $data = get_customer_gold_balance_chart_data($user_id);
        $chart_data = $data['chart_data'];
        $formatted_data = [];
        foreach ($chart_data as $date => $value) {
            $formatted_data[] = [
                'full_date' => jdate('d F Y', strtotime($date)),
                'short_date' => jdate('d F', strtotime($date)),
                'mobile_date' => jdate('m/d', strtotime($date)),
                'value' => $value
            ];
        }
    
        $chart_data_json = json_encode($formatted_data);
        $chart_id = 'customerGoldBalanceChart_' . uniqid();
        ?>
        <canvas id="<?php echo esc_attr($chart_id); ?>" width="400" height="400"></canvas>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chartData = <?php echo $chart_data_json; ?>;
            const isMobile = window.innerWidth <= 768;
            const labels = chartData.length ? chartData.map(point => isMobile ? point.mobile_date : point.short_date) : [''];
            const data = chartData.length ? chartData.map(point => point.value) : [0];
            const ctx = document.getElementById('<?php echo esc_attr($chart_id); ?>').getContext('2d');
    
            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(18, 55, 63, 0.8)');
            gradient.addColorStop(1, 'rgba(18, 55, 63, 0.2)');
            const noDataPlugin = {
                id: 'noDataPlugin',
                afterDraw: (chart) => {
                    if (data.length === 1 && data[0] === 0) { // Check if data array contains only the default [0]
                        const ctx = chart.ctx;
                        const { width, height } = chart;
                        ctx.save();
                        ctx.font = "16px prk-font";
                        ctx.fillStyle = "#12373F";
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.fillText("اطلاعاتی جهت نمایش وجود ندارد", width / 2, height / 2);
                        ctx.restore();
                    }
                }
            };
            const options = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        enabled: data.length > 1,
                        mode: 'nearest',
                        intersect: false,
                        callbacks: {
                            title: (tooltipItems) => {
                                const index = tooltipItems[0].dataIndex;
                                const date = chartData[index]?.full_date || '';
                                return `تاریخ: ${date}`;
                            },
                            label: (tooltipItem) => {
                                const weight = tooltipItem.raw.toFixed(3);
                                return `دارایی گاوصندوق: ${weight} گرم`;
                            }
                        },
                        displayColors: false,
                        bodyFont: {
                            family: 'prk-font',
                            rtl: true
                        },
                        titleFont: {
                            family: 'prk-font',
                            rtl: true
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'prk-font'
                            },
                            autoSkip: true,
                            maxTicksLimit: isMobile ? 5 : 9
                        }
                    },
                    y: {
                        beginAtZero: true,
                        min: 0,
                        suggestedMin: data.length > 1 ? Math.min(...data) - 20 : 0,
                        suggestedMax: data.length > 1 ? Math.max(...data) : 10,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            drawBorder: true
                        },
                        ticks: {
                            font: {
                                family: 'prk-font'
                            },
                            callback: function(value) {
                                return value + ' گرم';
                            }
                        }
                    }
                }
            };
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        borderColor: '#12373F',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: options,
                plugins: [noDataPlugin]
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
    public function customer_safebox_weight_transactions_chart()
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if (!$user_id) {
            return '<p>لطفاً ابتدا وارد حساب کاربری خود شوید.</p>';
        }
        global $wpdb;
        $table_shop = $wpdb->prefix . "almas_gold_shop";
        $table_sale = $wpdb->prefix . "almas_gold_sale";
        $table_delivery = $wpdb->prefix . "almas_gold_delivery";
        $total_shop_weight = $wpdb->get_var($wpdb->prepare("SELECT SUM(gold_weight) FROM " . $table_shop . " WHERE transaction_processed = 1 AND user_id = %d", $user_id));
        $total_sale_weight = $wpdb->get_var($wpdb->prepare("SELECT SUM(gold_weight) FROM " . $table_sale . " WHERE transaction_processed = 1 AND user_id = %d", $user_id));
        $total_delivery_weight = $wpdb->get_var($wpdb->prepare("SELECT SUM(delivery_gold_weight) FROM " . $table_delivery . " WHERE transaction_processed = 1 AND user_id = %d", $user_id));
        $total_shop_weight = $total_shop_weight ?: 0;
        $total_sale_weight = $total_sale_weight ?: 0;
        $total_delivery_weight = $total_delivery_weight ?: 0;
        ?>
        <canvas id="userSafeboxWeightTransactionsChart" width="400" height="250"></canvas>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('userSafeboxWeightTransactionsChart').getContext('2d');
                var totalShopWeight = <?php echo esc_js($total_shop_weight); ?>;
                var totalSaleWeight = <?php echo esc_js($total_sale_weight); ?>;
                var totalDeliveryWeight = <?php echo esc_js($total_delivery_weight); ?>;
                const noDataPlugin = {
                    id: 'noDataPlugin',
                    afterDraw: function(chart) {
                        if (chart.data.datasets[0].data.every((value) => value === 0)) {
                            const ctx = chart.ctx;
                            const { width, height } = chart;
                            ctx.save();
                            ctx.font = "14px prk-font";
                            ctx.fillStyle = "#12373F";
                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";
                            ctx.fillText("اطلاعاتی جهت نمایش وجود ندارد", width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                };
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['خریداری شده', 'فروخته شده', 'دریافت شده'],
                        datasets: [{
                            data: [totalShopWeight, totalSaleWeight, totalDeliveryWeight],
                            backgroundColor: ['#e6f4f1', '#fbe7eb', '#D1B38C'],
                            borderColor: ['#0D9277', '#DC143C', '#7E623F'],
                            borderWidth: 2,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        family: 'prk-font'
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        family: 'prk-font'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'مجموع وزن: ' + context.raw;
                                    }
                                },
                                bodyFont: {
                                    family: 'prk-font'
                                },
                                titleFont: {
                                    family: 'prk-font'
                                },
                                rtl: true,
                                textDirection: 'rtl',
                                boxPadding: 4,
                                boxWidth: 15,
                                boxHeight: 15,
                                boxBorderRadius: 5
                            }
                        },
                        layout: {
                            padding: {
                                top: 10
                            }
                        }
                    },
                    plugins: [noDataPlugin]
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
    public function almas_gold_user_transactions_chart($atts)
    {
        $atts = shortcode_atts(['type' => 'chart'], $atts);
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if (!$user_id) {
            return '<p>لطفاً ابتدا وارد حساب کاربری خود شوید.</p>';
        }
        global $wpdb;
        $table_shop = $wpdb->prefix . "almas_gold_shop";
        $table_sale = $wpdb->prefix . "almas_gold_sale";
        $table_delivery = $wpdb->prefix . "almas_gold_delivery";
        $shop_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_shop . " WHERE transaction_processed = 1 AND user_id = %d",$user_id));
        $sale_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_sale . " WHERE transaction_processed = 1 AND user_id = %d",$user_id));
        $delivery_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_delivery . " WHERE transaction_processed = 1 AND user_id = %d",$user_id));
        $chart_id = 'userTransactionsChart_' . uniqid();
        if ($atts['type'] === 'chart') {
            ?>
            <canvas id="<?php echo esc_attr($chart_id); ?>" width="400" height="250"></canvas>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var ctx = document.getElementById('<?php echo esc_attr($chart_id); ?>').getContext('2d');
                    var data = [<?php echo esc_js($shop_count); ?>, <?php echo esc_js($sale_count); ?>, <?php echo esc_js($delivery_count); ?>];
                    var noDataPlugin = {
                        id: 'noData',
                        afterDraw: function(chart) {
                            if (chart.data.datasets[0].data.every(value => value === 0)) {
                                const ctx = chart.ctx;
                                const { width, height } = chart;
                                ctx.save();
                                ctx.font = "14px prk-font";
                                ctx.fillStyle = "#12373F";
                                ctx.textAlign = "center";
                                ctx.textBaseline = "middle";
                                ctx.fillText("اطلاعاتی جهت نمایش وجود ندارد", width / 2, height / 2);
                                ctx.restore();
                            }
                        }
                    };
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['خریدها', 'فروش‌ها', 'دریافت‌ها'],
                            datasets: [{
                                data: data,
                                backgroundColor: ['#e6f4f1', '#fbe7eb', '#D1B38C'],
                                borderColor: ['#0D9277', '#DC143C', '#7E623F'],
                                borderWidth: 2,
                                borderRadius: 10
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            family: 'prk-font'
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            family: 'prk-font'
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return ' تعداد تراکنش‌ها: ' + context.raw;
                                        }
                                    },
                                    bodyFont: {
                                        family: 'prk-font'
                                    },
                                    titleFont: {
                                        family: 'prk-font'
                                    },
                                    rtl: true,
                                    textDirection: 'rtl',
                                    boxPadding: 4,
                                    boxWidth: 15,
                                    boxHeight: 15,
                                    boxBorderRadius: 5
                                }
                            },
                            layout: {
                                padding: {
                                    top: 10
                                }
                            }
                        },
                        plugins: [noDataPlugin]
                    });
                });
            </script>
            <?php
        } elseif ($atts['type'] === 'shop_number') {
            echo $shop_count;
        } elseif ($atts['type'] === 'sell_number') {
            echo $sale_count;
        } elseif ($atts['type'] === 'delivery_number') {
            echo $delivery_count;
        }
        return ob_get_clean();
    }
    public function customer_delivery_balance_chart($atts)
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if (!$user_id) {
            return '<p>لطفاً ابتدا وارد حساب کاربری خود شوید.</p>';
        }
        $delivery_data = get_customer_delivery_balance_chart_data($user_id);
        $delivery_formatted_data = [];
        foreach ($delivery_data as $delivery_date => $delivery_value) {
            $delivery_formatted_data[] = [
                'full_date' => jdate('d F Y', strtotime($delivery_date)),
                'short_date' => jdate('d F', strtotime($delivery_date)),
                'mobile_date' => jdate('m/d', strtotime($delivery_date)),
                'value' => $delivery_value
            ];
        }
        $delivery_chart_data_json = json_encode($delivery_formatted_data);
        ?>
        <canvas id="customerDeliveryBalanceChart" width="400" height="400"></canvas>
        <script>
        document.addEventListener("DOMContentLoaded", function() {

            const deliveryChartData = <?php echo $delivery_chart_data_json; ?>;
            const isMobileDeliveryChart = window.innerWidth <= 768;
            const deliveryLabels = deliveryChartData.length ? deliveryChartData.map(point => isMobileDeliveryChart ? point.mobile_date : point.short_date) : [''];
            const deliveryData = deliveryChartData.length ? deliveryChartData.map(point => point.value) : [0];
            const ctxDeliveryChart = document.getElementById('customerDeliveryBalanceChart').getContext('2d');
            const deliveryGradient = ctxDeliveryChart.createLinearGradient(0, 0, 0, 200);
            const data = deliveryChartData.length ? deliveryChartData.map(point => point.value) : [0];
            deliveryGradient.addColorStop(0, 'rgba(18, 55, 63, 0.8)');
            deliveryGradient.addColorStop(1, 'rgba(18, 55, 63, 0.2)');

            const noDeliveryDataPlugin = {
                id: 'noDeliveryDataPlugin',
                afterDraw: (chart) => {
                    if (deliveryData.length === 1 && deliveryData[0] === 0) { // Check if data array contains only the default [0]
                        const ctx = chart.ctx;
                        const { width, height } = chart;
                        ctx.save();
                        ctx.font = "16px prk-font";
                        ctx.fillStyle = "#12373F";
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.fillText("اطلاعاتی جهت نمایش وجود ندارد", width / 2, height / 2);
                        ctx.restore();
                    }
                }
            };
            const options = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        enabled: deliveryData.length > 1,
                        mode: 'nearest',
                        intersect: false,
                        callbacks: {
                            title: (tooltipItems) => {
                                const index = tooltipItems[0].dataIndex;
                                const date = deliveryChartData[index]?.full_date || '';
                                return `تاریخ: ${date}`;
                            },
                            label: (tooltipItem) => {
                                const weight = tooltipItem.raw.toFixed(3);
                                return `مجموع طلای دریافت شده: ${weight} گرم`;
                            }
                        },
                        displayColors: false,
                        bodyFont: {
                            family: 'prk-font',
                            rtl: true
                        },
                        titleFont: {
                            family: 'prk-font',
                            rtl: true
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'prk-font'
                            },
                            autoSkip: true,
                            maxTicksLimit: isMobileDeliveryChart ? 5 : 9
                        }
                    },
                    y: {
                        beginAtZero: true,
                        min: 0,
                        suggestedMin: deliveryData.length > 1 ? Math.min(...deliveryData) - 20 : 0,
                        suggestedMax: deliveryData.length > 1 ? Math.max(...deliveryData) : 10,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            drawBorder: true
                        },
                        ticks: {
                            font: {
                                family: 'prk-font'
                            },
                            callback: function(delivery_value) {
                                return delivery_value + ' گرم';
                            }
                        }
                    }
                }
            };
            new Chart(ctxDeliveryChart, {
                type: 'line',
                data: {
                    labels: deliveryLabels,
                    datasets: [{
                        data: deliveryData,
                        borderColor: '#12373F',
                        backgroundColor: deliveryGradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: options,
                plugins: [noDeliveryDataPlugin]
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
    public function almas_gold_user_delivery_chart()
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if (!$user_id) {
            return '<p>لطفاً ابتدا وارد حساب کاربری خود شوید.</p>';
        }
        global $wpdb;
        $table_shop = $wpdb->prefix . "almas_gold_shop";
        $table_sale = $wpdb->prefix . "almas_gold_sale";
        $table_delivery = $wpdb->prefix . "almas_gold_delivery";
        $total_shop_weight = $wpdb->get_var($wpdb->prepare("SELECT SUM(gold_weight) FROM " . $table_shop . " WHERE transaction_processed = 1 AND user_id = %d", $user_id));
        $total_sale_weight = $wpdb->get_var($wpdb->prepare("SELECT SUM(gold_weight) FROM " . $table_sale . " WHERE transaction_processed = 1 AND user_id = %d", $user_id));
        $total_delivery_weight = $wpdb->get_var($wpdb->prepare("SELECT SUM(delivery_gold_weight) FROM " . $table_delivery . " WHERE transaction_processed = 1 AND user_id = %d", $user_id));
        $total_shop_weight = $total_shop_weight ?: 0;
        $total_sale_weight = $total_sale_weight ?: 0;
        $total_delivery_weight = $total_delivery_weight ?: 0;
        ?>
        <canvas id="userDeliveryChart" width="400" height="250"></canvas>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('userDeliveryChart').getContext('2d');
                var noDataPlugin = {
                    id: 'noData',
                    afterDraw: function(chart) {
                        if (chart.data.datasets[0].data.every(value => value === 0)) {
                            const ctx = chart.ctx;
                            const { width, height } = chart;
                            ctx.save();
                            ctx.font = "14px prk-font";
                            ctx.fillStyle = "#12373F";
                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";
                            ctx.fillText("اطلاعاتی جهت نمایش وجود ندارد", width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                };
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['خریداری شده', 'فروخته شده', 'دریافت شده'],
                        datasets: [{
                            data: [<?php echo esc_js($total_shop_weight); ?>, <?php echo esc_js($total_sale_weight); ?>, <?php echo esc_js($total_delivery_weight); ?>],
                            backgroundColor: ['#e6f4f1', '#fbe7eb', '#D1B38C'],
                            borderColor: ['#0D9277', '#DC143C', '#7E623F'],
                            borderWidth: 2,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        family: 'prk-font'
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        family: 'prk-font'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'مجموع وزن: ' + context.raw;
                                    }
                                },
                                bodyFont: {
                                    family: 'prk-font'
                                },
                                titleFont: {
                                    family: 'prk-font'
                                },
                                rtl: true,
                                textDirection: 'rtl',
                                boxPadding: 4,
                                boxWidth: 15,
                                boxHeight: 15,
                                boxBorderRadius: 5
                            }
                        },
                        layout: {
                            padding: {
                                top: 10
                            }
                        }
                    },
                    plugins: [noDataPlugin]
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
    public function almas_gold_user_wallet_transactions_chart()
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if (!$user_id) {
            return '<p>لطفاً ابتدا وارد حساب کاربری خود شوید.</p>';
        }
        global $wpdb;
        $table_recharge = $wpdb->prefix . "almas_gold_recharge";
        $table_deposit = $wpdb->prefix . "almas_gold_deposit";
        $recharges = $wpdb->get_results("SELECT DATE(recharge_date) AS date, final_recharge_amount AS amount FROM {$table_recharge} WHERE user_id = {$user_id} AND transaction_processed = 1 ORDER BY recharge_date ASC");
        $deposits = $wpdb->get_results("SELECT DATE(deposit_date) AS date, final_deposit_amount AS amount FROM {$table_deposit} WHERE user_id = {$user_id} AND transaction_processed = 1 ORDER BY deposit_date ASC");
    
        $formatted_recharges = [];
        foreach ($recharges as $recharge) {
            $formatted_recharges[] = [
                'date' => $recharge->date,
                'amount' => $recharge->amount
            ];
        }
        $formatted_deposits = [];
        foreach ($deposits as $deposit) {
            $formatted_deposits[] = [
                'date' => $deposit->date,
                'amount' => $deposit->amount
            ];
        }
    
        $recharge_dates = [];
        foreach ($formatted_recharges as $recharge) {
            $dateKey = $recharge['date'];
            if (!isset($recharge_dates[$dateKey])) {
                $recharge_dates[$dateKey] = 0;
            }
            $recharge_dates[$dateKey] += $recharge['amount'];
        }
        $deposit_dates = [];
        foreach ($formatted_deposits as $deposit) {
            $dateKey = $deposit['date'];
            if (!isset($deposit_dates[$dateKey])) {
                $deposit_dates[$dateKey] = 0;
            }
            $deposit_dates[$dateKey] += $deposit['amount'];
        }
    
        $uniqueRechargeDates = $recharge_dates;
        $uniqueDepositDates = $deposit_dates;
    
        $recharge_labels = array_keys($uniqueRechargeDates);
        $deposit_labels = array_keys($uniqueDepositDates);
        
        $recharge_data = array_values($uniqueRechargeDates);
        $deposit_data = array_values($uniqueDepositDates);
    
        if (count($formatted_recharges) === 1) {
            $singleRechargeDate = $formatted_recharges[0]['date'];
            $singleRechargeDateUnix = strtotime($singleRechargeDate);
            for ($i = -2; $i <= 2; $i++) {
                $date = date('Y-m-d', strtotime("$i day", $singleRechargeDateUnix));
                if (!isset($recharge_dates[$date])) {
                    $recharge_dates[$date] = 0;
                }
            }
            ksort($recharge_dates);
        }
        if (count($formatted_deposits) === 1) {
            $singleDepositDate = $formatted_deposits[0]['date'];
            $singleDepositDateUnix = strtotime($singleDepositDate);
            for ($i = -2; $i <= 2; $i++) {
                $date = date('Y-m-d', strtotime("$i day", $singleDepositDateUnix));
                if (!isset($deposit_dates[$date])) {
                    $deposit_dates[$date] = 0;
                }
            }
            ksort($deposit_dates);
        }
    
        $recharge_labels = array_map(function($date) {
            return [
                'full_date' => jdate('d F Y', strtotime($date)),
                'short_date' => jdate('d F', strtotime($date)),
                'mobile_date' => jdate('m/d', strtotime($date))
            ];
        }, array_keys($recharge_dates));
        $deposit_labels = array_map(function($date) {
            return [
                'full_date' => jdate('d F Y', strtotime($date)),
                'short_date' => jdate('d F', strtotime($date)),
                'mobile_date' => jdate('m/d', strtotime($date))
            ];
        }, array_keys($deposit_dates));
        
        $recharge_labels_formatted = [
            'desktop' => array_column($recharge_labels, 'short_date'),
            'mobile' => array_column($recharge_labels, 'mobile_date'),
            'tooltip' => array_column($recharge_labels, 'full_date')
        ];
        $deposit_labels_formatted = [
            'desktop' => array_column($deposit_labels, 'short_date'),
            'mobile' => array_column($deposit_labels, 'mobile_date'),
            'tooltip' => array_column($deposit_labels, 'full_date')
        ];
    
        $recharge_data = array_values($recharge_dates);
        $deposit_data = array_values($deposit_dates);
        ?>
        <style>
        .transaction-controller {
            display: flex;
            width: fit-content;
            margin: auto;
            padding: 6px;
            border: 1px solid #e3e3e3;
            border-radius: 12px;
            max-width: 100%;
            align-items: center;
            gap: 10px;
        }
        .transaction-controller input[type="radio"] {
            display: none;
        }
        .transaction-controller-label {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 10px;
            background-color: white;
            color: #54595f;
            border-radius: 6px;
            cursor: pointer;
            transition: color 0.3s ease, background-color 0.3s ease;
        }
        .transaction-controller-label i {
            font-size: 20px;
        }
        input[type="radio"]:checked + label[for="toggleRecharge"],
        label[for="toggleRecharge"]:hover {
            background-color: #e6f4f1;
            color: #0D9277;
        }
        input[type="radio"]:checked + label[for="toggleDeposit"],
        label[for="toggleDeposit"]:hover {
            background-color: #fbe7eb;
            color: #DC143C;
        }
        </style>
        <div class="transaction-controller">
            <input type="radio" name="toggleTransaction" id="toggleRecharge" value="recharge" checked>
            <label for="toggleRecharge" class="transaction-controller-label"><i class=" prk-wallet-add-1"></i>واریزها</label>
            <input type="radio" name="toggleTransaction" id="toggleDeposit" value="deposit">
            <label for="toggleDeposit" class="transaction-controller-label"><i class=" prk-wallet-minus"></i>برداشت‌ها</label>
        </div>
        <canvas id="transactionChart" width="400" height="250" style="height: 400px !important;"></canvas>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ctx = document.getElementById('transactionChart').getContext('2d');
                const rechargeLabelsTooltip = <?php echo json_encode($recharge_labels_formatted['tooltip']); ?>;
                const rechargeLabelsDesktop = <?php echo json_encode($recharge_labels_formatted['desktop']); ?>;
                const rechargeLabelsMobile = <?php echo json_encode($recharge_labels_formatted['mobile']); ?>;
                const depositLabelsTooltip = <?php echo json_encode($deposit_labels_formatted['tooltip']); ?>;
                const depositLabelsDesktop = <?php echo json_encode($deposit_labels_formatted['desktop']); ?>;
                const depositLabelsMobile = <?php echo json_encode($deposit_labels_formatted['mobile']); ?>;
                const rechargeAllData = <?php echo json_encode($recharge_data); ?>;
                const depositAllData = <?php echo json_encode($deposit_data); ?>;
                const rechargeGradient = ctx.createLinearGradient(0, 0, 0, 200);
                rechargeGradient.addColorStop(0, 'rgba(230, 244, 241, 0.8)');
                rechargeGradient.addColorStop(1, 'rgba(230, 244, 241, 0.2)');
                const depositGradient = ctx.createLinearGradient(0, 0, 0, 200);
                depositGradient.addColorStop(0, 'rgba(251, 231, 235, 0.8)');
                depositGradient.addColorStop(1, 'rgba(251, 231, 235, 0.2)');
        
                function getResponsiveLabels(isRecharge) {
                    const labelsDesktop = isRecharge ? rechargeLabelsDesktop : depositLabelsDesktop;
                    const labelsMobile = isRecharge ? rechargeLabelsMobile : depositLabelsMobile;
                    return window.innerWidth <= 768 ? labelsMobile : labelsDesktop;
                }
        
                const rechargeData = {
                    label: 'واریز',
                    data: rechargeAllData,
                    backgroundColor: rechargeGradient,
                    borderColor: '#0D9277',
                    borderWidth: 1,
                    fill: true
                };
        
                const depositData = {
                    label: 'برداشت',
                    data: depositAllData,
                    backgroundColor: depositGradient,
                    borderColor: '#DC143C',
                    borderWidth: 1,
                    fill: true
                };
        
                let isRecharge = true;
                let currentData = rechargeData;
        
                // پلاگین noDataPlugin برای نمایش پیام زمانی که داده‌ها خالی هستند
                const noDataPlugin = {
                    id: 'noDataPlugin',
                    afterDraw: (chart) => {
                        const data = isRecharge ? rechargeAllData : depositAllData;
                        // اگر داده‌ها خالی باشند یا همه مقادیر صفر باشند
                        const isDataEmpty = data.length === 0 || data.every(item => item === 0);
                        if (isDataEmpty) {
                            const ctx = chart.ctx;
                            const { width, height } = chart;
                            ctx.save();
                            ctx.font = "16px prk-font";
                            ctx.fillStyle = "#12373F";
                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";
                            ctx.fillText("اطلاعاتی جهت نمایش وجود ندارد", width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                };
        
                const transactionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: getResponsiveLabels(isRecharge),
                        datasets: [currentData]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function(tooltipItems) {
                                        const index = tooltipItems[0].dataIndex;
                                        return isRecharge ? rechargeLabelsTooltip[index] : depositLabelsTooltip[index];
                                    },
                                    label: function(tooltipItem) {
                                        const dataPoint = isRecharge ? rechargeAllData[tooltipItem.dataIndex] : depositAllData[tooltipItem.dataIndex];
                                        return `مبلغ: ${dataPoint.toLocaleString()} تومان`;
                                    }
                                },
                                titleFont: { family: 'prk-font', rtl: true },
                                bodyFont: { family: 'prk-font', rtl: true },
                                mode: 'nearest',
                                intersect: false,
                                rtl: true,
                                textDirection: 'rtl'
                            },
                            noDataPlugin: {} // اضافه کردن پلاگین noDataPlugin
                        },
                        scales: {
                            x: {
                                ticks: { font: { family: 'prk-font' } }
                            },
                            y: {
                                min: 0,
                                ticks: { font: { family: 'prk-font' } }
                            }
                        }
                    },
                    plugins: [noDataPlugin] // اضافه کردن پلاگین به نمودار
                });
        
                document.querySelectorAll('input[name="toggleTransaction"]').forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        isRecharge = radio.value === 'recharge';
                        currentData = isRecharge ? rechargeData : depositData;
                        transactionChart.data.labels = getResponsiveLabels(isRecharge);
                        transactionChart.data.datasets = [currentData];
                        transactionChart.update();
                    });
                });
        
                window.addEventListener('resize', function() {
                    transactionChart.data.labels = getResponsiveLabels(isRecharge);
                    transactionChart.update();
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
    public function customer_wallet_amount_transactions_chart()
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if (!$user_id) {
            return '<p>لطفاً ابتدا وارد حساب کاربری خود شوید.</p>';
        }
        global $wpdb;
        $table_recharge = $wpdb->prefix . "almas_gold_recharge";
        $table_deposit = $wpdb->prefix . "almas_gold_deposit";
        $total_recharge_amount = $wpdb->get_var($wpdb->prepare("SELECT SUM(recharge_amount) FROM " . $table_recharge . " WHERE transaction_processed = 1 AND user_id = %d", $user_id));
        $total_deposit_amount = $wpdb->get_var($wpdb->prepare("SELECT SUM(deposit_amount) FROM " . $table_deposit . " WHERE transaction_processed = 1 AND user_id = %d", $user_id));
        $total_recharge_amount = $total_recharge_amount ?: 0;
        $total_deposit_amount = $total_deposit_amount ?: 0;
        ?>
        <canvas id="userWalletAmountTransactionsChart" width="400" height="250"></canvas>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('userWalletAmountTransactionsChart').getContext('2d');
                const noDataPlugin = {
                    id: 'noDataPlugin',
                    afterDraw: function(chart) {
                        if (chart.data.datasets[0].data.every((value) => value === 0)) {
                            const ctx = chart.ctx;
                            const { width, height } = chart;
                            ctx.save();
                            ctx.font = "14px prk-font";
                            ctx.fillStyle = "#12373F";
                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";
                            ctx.fillText("اطلاعاتی جهت نمایش وجود ندارد", width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                };
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['واریز شده', 'برداشت شده'],
                        datasets: [{
                            data: [<?php echo esc_js($total_recharge_amount); ?>, <?php echo esc_js($total_deposit_amount); ?>],
                            backgroundColor: ['#e6f4f1', '#fbe7eb'],
                            borderColor: ['#0D9277', '#DC143C'],
                            borderWidth: 2,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        family: 'prk-font'
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        family: 'prk-font'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'nearest',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'مجموع مبلغ: ' + context.raw.toLocaleString() + ' تومان';
                                    }
                                },
                                bodyFont: {
                                    family: 'prk-font'
                                },
                                titleFont: {
                                    family: 'prk-font'
                                },
                                rtl: true,
                                textDirection: 'rtl',
                                boxPadding: 4,
                                boxWidth: 15,
                                boxHeight: 15,
                                boxBorderRadius: 5
                            }
                        },
                        layout: {
                            padding: {
                                top: 10
                            }
                        }
                    },
                    plugins: [noDataPlugin]
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
    public function almas_gold_user_transactions_num_chart()
    {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        if (!$user_id) {
            return '<p>لطفاً ابتدا وارد حساب کاربری خود شوید.</p>';
        }
        global $wpdb;
        $table_recharge = $wpdb->prefix . "almas_gold_recharge";
        $table_deposit = $wpdb->prefix . "almas_gold_deposit";
        $recharge_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_recharge . " WHERE transaction_processed = 1 AND user_id = %d",$user_id));
        $deposit_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_deposit . " WHERE transaction_processed = 1 AND user_id = %d",$user_id));
        ?>
        <canvas id="userWalletTransactionsChart" width="400" height="250"></canvas>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('userWalletTransactionsChart').getContext('2d');
                const noDataPlugin = {
                    id: 'noDataPlugin',
                    afterDraw: function(chart) {
                        if (chart.data.datasets[0].data.every((value) => value === 0)) {
                            const ctx = chart.ctx;
                            const { width, height } = chart;
                            ctx.save();
                            ctx.font = "14px prk-font";
                            ctx.fillStyle = "#12373F";
                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";
                            ctx.fillText("اطلاعاتی جهت نمایش وجود ندارد", width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                };
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['واریزها', 'برداشت‌ها'],
                        datasets: [{
                            data: [<?php echo esc_js($recharge_count); ?>, <?php echo esc_js($deposit_count); ?>],
                            backgroundColor: ['#e6f4f1', '#fbe7eb'],
                            borderColor: ['#0D9277', '#DC143C'],
                            borderWidth: 2,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        family: 'prk-font'
                                    },
                                    callback: function(value) {
                                        if (value < 5) {
                                            return value;
                                        } else if (value < 10) {
                                            return value % 5 === 0 ? value : '';
                                        } else if (value < 50) {
                                            return value % 10 === 0 ? value : '';
                                        } else {
                                            return value % 50 === 0 ? value : '';
                                        }
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        family: 'prk-font'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' تعداد تراکنش‌ها: ' + context.raw;
                                    }
                                },
                                bodyFont: {
                                    family: 'prk-font'
                                },
                                titleFont: {
                                    family: 'prk-font'
                                },
                                rtl: true,
                                textDirection: 'rtl',
                                boxPadding: 4,
                                boxWidth: 15,
                                boxHeight: 15,
                                boxBorderRadius: 5
                            }
                        },
                        layout: {
                            padding: {
                                top: 10
                            }
                        }
                    },
                    plugins: [noDataPlugin]
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
    public function display_profile_completion_chart() {
        $user_id = get_current_user_id();
        if (!$user_id) return '<p>برای مشاهده درصد تکمیل پروفایل، وارد حساب کاربری خود شوید.</p>';
    
        $percentage = get_user_profile_completion_percentage($user_id);
        $completion_text = $percentage === 100 ? 'احراز هویت تکمیل شده' : 'پروفایل تکمیل شده';
    
        ob_start();
        ?>
        
        <canvas id="profileCompletionChart" width="200" height="200"></canvas>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('profileCompletionChart').getContext('2d');
                var percentage = <?php echo $percentage; ?>;
                var completionText = <?php echo json_encode($completion_text); ?>;
    
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['تکمیل شده', 'باقی‌مانده'],
                        datasets: [{
                            data: [percentage, 100 - percentage],
                            backgroundColor: ['#12373F', '#B7C5BF'],
                            borderWidth: 0,
                            borderRadius: 15,
                        }]
                    },
                    options: {
                        cutout: '75%',
                        responsive: true,
                        plugins: {
                            tooltip: { enabled: false },
                            legend: { display: false }
                        }
                    },
                    plugins: [{
                        id: 'customCenterText',
                        beforeDraw(chart) {
                            const { ctx, chartArea: { width, height } } = chart;
                            ctx.save();
                            
                            // متن درصد در مرکز
                            ctx.font = 'bold 18px prk-font';
                            ctx.fillStyle = '#333';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(percentage.toFixed(2) + '%', width / 2, height / 2 - 10);
                            
                            // متن توضیحات در زیر درصد
                            ctx.font = '10px prk-font';
                            ctx.fillStyle = '#555';
                            ctx.fillText(completionText, width / 2, height / 2 + 20);
                            
                            ctx.restore();
                        }
                    }]
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
    public function login_logout_links()
    {
        global $wpdb;
        if(is_user_logged_in()) {
            $user_id = get_current_user_id();
            $current_user = wp_get_current_user();
            $avatar = get_avatar($current_user->ID, 96);
            $table_name = $wpdb->prefix . "almas_gold_customers";
            $customer_data = $wpdb->get_row($wpdb->prepare("SELECT firstname FROM " . $table_name . " WHERE user_id = %d", $user_id));
            $firstname = $customer_data->firstname;
        }
        if(is_user_logged_in()) {
            $link = "\r\n                    <div class=\"flex-space-between-align-center\">\r\n                        <a style=\"font-size: 13px; margin-left: 30px\" href=\"/almas.gold/profile\">" . $avatar . "</a>\r\n                        <span style=\"font-size: 13px; margin-left: 20px; width: 80%\">\r\n                            سلام <span style=\"font-weight: 700; padding: 0 4px\">" . $firstname . "</span> عزیز!\r\n                        </span>\r\n                        <a style=\"font-size: 16px\" title=\"خروج از حساب\" href=\"" . wp_logout_url() . "\">\r\n                            <i class=\"fa-solid fa-arrow-right-from-bracket\"></i>\r\n                        </a>\r\n                    </div>\r\n                ";
        } else {
            $link = "\r\n                    <a style=\"font-size: 13px\" href=\"" . wp_login_url() . "\">\r\n                        ورود به حساب\r\n                    </a>\r\n                ";
        }
        return $link;
    }
}

?>