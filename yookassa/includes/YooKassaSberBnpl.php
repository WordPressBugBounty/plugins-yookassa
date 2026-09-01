<?php

/**
 * The SberBnpl functionality of the plugin.
 *
 * Renders the Sber «Плати частями» (SberBnpl) widget in the product page,
 * cart and checkout form.
 */
class YooKassaSberBnpl
{
    private $plugin_name;

    /**
     * YooKassaSberBnpl constructor.
     *
     * @param string $plugin_name The name of this plugin.
     */
    public function __construct($plugin_name)
    {
        $this->plugin_name = $plugin_name;
    }

    /**
     * Whether the SberBnpl method is enabled.
     *
     * @return bool
     */
    private function isMethodEnabled()
    {
        return get_option('yookassa_sber_bnpl_enabled') === '1';
    }

    /**
     * Build the attributes string for the <bnpl-payments> widget.
     *
     * @param int|float $sum The order/product sum.
     * @param string $place The placement key: 'product', 'list', 'cart' or 'checkout'.
     * @return string
     */
    private function getWidgetAttributes($sum, $place)
    {
        $attrs = array(
            'sum' => $sum,
            'size' => get_option('yookassa_sber_bnpl_' . $place . '_size', 'medium'),
            'theme' => get_option('yookassa_sber_bnpl_' . $place . '_theme', 'classic'),
            'align' => get_option('yookassa_sber_bnpl_' . $place . '_align', 'left'),
        );

        if ('list' !== $place) {
            $attrs['template'] = get_option('yookassa_sber_bnpl_' . $place . '_template', 'informer');
        }

        if ('list' === $place) {
            $attrs['variant'] = 'popup';
        }

        if (get_option('yookassa_sber_bnpl_' . $place . '_compact') === '1') {
            $attrs['compact'] = 'true';
        }

        $attrs['label'] = (get_option('yookassa_sber_bnpl_' . $place . '_hide_sum') === '1') ? 'false' : 'true';

        $output = '';
        foreach ($attrs as $key => $value) {
            $output .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }

        return '<bnpl-payments' . $output . '></bnpl-payments>';
    }

    /**
     * Show the widget in the product page.
     */
    public function showInfo()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();

        global $product;
        if (!$product) {
            return;
        }

        if (!$this->isMethodEnabled()) {
            return;
        }

        if (get_option('yookassa_add_sber_bnpl_product') !== '1') {
            return;
        }

        $sum = $product->get_price();

