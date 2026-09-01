<?php

namespace Cmssdk\Metrics\Model;

use OpenApi\Attributes as OA;
use YooKassa\Common\AbstractObject;

#[OA\Schema()]
class Payment extends AbstractObject
{
    /** @var string|null Сценарий оплаты (ЕПЛ или виджет) */
    #[OA\Property(property: "scenario", example: "1")]
    private $scenario;
    /** @var bool|null Флаг сохранения карты */
    #[OA\Property(property: "save_card_enabled", example: true)]
    private $saveCardEnabled;
    /** @var bool|null Флаг использования холдов */
    #[OA\Property(property: "hold_enabled", example: false)]
    private $holdEnabled;
    /** @var bool|null Флаг использования СББОЛ */
    #[OA\Property(property: "sbbol_enabled", example: false)]
    private $sbbolEnabled;
    /** @var bool|null Флаг использования Сбер BNPL */
    #[OA\Property(property: "sber_bnpl_enabled", example: false)]
    private $sberBnplEnabled;

    /**
     * @return string|null
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * @return bool|null
     */
    public function getSaveCardEnabled()
    {
        return $this->saveCardEnabled;
    }

    /**
     * @return bool|null
     */
    public function getHoldEnabled()
    {
        return $this->holdEnabled;
    }

    /**
     * @return bool|null
     */
    public function getSbbolEnabled()
    {
        return $this->sbbolEnabled;
    }

    /**
     * @return bool|null
     */
    public function getSberBnplEnabled()
    {
        return $this->sberBnplEnabled;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setScenario($value = null)
    {
        $this->scenario = $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setSaveCardEnabled($value = null)
    {
        $this->saveCardEnabled = (bool) $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setHoldEnabled($value = null)
    {
        $this->holdEnabled = (bool) $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setSbbolEnabled($value = null)
    {
        $this->sbbolEnabled = (bool) $value;
        return $this;
    }

    /**
     * @param bool|null $value
     * @return self
     */
    public function setSberBnplEnabled($value = null)
    {
        $this->sberBnplEnabled = (bool) $value;
        return $this;
    }
}
