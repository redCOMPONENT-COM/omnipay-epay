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
 * ePay Refund Request
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class RefundRequest extends CaptureRequest
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
		$this->validate('merchantnumber', 'amount', 'transactionId');
		$data = array();

		foreach ($this->getSupportedParameters() as $key)
		{
			$value = $this->parameters->get($key);

			if (!is_null($value))
			{
				$data[$key] = $value;
			}
		}

		$data['transactionid'] = $this->getTransactionId();
		$data['amount'] = $this->getAmountInteger();
		$data['pbsresponse'] = -1;
		$data['epayresponse'] = -1;

		return $data;
	}

	/**
	 * Send the request with specified data
	 *
	 * @param   mixed  $data  The data to send
	 *
	 * @return RefundResponse
	 */
	public function sendData($data)
	{
		$client = new \SoapClient($this->endpoint . '?WSDL');
		$result = $client->credit($data);

		return $this->response = new RefundResponse(
			$this,
			array(
				'creditResult' => $result->creditResult,
				'pbsresponsecode' => $result->pbsresponse,
				'epayresponsecode' => $result->epayresponse,
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