        echo '<div class="sber-bnpl-info">';
        echo $this->getWidgetAttributes($sum, 'product');
        echo '</div>';
    }

    /**
     * Show the widget in the product list (shop loop), under the price.
     */
    public function showListInfo()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();

        global $product;
        if (!$product) {
            return;
        }

        if (!$this->isMethodEnabled()) {
            return;
        }

        if (get_option('yookassa_add_sber_bnpl_list') !== '1') {
            return;
        }

        $sum = $product->get_price();

        echo '<div class="sber-bnpl-info">';
        echo $this->getWidgetAttributes($sum, 'list');
        echo '</div>';
    }

    /**
     * Show the widget in the cart totals.
     */
    public function showCartInfo()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();

        if (!$this->isMethodEnabled()) {
            return;
        }

        if (get_option('yookassa_add_sber_bnpl_cart') !== '1') {
            return;
        }

        if (!function_exists('WC') || !WC()->cart) {
            return;
        }

        $sum = (float)WC()->cart->total;

        echo '<div class="sber-bnpl-info">';
        echo $this->getWidgetAttributes($sum, 'cart');
        echo '</div>';
    }

    /**
     * Show the widget in the checkout form.
     */
    public function showExtraCheckoutInfo()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();

        if (!$this->isMethodEnabled()) {
            return;
        }

        if (get_option('yookassa_add_sber_bnpl_checkout') !== '1') {
            return;
        }

        if (!function_exists('WC') || !WC()->cart) {
            return;
        }

        $sum = (float)WC()->cart->total;

        echo '<li class="sber-bnpl-info" style="display:none;">';
        echo $this->getWidgetAttributes($sum, 'checkout');
        echo '</li>';

        $this->renderCheckoutToggleScript();
    }

    /**
     * Output the script that shows the checkout widget only when the
     * «Плати частями» (SberBnpl) payment method is selected.
     */
    private function renderCheckoutToggleScript()
    {
        ?>
        <script>
            (function ($) {
                function updateSberBnplVisibility() {
                    var $info = $('.sber-bnpl-info');
                    if (!$info.length) {
                        return false;
                    }
                    // Works for both classic checkout (name="payment_method") and
                    // block checkout (name="radio-control-wc-payment-method-options").
                    var $bnpl = $('input[type="radio"][value="yookassa_sber_bnpl"]');
                    if (!$bnpl.length) {
                        return false;
                    }
                    $info.toggle($bnpl.prop('checked'));
                    return true;
                }

                $(document).on('change', 'input[type="radio"]', updateSberBnplVisibility);
                $(document).on('updated_checkout', updateSberBnplVisibility);

                // Block checkout renders the widget and payment methods asynchronously.
                // Apply the initial state once both are present, then stop — repeated
                // toggling during async re-renders causes visible flicker.
                var attempts = 0;
                var timer = setInterval(function () {
                    attempts++;
                    if (updateSberBnplVisibility() || attempts >= 20) {
                        clearInterval(timer);
                    }
                }, 250);
            })(jQuery);
        </script>
        <?php
    }

    /**
     * Enqueue the plugin styles.
     */
    private function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, YooKassa::$pluginUrl . 'assets/css/yookassa.css');
    }

    /**
     * Enqueue the SberBnpl widget script.
     */
    private function enqueue_scripts()
    {
        wp_enqueue_script(
                'yookassa-sber-bnpl-widget',
                'https://yookassa.ru/integration/oauth-cms/widgets/sber-bnpl-widget.js',
                array(),
                null,
                true
        );
    }

    /**
     * Fallback rendering for block-based cart/checkout pages.
     * Injects the widget via JavaScript since PHP hooks don't fire with WooCommerce Blocks.
     */
    public function renderBlockFallback()
    {
        $isCart = is_cart();
        $isCheckout = is_checkout();

        if (!$isCart && !$isCheckout) {
            return;
        }

        if (!$this->isMethodEnabled()) {
            return;
        }

        if ($isCart && get_option('yookassa_add_sber_bnpl_cart') !== '1') {
            return;
        }
        if ($isCheckout && get_option('yookassa_add_sber_bnpl_checkout') !== '1') {
            return;
        }

        $this->enqueue_styles();
        $this->enqueue_scripts();

        $sum = 0;
        if (function_exists('WC') && WC()->cart) {
            $sum = (float)WC()->cart->total;
        }

        $widgetHtml = $this->getWidgetAttributes($sum, $isCart ? 'cart' : 'checkout');
        if ($isCart) {
            $selector = '.wp-block-woocommerce-cart-order-summary-block, .cart_totals';
            $position = 'afterend';
        } else {
            $selector = '.wp-block-woocommerce-checkout-terms-block, .woocommerce-checkout-review-order';
            $position = 'beforebegin';
        }
        ?>
        <script>
            jQuery(function ($) {
                function inject() {
                    if ($('.sber-bnpl-info').length) {
                        return true;
                    }
                    var $target = $('<?php echo esc_js($selector); ?>').first();
                    if (!$target.length) {
                        return false;
                    }
                    var $el = $('<div class="sber-bnpl-info has-text-align-left"></div>');
                    <?php if ($isCheckout): ?>
                    $el.hide();
                    <?php endif; ?>
                    $el.html(<?php echo wp_json_encode($widgetHtml); ?>);
                    if ('<?php echo esc_js($position); ?>' === 'beforebegin') {
                        $target.before($el);
                    } else {
                        $target.after($el);
                    }
                    return true;
                }

                if (inject()) {
                    return;
                }

                var attempts = 0;
                var timer = setInterval(function () {
                    attempts++;
                    if (inject() || attempts >= 20) {
                        clearInterval(timer);
                    }
                }, 250);
            });
        </script>
        <?php
        if ($isCheckout) {
            $this->renderCheckoutToggleScript();
        }
    }

    /**
     * Reposition the product-list widget below the price in block-based shop loops.
     *
     * In the WooCommerce Blocks product grid the price is a separate block rendered
     * by React, so the classic PHP hook cannot place the widget after it. This script
     * moves each list widget (identified by variant="popup") to just after its sibling
     * price block. In classic loops there is no such block, so it is a no-op there.
     */
    public function renderListFallback()
    {
        if (!$this->isMethodEnabled()) {
            return;
        }

        if (get_option('yookassa_add_sber_bnpl_list') !== '1') {
            return;
        }

        $this->enqueue_styles();
        $this->enqueue_scripts();
        ?>
        <script>
            jQuery(function ($) {

                document.addEventListener('click', function (e) {
                    if (e.target && e.target.closest && e.target.closest('.sber-bnpl-info')) {
                        e.preventDefault();
                    }
                }, true);

                function relocate() {
                    var moved = false;
                    $('.sber-bnpl-info bnpl-payments[variant="popup"]').each(function () {
                        var $widget = $(this).closest('.sber-bnpl-info');
                        if (!$widget.length) {
                            return;
                        }
                        var $product = $widget.closest('li');
                        if (!$product.length) {
                            return;
                        }
                        var $price = $product.find('.wp-block-woocommerce-product-price');
                        if (!$price.length) {
                            return;
                        }
                        // Skip if the widget already follows the price.
                        if ($widget[0].compareDocumentPosition($price[0]) & Node.DOCUMENT_POSITION_PRECEDING) {
                            return;
                        }
                        $price.after($widget);
                        moved = true;
                    });
                    return moved;
                }

                if (relocate()) {
                    return;
                }

                var attempts = 0;
                var timer = setInterval(function () {
                    attempts++;
                    if (relocate() || attempts >= 20) {
                        clearInterval(timer);
                    }
                }, 250);
            });
        </script>
        <?php
    }
}
