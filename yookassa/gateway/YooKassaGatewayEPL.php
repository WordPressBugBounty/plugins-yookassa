<?php

if ( ! class_exists('YooKassaGateway')) {
    return;
}

class YooKassaGatewayEPL extends YooKassaGateway
{
    public $paymentMethod = '';

    public $id = 'yookassa_epl';

    /**
     * YooKassaGatewayEPL constructor.
     * @TODO вынести функцию перевода в методы getTitle и getDescription. в способах оставить голое название
     */
    public function __construct()
    {
        parent::__construct();

        $this->icon = YooKassa::$pluginUrl.'assets/images/kassa.png';

        $this->method_title       = __('Умный платёж', 'yookassa');
        $this->method_description = __('Из вашего магазина покупатель перейдёт на страницу ЮKassa и заплатит любым из способов, которые вы подключили.', 'yookassa');

        $this->defaultTitle       = __('ЮKassa', 'yookassa');
        $this->defaultDescription = __('Банковской картой, через SberPay и другими подключёнными способами', 'yookassa');

        $this->title              = $this->getTitle();
        $this->description        = $this->getDescription();
    }
}
