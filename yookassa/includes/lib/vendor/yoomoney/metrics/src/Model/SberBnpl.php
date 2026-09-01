<?php

namespace Cmssdk\Metrics\Model;

use OpenApi\Attributes as OA;
use YooKassa\Common\AbstractObject;

#[OA\Schema()]
class SberBnpl extends AbstractObject
{
    /** @var bool|null Флаг отображения виджета в карочке товара */
    #[OA\Property(property: "sber_bnpl_product_enabled")]
    private $sberBnplProductEnabled;
    /** @var bool|null Флаг отображения виджета в списке товаров */
    #[OA\Property(property: "sber_bnpl_list_enabled")]
    private $sberBnplListEnabled;
    /** @var bool|null Флаг отображения виджета в корзине */
    #[OA\Property(property: "sber_bnpl_cart_enabled")]
    private $sberBnplCartEnabled;
    /** @var bool|null Флаг отображения виджета в форме оформления заказа */
    #[OA\Property(property: "sber_bnpl_checkout_enabled")]
    private $sberBnplCheckoutEnabled;

    /**
     * @return bool|null
     */
    public function getSberBnplProductEnabled()
    {
        return $this->sberBnplProductEnabled;
    }

    /**
     * @param bool|null $sberBnplProductEnabled
     * @return SberBnpl
     */
    public function setSberBnplProductEnabled($sberBnplProductEnabled)
    {
        $this->sberBnplProductEnabled = $sberBnplProductEnabled;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSberBnplListEnabled()
    {
        return $this->sberBnplListEnabled;
    }

    /**
     * @param bool|null $sberBnplListEnabled
     * @return SberBnpl
     */
    public function setSberBnplListEnabled($sberBnplListEnabled)
    {
        $this->sberBnplListEnabled = $sberBnplListEnabled;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSberBnplCartEnabled()
    {
        return $this->sberBnplCartEnabled;
    }

    /**
     * @param bool|null $sberBnplCartEnabled
     * @return SberBnpl
     */
    public function setSberBnplCartEnabled($sberBnplCartEnabled)
    {
        $this->sberBnplCartEnabled = $sberBnplCartEnabled;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSberBnplCheckoutEnabled()
    {
        return $this->sberBnplCheckoutEnabled;
    }

    /**
     * @param bool|null $sberBnplCheckoutEnabled
     * @return SberBnpl
     */
    public function setSberBnplCheckoutEnabled($sberBnplCheckoutEnabled)
    {
        $this->sberBnplCheckoutEnabled = $sberBnplCheckoutEnabled;
        return $this;
    }

}
