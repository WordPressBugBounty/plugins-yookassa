<?php

namespace Cmssdk\Metrics\Model;

use OpenApi\Attributes as OA;
use YooKassa\Common\AbstractObject;

#[OA\Schema()]
class Fiscalization extends AbstractObject
{
    /** @var bool|null Флаг работы с чеками */
    #[OA\Property(property: "receipt_enabled", example: true)]
    private $receiptEnabled;
    /** @var bool|null Флаг самозанятого */
    #[OA\Property(property: "self_employed", example: false)]
    private $selfEmployed;
    /** @var string|null Версия ФФД */
    #[OA\Property(property: "ffd", example: "ffd12")]
    private $ffd;
    /** @var string|null Ставка НДС по умолчанию */
    #[OA\Property(property: "default_tax_rate", example: "1")]
    private $defaultTaxRate;
    /** @var string|null Система налогообложения по умолчанию */
    #[OA\Property(property: "default_tax_system_code", example: "6")]
    private $defaultTaxSystemCode;
    /** @var string|null Предмет расчета по умолчанию */
    #[OA\Property(property: "default_payment_subject", example: "commodity")]
    private $defaultPaymentSubject;
    /** @var string|null Способ расчета по умолчанию */
    #[OA\Property(property: "default_payment_mode", example: "full_prepayment")]
    private $defaultPaymentMode;
    /** @var string|null Предмет расчета по умолчанию для доставки */
    #[OA\Property(property: "default_shipping_payment_subject", example: "service")]
    private $defaultShippingPaymentSubject;
    /** @var string|null Способ расчета по умолчанию для доставки */
    #[OA\Property(property: "default_shipping_payment_mode", example: "full_payment")]
    private $defaultShippingPaymentMode;
    /** @var array<string, string>|null Налоговые ставки */
    #[OA\Property(property: "tax_rates", type: "array", example: [["key"=>"2","value"=>"1"],["key"=>"3","value"=>"1"]])]
    private $taxRates;
    /** @var bool|null Флаг работы с маркировкой */
    #[OA\Property(property: "marking_enabled", example: false)]
    private $markingEnabled;
    /** @var bool|null Флаг работы со вторым чеком */
    #[OA\Property(property: "second_receipt_enabled", example: true)]
    private $secondReceiptEnabled;
    /** @var string|null Статус заказа при котором формируется второй чек */
    #[OA\Property(property: "second_receipt_order_status", example: "wc-processing")]
    private $secondReceiptOrderStatus;

    /**
     * @return bool|null
     */
    public function getReceiptEnabled()
    {
        return $this->receiptEnabled;
    }

    /**
     * @return bool|null
     */
    public function getSelfEmployed()
    {
        return $this->selfEmployed;
    }

    /**
     * @return string|null
     */
    public function getFfd()
    {
        return $this->ffd;
    }

    /**
     * @return string|null
     */
    public function getDefaultTaxRate()
    {
        return $this->defaultTaxRate;
    }

    /**
     * @return string|null
     */
    public function getDefaultTaxSystemCode()
    {
        return $this->defaultTaxSystemCode;
    }

    /**
     * @return string|null
     */
    public function getDefaultPaymentSubject()
    {
        return $this->defaultPaymentSubject;
    }

    /**
     * @return string|null
     */
    public function getDefaultPaymentMode()
    {
        return $this->defaultPaymentMode;
    }

    /**
     * @return string|null
     */
    public function getDefaultShippingPaymentSubject()
    {
        return $this->defaultShippingPaymentSubject;
    }

    /**
     * @return string|null
     */
    public function getDefaultShippingPaymentMode()
    {
        return $this->defaultShippingPaymentMode;
    }

    public function getTaxRates()
    {
        return $this->taxRates;
    }

    /**
     * @return bool|null
     */
    public function getMarkingEnabled()
    {
        return $this->markingEnabled;
    }

    /**
     * @return bool|null
     */
    public function getSecondReceiptEnabled()
    {
        return $this->secondReceiptEnabled;
    }

    /**
     * @return string|null
     */
    public function getSecondReceiptOrderStatus()
    {
        return $this->secondReceiptOrderStatus;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setReceiptEnabled($value = null)
    {
        $this->receiptEnabled = (bool) $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setSelfEmployed($value = null)
    {
        $this->selfEmployed = (bool) $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setFfd($value = null)
    {
        $this->ffd = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setDefaultTaxRate($value = null)
    {
        $this->defaultTaxRate = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setDefaultTaxSystemCode($value = null)
    {
        $this->defaultTaxSystemCode = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setDefaultPaymentSubject($value = null)
    {
        $this->defaultPaymentSubject = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setDefaultPaymentMode($value = null)
    {
        $this->defaultPaymentMode = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setDefaultShippingPaymentSubject($value = null)
    {
        $this->defaultShippingPaymentSubject = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setDefaultShippingPaymentMode($value = null)
    {
        $this->defaultShippingPaymentMode = $value;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setTaxRates($value = null)
    {
        $this->taxRates = $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setSecondReceiptEnabled($value = null)
    {
        $this->secondReceiptEnabled = (bool) $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setMarkingEnabled($value = null)
    {
        $this->markingEnabled = (bool) $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setSecondReceiptOrderStatus($value = null)
    {
        $this->secondReceiptOrderStatus = $value;
        return $this;
    }

    #[\ReturnTypeWillChange]
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        if (!empty($data['tax_rates'])) {
            $newTaxRates = array();
            foreach ($data['tax_rates'] as $key => $value) {
                $newTaxRates[] = array(
                    'key' => $key,
                    'value' => $value,
                );
            }
            $data['tax_rates'] = $newTaxRates;
        }
        return $data;
    }
}
