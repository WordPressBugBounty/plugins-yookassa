<?php

use YooKassa\Client;
use YooKassa\Model\PaymentData\B2b\Sberbank\VatDataRate;
use YooKassa\Model\PaymentInterface;
use YooKassa\Model\PaymentMethodType;
use YooKassa\Model\PaymentStatus;
use YooKassa\Model\Receipt;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Request\Payments\CreatePaymentRequestBuilder;
use YooKassa\Request\Payments\Payment\CreateCaptureRequest;
use YooKassa\Request\Payments\Payment\CreateCaptureRequestBuilder;

/**
 * The payment-facing functionality of the plugin.
 * @Todo rename class
 */
class YooKassaHandler
{
    const VAT_CODE_1 = 1;

    /**
     * @return bool
     */
    public static function isReceiptEnabled()
    {
        return get_option('yookassa_enable_receipt');
    }

    /**
     * @return bool
     */
    public static function isLegalEntity()
    {
        $taxRatesRelations = get_option('yookassa_tax_rate');
        $defaultTaxRate    = get_option('yookassa_default_tax_rate');

        return ($taxRatesRelations || $defaultTaxRate) && !self::isSelfEmployed();
    }

    /**
     * @return bool
     */
    public static function isSelfEmployed()
    {
        return (bool)get_option('yookassa_self_employed', '0');
    }

