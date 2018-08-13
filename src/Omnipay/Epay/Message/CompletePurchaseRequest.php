<?php
/**
 * @package     Redpayment
 * @subpackage  omnipay
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Omnipay\Epay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * ePay Complete Purchase Request
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class CompletePurchaseRequest extends PurchaseRequest
{
	/**
	 * Get the raw data array for this message. The format of this varies from gateway to
	 * gateway, but will usually be either an associative array, or a SimpleXMLElement.
	 *
	 * @return mixed
	 *
	 * @throws InvalidResponseException
	 */
	public function getData()
	{
		if ($this->getParameter('secret') && !$this->checkMD5($this->httpRequest->query->all()))
		{
			throw new InvalidResponseException('Invalid key');
		}

		return $this->httpRequest->query->all();
	}

	/**
	 * Get the raw data array for this message. The format of this varies from gateway to
	 *
	 * @param   array  $data  The data to send
	 *
	 * @return bool
	 */
	public function checkMD5($data)
	{
		$var = '';

		foreach ($data as $key => $value)
		{
			if ($key != "hash")
			{
				$var .= $value;
			}
		}

		$genstamp = md5($var . $this->getParameter('secret'));

		return isset($data['hash']) && $genstamp == $data['hash'];
	}

	/**
	 * Send the request with specified data
	 *
	 * @param   mixed  $data  The data to send
	 *
	 * @return CompletePurchaseResponse
	 */
	public function sendData($data)
	{
		return $this->response = new CompletePurchaseResponse($this, $data);
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
