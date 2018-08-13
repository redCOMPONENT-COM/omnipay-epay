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
 * ePay Check Request
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class CheckRequest extends PurchaseRequest
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
		$this->validate('merchantnumber', 'transactionId');
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
		$data['pbsResponse'] = -1;
		$data['epayresponse'] = -1;

		return $data;
	}

	/**
	 * Send data
	 *
	 * @param   mixed  $data  Data
	 *
	 * @return CheckResponse
	 */
	public function sendData($data)
	{
		$client = new \SoapClient($this->endpoint . '?WSDL');
		$result = $client->gettransaction($data);

		$response = array(
			'gettransactionResult' => $result->gettransactionResult,
			'pbsresponsecode' => $result->pbsResponse,
			'epayresponsecode' => $result->epayresponse,
			'currency' => '',
			'capturedamount' => '',
			'status' => '',
		);

		if (isset($result->transactionInformation))
		{
			$response['currency'] = $result->transactionInformation->currency;
			$response['capturedamount'] = $result->transactionInformation->capturedamount;
			$response['status'] = $result->transactionInformation->status;
		}

		return $this->response = new CheckResponse($this, $response);
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
