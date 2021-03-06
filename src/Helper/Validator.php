<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 22/08/2017
 * Time: 10:19
 */

namespace PaymentGateway\VPosPosnet\Helper;

use PaymentGateway\VPosPosnet\Constant\BankType;
use PaymentGateway\VPosPosnet\Constant\Currency;
use PaymentGateway\VPosPosnet\Constant\OosRequestDataType;
use PaymentGateway\VPosPosnet\Constant\ReverseTransaction;
use PaymentGateway\VPosPosnet\Exception\ValidationException;

class Validator
{
    public static function validateCurrency($value)
    {
        if (!$value instanceof \PaymentGateway\ISO4217\Model\Currency) {
            throw new ValidationException('Invalid Currency Type', 'INVALID_CURRENCY_TYPE');
        }

        $alpha3 = $value->getAlpha3();

        if (!in_array($alpha3, Helper::getConstants(Currency::class))) {
            throw new ValidationException('Invalid Currency', 'INVALID_CURRENCY');
        }
    }

    public static function validateNotEmpty($name, $value)
    {
        if (empty($value)) {
            throw new ValidationException("Invalid $name", "INVALID_$name");
        }
    }

    public static function validateCardNumber($value)
    {
        $number = preg_replace('/\D/', '', $value);
        $number_length = strlen($number);
        $parity = $number_length % 2;

        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $number[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $total += $digit;
        }

        if (!($total % 10 == 0)) {
            throw new ValidationException('Invalid Card Number', 'INVALID_CARD_NUMBER');
        }
    }

    public static function validateCvv($value)
    {
        if (!is_string($value) || !in_array(strlen($value), array(3, 4))) {
            throw new ValidationException('Invalid Cvv', 'INVALID_CVV');
        }
    }

    public static function validateExpiryMonth($value)
    {
        if (!is_string($value) || strlen($value) != 2) {
            throw new ValidationException('Invalid Expiry Month', 'INVALID_EXPIRY_MONTH');
        }
    }

    public static function validateExpiryYear($value)
    {
        if (!is_string($value) || strlen($value) != 2) {
            throw new ValidationException('Invalid Expiry Year', 'INVALID_EXPIRY_YEAR');
        }
    }

    public static function validateAmount($value)
    {
        if (!is_numeric($value) || $value <= 0 || strpos(strval($value), ',') !== false) {
            throw new ValidationException('Invalid Amount', 'INVALID_AMOUNT');
        }
    }

    public static function validateInstallment($value)
    {
        if (empty($value) || !is_int($value)) {
            throw new ValidationException('Invalid Installment', 'INVALID_INSTALLMENT');
        }
    }

    public static function validateOrderId($value)
    {
        if (empty($value) || strlen($value) > 24) {
            throw new ValidationException('Invalid Order Id', 'INVALID_ORDER_ID');
        }
    }

    public static function validateReverseTransaction($value)
    {
        if (!in_array($value, Helper::getConstants(ReverseTransaction::class))) {
            throw new ValidationException('Invalid Reverse Transaction', 'INVALID_REVERSE_TRANSACTION');
        }
    }

    public static function validateOosRequestDataType($value)
    {
        if (!in_array($value, Helper::getConstants(OosRequestDataType::class))) {
            throw new ValidationException('Invalid Oos Request Data Type', 'INVALID_OOS_REQUEST_DATA_TYPE');
        }
    }

    public static function validateBankType($value)
    {
        if (!in_array($value, Helper::getConstants(BankType::class))) {
            throw new ValidationException('Invalid Bank Type', 'INVALID_BANK_TYPE');
        }
    }
}