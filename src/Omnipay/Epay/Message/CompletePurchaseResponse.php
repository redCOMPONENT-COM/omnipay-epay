<?php
/**
 * @package     Redpayment
 * @subpackage  omnipay
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Omnipay\Epay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * ePay Complete Purchase Response
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class CompletePurchaseResponse extends AbstractResponse
{
	/**
	 * Is the response successful?
	 *
	 * @return boolean
	 */
	public function isSuccessful()
	{
		return true;
	}

	/**
	 * Gateway Reference
	 *
	 * @return string A reference provided by the gateway to represent this transaction
	 */
	public function getTransactionReference()
	{
		return isset($this->data['txnid']) ? $this->data['txnid'] : null;
	}
}
