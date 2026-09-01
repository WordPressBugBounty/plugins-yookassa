<?php

namespace Cmssdk\Metrics\Model;

use DateTime;
use DateTimeZone;
use Exception;
use OpenApi\Attributes as OA;
use YooKassa\Common\AbstractObject;
use YooKassa\Helpers\TypeCast;
use YooKassa\Helpers\UUID;

#[OA\Schema()]
class Settings extends AbstractObject
{
    /** @var string Id записи */
    #[OA\Property(property: "id", example: "5b78882a-10e1-423a-8c6a-ccd9e98b019f")]
    private $id;
    /** @var DateTime|null Дата события */
    #[OA\Property(property: "eventTime", example: "2025-01-13T15:09:07.453+03:00")]
    private $eventTime;
    /** @var ModuleInfo|null Информация о модуле */
    #[OA\Property(property: "module_info")]
    private $moduleInfo;
    /** @var ShopInfo|null Информация о магазине */
    #[OA\Property(property: "shop_info")]
    private $shopInfo;
    /** @var Payment|null Настройки платежей */
    #[OA\Property(property: "payment")]
    private $payment;
    /** @var Fiscalization|null Настройки фискализации */
    #[OA\Property(property: "fiscalization")]
    private $fiscalization;
    /** @var SberbankBusinessOnline|null Настройки СББОЛ */
    #[OA\Property(property: "sbbol")]
    private $sbbol;
    /** @var SberBnpl|null Настройки для Сбер BNPL */
    #[OA\Property(property: "sber_bnpl")]
    private $sberBnpl;
    /** @var Advanced|null Дополнительные настройки */
    #[OA\Property(property: "advanced")]
    private $advanced;

    public function __construct($data = array())
    {
        $this->id = UUID::v4();
        $this->eventTime = date_create('now', new DateTimeZone('UTC'));
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DateTime|null
     */
    public function getEventTime()
    {
        return $this->eventTime;
    }

    /**
     * @return ModuleInfo|null
     */
    public function getModuleInfo()
    {
        return $this->moduleInfo;
    }

    /**
     * @return ShopInfo|null
     */
    public function getShopInfo()
    {
        return $this->shopInfo;
    }

    /**
     * @return Payment|null
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return Fiscalization|null
     */
    public function getFiscalization()
    {
        return $this->fiscalization;
    }

    /**
     * @return SberbankBusinessOnline|null
     */
    public function getSbbol()
    {
        return $this->sbbol;
    }

    /**
     * @return SberBnpl|null
     */
    public function getSberBnpl()
    {
        return $this->sberBnpl;
    }

    /**
     * @return Advanced|null
     */
    public function getAdvanced()
    {
        return $this->advanced;
    }

    /**
     * @param string $value
     * @return self
     * @throws Exception
     */
    public function setId($value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     * @throws Exception
     */
    public function setEventTime($value = null)
    {
        if ($value === null || $value === '') {
            $this->eventTime = date_create();
        } elseif (TypeCast::canCastToDateTime($value)) {
            $dateTime = TypeCast::castToDateTime($value);
            $this->eventTime = $dateTime;
        }

        return $this;
    }

    /**
     * @param ModuleInfo|array|null $value
     * @return self
     */
    public function setModuleInfo($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->moduleInfo = false;
        } elseif (is_array($value)) {
            $this->moduleInfo = new ModuleInfo($value);
        } elseif ($value instanceof ModuleInfo) {
            $this->moduleInfo = $value;
        }
        return $this;
    }

    /**
     * @param ShopInfo|array|null $value
     * @return self
     */
    public function setShopInfo($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->shopInfo = false;
        } elseif (is_array($value)) {
            $this->shopInfo = new ShopInfo($value);
        } elseif ($value instanceof ShopInfo) {
            $this->shopInfo = $value;
        }
        return $this;
    }

    /**
     * @param Payment|array|null $value
     * @return self
     */
    public function setPayment($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->payment = false;
        } elseif (is_array($value)) {
            $this->payment = new Payment($value);
        } elseif ($value instanceof Payment) {
            $this->payment = $value;
        }
        return $this;
    }

    /**
     * @param Fiscalization|array|null $value
     * @return self
     */
    public function setFiscalization($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->fiscalization = false;
        } elseif (is_array($value)) {
            $this->fiscalization = new Fiscalization($value);
        } elseif ($value instanceof Fiscalization) {
            $this->fiscalization = $value;
        }
        return $this;
    }

    /**
     * @param SberbankBusinessOnline|array|null $value
     * @return self
     */
    public function setSbbol($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->sbbol = false;
        } elseif (is_array($value)) {
            $this->sbbol = new SberbankBusinessOnline($value);
        } elseif ($value instanceof SberbankBusinessOnline) {
            $this->sbbol = $value;
        }
        return $this;
    }

    /**
     * @param SberBnpl|array|null $value
     * @return self
     */
    public function setSberBnpl($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->sberBnpl = false;
        } elseif (is_array($value)) {
            $this->sberBnpl = new SberBnpl($value);
        } elseif ($value instanceof SberBnpl) {
            $this->sberBnpl = $value;
        }
        return $this;
    }

    /**
     * @param Advanced|array|null $value
     * @return self
     */
    public function setAdvanced($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->advanced = false;
        } elseif (is_array($value)) {
            $this->advanced = new Advanced($value);
        } elseif ($value instanceof Advanced) {
            $this->advanced = $value;
        }
        return $this;
    }

    #[\ReturnTypeWillChange]
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        $data['eventTime'] = $data['event_time'];
        unset($data['event_time']);
        return $data;
    }
}
