<?php

namespace Yoomoney\Includes;

use YooKassa\Model\PaymentStatus;

class PaymentsTableModel
{
    const TABLE_NAME = 'yookassa_payment';
    const COL_PAYMENT_ID = 'payment_id';
    const COL_PAID = 'paid';
    const COL_CAPTURED_AT = 'captured_at';
    const COL_STATUS = 'status';
    const PAID_Y = 'Y';

    /** @var \wpdb */
    private $wpdb;

    public function __construct($wpdb)
    {
        $this->wpdb = $wpdb;
    }

    public function getPaymentById($paymentId)
    {
        $table_name = $this->wpdb->prefix . self::TABLE_NAME;

        $query = 'SELECT * FROM ' . $table_name . ' WHERE ' . self::COL_PAYMENT_ID . ' = %s';

        $query = $this->wpdb->prepare($query, $paymentId);

        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function isPaymentPaid($paymentId)
    {
        $payment = $this->getPaymentById($paymentId);

        return $payment[self::COL_PAID] == self::PAID_Y;
    }

    public function isPaymentCaptured($paymentId)
    {
        $payment = $this->getPaymentById($paymentId);

        return $payment[self::COL_CAPTURED_AT] !== '0000-00-00 00:00:00';
    }

    public function isPaymentSucceeded($paymentId)
    {
        $payment = $this->getPaymentById($paymentId);

        return $payment[self::COL_STATUS] === PaymentStatus::SUCCEEDED;
    }

    /**
     * @param $status
     * @param $isPaid
     * @param $currency
     * @return mixed [total, count]
     */
    public function getSuccessPaymentStat($status = 'succeeded', $isPaid = 'Y', $currency = 'RUB')
    {
        $table_name = $this->wpdb->prefix . self::TABLE_NAME;

        $query = "SELECT COUNT(`payment_id`) AS count, SUM(`amount`) as total FROM {$table_name} WHERE `status` = %s AND `paid` = %s AND `currency` = %s";

        $query = $this->wpdb->prepare($query, $status, $isPaid, $currency);

        return $this->wpdb->get_row($query, ARRAY_A);
    }
}
