<?php
/**
 * @package     Redpayment
 * @subpackage  omnipay
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Omnipay\Epay\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * ePay Epayerror Request
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class EpayerrorRequest extends PurchaseRequest
{
	protected $endpoint = 'https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx';

	/**
	 * Get the raw data array for this message. The format of this varies from gateway to
	 * gateway, but will usually be either an associative array, or a SimpleXMLElement.
	 *
	 * @return mixed
	 */
	public function getData()
	{
		$this->validate('merchantnumber', 'epayresponsecode');
		$data = array();

		foreach ($this->getSupportedParameters() as $key)
		{
			$value = $this->parameters->get($key);

			if (!is_null($value))
			{
				$data[$key] = $value;
			}
		}

		$data['language'] = empty($data['language']) ? 2 : $data['language'];
		$data['pbsResponse'] = -1;
		$data['epayresponse'] = -1;

		return $data;
	}

	/**
	 * Send the request with specified data
	 *
	 * @param   mixed  $data  The data to send
	 *
	 * @return EpayerrorResponse
	 */
	public function sendData($data)
	{
		// We have this fixed for localhosts so we cannot even check the error message
		if ($data['epayresponsecode'] == -1003)
		{
			return $this->response = new EpayerrorResponse(
				$this,
				array(
					'getEpayErrorResult' => true,
					'epayresponsestring' => 'An error -1003 occurred in the communication to ePay: The IP address your '
						. 'system calls ePay from is UNKNOWN. Please log into your ePay account to verify enter the IP '
						. 'address your system calls ePay from. This can be done from the menu: API / WEBSERVICES -> ACCESS.',
				)
			);
		}
		// We have this fixed for password protected webservice access so we cannot even check the error message
		elseif ($data['epayresponsecode'] == -1019)
		{
			return $this->response = new EpayerrorResponse(
				$this,
				array(
					'getEpayErrorResult' => true,
					'epayresponsestring' => 'Invalid password used for webservice access! '
						. 'Please log into your ePay account to verify webservice password. This can be done from the menu: API / WEBSERVICES -> ACCESS.',
				)
			);
		}

		$client = new \SoapClient($this->endpoint . '?WSDL');

		$result = $client->getEpayError($data);

		return $this->response = new EpayerrorResponse(
			$this,
			array(
				'getEpayErrorResult' => isset($result->getEpayErrorResult) ? $result->getEpayErrorResult : '',
				'epayresponsestring' => isset($result->getEpayErrorResult) ? $result->epayresponsestring : '',
			)
		);
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
