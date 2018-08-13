<?php
/**
 * @package     Redpayment
 * @subpackage  omnipay
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Omnipay\Epay\Message;

use Omnipay\Common\Helper;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * ePay Purchase Request
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class PurchaseRequest extends AbstractRequest
{
	/**
	 * Initialize the object with parameters.
	 * If any unknown parameters passed, they will be ignored.
	 *
	 * @param   array  $parameters  An associative array of parameters
	 *
	 * @return $this
	 *
	 * @throws RuntimeException
	 */
	public function initialize(array $parameters = array())
	{
		if ($this->response !== null)
		{
			throw new RuntimeException('Request cannot be modified after it has been sent!');
		}

		$this->parameters = new ParameterBag;
		$supportedKeys = $this->getSupportedParameters();

		if (is_array($parameters))
		{
			foreach ($parameters as $key => $value)
			{
				$method = 'set' . ucfirst(Helper::camelCase($key));

				if (method_exists($this, $method))
				{
					$this->$method($value);
				}
				elseif (in_array($key, $supportedKeys))
				{
					$this->parameters->set($key, $value);
				}
			}
		}

		return $this;
	}

	/**
	 * Returns list of needed parameters for the request
	 *
	 * @return array
	 */
	public function getSupportedParameters()
	{
		return array('merchantnumber', 'currency','amount', 'secret', 'orderid', 'windowstate', 'mobile', 'windowid',
			'paymentcollection', 'lockpaymentcollection', 'paymenttype', 'language', 'encoding', 'cssurl', 'mobilecssurl',
			'instantcapture', 'splitpayment', 'instantcallback', 'callbackurl', 'accepturl', 'cancelurl', 'ownreceipt',
			'ordertext', 'group', 'description', 'subscription', 'subscriptionname', 'mailreceipt', 'googletracker',
			'backgroundcolor', 'opacity', 'declinetext', 'iframeheight', 'iframewidth', 'timeout', 'epayresponsecode',
			'pbsresponsecode', 'pwd');
	}

	/**
	 * Get the raw data array for this message. The format of this varies from gateway to
	 * gateway, but will usually be either an associative array, or a SimpleXMLElement.
	 *
	 * @return mixed
	 */
	public function getData()
	{
		$this->validate('merchantnumber', 'currency', 'amount');
		$data = array();

		foreach ($this->getSupportedParameters() as $key)
		{
			$value = $this->parameters->get($key);

			if ($value !== null)
			{
				$data[$key] = $value;
			}
		}

		$data['amount'] = $this->getAmountInteger();

		if (empty($data['accepturl']))
		{
			$data['accepturl'] = $this->getNotifyUrl();
		}

		if (isset($data['secret']))
		{
			unset($data['secret']);
			$data['hash'] = md5(implode("", array_values($data)) . $this->getParameter('secret'));
		}

		return $data;
	}

	/**
	 * Send the request with specified data
	 *
	 * @param   mixed  $data  The data to send
	 *
	 * @return PurchaseResponse
	 */
	public function sendData($data)
	{
		return $this->response = new PurchaseResponse($this, $data);
	}

	/**
	 * Send the request
	 *
	 * @return ResponseInterface
	 */
	public function send()
	{
		return $this->sendData($this->getData());
	}
}
