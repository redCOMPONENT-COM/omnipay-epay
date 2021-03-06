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
 * ePay Delete Response
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class DeleteResponse extends AbstractResponse
{
	/**
	 * Is the response successful?
	 *
	 * @return boolean
	 */
	public function isSuccessful()
	{
		$data = $this->getData();

		return isset($data['deleteResult']) && $data['deleteResult'];
	}
}
