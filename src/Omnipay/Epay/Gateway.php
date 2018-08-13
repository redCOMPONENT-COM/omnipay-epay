<?php
/**
 * @package     Redpayment
 * @subpackage  omnipay
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Omnipay\Epay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Epay\Message\CaptureRequest;
use Omnipay\Epay\Message\CompletePurchaseRequest;
use Omnipay\Epay\Message\DeleteRequest;
use Omnipay\Epay\Message\PurchaseRequest;
use Omnipay\Epay\Message\RefundRequest;

/**
 * ePay payment gateway
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class Gateway extends AbstractGateway
{
	public function getName()
	{
		return 'Epay';
	}

    /**
     * @link http://tech.epay.dk/en/payment-window-parameters
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'merchantnumber' => '',
            'secret' => '',
            'language' => '0',
            'ownreceipt' => '1',
            'timeout'    => '',
            'paymentcollection' => '1',
            'lockpaymentcollection' => '1',
            'windowid' => '1',
	        'instantcapture' => '0',
	        'windowstate' => '',
	        'instantcallback' => '1',
	        'group' => '',
	        'opacity' => '50',
        );
    }

    public function setTimeout($timeout)
    {
        $this->parameters->set('timeout', $timeout);
    }

	public function setInstantcallback($param)
	{
		$this->parameters->set('instantcallback', $param);
	}

	public function setGroup($param)
	{
		$this->parameters->set('group', $param);
	}

	public function setOpacity($param)
	{
		$this->parameters->set('opacity', $param);
	}

	public function setPaymentcollection($param)
	{
		$this->parameters->set('paymentcollection', $param);
	}

	public function setLockpaymentcollection($param)
	{
		$this->parameters->set('lockpaymentcollection', $param);
	}

	public function setInstantcapture($param)
	{
		$this->parameters->set('instantcapture', $param);
	}

    public function setMerchantnumber($merchantNumber)
    {
        $this->parameters->set('merchantnumber', (string) $merchantNumber);
    }

    public function setSecret($secret)
    {
        $this->parameters->set('secret', $secret);
    }

    public function setLanguage($language)
    {
        $this->parameters->set('language', $language);
    }

    public function setWindowstate($windowstate)
    {
        $this->parameters->set('windowstate', $windowstate);
    }

	public function setPwd($pwd)
	{
		$this->parameters->set('pwd', $pwd);
	}

    public function setWindowid($windowId) {
        $this->parameters->set('windowid', $windowId);
    }

    public function setMobile($mobile) {
        $this->parameters->set('mobile', $mobile);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return CaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\CaptureRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return RefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\RefundRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return DeleteRequest
     */
    public function delete(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\DeleteRequest', $parameters);
    }

	/**
	 * @param array $parameters
	 * @return mixed
	 */
	public function check(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\Epay\Message\CheckRequest', $parameters);
	}

	/**
	 * @param array $parameters
	 * @return mixed
	 */
	public function getEpayError(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\Epay\Message\EpayerrorRequest', $parameters);
	}

	/**
	 * @param array $parameters
	 * @return mixed
	 */
	public function getPbsError(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\Epay\Message\PbserrorRequest', $parameters);
	}
}
