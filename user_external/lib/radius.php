<?php
/**
 * Copyright (c) 2012 Alessandro "alorenzi" Lorenzi <alessandro.lorenzi@gmail.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */

class OC_User_RADIUS extends \OCA\user_external\Base {
	private $host;
	private $realm;
	private $secret;

	const backend='radius';

	public function __construct($host, $realm, $secret) {
		$this->host = $host;
		$this->realm = $realm;
		$this->secret = $secret;
		$backend = backend.'://';
		$backend .= $host;
		$backend .= ($realm == ""?"":$realm."@");
		parent::__construct($backend);
	}

	/**
	 * @brief Check if the password is correct
	 * @param $uid The username
	 * @param $password The password
	 *
	 * @returns true/false
	 */
	public function checkPassword($uid, $password) {
		require_once('apps/user_external/lib/radius.class.php');

		$radius = new Radius($this->host, $this->secret);
		$user = $uid.($this->realm == ""?"":"@".$this->realm);

		if(! $radius->AccessRequest($user, $password) )
		{
			return false;
		} else {
			$this->storeUser($uid);
			return $uid;
		}
	}
}
