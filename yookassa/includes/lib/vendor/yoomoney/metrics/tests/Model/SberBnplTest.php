<?php

namespace Tests\Cmssdk\Metrics\Model;

use Cmssdk\Metrics\Model\SberBnpl;
use PHPUnit\Framework\TestCase;

class SberBnplTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param $data
     * @return void
     */
    public function testSetAndGetSberBnplProductEnabled($data)
    {
        $sberBnpl = new SberBnpl();
        $sberBnpl->setSberBnplProductEnabled($data['sber_bnpl_product_enabled']);
        $this->assertEquals($data['sber_bnpl_product_enabled'], $sberBnpl->getSberBnplProductEnabled());
    }

    /**
     * @dataProvider dataProvider
     * @param $data
     * @return void
     */
    public function testSetAndGetSberBnplListEnabled($data)
    {
        $sberBnpl = new SberBnpl();
        $sberBnpl->setSberBnplListEnabled($data['sber_bnpl_list_enabled']);
        $this->assertEquals($data['sber_bnpl_list_enabled'], $sberBnpl->getSberBnplListEnabled());
    }

    /**
     * @dataProvider dataProvider
     * @param $data
     * @return void
     */
    public function testSetAndGetSberBnplCartEnabled($data)
    {
        $sberBnpl = new SberBnpl();
        $sberBnpl->setSberBnplCartEnabled($data['sber_bnpl_cart_enabled']);
        $this->assertEquals($data['sber_bnpl_cart_enabled'], $sberBnpl->getSberBnplCartEnabled());
    }

    /**
     * @dataProvider dataProvider
     * @param $data
     * @return void
     */
    public function testSetAndGetSberBnplCheckoutEnabled($data)
    {
        $sberBnpl = new SberBnpl();
        $sberBnpl->setSberBnplCheckoutEnabled($data['sber_bnpl_checkout_enabled']);
        $this->assertEquals($data['sber_bnpl_checkout_enabled'], $sberBnpl->getSberBnplCheckoutEnabled());
    }

    /**
     * @dataProvider dataProvider
     * @return void
     */
    public function testSetAll($data)
    {
        $sberBnpl = new SberBnpl($data);

        $this->assertEquals($data['sber_bnpl_product_enabled'], $sberBnpl->getSberBnplProductEnabled());
        $this->assertEquals($data['sber_bnpl_list_enabled'], $sberBnpl->getSberBnplListEnabled());
        $this->assertEquals($data['sber_bnpl_cart_enabled'], $sberBnpl->getSberBnplCartEnabled());
        $this->assertEquals($data['sber_bnpl_checkout_enabled'], $sberBnpl->getSberBnplCheckoutEnabled());
    }

    public function dataProvider()
    {
        return array(
            array(
                array(
                    'sber_bnpl_product_enabled'=> true,
                    'sber_bnpl_list_enabled'=> true,
                    'sber_bnpl_cart_enabled'=> false,
                    'sber_bnpl_checkout_enabled'=> false,
                )
            ),
            array(
                array(
                    'sber_bnpl_product_enabled'=> false,
                    'sber_bnpl_list_enabled'=> false,
                    'sber_bnpl_cart_enabled'=> true,
                    'sber_bnpl_checkout_enabled'=> true,
                )
            ),
            array(
                array(
                    'sber_bnpl_product_enabled'=> true,
                    'sber_bnpl_list_enabled'=> false,
                    'sber_bnpl_cart_enabled'=> true,
                    'sber_bnpl_checkout_enabled'=> false,
                )
            ),
        );
    }
}
