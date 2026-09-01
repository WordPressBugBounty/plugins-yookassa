<?php

namespace Cmssdk\Metrics\Model;

use OpenApi\Attributes as OA;
use YooKassa\Common\AbstractObject;

#[OA\Schema()]
class Advanced extends AbstractObject
{
    /** @var string|null Шаблон описания платежа */
    #[OA\Property(property: "description_template", example: "Оплата заказа №%order_number%")]
    private $descriptionTemplate;
    /** @var string|null Ссылка на страницу успеха */
    #[OA\Property(property: "success_url", example: "wc_success")]
    private $successUrl;
    /** @var string|null Ссылка на страницу ошибки */
    #[OA\Property(property: "failure_url", example: "wc_checkout")]
    private $failureUrl;
    /** @var string|null Валюта магазина */
    #[OA\Property(property: "yookassa_currency", example: "RUB")]
    private $yookassaCurrency;
    /** @var bool|null Флаг конвертации валюты */
    #[OA\Property(property: "yookassa_currency_convert", example: true)]
    private $yookassaCurrencyConvert;
    /** @var bool|null Флаг принудительной очистки корзины после оплаты */
    #[OA\Property(property: "force_clear_cart", example: true)]
    private $forceClearCart;
    /** @var bool|null Флаг включения лога */
    #[OA\Property(property: "debug_enabled", example: true)]
    private $debugEnabled;
    /** @var string|null Ссылка на обработчика уведомлений */
    #[OA\Property(property: "notify_url", example: "https://merchant-site.ru/yookassa/callback")]
    private $notifyUrl;

    /**
     * @return string|null
     */
    public function getDescriptionTemplate()
    {
        return $this->descriptionTemplate;
    }

    /**
     * @return string|null
     */
    public function getSuccessUrl()
    {
        return $this->successUrl;
    }

    /**
     * @return string|null
     */
    public function getFailureUrl()
    {
        return $this->failureUrl;
    }

    /**
     * @return string|null
     */
    public function getYookassaCurrency()
    {
        return $this->yookassaCurrency;
    }

    /**
     * @return bool|null
     */
    public function getYookassaCurrencyConvert()
    {
        return $this->yookassaCurrencyConvert;
    }

    /**
     * @return bool|null
     */
    public function getForceClearCart()
    {
        return $this->forceClearCart;
    }

    /**
     * @return bool|null
     */
    public function getDebugEnabled()
    {
        return $this->debugEnabled;
    }

    /**
     * @return string|null
     */
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setDescriptionTemplate($value = null)
    {
        $this->descriptionTemplate = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setSuccessUrl($value = null)
    {
        $this->successUrl = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setFailureUrl($value = null)
    {
        $this->failureUrl = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setYookassaCurrency($value = null)
    {
        $this->yookassaCurrency = $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setYookassaCurrencyConvert($value = null)
    {
        $this->yookassaCurrencyConvert = (bool) $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setForceClearCart($value = null)
    {
        $this->forceClearCart = (bool) $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setDebugEnabled($value = null)
    {
        $this->debugEnabled = (bool) $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setNotifyUrl($value = null)
    {
        $this->notifyUrl = $value;
        return $this;
    }
}
