<?php

use YooKassa\Model\PaymentData\PaymentDataSberBnpl;
use YooKassa\Model\PaymentMethodType;
use YooKassa\Request\Payments\CreatePaymentRequest;

if ( ! class_exists('YooKassaGateway')) {
    return;
}

class YooKassaGatewaySberBnpl extends YooKassaGateway
{
    public $paymentMethod = PaymentMethodType::SBER_BNPL;

    public $id = 'yookassa_sber_bnpl';

    public function __construct()
    {
        parent::__construct();

        $this->icon = YooKassa::$pluginUrl.'assets/images/sber_bnpl.svg';

        $this->method_title           = __('Плати частями', 'yookassa');
        $this->method_description     = __('Сервис Сбера, который позволяет разбить платёж за покупку на 4 части.', 'yookassa');

        $this->defaultTitle           = __('Плати частями', 'yookassa');
        $this->defaultDescription     = __('1 из 4 платежей сейчас, остальные потом', 'yookassa');

        $this->title                  = $this->getTitle();
        $this->description            = $this->getDescription();

        $this->enableRecurrentPayment = false;

        $this->has_fields             = true;
    }

    public function init_form_fields()
    {
        parent::init_form_fields();
    }

    public function is_available()
    {
        if (get_option('yookassa_sber_bnpl_enabled') !== '1') {
            return false;
        }

        if (is_add_payment_method_page() && !$this->enableRecurrentPayment) {
            return false;
        }

        $is_available = parent::is_available();

        // The SberBnpl widget only renders for sums between 1000 and 50000,
        // so hide the payment method in checkout when the cart total is outside that range.
        // Only apply when there is an actual cart total (> 0): the block checkout
        // generates payment method data with an empty cart (total 0), and hiding the
        // gateway then would prevent it from appearing in the block checkout at all.
        if ($is_available && function_exists('WC') && WC()->cart) {
            $total = (float) WC()->cart->total;
            if (0 < $total && ($total < 1000 || $total > 50000)) {
                $is_available = false;
            }
        }

        return $is_available;
    }

    public function payment_fields()
    {
        wp_enqueue_script(
            'yookassa-sber-bnpl-mask',
            YooKassa::$pluginUrl . 'assets/js/yookassa-sber-bnpl-mask.js',
            array(),
            YOOKASSA_VERSION,
            true
        );

        if ($this->description) {
            echo wpautop(wptexturize($this->description));
        }

        $phone = '';
        if (!empty($_POST['wc-yookassa_sber_bnpl-phone'])) {
            $phone = sanitize_text_field(wp_unslash($_POST['wc-yookassa_sber_bnpl-phone']));
        } elseif (!empty($_POST['billing_phone'])) {
            $phone = sanitize_text_field(wp_unslash($_POST['billing_phone']));
        } elseif (function_exists('WC') && WC()->customer) {
            $phone = WC()->customer->get_billing_phone();
        }
        ?>
        <p class="form-row form-row-wide">
            <label for="wc-<?php echo esc_attr($this->id); ?>-phone"><?php echo esc_html__('Номер телефона', 'yookassa'); ?> <span class="required">*</span></label>
            <input type="tel" class="input-text" name="wc-<?php echo esc_attr($this->id); ?>-phone" id="wc-<?php echo esc_attr($this->id); ?>-phone" value="<?php echo esc_attr($phone); ?>" placeholder="+7 (___) ___-__-__" autocomplete="tel" required />
        </p>
        <?php
    }

    public function validate_fields()
    {
        $phone = '';
        if (!empty($_POST['wc-yookassa_sber_bnpl-phone'])) {
            $phone = self::normalizePhone(sanitize_text_field(wp_unslash($_POST['wc-yookassa_sber_bnpl-phone'])));
        }

        if ($phone === '') {
            wc_add_notice(__('Введите корректный номер телефона', 'yookassa'), 'error');
            return false;
        }

        return true;
    }

    /**
     * @param WC_Order $order
     *
     * @return CreatePaymentRequestBuilder
     * @throws Exception
     */
    protected function getBuilder($order)
    {
        YooKassaLogger::sendHeka(array('payment.create.init'));

        $paymentData = new PaymentDataSberBnpl();

        $phone = $this->getPhone($order);

        if (!empty($phone)) {
            $paymentData->setPhone($phone);
        }

        $amount = YooKassaOrderHelper::getTotal($order);
        $metadata = $this->createMetadata();

        $builder = CreatePaymentRequest::builder()
            ->setAmount(YooKassaOrderHelper::getAmountByCurrency($amount))
            ->setPaymentMethodData($paymentData)
            ->setCapture(true)
            ->setDescription($this->createDescription($order))
            ->setConfirmation(array(
                'type'      => $this->confirmationType,
                'returnUrl' => get_site_url(null, sprintf(self::getReturnUrlPattern(), $order->get_order_key())),
            ))
            ->setMetadata($metadata);

        YooKassaLogger::info('Return url: '.$order->get_checkout_payment_url(true));

        YooKassaHandler::setReceiptIfNeeded($builder, $order, $this->subscribe);

        YooKassaLogger::sendHeka(array('payment.create.success'));

        return $builder;
    }

    /**
     * Возвращает нормализованный номер телефона покупателя в формате E.164
     *
     * @param WC_Order $order
     * @return string
     */
    private function getPhone($order)
    {
        $phone = '';

        if (!empty($_POST['wc-yookassa_sber_bnpl-phone'])) {
            $phone = self::normalizePhone(sanitize_text_field(wp_unslash($_POST['wc-yookassa_sber_bnpl-phone'])));
        }

        if ($phone === '' && $order->get_billing_phone()) {
            $phone = self::normalizePhone($order->get_billing_phone());
        }

        return $phone;
    }

    /**
     * Нормализует номер телефона до цифр в формате E.164 (4-15 символов)
     *
     * @param mixed $raw
     * @return string
     */
    private static function normalizePhone($raw)
    {
        $phone = preg_replace('/\D+/', '', (string) $raw);

        if (strlen($phone) > 15) {
            $phone = substr($phone, 0, 15);
        }

        if (strlen($phone) === 11 && $phone[0] === '8') {
            $phone = '7' . substr($phone, 1);
        } elseif (strlen($phone) === 10 && $phone[0] === '9') {
            $phone = '7' . $phone;
        }

        return strlen($phone) >= 4 ? $phone : '';
    }
}
