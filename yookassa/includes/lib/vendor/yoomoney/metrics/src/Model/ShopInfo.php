<?php

namespace Cmssdk\Metrics\Model;

use OpenApi\Attributes as OA;
use YooKassa\Common\AbstractObject;

#[OA\Schema()]
class ShopInfo extends AbstractObject
{
    /** @var string|null ShopID магазина */
    #[OA\Property(property: "account_id", example: "123456")]
    private $accountId;
    /** @var string|null Статус магазина */
    #[OA\Property(property: "status", example: "enabled")]
    private $status;
    /** @var bool|null Тестовый или нет */
    #[OA\Property(property: "test", example: false)]
    private $test;
    /** @var Fiscalization|null Установки фискализации */
    #[OA\Property(property: "fiscalization")]
    private $fiscalization;
    /** @var bool|null Статус фискализации */
    #[OA\Property(property: "fiscalization_enabled", example: true)]
    private $fiscalizationEnabled;
    /** @var array<int, string> Доступные платёжные методы */
    #[OA\Property(property: "payment_methods", type: "array", example: ["bank_card", "yoo_money"])]
    private $paymentMethods;
    /** @var string|null ИНН магазина */
    #[OA\Property(property: "itn", example: "123456789012")]
    private $itn;

    /**
     * @return string|null
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool|null
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @return Fiscalization|null
     */
    public function getFiscalization()
    {
        return $this->fiscalization;
    }

    /**
     * @return bool|null
     */
    public function getFiscalizationEnabled()
    {
        return $this->fiscalizationEnabled;
    }

    /**
     * @return array<int, string>
     */
    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }

    /**
     * @return string|null
     */
    public function getItn()
    {
        return $this->itn;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setAccountId($value = null)
    {
        $this->accountId = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setStatus($value = null)
    {
        $this->status = $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setTest($value = null)
    {
        $this->test = (bool) $value;
        return $this;
    }

    /**
     * @param array|null $value
     * @return self
     */
    public function setFiscalization($value = null)
    {
        $this->fiscalization = $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setFiscalizationEnabled($value = null)
    {
        $this->fiscalizationEnabled = (bool) $value;
        return $this;
    }

    /**
     * @param array<int, string> $value
     * @return self
     */
    public function setPaymentMethods(array $value = array())
    {
        $this->paymentMethods = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setItn($value = null)
    {
        $this->itn = $value;
        return $this;
    }
}
