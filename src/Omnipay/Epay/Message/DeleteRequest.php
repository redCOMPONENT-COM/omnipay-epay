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
 * ePay Delete Request
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class DeleteRequest extends CaptureRequest
{
	protected $endpoint = 'https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx';

	/**
	 * Returns list of needed parameters for the request
	 *
	 * @return array
	 */
	public function getSupportedParameters()
	{
		return array('merchantnumber', 'transactionId', 'group', 'pwd');
	}

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
		$data['pbsresponse'] = -1;
		$data['epayresponse'] = -1;

		return $data;
	}

	/**
	 * Send the request with specified data
	 *
	 * @param   mixed  $data  The data to send
	 *
	 * @return DeleteResponse
	 */
	public function sendData($data)
	{
		$client = new \SoapClient($this->endpoint . '?WSDL');
		$result = $client->delete($data);

		// If we get this error code then this transaction is already deleted
		$deleteResult = $result->epayresponse == -1020;

		return $this->response = new DeleteResponse(
			$this,
			array(
				'deleteResult' => $deleteResult ? $deleteResult : $result->deleteResult,
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