    /**
     * @param CreatePaymentRequestBuilder|CreateCaptureRequestBuilder $builder
     * @param WC_Order $order
     * @param bool $subscribe
     * @throws Exception
     */
    public static function setReceiptIfNeeded($builder, WC_Order $order, $subscribe = false)
    {
        if (!self::isReceiptEnabled()) {
            return;
        }
        YooKassaLogger::sendHeka(array('receipt.create.init'));
        self::replaceOldTaxRates();
        if ($order->get_billing_email()) {
            $builder->setReceiptEmail($order->get_billing_email());
        }
        if ($order->get_billing_phone()) {
            $builder->setReceiptPhone(preg_replace('/[^\d]/', '', $order->get_billing_phone()));
        }

        $items = $order->get_items();

        /** @var WC_Order_Item_Product $item */
        foreach ($items as $item) {
            $amount = YooKassaOrderHelper::getAmountByCurrency($item->get_total() / $item->get_quantity() + $item->get_total_tax() / $item->get_quantity());
            if ($subscribe && $amount <= 0) {
                $amount = YooKassaGateway::MINIMUM_SUBSCRIBE_AMOUNT;
            }

            $builder->addReceiptItem(
                $item['name'],
                $amount->getValue(),
                $item->get_quantity(),
                self::getYmTaxRate($item->get_taxes()),
                self::getPaymentMode($item),
                self::getPaymentSubject($item)
            );
        }

        $orderData = $order->get_data();
        $shipping = $orderData['shipping_lines'];

        if (count($shipping)) {
            $shippingData = array_shift($shipping);
            if (self::isSelfEmployed() && (float)$shippingData['total'] > 0) {
                $builder->addReceiptShipping(
                    __('Доставка', 'yookassa'),
                    $shippingData['total'],
                    self::VAT_CODE_1
                );
            }

            if (self::isLegalEntity()) {
                $amount = YooKassaOrderHelper::getAmountByCurrency((float)$shippingData['total'] + (float)$shippingData['total_tax']);
                $taxes = $shippingData->get_taxes();
                $builder->addReceiptShipping(
                    __('Доставка', 'yookassa'),
                    $amount->getValue(),
                    self::getYmTaxRate($taxes),
                    self::getShippingPaymentMode(),
                    self::getShippingPaymentSubject()
                );
            }
        }

        if (self::isLegalEntity()) {
            $defaultTaxSystemCode = get_option('yookassa_default_tax_system_code');
            if (!empty($defaultTaxSystemCode)) {
                $builder->setTaxSystemCode($defaultTaxSystemCode);
            }
        }
        YooKassaLogger::sendHeka(array('receipt.create.success'));
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function updateOrderStatus(WC_Order $order, PaymentInterface $payment)
    {
        switch ($payment->getStatus()) {
            case PaymentStatus::SUCCEEDED:
                self::completeOrder($order, $payment);
                break;
            case PaymentStatus::CANCELED:
                self::cancelOrder($order, $payment);
                break;
            case PaymentStatus::WAITING_FOR_CAPTURE:
                self::holdOrder($order, $payment);
                break;
            case PaymentStatus::PENDING:
                self::pendingOrder($order, $payment);
                break;
        }
        YooKassaHandler::logOrderStatus($order->get_status());
    }

    /**
     * @param Client $apiClient
     * @param WC_Order $order
     * @param PaymentInterface $payment
     *
     * @return PaymentInterface|\YooKassa\Request\Payments\Payment\CreateCaptureResponse
     * @throws Exception
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\NotFoundException
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
    public static function capturePayment(Client $apiClient, WC_Order $order, PaymentInterface $payment)
    {
        $builder = CreateCaptureRequest::builder();
        $builder->setAmount(YooKassaOrderHelper::getTotal($order));
        self::setReceiptIfNeeded($builder, $order);
        $captureRequest = $builder->build();
        /** if merchant wants to change */
        $captureRequest = apply_filters( 'woocommerce_yookassa_capture_payment_request', $captureRequest );
        $receipt = $captureRequest->getReceipt();
        if ($receipt instanceof Receipt) {
            $receipt->normalize($captureRequest->getAmount());
        }

        $payment = $apiClient->capturePayment($captureRequest, $payment->getId());

        return $payment;
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     * @return bool
     */
    public static function completeOrder(WC_Order $order, PaymentInterface $payment)
    {
        $message = '';
        if ($payment->getPaymentMethod()->getType() == PaymentMethodType::B2B_SBERBANK) {
            $payerBankDetails = $payment->getPaymentMethod()->getPayerBankDetails();

            $fields  = array(
                'fullName'   => 'Полное наименование организации',
                'shortName'  => 'Сокращенное наименование организации',
                'adress'     => 'Адрес организации',
                'inn'        => 'ИНН организации',
                'kpp'        => 'КПП организации',
                'bankName'   => 'Наименование банка организации',
                'bankBranch' => 'Отделение банка организации',
                'bankBik'    => 'БИК банка организации',
                'account'    => 'Номер счета организации',
            );
            $message = '';

            foreach ($fields as $field => $caption) {
                if (isset($requestData[$field])) {
                    $message .= $caption . ': ' . $payerBankDetails->offsetGet($field) . '\n';
                }
            }
        }
        YooKassaLogger::info(
            sprintf(__('Успешный платеж. Id заказа - %1$s. Данные платежа - %2$s.', 'yookassa'),
                $order->get_id(), json_encode($payment))
        );

        if ($order->payment_complete($payment->getId())) {
            $order->add_order_note(sprintf(
                    __('Номер транзакции в ЮKassa: %1$s. Сумма: %2$s', 'yookassa' . $message
                    ), $payment->getId(), $payment->getAmount()->getValue())
            );
            return true;
        }

        YooKassaLogger::error('Не удалось обновить статус заказа ' . $order->get_id() . ' в базе данных!');
        return false;
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function competeSubscribe(WC_Order $order, PaymentInterface $payment)
    {
        YooKassaLogger::info(
            sprintf(__('Успешная подписка. Id заказа - %1$s. Данные платежа - %2$s.', 'yookassa'),
                $order->get_id(), json_encode($payment))
        );
        $order->payment_complete($payment->getId());
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function cancelOrder(WC_Order $order, PaymentInterface $payment)
    {
        YooKassaLogger::warning(
            sprintf(__('Неуспешный платеж. Id заказа - %1$s. Данные платежа - %2$s.', 'yookassa'),
                $order->get_id(), json_encode($payment))
        );
        $order->update_status(YooKassaOrderHelper::WC_STATUS_CANCELLED);
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function pendingOrder(WC_Order $order, PaymentInterface $payment)
    {
        YooKassaLogger::warning(
            sprintf(__('Платеж в ожидании оплаты. Id заказа - %1$s. Данные платежа - %2$s.', 'yookassa'),
                $order->get_id(), json_encode($payment))
        );
        $order->update_status(YooKassaOrderHelper::WC_STATUS_PENDING);
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function holdOrder(WC_Order $order, PaymentInterface $payment)
    {
        YooKassaLogger::warning(
            sprintf(__('Платеж ждет подтверждения. Id заказа - %1$s. Данные платежа - %2$s.', 'yookassa'),
                $order->get_id(), json_encode($payment))
        );
        $order->update_status(YooKassaOrderHelper::WC_STATUS_ON_HOLD);
        $order->add_order_note(sprintf(
                __('Поступил новый платёж. Он ожидает подтверждения до %1$s, после чего автоматически отменится',
                    'yookassa'
                ), $payment->getExpiresAt()->format('d.m.Y H:i'))
        );
    }

    /**
     * @param string $status
     */
    public static function logOrderStatus($status)
    {
        YooKassaLogger::info(sprintf(__('Статус заказа. %1$s', 'yookassa'), $status));
    }

    /**
     * @param WC_Order $order
     * @return void
     * @throws Exception
     */
    public static function checkConditionForSelfEmployed(WC_Order $order)
    {
        $items = (int)$order->get_shipping_total() > 0 ? $order->get_items(['line_item', 'shipping']) : $order->get_items(['line_item']);
        if (count($items) > 6) {
            throw new Exception(
                __('<b>Нельзя добавить больше 6 разных позиций </b><br>Такое ограничение для владельца магазина. Уберите лишние позиции из корзины — остальные можно добавить в другом заказе.', 'yookassa')
            );
        }

        foreach ($items as $item) {
            if (!is_int($item->get_quantity())) {
                throw new Exception(
                    __('<b>Нельзя добавить позицию с дробным количеством </b><br>Только с целым. Свяжитесь с магазином, чтобы исправили значение и помогли сделать заказ.', 'yookassa')
                );
            }

            if ((int)$item->get_total() <= 0) {
                throw new Exception(
                    __('<b>Не получается создать чек </b><br>Цена позиции должна быть больше 0 ₽. Уберите позицию из корзины и попробуйте ещё раз.', 'yookassa')
                );
            }
        }
    }

    public static function replaceOldTaxRates()
    {
        $defaultTaxRate = get_option('yookassa_default_tax_rate');
        if ($defaultTaxRate === '4') {
            update_option('yookassa_default_tax_rate', '11');
        }
        if ($defaultTaxRate === '6') {
            update_option('yookassa_default_tax_rate', '12');
        }
        $sbbolDefaultTaxRate    = get_option('yookassa_sbbol_default_tax_rate');
        if ($sbbolDefaultTaxRate === VatDataRate::RATE_20) {
            update_option('yookassa_sbbol_default_tax_rate', VatDataRate::RATE_22);
        }
        $ymTaxes = get_option('yookassa_tax_rate');
        if ($ymTaxes && (in_array('4', $ymTaxes, true) || in_array('6', $ymTaxes, true))) {
            $ymTaxes = array_map(static function($a) {
                $mappings = array('4' => '11', '6' => '12');
                return isset($mappings[$a]) ? $mappings[$a] : $a;
            }, $ymTaxes);
            update_option('yookassa_tax_rate', $ymTaxes);
        }
        $sbbolTaxRates = get_option('yookassa_sbbol_tax_rate');
        if ($sbbolTaxRates && in_array('20', $sbbolTaxRates, true)) {
            $sbbolTaxRates = array_map(static function($a) {
                return $a === '20' ? '22' : $a;
            }, $sbbolTaxRates);
            update_option('yookassa_sbbol_tax_rate', $sbbolTaxRates);
        }
    }

    /**
     * @param $taxes
     *
     * @return int
     */
    private static function getYmTaxRate($taxes)
    {
        $taxRatesRelations = get_option('yookassa_tax_rate');
        $defaultTaxRate    = (int)get_option('yookassa_default_tax_rate');

        if ($taxRatesRelations) {
            $taxesSubtotal = $taxes['total'];
            if ($taxesSubtotal) {
                $wcTaxIds = array_keys($taxesSubtotal);
                $wcTaxId = $wcTaxIds[0];
                if (isset($taxRatesRelations[$wcTaxId])) {
                    return (int)$taxRatesRelations[$wcTaxId];
                }
            }
        }

        return $defaultTaxRate;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private static function getShippingPaymentMode()
    {
        $paymentModeValue = get_option('yookassa_shipping_payment_mode_default');
        self::checkValidModeOrSubject($paymentModeValue, true);
        return $paymentModeValue;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private static function getShippingPaymentSubject()
    {
        $paymentSubjectValue = get_option('yookassa_shipping_payment_subject_default');
        self::checkValidModeOrSubject($paymentSubjectValue, true);
        return $paymentSubjectValue;
    }

    /**
     * @param WC_Order_Item_Product $item
     * @return mixed
     * @throws Exception
     */
    private static function getPaymentMode($item)
    {
        if ($product = $item->get_product()) {
            $paymentModeValue = $product->get_attribute('pa_yookassa_payment_mode');
        }

        if (empty($paymentModeValue)) {
            $paymentModeValue = get_option('yookassa_payment_mode_default');
        }
        self::checkValidModeOrSubject($paymentModeValue);

        return $paymentModeValue;
    }

    /**
     * @return array<string, string>
     */
    public static function getPaymentSubjectEnum()
    {
        return array(
            PaymentSubject::COMMODITY                         => __('Товар', 'yookassa') . ' (' . PaymentSubject::COMMODITY . ')',
            PaymentSubject::EXCISE                            => __('Подакцизный товар', 'yookassa') . ' (' . PaymentSubject::EXCISE . ')',
            PaymentSubject::JOB                               => __('Работа', 'yookassa') . ' (' . PaymentSubject::JOB . ')',
            PaymentSubject::SERVICE                           => __('Услуга', 'yookassa') . ' (' . PaymentSubject::SERVICE . ')',
            PaymentSubject::PAYMENT                           => __('Платеж', 'yookassa') . ' (' . PaymentSubject::PAYMENT . ')',
            PaymentSubject::CASINO                            => __('Платеж казино', 'yookassa') . ' (' . PaymentSubject::CASINO . ')',
            PaymentSubject::GAMBLING_BET                      => __('Ставка в азартной игре', 'yookassa') . ' (' . PaymentSubject::GAMBLING_BET . ')',
            PaymentSubject::GAMBLING_PRIZE                    => __('Выигрыш в азартной игре', 'yookassa') . ' (' . PaymentSubject::GAMBLING_PRIZE . ')',
            PaymentSubject::LOTTERY                           => __('Лотерейный билет', 'yookassa') . ' (' . PaymentSubject::LOTTERY . ')',
            PaymentSubject::LOTTERY_PRIZE                     => __('Выигрыш в лотерею', 'yookassa') . ' (' . PaymentSubject::LOTTERY_PRIZE . ')',
            PaymentSubject::INTELLECTUAL_ACTIVITY             => __('Результаты интеллектуальной деятельности', 'yookassa') . ' (' . PaymentSubject::INTELLECTUAL_ACTIVITY . ')',
            PaymentSubject::AGENT_COMMISSION                  => __('Агентское вознаграждение', 'yookassa') . ' (' . PaymentSubject::AGENT_COMMISSION . ')',
            PaymentSubject::PROPERTY_RIGHT                    => __('Имущественное право', 'yookassa') . ' (' . PaymentSubject::PROPERTY_RIGHT . ')',
            PaymentSubject::NON_OPERATING_GAIN                => __('Внереализационный доход', 'yookassa') . ' (' . PaymentSubject::NON_OPERATING_GAIN . ')',
            PaymentSubject::INSURANCE_PREMIUM                 => __('Страховой сбор', 'yookassa') . ' (' . PaymentSubject::INSURANCE_PREMIUM . ')',
            PaymentSubject::SALES_TAX                         => __('Торговый сбор', 'yookassa') . ' (' . PaymentSubject::SALES_TAX . ')',
            PaymentSubject::RESORT_FEE                        => __('Курортный сбор', 'yookassa') . ' (' . PaymentSubject::RESORT_FEE . ')',
            PaymentSubject::MARKED                            => __('Товар, подлежащий маркировке с кодом (в чеке — ТМ)', 'yookassa') . ' (' . PaymentSubject::MARKED . ')',
            PaymentSubject::NON_MARKED                        => __('Товар, подлежащий маркировке без кода (в чеке — ТНМ)', 'yookassa') . ' (' . PaymentSubject::NON_MARKED . ')',
            PaymentSubject::MARKED_EXCISE                     => __('Подакцизный товар, подлежащий маркировке с кодом (в чеке — АТМ)', 'yookassa') . ' (' . PaymentSubject::MARKED_EXCISE . ')',
            PaymentSubject::NON_MARKED_EXCISE                 => __('Подакцизный товар, подлежащий маркировке без кода (в чеке — АТНМ)', 'yookassa') . ' (' . PaymentSubject::NON_MARKED_EXCISE . ')',
            PaymentSubject::FINE                              => __('Выплата', 'yookassa') . ' (' . PaymentSubject::FINE . ')',
            PaymentSubject::TAX                               => __('Страховые взносы', 'yookassa') . ' (' . PaymentSubject::TAX . ')',
            PaymentSubject::LIEN                              => __('Залог', 'yookassa') . ' (' . PaymentSubject::LIEN . ')',
            PaymentSubject::COST                              => __('Расход', 'yookassa') . ' (' . PaymentSubject::COST . ')',
            PaymentSubject::AGENT_WITHDRAWALS                 => __('Выдача денежных средств', 'yookassa') . ' (' . PaymentSubject::AGENT_WITHDRAWALS . ')',
            PaymentSubject::PENSION_INSURANCE_WITHOUT_PAYOUTS => __('Взносы на обязательное пенсионное страхование ИП без выплат физлицам', 'yookassa') . ' (' . PaymentSubject::PENSION_INSURANCE_WITHOUT_PAYOUTS . ')',
            PaymentSubject::PENSION_INSURANCE_WITH_PAYOUTS    => __('Взносы на обязательное пенсионное страхование с выплатами физлицам', 'yookassa') . ' (' . PaymentSubject::PENSION_INSURANCE_WITH_PAYOUTS . ')',
            PaymentSubject::HEALTH_INSURANCE_WITHOUT_PAYOUTS  => __('Взносы на обязательное медицинское страхование ИП без выплат физлицам', 'yookassa') . ' (' . PaymentSubject::HEALTH_INSURANCE_WITHOUT_PAYOUTS . ')',
            PaymentSubject::HEALTH_INSURANCE_WITH_PAYOUTS     => __('Взносы на обязательное медицинское страхование с выплатами физлицам', 'yookassa') . ' (' . PaymentSubject::HEALTH_INSURANCE_WITH_PAYOUTS . ')',
            PaymentSubject::HEALTH_INSURANCE                  => __('Взносы на обязательное социальное страхование', 'yookassa') . ' (' . PaymentSubject::HEALTH_INSURANCE . ')',
            PaymentSubject::COMPOSITE                         => __('Несколько вариантов', 'yookassa') . ' (' . PaymentSubject::COMPOSITE . ')',
            PaymentSubject::ANOTHER                           => __('Другое', 'yookassa') . ' (' . PaymentSubject::ANOTHER . ')',
        );
    }

    /**
     * @return array<string, string>
     */
    public static function getPaymentModeEnum()
    {
        return array(
            PaymentMode::FULL_PREPAYMENT    => __('Полная предоплата', 'yookassa') . ' (' . PaymentMode::FULL_PREPAYMENT . ')',
            PaymentMode::PARTIAL_PREPAYMENT => __('Частичная предоплата', 'yookassa') . ' (' . PaymentMode::PARTIAL_PREPAYMENT . ')',
            PaymentMode::ADVANCE            => __('Аванс', 'yookassa') . ' (' . PaymentMode::ADVANCE . ')',
            PaymentMode::FULL_PAYMENT       => __('Полный расчет', 'yookassa') . ' (' . PaymentMode::FULL_PAYMENT . ')',
            PaymentMode::PARTIAL_PAYMENT    => __('Частичный расчет и кредит', 'yookassa') . ' (' . PaymentMode::PARTIAL_PAYMENT . ')',
            PaymentMode::CREDIT             => __('Кредит', 'yookassa') . ' (' . PaymentMode::CREDIT . ')',
            PaymentMode::CREDIT_PAYMENT     => __('Выплата по кредиту', 'yookassa') . ' (' . PaymentMode::CREDIT_PAYMENT . ')',
        );
    }

    private static function getPaymentSubject($item)
    {
        if ($product = $item->get_product()) {
            $paymentSubjectValue = $product->get_attribute('pa_yookassa_payment_subject');
        }

        if (empty($paymentSubjectValue)) {
            $paymentSubjectValue = get_option('yookassa_payment_subject_default');
        }
        self::checkValidModeOrSubject($paymentSubjectValue);

        return $paymentSubjectValue;
    }

    /**
     * @param $value
     * @param bool $isShipping
     * @throws Exception
     */
    private static function checkValidModeOrSubject($value, $isShipping = false)
    {
        if (!empty($value)) {
            return;
        }

        $errorMessage = 'Оплата временно не работает — ошибка на сайте. Пожалуйста, сообщите в техподдержку: «Не установлены признаки предмета или способа расчёта»';
        if ($isShipping) {
            $errorMessage = 'Оплата временно не работает — ошибка на сайте. Пожалуйста, сообщите в техподдержку: «Не установлены признаки предмета или способа расчёта для доставки»';
        }

        throw new Exception(
            __($errorMessage, 'yookassa')
        );
    }

}
