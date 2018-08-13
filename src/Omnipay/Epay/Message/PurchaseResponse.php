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
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * ePay Purchase Response
 *
 * @package     Redpayment
 * @subpackage  omnipay.epay
 * @since       1.5
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
	protected $endpoint = 'https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/Default.aspx';

	/**
	 * Is the response successful?
	 *
	 * @return boolean
	 */
	public function isSuccessful()
	{
		return false;
	}

	/**
	 * Does the response require a redirect?
	 *
	 * @return boolean
	 */
	public function isRedirect()
	{
		return true;
	}

	/**
	 * Gets the redirect target url.
	 *
	 * @return string
	 */
	public function getRedirectUrl()
	{
		return $this->endpoint . '?' . http_build_query($this->data);
	}

	/**
	 * Get the required redirect method (either GET or POST).
	 *
	 * @return string
	 */
	public function getRedirectMethod()
	{
		return 'GET';
	}

	/**
	 * Gets the redirect form data array, if the redirect method is POST.
	 *
	 * @return mixed
	 */
	public function getRedirectData()
	{
		return null;
	}
}
